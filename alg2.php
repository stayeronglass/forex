<?php

    $i = 0;
    $n = 10;
    $line = 0;
    $file = '';
    $skip = 0;

    $chartdataX = array();
    $chartdataY = array();

    $pointdataX = array();
    $pointdataY = array();

    function nextItem(){
        global $i;
        global $n;
        global $file;
        global $line;


        global $chartdataX;
        global $chartdataY;

        $line = fgetcsv($file, 10000, ",");
        if(!$line) return false;

        $chartdataX[] = $i+$n;
        $chartdataY[] = (float) $line[2];



        $i++;
        if ($i > 100) return false;

        $result = (float) $line[2];


        //var_dump($result);

        return $result;
    }//function nextItem(){


    function fillInput(){
        global $n;
        global $line ;
        global $file;
        global $skip;

        global $chartdataX;
        global $chartdataY;
        global $pointdataX;
        global $pointdataY;

        $vector = new \SplQueue();

        if(!$file)
            $file = fopen('eurusd.csv', 'r');

        for($i = 0; $i < $n + $skip; $i++){
            $line = fgetcsv($file, 10000, ",");

            if(!$line) return false;
            $line++;

            if ($line < $skip) continue;

            $date = date_create_from_format('Y.m.d', $line[0]);

            if ('2013' != $date->format('Y')) return false;

            $chartdataX[] = $i;
            $chartdataY[] = (float) $line[2];

            $pointdataX[] = $i;
            $pointdataY[] = 1.33;

            $vector->push( (float) $line[2] );
        }//for($i = 0; $i <= $this->input; $i++){
        return $vector;
    }//public function fillInput(){


    /**
     * @param $input SplQueue
     * @return bool
     */
    function situationChangedToBuy($input){
        global $n;

        $last = $input->offsetGet($n-1);

        for($i = $n-1; $i >= 6; $i--):
            $current = $input->offsetGet($i);
            if($current > $last) return false;
        endfor;

        return true;

    }//function situationChangedToBuy(){

    /**
     * @param $input SplQueue
     * @return bool
     */
    function situationChangedToSell($input){
        global $n;

        $last = $input->offsetGet($n-1);

        for($i = $n-1; $i >= 6; $i--):
            $current = $input->offsetGet($i);
            if($current < $last) return false;
        endfor;
        return true;

    }//function situationChangedToSell(){



    $input = fillInput();
    $lastSituation = 'Buy';
    $sum = 0;
    $lastBuy = 0;
    $lastSell = 0;

    while($next = nextItem()):


        if( situationChangedToBuy($input) && ('Sell' == $lastSituation) ):
            //var_dump('>>>>>>>>>>>>>situationChangedToBuy');
            $lastSituation = 'Buy';
            $lastBuy = $next;
            $sum = $sum + ($lastSell - $next);

            $pointdataY[] = $next;
        elseif( situationChangedToSell($input) && ('Buy' == $lastSituation) ):
            //var_dump('>>>>>>>>>>>>>situationChangedToSell');
            $lastSituation = 'Sell';
            $lastSell = $next;
            $sum = $sum + ($next - $lastBuy);

            $pointdataY[] = $next;
        else:

            $pointdataY[] = 1.33;
        endif;

        $pointdataX[] = $i + $n;

        $input->dequeue();

        $input->enqueue($next);

    endwhile;

    require_once ('jpgraph/jpgraph.php');
    require_once ('jpgraph/jpgraph_line.php');
    require_once ('jpgraph/jpgraph_bar.php');
    require_once ('jpgraph/jpgraph_iconplot.php');
    require_once ('jpgraph/jpgraph_plotline.php');
    require_once ('jpgraph/jpgraph_scatter.php');
    require_once ('jpgraph/jpgraph_regstat.php');


// Setup the graph
    $graph = new Graph(2500,1500);
    $graph->SetMargin(30,20,60,20);
    $graph->SetMarginColor('white');
    $graph->SetScale("texlin");

// Hide the frame around the graph
    $graph->SetFrame(false);

// Setup title
    $graph->title->Set("HISTORICAL");
    $graph->title->SetFont(FF_VERDANA,FS_BOLD,14);



// Format the legend box
    $graph->legend->SetColor('navy');
    $graph->legend->SetFillColor('lightgreen');
    $graph->legend->SetLineWeight(1);
    $graph->legend->SetFont(FF_ARIAL,FS_BOLD,8);
    $graph->legend->SetShadow('gray@0.4',3);
    $graph->legend->SetAbsPos(15,120,'right','bottom');


    $p2 = new LinePlot($pointdataY, $pointdataX);
    $p2->mark->SetType(MARK_IMG_MBALL,'red');
    $graph->Add($p2);
    $p2->SetWeight(0);


    $mainline = new LinePlot($chartdataY,$chartdataX);
    $graph->Add($mainline);
    $mainline->value->SetFormat('%01.3F');
    $mainline->value->Show();



    $graph->Stroke();

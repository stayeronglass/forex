<?php

    $i = 0;
    $n = 10;
    $chartdataX = array();
    $chartdataY = array();

    $pointdataX = array();
    $pointdataY = array();

    function nextItem(){
        global $i;
        global $n;
        global $chartdataX;
        global $chartdataY;

        $result = sin($i);
        $chartdataX[] = $result;
        $chartdataY[] =  $i;

        $i += 0.1;

        if($i > 13 ) return false;
//var_dump($result);
        return $result;
    }//function nextItem(){

    function fillInput(){
        global $n;

        $vector = new \SplQueue();
        for($i = 1; $i <= $n; $i++){
            $vector->push(nextItem());
        }

        return $vector;
    }//function fillInput(){

    /**
     * @param $input SplQueue
     * @return bool
     */
    function situationChangedToBuy($input){
        global $n;

        $last = $input->offsetGet($n-1);

        for($i = $n-1; $i >= 5; $i--):
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

        for($i = $n-1; $i >= 5; $i--):
            $current = $input->offsetGet($i);
            if($current < $last) return false;
        endfor;
        return true;

    }//function situationChangedToSell(){



    $input = fillInput();

    $chart = clone $input;

    $lastSituation = 'Buy';
    $sum = 0;
    $lastBuy = 0;
    $lastSell = 0;

    while($next = nextItem()):


        if( situationChangedToBuy($input) && ('Sell' == $lastSituation) ):
            //var_dump('>>>>>>>>>>>>>situationChangedToBuy');
            $pointdataX[] = $next;
            $pointdataY[] = $i;

            $lastSituation = 'Buy';
            $lastBuy = $next;
            $sum = $sum + ($lastSell - $next);
        elseif( situationChangedToSell($input) && ('Buy' == $lastSituation) ):
            //var_dump('>>>>>>>>>>>>>situationChangedToSell');
            $pointdataX[] = $next;
            $pointdataY[] = $i;

            $lastSituation = 'Sell';
            $lastSell = $next;
            $sum = $sum + ($next - $lastBuy);
        else:
            $pointdataX[] = 0;
            $pointdataY[] = $i;

        endif;

        $input->dequeue();

        $input->enqueue($next);
        $chart->enqueue($next);

    endwhile;
    require_once ('jpgraph/jpgraph.php');
    require_once ('jpgraph/jpgraph_line.php');
    require_once ('jpgraph/jpgraph_bar.php');
    require_once ('jpgraph/jpgraph_iconplot.php');
    require_once ('jpgraph/jpgraph_plotline.php');
    require_once ('jpgraph/jpgraph_scatter.php');
    require_once ('jpgraph/jpgraph_regstat.php');


// Setup the graph
    $graph = new Graph(2500,500);
    $graph->SetMargin(30,20,60,20);
    $graph->SetMarginColor('white');
    $graph->SetScale("linlin");

// Hide the frame around the graph
    $graph->SetFrame(false);

// Setup title
    $graph->title->Set("SIN(X)");
    $graph->title->SetFont(FF_VERDANA,FS_BOLD,14);



// Format the legend box
    $graph->legend->SetColor('navy');
    $graph->legend->SetFillColor('lightgreen');
    $graph->legend->SetLineWeight(1);
    $graph->legend->SetFont(FF_ARIAL,FS_BOLD,8);
    $graph->legend->SetShadow('gray@0.4',3);
    $graph->legend->SetAbsPos(15,120,'right','bottom');

    $p2 = new LinePlot($pointdataX, $pointdataY);


    $p2->mark->SetType(MARK_IMG_MBALL,'red');
    $graph->Add($p2);

    $p2->SetWeight(0);

    $mainline = new LinePlot($chartdataX, $chartdataY);
    $graph->Add($mainline);
    $mainline->value->Show();



    $graph->Stroke();




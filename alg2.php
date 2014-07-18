<?php

    $i = 0;
    $n = 10;
    $line = 0;
    $file = '';
    function nextItem(){
        global $i;
        global $n;
        global $file;
        global $line;

        $line = fgetcsv($file, 10000, ",");
        if(!$line) return false;
        $i++;
        if ($i > 1000) return false;

        $result = (float) $line[2];
        var_dump($result);

        return $result;
    }//function nextItem(){


    function fillInput(){
        global $n;
        global $line ;
        global $file;

        $vector = new \SplQueue();

        if(!$file)
            $file = fopen('eurusd.csv', 'r');

        for($i = 0; $i < $n; $i++){
            $line = fgetcsv($file, 10000, ",");
            if(!$line) return false;
            $line++;
            $date = date_create_from_format('Y.m.d', $line[0]);

            if ('2013' != $date->format('Y')) return false;

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

        for($i = $n-1; $i >= 1; $i--):
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

        for($i = $n-1; $i >= 1; $i--):
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
            var_dump('>>>>>>>>>>>>>situationChangedToBuy');
            $lastSituation = 'Buy';
            $lastBuy = $next;
            $sum = $sum + ($lastSell - $next);
        elseif( situationChangedToSell($input) && ('Buy' == $lastSituation) ):
            var_dump('>>>>>>>>>>>>>situationChangedToSell');
            $lastSituation = 'Sell';
            $lastSell = $next;
            $sum = $sum + ($next - $lastBuy);
        else:

        endif;

        $input->dequeue();

        $input->enqueue($next);

    endwhile;

    var_dump($sum);

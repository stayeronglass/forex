<?php

$i = 0;
$n = 10;
function nextItem(){
    global $i;
    global $n;

    $result = sin($i);
    $i += 0.1;

    if($i > $n ) return false;

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

    for($i = $n-1; $i >= 0; $i--):
        $current = $input->offsetGet($i);
        if($current > $last) return false;
    endfor;

    var_dump('situationChangedToBuy');
    return true;

}//function situationChangedToBuy(){

    /**
     * @param $input SplQueue
     * @return bool
     */
function situationChangedToSell($input){
    global $n;

    $last = $input->offsetGet($n-1);

    for($i = $n-1; $i >= 0; $i--):
        $current = $input->offsetGet($i);
        if($current < $last) return false;
    endfor;
    var_dump('situationChangedToSell');
    return true;

}//function situationChangedToSell(){



$input = fillInput();
$lastSituation = 'Buy';

while($next = nextItem()):


    if( situationChangedToBuy($input) && ('Sell' == $lastSituation) ):
        var_dump('>>>>>>>>>>>>>situationChangedToBuy');
        $lastSituation = 'Buy';
    elseif( situationChangedToSell($input) && ('Buy' == $lastSituation) ):
        var_dump('>>>>>>>>>>>>>situationChangedToSell');
        $lastSituation = 'Sell';
    else:

    endif;


    $input->dequeue();

    $input->enqueue($next);

endwhile;

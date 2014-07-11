<?php

$i = 0;

function nextItem(){
    global $i;

    $result = sin($i);
    $i += 0.1;
    return $result;
}

function fillInput(){
    $vector = array();
    for($i = 0; $i < 10; $i++){
        $vector[] = nextItem();
    }

    return $vector;
}

$input = fillInput();

var_dump($input);

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>

<?php

    error_reporting(-1);
    ini_set("display_errors", 1);
    $file = null;


class ny{

    public $input = array();
    public $output = 0;

    public $result = 0;
    public $inverse_result = 0;


    public function __construct($n){
        for($i = 0; $i < $n; $i++){
            $this->input[] = mt_rand(-2,2) / 10;
        }

    }//public function __construct($n){

    function sigmoid($t){
        if ($t >= 10):
            return 1;
        elseif ($t <= -10):
            return 0;
        endif;

        return 1 / (1 + exp(-$t));
    }//function sigmoid($t)

    function bipolar_sigmoid($t){
        if ($t >= 10):
            return 1;
        elseif ($t <= -10):
            return -1;
        endif;

        return (1 - exp(-$t)) / (1 + exp(-$t));
    }//function bipolar_sigmoid()

    function binary_step($t){
        if ($t >= 0):
            return 1;
        endif;

        return 0;
    }//function binary_step()

    function identity ($t){
       return $t;

    }//function identity()


    public function calc($vector){

        $result = 0;
        foreach($vector as $key => $value){
            $result += $value * $this->input[$key];
        }

        $this->result =  $this->sigmoid($result);

        $this->inverse_result = $this->result * (1 - $this->result); // говорят, что это производная сигмоиды

        return $this->result;
    }//public function calc($vector, $w){

}


 function fillInput($n){
     global $file;

    $vector = array();
    $vector[0] = -1;

    if(!$file)
        $file = fopen('eurusd.csv', 'r');

    for($i = 0; $i < $n; $i++){
        $line = fgetcsv($file, 10000, ",");
        if(!$line) return false;

        $date = date_create_from_format('Y.m.d', $line[0]);

        if ('2013' != $date->format('Y')) return false;

        $vector[$i] = (float) $line[2];
    }//for($i = 0; $i <= $this->input; $i++){

    return $vector;
}//public function fillInput(){




$n = 10; //входной слой n признаков
$H = 12; //скрытый слой H нейронов
$M = 1; //выходной слой M нейронов

$w = array(); //веса
$x = array(); //входные значения

mt_srand();

$network = array();


for($i = 0; $i < $n; $i++){
    $network['H'][] = new ny($n);
}

for($i = 0; $i < $M; $i++){
        $network['OUT'][] = new ny($H);
}

$input = fillInput($n);

var_dump($input );

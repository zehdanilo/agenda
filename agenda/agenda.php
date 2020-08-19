<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/system/sessao.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/system/includes.php');


$objs = getSessionAtribute($_SESSION["Agendas"]);

if (is_null($objs) || !$objs){
    $dado =  array(
            'title'  => 'Vacinar Goku',
            'start' => '2020-08-19',
            'end'   => '2020-08-19',
            'color' => '#3a87ad',
            'target'=> Encode(1)
        ); 

    $dados[] = $dado;    

    $dado =  array(
        'title'  => 'Bar da tripa',
        'start' => '2020-08-22',
        'end'   => '2020-08-24',
        'color' => '#dd6777',
        'target'=> Encode(2)
    );  

    $dados[] = $dado;    
} else {
    $dados = $objs;
}

$_SESSION["Agendas"] = serialize($dados);			

echo json_encode($dados);                        
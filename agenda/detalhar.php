<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/system/sessao.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/system/includes.php');

use danilo\view\Template;

$tpl = new Template("./html/template.html");
$tpl->addFile("TEMPLATE", "./html/detalhar.html");
include_once($_SERVER['DOCUMENT_ROOT'].'/system/variaveis.php');

#######
$agenda_id = Decode(getParameterGET("target"));

$objs = getSessionAtribute($_SESSION["Agendas"]);

if ($objs) {
	
    foreach ($objs as $obj){
        if (Decode($obj["target"]) == $agenda_id){
            $tpl->TITULO = $obj["title"];
        }
    }

    $tpl->show();

} else {
    $dados = array(
        "message"  => MensagemArray(CLASSE_ERRO, MENSAGEM_ERRO, $Response["message"], false),
        "callback" => null
    );

    echo json_encode($dados);
}
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/system/sessao.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/system/includes.php');

use danilo\view\Template;

$tpl = new Template("./html/template.html");
$tpl->addFile("TEMPLATE", 		"./html/index.html");
include_once($_SERVER['DOCUMENT_ROOT'].'/system/variaveis.php');

$_SESSION["Agenda"] = null;

#######
$tpl->SCRIPT = GeraTagScript("./js/calendario.js", "");


$tpl->addFile("APP_ACOES", "./html/acoes.html");
$tpl->block("BLOCK_BTN_ACAO_NOVO");

$tpl->show();
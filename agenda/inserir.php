<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/system/sessao.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/system/includes.php');

use danilo\view\Template;

$tpl = new Template("./html/template.html");
$tpl->addFile("TEMPLATE", 		"./html/inserir.html");
include_once($_SERVER['DOCUMENT_ROOT'].'/system/variaveis.php');

$_SESSION["Agenda"] = null;

#######
$tpl->FORMULARIO_ACAO   = TITULO_INSERT;
$tpl->FORMULARIO_PAGINA = "./inserir.acao.php";
$tpl->FORMULARIO_ICONE  = "pencil";

#######
$tpl->TOKEN = setToken("form-inserir");
$tpl->FORM  = "form-inserir";

#######

$tpl->show();
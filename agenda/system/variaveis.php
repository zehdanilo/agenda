<?php
/*
 * Vars definidas no sistema
 */

if($tpl->exists("DATA_HORA_ATUAL")){
	$tpl->DATA_HORA_ATUAL = DataHoraAtual();
}

if($tpl->exists("SISTEMA_LOGO")){
	$tpl->SISTEMA_LOGO = SISTEMA_LOGO;
}
if($tpl->exists("SISTEMA_TITULO")){
	$tpl->SISTEMA_TITULO = SISTEMA_TITULO;
}
if($tpl->exists("SISTEMA_AUTOR")){
	$tpl->SISTEMA_AUTOR = SISTEMA_AUTOR;
}
if($tpl->exists("SISTEMA_KEYWORDS")){
	$tpl->SISTEMA_KEYWORDS = SISTEMA_KEYWORDS;
}


if($tpl->exists("CLIENTE_URL")){
	$tpl->CLIENTE_URL = CLIENTE_URL;
}
if($tpl->exists("CLIENTE_NOME_FANTASIA")){
	$tpl->CLIENTE_NOME_FANTASIA = CLIENTE_NOME_FANTASIA;
}
if($tpl->exists("CLIENTE_COPYRIGHT")){
	$tpl->CLIENTE_COPYRIGHT = date("Y");	
}
/*
 * Var com os dados do USUARIO
 */
if (isset($_SESSION["USER"])){
	$UserAPP = unserialize($_SESSION["USER"]);
	
	if($tpl->exists("USER")){
		$tpl->USER = $UserAPP;
	}
}

/*
 * Vars da pagina atual
 */
if($tpl->exists("PAGINA_ATUAL")){
	$tpl->PAGINA_ATUAL = getPaginaAtual();
}


/*
 * Var com a mensagem de sessao
 */
if (isset($_SESSION["mensagem"]) && $tpl->exists("APP_MENSAGEM")){
	$tpl->APP_MENSAGEM = $_SESSION["mensagem"];
	unset($_SESSION["mensagem"]);
}

/*
 * Vars definidas da aplicacao
 */
if (isset($_SESSION["APP"])){
	$ModuloAPP = unserialize($_SESSION["APP"]);
	
	if($tpl->exists("APP")){
		$tpl->APP = $ModuloAPP;
	}
}

if (!isset($_SESSION["CONSULTORIO"])){
	$_SESSION["CONSULTORIO"] = null;
}else{
	$Consultorio = unserialize($_SESSION["CONSULTORIO"]);
	if ($tpl->exists("CONSULTORIO")){
		$tpl->CONSULTORIO = $Consultorio;
	}
}

if (!isset($_SESSION["UNIDADE"])){
	$_SESSION["UNIDADE"] = null;
}else{
	$Unidade = unserialize($_SESSION["UNIDADE"]);
	if ($tpl->exists("UNIDADE")){
		$tpl->UNIDADE = $Unidade;
	}
}

if (!isset($_SESSION["TV_UNIDADE"])){
	$_SESSION["TV_UNIDADE"] = null;
}else{
	$Unidade = unserialize($_SESSION["TV_UNIDADE"]);
	if ($tpl->exists("TV_UNIDADE")){
		$tpl->TV_UNIDADE = $Unidade;
	}
}

if (isset($_SESSION["APP_MENU"])){
	$MenuAPP = unserialize($_SESSION["APP_MENU"]);
	
	if($tpl->exists("APP_MENU_NAME")){
		for ($i = 0; $i < count($MenuAPP); $i++) {
			if (is_null($MenuAPP[$i]->getPai())) {
				$tpl->APP_SUBMENU_NAME  = $MenuAPP[$i]->getDescricao();
				$tpl->APP_SUBMENU_ICONE = $MenuAPP[$i]->getIcone();
				for ($j = 0; $j < count($MenuAPP); $j++) {
					if ($MenuAPP[$j]->getPai() == $MenuAPP[$i]->getId()) {
						$tpl->APP_MENU_ICONE = $MenuAPP[$j]->getIcone();
						$tpl->APP_MENU_NAME  = $MenuAPP[$j]->getDescricao();
						$tpl->APP_MENU_URL   = $MenuAPP[$j]->getUrl();
						
						$tpl->block("BLOCK_LIST_ROW_APP_MENU");
					}
				}
				$tpl->block("BLOCK_LIST_ROW_APP_SUBMENU");
			}
		}
	}
	
	if (isset($_SESSION["APP_COUNT"])){
		if ( ($_SESSION["APP_COUNT"] > 1) && ($tpl->existsBlock("BLOCK_LIST_ROW_APP_MODULO_OTHER")) ){
			$tpl->block("BLOCK_LIST_ROW_APP_MODULO_OTHER");
		}
	}
}

if ($tpl->exists("APP_ICON_TITULO")){
	$tpl->APP_ICON_TITULO = "fa-th";
}
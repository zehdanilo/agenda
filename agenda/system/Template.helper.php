<?php

use danilo\view\Template;
/**
 * Gera um ComboBox de Situacao
 *
 * @param   object $tpl
 */
function geraComboBoxSituacao(Template $template, $name, $selected){
	
	foreach(array("Ativo" => 1, "Inativo" => 0) as $key => $val){
		$template->{"OPTION_VALUE_{$name}"} = (int) $val;
		$template->{"OPTION_TEXT_{$name}"}  = $key;
		
		if($val == $selected){
			$template->{"OPTION_SELECTED_{$name}"} = "selected";
		}else {
			$template->clear("OPTION_SELECTED_{$name}");
		}
		
		$template->block("BLOCK_OPTION_{$name}");
	}
}

/**
 * Gera um ComboBox de Categoria
 *
 * @param   object $tpl
 */
function geraComboBoxCategoria(Template $template, $name, $selected){		
	$Categoria = new Categoria();
	$Categoria->setAtivo(true);

	$CategoriaController = new CategoriaController();
	$Response = $CategoriaController->find($Categoria);

	if ($Response["status"]){
		$objs = $Response["data"];

		if ($objs){
			foreach($objs as $obj){
				$template->{"OPTION_VALUE_{$name}"} = Encode($obj->getId());
				$template->{"OPTION_TEXT_{$name}"}  = $obj->getDenominacao();
				
				if($obj->getId() == $selected){
					$template->{"OPTION_SELECTED_{$name}"} = "selected";
				}else {
					$template->clear("OPTION_SELECTED_{$name}");
				}
				
				$template->block("BLOCK_OPTION_{$name}");
			}		
		}	
	}	
}

/**
 * Gera um ComboBox de Quarto
 *
 * @param   object $tpl
 */
function geraComboBoxQuarto(Template $template, $name, $selected){		
	$Quarto = new Quarto();
	$Quarto->setAtivo(true);

	$QuartoController = new QuartoController();
	$Response = $QuartoController->find($Quarto);

	if ($Response["status"]){
		$objs = $Response["data"];

		if ($objs){
			foreach($objs as $obj){
				$template->{"OPTION_VALUE_{$name}"} = Encode($obj->getId());
				$template->{"OPTION_TEXT_{$name}"}  = $obj->getDenominacao();
				
				if($obj->getId() == $selected){
					$template->{"OPTION_SELECTED_{$name}"} = "selected";
				}else {
					$template->clear("OPTION_SELECTED_{$name}");
				}
				
				$template->block("BLOCK_OPTION_{$name}");
			}		
		}	
	}	
}

function geraComboBoxStatusReserva(Template $template, $name, $selected){
	
	foreach(array("..: Todos ::." => NULL, "PRE-RESERVA" => "PRE-RESERVA", "RESERVADA" => "RESERVADA", "CANCELADA" => "CANCELADA") as $key => $val){
		$template->{"OPTION_VALUE_{$name}"} = $val;
		$template->{"OPTION_TEXT_{$name}"}  = $key;
		
		if($val == $selected){
			$template->{"OPTION_SELECTED_{$name}"} = "selected";
		}else {
			$template->clear("OPTION_SELECTED_{$name}");
		}
		
		$template->block("BLOCK_OPTION_{$name}");
	}
}


function geraComboBoxGeral(Template $template, $name, $selected, $Response){		
	
	if ($Response["status"]){
		$objs = $Response["data"];

		if ($objs){
			foreach($objs as $obj){
				$template->{"OPTION_VALUE_{$name}"} = Encode($obj->getId());
				$template->{"OPTION_TEXT_{$name}"}  = $obj->getDenominacao();
				
				if($obj->getId() == $selected){
					$template->{"OPTION_SELECTED_{$name}"} = "selected";
				}else {
					$template->clear("OPTION_SELECTED_{$name}");
				}
				
				$template->block("BLOCK_OPTION_{$name}");
			}		
		}	
	}	
}
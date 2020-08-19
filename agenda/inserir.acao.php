<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/system/sessao.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/system/includes.php');

$_SESSION["Agenda"] = null;

$form               = "form-inserir";
$token  	  		= getParameterPOST("{$form}-token");
$data_inicio 		= getParameterPOST("{$form}-data-inicio");
$nome      		    = getParameterPOST("{$form}-nome");


if ($token){
    
    $objs = getSessionAtribute($_SESSION["Agendas"]);
    
    if (is_null($objs) || !$objs ){
        $objs[] = array();
    }

    $last_id = 1;;
    foreach ($objs as $obj){
          if(Decode($obj["target"]) > $last_id ){
            $last_id = Decode($obj["target"]);
          }       
    }

    $dado =  array(
        'title'  => $nome,
        'start' => Datahora($data_inicio),
        'end'   => Datahora($data_inicio),
        'color' => '#3a87ad',
        'target'=> Encode($last_id++)
    );  

    array_push($objs, $dado);


    $_SESSION["Agendas"] = serialize($objs);
        
    $callback = "index.php?";
    $callback.= http_build_query(
        array(
            "_utm" => SomenteNumero(DataHoraAtual())
        )
    );

    $dados = array(
            "message"  => null,
            "callback" => "
                $('#main-container').html('').addClass('data-loading');
                goToURL('{$callback}');
            "
    );
    
    $_SESSION["mensagem"] = Mensagem::addMessageSuccess(MENSAGEM_SUCCESSO_INSERT);


}else{
	$erros = TransfomErrorArrayToList($_FORM->get_errors(), array("token"));

	$dados = array(
		"message"  => MensagemArray(CLASSE_ERRO, MENSAGEM_ERRO_INSERT, $erros, true, TIME_CLOSE_ALERT),
		"callback" => null
	);
}

echo json_encode($dados);
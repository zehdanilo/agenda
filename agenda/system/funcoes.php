<?php
/**
 * Funções gerais do Sistema
 **
 */

/**
 * Verifica se existe sessão do usuário
 * @return	(boolean)
 * */
function isUserAuth(){
	if (isset($_SESSION["USER"])){
		return true;
	}
	return false;
}
function VerificaUsuarioLogado(){
	if (!isUserAuth()){
		session_destroy();
		session_start();
		$_SESSION["mensagem"] = "<div class=\"alert alert-danger\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button> Sessão expirada</div>";
		
		if (isAjaxRequest()) {
			$dados = array(
					"message"  => null,
					"callback" => "goToURL('/');"
			);
			echo json_encode($dados); exit;
		} else {
			Redirecionar("/");
		}
	}
}


/**
 * Obtém um atributo do usuário logado
 * @param	(string)	$attr	Parametro do Objeto de Pessoa
 * */
function getUsuarioLogado($attr = ''){
	if (isset($_SESSION["USER"])){
		$UsuarioLogado = unserialize($_SESSION["USER"]);
		
		$obj = $UsuarioLogado;
		if ($attr != '') {
			$property = explode("->", $attr);
			
			if (sizeof($property) == 1) {
				return $obj->{"get$property[0]"}();
			}else{
				for($i = 0; $i < sizeof($property); $i++){
					$obj = $obj->$property[$i];
				}
			}
		}
		return $obj;
	}
}


/**
 * Obtém um atributo de uma variável de sessão
 * @param	(string|object)		$attr	Variável de Sessão
 * */
function getSessionAtribute($sessao, $attr = ''){
	
	if (is_object($sessao)) {
		$obj = $sessao;
	}else {
		$obj = unserialize($sessao);
	}
	
	if ($attr != '') {
		$property = explode("->", $attr);

		if (sizeof($property) == 1) {
			return $obj->$property[0];
		}else{
			for($i = 0; $i < sizeof($property); $i++){
				$obj = $obj->$property[$i];
			}
		}
	}
	return $obj;
}


/**
 * Redirecionar página
 * @param	(string)	$link	URL de destino
 */
function Redirecionar($link){
	if (headers_sent()) {
		echo '<script type="text/javascript">';
        echo 'window.location.href="'.$link.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$link.'" />';
        echo '</noscript>';
		//die("O redirecionamento falhou. <a href=\"{$link}\">CLIQUE AQUI<a> para continuar.");
	} else {
		exit(header("Location: {$link}"));
	}
}

/**
 * Coloca um objeto serializado no cookie
 */
function setObjectCookie($CookieName, $obj) {
	$_SESSION[$CookieName] = Encode(serialize($obj));
}

/**
 * Obtem um objeto do cookie
 */
function getObjectCookie($CookieName, $obj) {
	if (isset($_SESSION[$CookieName])) {
		$objCookie = unserialize(Decode($_SESSION[$CookieName]));
		if (is_object($objCookie)) {
			$obj = $objCookie;
		}
	}
	return $obj;
}

/**
 * Array com valores para mensagem
 */
function MensagemArray($status, $title, $message, $dismissable = true, $timeout = false){
	return array(
				"status"		=> $status,
				"title"			=> $title,
				"message"		=> $message,
				"dismissable"	=> $dismissable,
				"timeout"		=> $timeout
			);
}

/**
 * Transforma um array em uma lista HTML
 */
function TransfomErrorArrayToList(array $errors, array $remove = array()){
	$fields = array_keys($errors);
	$fields = array_diff($fields, $remove);
	
	$msg  = "";
	$msg .= "<ul class=\"list-unstyled font-size-12\">";
	foreach ( $fields as $field ) {
		$msg .= "<li><b>{$field}:</b>";
		$msg .= "<ul>";
		foreach ( $errors[$field] as $err ) {
			$msg .= "<li>{$err}</li>";
		}
		$msg .= "</ul>";
		$msg .= "</li>";
	}
	$msg .= "</ul>";
	
	return $msg;
}

/**
 * Criptografar texto ou número
 * @param	(string)	$valor		Variável a ser criptografada
 */
function Encode($valor, $key = null){
	if (is_null($key)) {
		$session_id = session_id();
	}else {
		$session_id = $key;
	}
	
	if(mb_strlen($valor, mb_detect_encoding($valor)) > 0){
        $enc_string = base64_encode($valor);
        $enc_string = str_replace("=","",$enc_string);
        $enc_string = strrev($enc_string);
        $md5 = md5($valor.$session_id);
        $enc_string = substr($md5,0,3).$enc_string.substr($md5,-3);
    }else{
        $enc_string = $valor;
    }
    return $enc_string;
}

/**
 * Des-Criptografar texto ou número
 * @param	(string)	$valor		Variável a ser des-criptografada
 */
function Decode($valor, $key = null){
	if (is_null($key)) {
		$session_id = session_id();
	}else {
		$session_id = $key;
	}
	
	if(mb_strlen($valor, mb_detect_encoding($valor)) > 0){
        $ini = substr($valor,0,3);
        $end = substr($valor,-3);
        $des_string = substr($valor,0,-3);
        $des_string = substr($des_string,3);
        $des_string = strrev($des_string);
        $des_string = base64_decode($des_string);
        $md5 = md5($des_string.$session_id);
        $ver = substr($md5,0,3).substr($md5,-3);
        if($ver != $ini.$end){
            $des_string = "";
        }
    }else{
        $des_string = $valor;
    }
    return $des_string;
}


/**
 * Verificar se a requisicao foi solicitada via AJAX
 */
function isAjaxRequest() {
	$header = ( array_key_exists( 'HTTP_X_REQUESTED_WITH', $_SERVER ) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : '' );
	return ( strtolower($header) == 'xmlhttprequest' );
}
function ValidaRequisicaoAjax() {
	if (!isAjaxRequest()) {
		die(MENSAGEM_ERRO);
	}
}


/**
 * Deixar string em formato camelCase
 * @param	(string)	$valor		Variável a ser transformada
 * @param	(array)		$exclude	Array contendo valores a serem ignorados
 */
function setCamelCase($valor, $exclude = array()) {
	$str = removeAcento($valor);
	$str = preg_replace('/[^a-z0-9' . implode("", $exclude) . ']+/i', ' ', $str);
	$str = ucwords(trim($str));
	return lcfirst(str_replace(" ", "", $str));
	/*
	 function camelcase($str) {
	 return preg_replace_callback(
	 '/_(\w)/',
	 function($match) {
	 return strtoupper($match[1]);
	 },
	 $str
	 );
	 }
	 */
}

/**
 * Obter o nome da página atual
 * @param	(boolean)	$extensao	Exibir a extensão do arquivo
 */
function getPaginaAtual($extensao = true) {
	$return = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
	if ($extensao == FALSE){
		$arr = explode(".", $return);
		$return = $arr[0];
	}
	return $return;
}

/**
 * Obtém um parametro passado via $_GET ou $_POST
 * @param	(string)	$param		Parâmetro a ser obtido pelo $_GET ou $_POST
 * @param	(string)	$return		Retorno do padrão do parâmetro caso não seja identificado pelo $_GET ou $_POST
 */
function getParameter($param, $return = null){
	$data = $return;
	if(isset($_REQUEST[$param])){
		if (is_array($_REQUEST[$param])) {
			foreach($_REQUEST[$param] as $value) {
				$arr[] = trim($value);
	        }
	        $data = $arr;
		}else{
			if (mb_strlen(trim($_REQUEST[$param]), mb_detect_encoding($_REQUEST[$param])) > 0) {
				$data = trim($_REQUEST[$param]);
			}
		}
	}
	return $data;
}

/**
 * Obtém um parametro passado via $_GET
 * @param	(string)	$param		Parâmetro a ser obtido pelo $_GET
 * @param	(string)	$return		Retorno do padrão do parâmetro caso não seja identificado pelo $_GET
 */
function getParameterGET($param, $return = null){
	$data = $return;
	if(isset($_GET[$param])){
		if (is_array($_GET[$param])) {
			foreach($_GET[$param] as $value) {
				$arr[] = trim($value);
	        }
	        $data = $arr;
		}else{
			if (mb_strlen(trim($_GET[$param]), mb_detect_encoding($_GET[$param])) > 0) {
				$data = trim($_GET[$param]);
			}
		}
	}
	return $data;
}

/**
 * Obtém um parametro passado via $_POST
 * @param	(string)	$param		Parâmetro a ser obtido pelo $_POST
 * @param	(string)	$return		Retorno do padrão do parâmetro caso não seja identificado pelo $_POST
 */
function getParameterPOST($param, $return = null){
	$data = $return;
	if(isset($_POST[$param])){
		if (is_array($_POST[$param])) {
			foreach($_POST[$param] as $value) {
				$arr[] = trim($value);
	        }
	        $data = $arr;
		}else{
			if (mb_strlen(trim($_POST[$param]), mb_detect_encoding($_POST[$param])) > 0) {
				$data = trim($_POST[$param]);
			}
		}
	}
	return $data;
}

/**
 * Obtém um parametro boleano passado via $_POST
 * @param	(string)	$param		Parâmetro a ser obtido pelo $_POST
 */
function getParameterBooleanPOST($param){
	$data = false;
	if(isset($_POST[$param])){
		if ($_POST[$param] == "1") {
			$data = true;
		}
	}
	return $data;
}

/**
 * Obtém um parametro passado via $_FILES
 * @param	(string)	$param		Parâmetro a ser obtido pelo $_FILES
 * @param	(string)	$return		Retorno do padrão do parâmetro caso não seja identificado pelo $_FILES
 */
function getParameterFILES($param, $return = null){
	$data = $return;
	if(isset($_FILES[$param])){
		$data = $_FILES[$param];
	}
	return $data;
}

/**
 * Gerar tag HTML de JavaScript
 * @param	(string||array)		$files		Nome/Diretório do(s) arquivo(s) a ser(em) gerado(s)
 * @param	(string)			$track		Diretório do javascript
 * @return	&lt;script src="..."&gt;&lt;/script&gt;
 */
function GeraTagScript($files = null, $track = null){
	if (is_null($track)) {
		$dir = "/sistema/resources/web/";
	} else {
		$dir = $track;
	}
	$script = "";
	if (!is_null($files)) {
		if (is_array($files)){
			foreach($files as $value){
				$script .= "\n\t<script src=\"{$dir}{$value}\"></script>";
			}
		}else{
			$script = "\n\t<script src=\"{$dir}{$files}\"></script>";
		}
	}
	return $script;
}

/**
 * Gerar tag HTML de JavaScript inline
 * @param	(string)	$valor	código javascript
 * @param	(boolean)	$jQuery	informar se inicia com o jQuery.document
 * @return	&lt;script&gt;[code]&lt;/script&gt;
 */
function GeraTagScriptInLine($valor, $jQuery = true){
	$script = "\n\t<script>";
	if ($jQuery) {
		$script.= "$(document).ready(function(){ {$valor} });";
	}else {
		$script.= $valor;
	}
	$script.= "</script>"; 
	return $script;
}

/**
 * Gerar tag HTML de CSS
 * @param	(string||array)		$files		Nome/Diretório do(s) arquivo(s) a ser(em) gerado(s)
 * @param	(string)			$track		Diretório do javascript
 * @return	&lt;link rel="stylesheet" href="..."&gt;
 */
function GeraTagStyle($files = null, $track = ""){
	if (is_null($track)) {
		$dir = "/sistema/resources/web/";
	} else {
		$dir = $track;
	}
	
	$styles = "";
	if (!is_null($files)) {
		if (is_array($files)){
			foreach($files as $value){
				$styles .= "\n\t<link rel=\"stylesheet\" href=\"{$dir}{$value}\">";
			}
		}else{
			$styles .= "\n\t<link rel=\"stylesheet\" href=\"{$dir}{$files}\">";
		}
	}
	return $styles;
}

/**
 * Gerar tag HTML de CSS inline
 * @param	(string)	$valor	código css
 * @return	&lt;style&gt;[code]&lt;/style&gt;
 */
function GeraTagStyleInLine($valor){
	return "\n\t<style>{$valor}</style>";
}

/**
 * Texto em Maiúsculo
 * @param	(string)	$string		Texto a ser transformado
 */
function upper($string){
	if (is_null($string)) {
		return $string;
	} else {
		return mb_strtoupper($string, mb_detect_encoding($string));
	}
}

/**
 * Texto em Minúsculo
 * @param	(string)	$string		Texto a ser transformado
 */
function lower($string){
	if (is_null($string)) {
		return $string;
	} else {
		return mb_strtolower($string, mb_detect_encoding($string));
	}
}

/**
 * Gerar token/hash MD5 para formulário
 * @return	Hash com 32 caracteres
 */
function setToken($form = "form") {
	$_SESSION["token-{$form}"] = md5(uniqid(mt_rand(), true));
	return $_SESSION["token-{$form}"];
}

/**
 * Obtém o token inserido em sessão
 * @return	Hash com 32 caracteres
 */
function getToken($form = "form") {
	if (isset($_SESSION["token-{$form}"])) {
		return $_SESSION["token-{$form}"];
	}
	return null;
}

/**
 * Verifica se o token é o mesmo que está em sessão
 * @param	(string)	$token		Hash a ser comparado com o token de sessão
 */
function isToken($token) {
	if ($token == getToken()) {
		return true;
	}else {
		return false;
	}
}

/**
 * Retorna os números de uma string
 * @param	(string)	$token		Hash a ser comparado com o token de sessão
 * @param	(string)	$return		Retorno do padrão do parâmetro caso não seja identificado nenhum número na string
 */
function SomenteNumero($valor, $return = NULL){
	$data = preg_replace("/[^0-9]/", "", $valor);
	if (mb_strlen($data, mb_detect_encoding($data)) == 0) {
		$data = $return;
	}
	return $data;
}

/**
 * Retorna uma string sem acentos
 * @param	(string)	$valor		String a ser removido os acentos
 */
function RemoveAcento($valor) {
	return preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $valor));
}

/**
 * Retorna uma string sem caracteres especiais
 * @param	(string)	$valor		String a ser removido os caracteres especiais
 * @param	(string)	$replace	Valor a ser substituído pelo caracterer especial
 */
function RemoveCaracterEspecial($valor, $replace = "_"){
	$var = htmlentities(trim($valor));
	$var = preg_replace("/&([a-z])[a-z]+;/i", "$1", $var);
	$var = str_replace(" ", $replace, $var);
	return $var;
}

/**
 * Verifica se a navegação está sendo por um DESKTOP
 */
function isDesktop() {
	if (! isset ( $agent )){
		$agent = $_SERVER['HTTP_USER_AGENT'];
	}
	return (bool) preg_match ( '/(bsd|linux|os\s+[x9]|solaris|windows)/i', $agent ) && ! $this->ismobile ( $agent );
}

/**
 * Verifica se a navegação está sendo por um MOBILE
 */
function isMobile() {
	if (! isset ( $agent )){
		$agent = $_SERVER['HTTP_USER_AGENT'];
	}
	return (bool) preg_match ( '/(android|blackberry|phone|ipod|palm|windows\s+ce)/i', $agent );
}

/**
 * Verifica se a navegação está sendo por um BOT
 */
function isBot() {
	if (! isset ( $agent )){
		$agent = $_SERVER['HTTP_USER_AGENT'];
	}
	return (bool) preg_match ( '/(bot|crawl|slurp|spider)/i', $agent );
}


/**
 * Retorna a data e hora atual
 * @return string com a data e hora atual no formato 'yyyy-mm-dd hh:mm:ss'
 */
function DataHoraAtual(){
	$datetime = new DateTime();
	return $datetime->format("Y-m-d H:i:s");
}


/**
 * Retorna o dia da semana por extenso
 * @param	(string)	$data	Data a ser verificada
 */
function DiaSemanaExtenso($data){
	$ano =  substr($data, 0, 4);
	$mes =  substr($data, 5, -3);
	$dia =  substr($data, 8, 9);
	$diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
    switch ($diasemana) {  
    	case 0 : $semana = 'Domingo';				break;
        case 1 : $semana = 'Segunda-Feira';			break; 
        case 2 : $semana = 'Terça-Feira';			break; 
        case 3 : $semana = 'Quarta-Feira';			break; 
        case 4 : $semana = 'Quinta-Feira';			break; 
        case 5 : $semana = 'Sexta-Feira';			break; 
        case 6 : $semana = 'Sábado';				break; 
    } 
    return $semana; 
}

/**
 * Retorna o mês do ano por extenso
 * @param	(string)	$data	Mes a ser retornado
 */
function MesExtenso($mes){ 
    switch ($mes) {  
        case 1  : $mes = 'Janeiro';			break; 
        case 2  : $mes = 'Fevereiro';		break; 
        case 3  : $mes = 'Mar&ccedil;o';	break; 
        case 4  : $mes = 'Abril';			break; 
        case 5  : $mes = 'Maio';			break; 
        case 6  : $mes = 'Junho';			break; 
        case 7  : $mes = 'Julho';			break; 
        case 8  : $mes = 'Agosto';			break; 
        case 9  : $mes = 'Setembro';		break; 
        case 10 : $mes = 'Outubro';			break; 
        case 11 : $mes = 'Novembro';		break; 
        case 12 : $mes = 'Dezembro';		break; 
    } 
    return $mes; 
}

/**
 * Retorna o ano da data
 * @param	(string)	$data	Data a ser verificada
 */
function AnoData($data){
	$ano =  substr($data, 0, 4);
	return $ano;
}


/**
 * Retorna o ícone de ativo ou inativo
 * @param	(string)	$situacao	Situação do registro
 */
function getIconeSituacao($situacao){
	$icone = "";
	switch ($situacao) {
		case "1":
			$icone = "<span class=\"label label-success\">Ativo</span>";
			break;
		case "0":
			$icone = "<span class=\"label label-danger\">Inativo</span>";
			break;
	}
	return $icone;
}

function getStatusData($data){
	$icone = "";
	if (empty($data)){
		$icone = "<span class=\"label label-warning\">Em Aberto</span>";
	}else{
		$icone = "<span class=\"label label-success\">Finalizado</span>";
	}
	return $icone;
}

function getSimNao($situacao, $label = false){
	$SimNao = "";
	switch ($situacao) {
		case "1":
			$SimNao = "Sim";
			$SimNaoLabel = "<span class=\"label label-danger\">SIM</span>";
			break;
		case "0":
			$SimNao = "Não";
			$SimNaoLabel = "<span class=\"label label-success\">NÃO</span>";
			break;
	}
	
	if ($label) {
		return $SimNaoLabel;
	} else {
		return $SimNao;
	}
}

function getAusentePresente($situacao){
	$AusentePresente = "";
	switch ($situacao) {
		case "1":
			$AusentePresente = "Presente";
			break;
		case "0":
			$AusentePresente = "Ausente";
			break;
	}
	return $AusentePresente;
}

function getIconeStatus($status){
	$icone = "";
	switch ($status) {
		case "PRE-RESERVA":
			$icone = "<span class=\"label label-warning\">Pré-reserva</span>";
			break;
		case "CANCELADA":
			$icone = "<span class=\"label label-danger\">Cancelada</span>";
			break;
		case "RESERVADA":
			$icone = "<span class=\"label label-success\">Reservada</span>";
			break;
	}
	return $icone;
}

function moeda($get_valor) {
	$source = array('.', ',');
	$replace = array('', '.');
	$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
	return $valor; //retorna o valor formatado para gravar no banco
}

/**
 * Retorna a data, e talvez a hora, no formato brasileiro
 * @param	(string)	$data_hora	Data(hora) a ser formatada
 * @param	(bool)		$time		Parametro para exibir a hora, quando existe
 * @param	(bool)		$prefixo	Parametro para exibir entre a data e a hora a string 'as'
 */
function DataHora($data_hora, $time = false, $prefixo = false){
	
	if (is_null($data_hora)) {
		return NULL;
	}
	if ($data_hora == "") {
		return "";
	}
	 
	$data = substr($data_hora, 0, 10);
	$hora = "";
	if (strlen($data_hora) > 10){
		$hora = " ".substr($data_hora, 11, 5);
	}
	if (strstr($data, "-")){
		$arr_data = explode("-", $data);
		$data = $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
	}else{
		$arr_data = explode("/", $data);
		$data = $arr_data[2]."-".$arr_data[1]."-".$arr_data[0];
	}
	if ($prefixo) {
		$var = " &agrave;s ";
	}else {
		$var = "";
	}
	 
	if ($time) {
		return $data.$var.$hora;
	}else{
		return $data;
	}
}

function getScriptSubmitForm($formDOM) {
	return GeraTagScriptInLine("setTimeout(function(){ $('{$formDOM}').submit() }, 0);");
}

function getSexoDenominacao($sexo) {
	if ($sexo == "M") {
		return "Masculino";
	} elseif ($sexo == "F") {
		return "Feminino";
	} else {
		return "";
	}
}

function isNullReplace($value, $replace) {
	if (is_null($value)) {
		return $replace;
	} else {
		return $value;
	}
}

function isNaoInformado($value) {
	if (is_null($value)) {
		return "<i class=\"text-muted font-size-11\">N&atilde;o informado</i>";
	} else {
		return $value;
	}
}

function UploadFile($file, $folder, $file_allowed, $file_max_size = 5) {
	$file_name = $file["name"];
	$file_type = $file["type"];
	$file_size = $file["size"];
	$file_temp = $file["tmp_name"];
	
	if (is_null($file_allowed)) {
		$file_allowed = array($file_type);
	}
	
	if (!is_array($file_allowed)) {
		$file_allowed = array("x");
	}
	
	if(in_array($file_type, $file_allowed)){
		if($file_size > ($file_max_size * 1024 * 1024)){
			$result = array (
				"status"  => false,
				"file"    => null,
				"message" => "Arquivo ultrapassa o limite permitido: {$file_max_size}mb"
			);
		} else {
			$datetime = new \DateTime('now');
			
			$file_name_new = $datetime->format('YmdHis');
			$file_name_new.= "-".uniqid();
			$file_name_new.= lower(strrchr($file_name, "."));
			
			$file_path_name = "{$_SERVER['DOCUMENT_ROOT']}/sistema/uploads/{$folder}/{$file_name_new}";
			
			if (move_uploaded_file($file_temp, $file_path_name)) {
				$result = array (
					"status"  => true,
					"file"    => $file_name_new,
					"message" => null
				);
			} else {
				$result = array (
					"status"  => false,
					"file"    => null,
					"message" => "Falha ao transferir arquivo"
				);
			}
		}
	} else {
		$result = array (
			"status"  => false,
			"file"    => null,
			"message" => "Tipo do arquivo não é permitido"
		);
	}
	
	return $result;
}


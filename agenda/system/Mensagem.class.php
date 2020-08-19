<?php
class Mensagem{
	
	public static function addMessage($type, $message, $title = null, $close = true, $class = null){

		$class_alert = "alert alert-".$type;

		if ($close) {
			$class_alert.= " alert-dismissible ";
		}
		if (!is_null($class)) {
			$class_alert.= " ".$class;
		}
		
		$container = "\n";
			$container.= "<div class=\"".$class_alert."\" role=\"alert\">\n";
			if ($close) {
				$container.= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\"><span aria-hidden=\"true\">&times;</span></button>\n";
			}
			if (!is_null($title)) {
				$container.= "<h4>".$title."</h4>\n";
			}
			$container.= $message."\n";	
		$container.= "</div>\n";
	
		return $container;
	}
	
	public static function addMessageSuccess($message, $title = null, $close = true, $class = null){
		return self::addMessage('success', $message, $title, $close, $class);
	}
	
	public static function addMessageInfo($message, $title = null, $close = true, $class = null){
		return self::addMessage('info', $message, $title, $close, $class);
	}
	
	public static function addMessageWarning($message, $title = null, $close = true, $class = null){
		return self::addMessage('warning', $message, $title, $close, $class);
	}
	
	public static function addMessageDanger($message, $title = null, $close = true, $class = null){
		return self::addMessage('danger', $message, $title, $close, $class);
	}
	
	public static function addMessageArray($data){
		if (array_key_exists("status", $data)) {
			$type = $data["status"];
		}else{
			$type = "info";
		}
		
		if (array_key_exists("message", $data)) {
			$message = $data["message"];
		}else{
			$message = "";
		}
		
		if (array_key_exists("title", $data)) {
			$title = $data["title"];
		}else{
			$title = null;
		}
		
		if (array_key_exists("close", $data)) {
			$close = $data["close"];
		}else{
			$close = true;
		}
		
		if (array_key_exists("class", $data)) {
			$class = $data["class"];
		}else{
			$class = null;
		}
		
		return self::addMessage($type, $message, $title, $close, $class);
	}
	
}

?>
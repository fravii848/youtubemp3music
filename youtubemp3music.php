<?php
define('BOT_TOKEN', 'INSIRA AQUI O TOKEN DO SEU BOT');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
function processMessage($message) {
  // processa a mensagem recebida
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    
    $text = $message['text'];//texto recebido na mensagem

    if (strpos($text, "/start") === 0) {
		//envia a mensagem ao usuário
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Olá, '. $message['from']['first_name'].
		'! Eu sou um Bot que te ajudará com o download de músicas do YouTube, cole o link do vídeo abaixo!'));
    } 
    else if (strpos($text, "youtube.com/watch?v=") !== false) {
		$text = explode("?v=", $message['text']);
		$json_url = file_get_contents("https://youtubemp3music.info/@api/json/mp3/".$text[1]);   
		$json_str = json_decode($json_url, true);
		$itens = $json_str['vidInfo'];
		$dlink = print_r(($itens[0]['dloadUrl']), true); 
		$dlink = "https:".$dlink;
        sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => "$dlink")); }
    else if (strpos($text, "youtu.be") !== false) {
		$text = explode(".be/", $message['text']);
		$json_url = file_get_contents("https://youtubemp3music.info/@api/json/mp3/".$text[1]);   
		$json_str = json_decode($json_url, true);
		$itens = $json_str['vidInfo'];
		$dlink = print_r(($itens[0]['dloadUrl']), true); 
		$dlink = "https:".$dlink;
        sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => "$dlink")); }	else { sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Olá, '. $message['from']['first_name'].
		"! O Link para download é inválido! Tente novamente!"));
    }
  }
}
function sendMessage($method, $parameters) {
  $options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => json_encode($parameters),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
    )
);
$context  = stream_context_create( $options );
file_get_contents(API_URL.$method, false, $context );
}
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);
if (isset($update["message"])) {
  processMessage($update["message"]);
}
?>

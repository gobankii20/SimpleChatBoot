<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 
 'YViittjZamNdeHQsO8/YAIxRoR87hAjtMRJ70Sf0lK56yZaeLixmhIkRSxBYcrGS1i2bSm1KtLFr9gkni41o/dQHyVRO/WVEql0EHF3zzdIey9tfAWP/4tWJD5LuWy1HMB1nua+LAhmFYEIlTEYYjwdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   if( $event['message']['type'] == 'text' )
   {
    $text = $event['message']['text'];
    if(strstr($text,"แท็กซี่")){
    $reply_message = 'กรุณาป้อนจุดรับและจุดส่ง ตัวอย่างเช่น พระรามเก้า, ลาดพร้าว';
    }else if($text == 'เป็นบอทฉี่ได้ด้วยเหรอ'){
       $reply_message = 'อ้าว ทำไมนายถามแมวๆ อย่างนี้ละ';
    }else if($text == 'วันนี้ว่างไหม'){
      $reply_message = 'สำหรับ Gobank แล้วผมว่างทุกวันครับ';
    }else if($text == 'ขอดูรูปน้องแมวหน่อย'){
      $reply_message = 'https://i.pinimg.com/originals/cc/22/d1/cc22d10d9096e70fe3dbe3be2630182b.jpg';
    }
    
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>

<?php
/**
 * Created by PhpStorm.
 * User: chrisvv
 * Date: 2015/02/06
 * Time: 1:42 PM
 */

// Prepares a text message for sending depending on keyword.
function prepareTextMessage($fromUserName, $toUserName, $content){



    return       '<xml>
                     <ToUserName><![CDATA['.$toUserName.']]></ToUserName>
                     <FromUserName><![CDATA['.$fromUserName.']]></FromUserName>
                     <CreateTime>'.mktime().'</CreateTime>
                     <MsgType><![CDATA[text]]></MsgType>
                     <Content><![CDATA['.$content.']]></Content>
                 </xml>';



}

// Prepares a rich-text message for sending depending on keyword.
function prepareRichTextMessage($fromUserName, $toUserName, $title1, $description1, $picUrl1, $title2, $description2, $picUrl2, $url){


    return      '<xml>
                     <ToUserName><![CDATA['.$toUserName.']]></ToUserName>
                     <FromUserName><![CDATA['.$fromUserName.']]></FromUserName>
                     <CreateTime>'.mktime().'</CreateTime>
                     <MsgType><![CDATA[news]]></MsgType>
                     <ArticleCount>2</ArticleCount>
                     <Articles>
                     <item>
                       <Title><![CDATA['.$title1.']]></Title>
                       <Description><![CDATA['.$description1.']]></Description>
                       <PicUrl><![CDATA['.$picUrl1.']]></PicUrl>
                       <Url><![CDATA['.$url.']]></Url>
                     </item>
                     <item>
                       <Title><![CDATA['.$title2.']]></Title>
                       <Description><![CDATA['.$description2.']]></Description>
                       <PicUrl><![CDATA['.$picUrl2.']]></PicUrl>
                       <Url><![CDATA['.$url.']]></Url>
                     </item>
                     </Articles>
                 </xml>';

}

function curl_send_json($url,$json=NULL,$method = 'POST'){

    $handle = curl_init();


    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true
    );

    curl_setopt_array($handle,($options));

    $resp = curl_exec($handle);

    $response =  json_decode($resp);

    return $response;
}




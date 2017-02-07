<?php


App::uses('Component', 'Controller');




class WechatApiComponent extends Component
{
    public $appID;
    public $appSecret;

    public function __construct(ComponentCollection $collection, $settings = array())
    {
       $this->appID = Configure::read('appID');
       $this->appSecret= Configure::read('appSecret');
    }

    public function refreshFollowers(){

        $followers = $this->get_followers();
        if (empty($followers)){

            $return = array( 'result' => array(
                'code' => false,
                'message' => 'No Followers found!'
            ));

        } else {
            $nextPage = false;

            $this->update_followers_from_api($followers->data->openid);
            if ($followers->count != $followers->total){
                $nextPage = $followers->next_openid;
            }
            while ($nextPage){
                $followers = $this->get_followers($nextPage);
                $nextPage = false;
                if (!empty($followers->data)){
                    $this->update_followers_from_api($followers->data->openid);
                    if ($followers->count != $followers->total){
                        $nextPage = $followers->next_openid;
                    }
                }
            }

            $return = array( 'result' => array(
                'code' => true,
                'message' => 'Updated Followers.'
            ));


        }

        return $return;

    }



    // Prepares a text message for sending depending on keyword.
    public function prepareTextMessage($fromUserName, $toUserName, $content){



        return       '<xml>
                     <ToUserName><![CDATA['.$toUserName.']]></ToUserName>
                     <FromUserName><![CDATA['.$fromUserName.']]></FromUserName>
                     <CreateTime>'.time().'</CreateTime>
                     <MsgType><![CDATA[text]]></MsgType>
                     <Content><![CDATA['.$content.']]></Content>
                 </xml>';



    }

// Prepares a rich-text message for sending depending on keyword.
    public function prepareRichTextMessage($fromUserName, $toUserName, $title1, $description1, $picUrl1, $title2, $description2, $picUrl2, $url){


        return      '<xml>
                     <ToUserName><![CDATA['.$toUserName.']]></ToUserName>
                     <FromUserName><![CDATA['.$fromUserName.']]></FromUserName>
                     <CreateTime>'.time().'</CreateTime>
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

    public function curl_send_json($url,$json=NULL,$method = 'POST'){

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

    public function getAccessToken(){

        $now = time();

        //Access token gets read from cache----------------------------------------------------------------------------------
        $oa_token = Cache::read('oa_token', 'default');
        $oa_token_expires = Cache::read('oa_token_expires', 'default');


        if (empty($oa_token_expires) || $now >= $oa_token_expires) {


            //get the access_token + expires_in values in string format
            $tokenString = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appID.'&secret='.$this->appSecret);

            if(!empty($tokenString)) {

                //the access_token + expires_in values as an object-------------------------------------------------------------
                $tokenObj = json_decode($tokenString);

                $oa_token = $tokenObj->access_token;
                $oa_token_expires = $tokenObj->expires_in + $now;

                Cache::write('oa_token', $oa_token, 'default');
                Cache::write('oa_token_expires', $oa_token_expires, 'default');
            }
        }

        return $oa_token;
    }



    public function push($toUser = NULL, $message = NULL){

        if(!empty($toUser)) {

            $accessToken = $this->getAccessToken();


            // Send push message------------------------------------------------------------------------------

            $jsonOutput = json_encode(array(
                'touser' => $toUser,
                'msgtype' => 'text',
                'text' => array(
                    'content' => $message
                )

            ));

            $sendPush = $this->curl_send_json('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $accessToken, $jsonOutput);

            //Echo response from API to test
//            echo '<pre>';
//            debug($jsonOutput);
//            debug($accessToken);
//            debug($sendPush);
//            echo('Ends here.');
//            echo '</pre>';

            return $sendPush;
        }
        else{

            echo 'Please specify a $toUser value';
            return false;
        }
    }

    public function get_menu(){
        //Do db query to replace $dbMenu
        $this->Menu = ClassRegistry::init('Menu');
        $dbMenu = $this->Menu->find('first');


        if (empty($dbMenu)) {
            $menuArray = array(
                'button' => array(
                    array(
                        'type' => 'click',
                        'name' => 'Daily Song',
                        'key' => 'V1001_TODAY_MUSIC',
                    ),
                    array(
                        'type' => 'click',
                        'name' => 'Weekly Song',
                        'key' => 'V1002_WEEKLY_MUSIC',
                    ),
                    array(
                        'name' => 'Menu',
                        'sub_button' => array(
                            array(
                                'type' => 'view',
                                'name' => 'Search',
                                'url' => 'http://www.google.com',

                            ),
                            array(
                                'type' => 'view',
                                'name' => 'Video',
                                'url' => 'http://www.youtube.com',

                            ),
                            array(
                                'type' => 'click',
                                'name' => 'Like Us',
                                'key' => 'V1001_GOOD',

                            )
                        )
                    )
                )
            );
        }else{
            $menuArray = unserialize($dbMenu['Menu']['value']);
        }
        return $menuArray;
    }

    public function update_menu($menuArray = array()){

        $accessToken = $this->getAccessToken();

        if(empty($menuArray)) {
            $menuArray = $this->get_menu();
        } else {
            //save serialize($newMenu) to database
            $this->Menu = ClassRegistry::init('Menu');
            $saveData = array(
                'id'=>1,
                'name'=>'Default Menu',
                'value'=>serialize($menuArray)
            );
            $this->Menu->save($saveData);
        }

        $jsonMenu = json_encode($menuArray);
        $sendMenu = $this->curl_send_json('https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$accessToken, $jsonMenu);

//        echo '<pre>';
//        debug($jsonMenu);
//        debug($accessToken);
//        debug($sendMenu);
//        echo '</pre>';

    }

    public function genQR(){
        $now = time();
        $oa_token = $this->getAccessToken();
        $baseJpgExpires = Cache::read('baseJpgExpires', 'default');
        $varArray = array('expire_seconds' => 1800, 'action_name' => 'QR_SCENE', 'action_info' => array('scene' => array('scene_id' => 123)));
        $jsonVar = json_encode($varArray);


        if(empty($baseJpgExpires) || $now > $baseJpgExpires){

            if($genQRticket = $this->curl_send_json('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$oa_token, $jsonVar)){

                if($jpgVar = file_get_contents('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $genQRticket->ticket)) {

                    $baseJpgExpires = $now + 1800;
                    Cache::write('baseJpgExpires', $baseJpgExpires, 'default');
                    $baseJpg = base64_encode($jpgVar);
                    Cache::write('baseJpg', $baseJpg, 'default');
                    return $baseJpg;
                }
            }
        }

        else{
            //Read base64 from cache
            $baseJpg = Cache::read('baseJpg', 'default');
            return $baseJpg;
        }

    }

//    public function groupChat(){
//
//        $oa_token = $this->getAccessToken();
//        $varArray = array('user_id' => 'baronsaturday', 'room_name' => 'test_room');
//        $jsonVar = json_encode($varArray);
//
//        if ($genGroupChat = $this->curl_send_json('https://api.weixin.qq.com/cgi-bin/chatroom/create?access_token='.$oa_token, $jsonVar)){
//
//            debug($genGroupChat);
//            die();
//        }
//
//    }


    public function get_followers($nextOpenId=null){
        $data = false;
        $oa_token = $this->getAccessToken();

        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$oa_token;
        if (!empty($nextOpenId)) $url .= '&next_openid='.$nextOpenId;
        $response = file_get_contents($url);
        $data = json_decode($response);

        return $data;
    }

    public function update_followers_from_api($openIds=array()){

        $this->Follower = ClassRegistry::init('Follower');

        if (empty($openIds)){
            die('Please specify the list of openIds to update');
        }

        $oa_token = $this->getAccessToken();

        if(!empty($oa_token)){

            foreach ($openIds as $openId){

                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$oa_token.'&openid='.$openId.'&lang=en';
                $response = file_get_contents($url);
                $data = json_decode($response);

                if (!empty($data->nickname)){
                    $saveData = array(
                        'subscribed'=>$data->subscribe,
                        'nickname'=>$data->nickname,
                        'sex'=>$data->sex,
                        'language'=>$data->language,
                        'city'=>$data->city,
                        'province'=>$data->province,
                        'country'=>$data->country,
                        'image'=>$data->headimgurl,
                        'openid'=>$openId,
                        'updated_from_api' => date('Y-m-d H:i:s')
                    );


                    //Check if the user exists
                    $user = $this->Follower->find('first', array('conditions' => array('openid' => $openId), 'recursive' => -1));

                    $this->Follower->create();
                    if (!empty($user)){
                        //Update user
                        $this->Follower->id = $user['Follower']['id'];
                        if ($this->Follower->save($saveData)){
//                            debug('saved '.$openId."\n");
                        } else {
//                           debug('error saving '.$openId."\n");
                        }
                    } else {
                        //Write new user
                        $saveData['created'] = date('Y-m-d H:i:s');
                        if ($this->Follower->save($saveData)){
//                            debug('created '.$openId."\n");
                        } else {
//                            debug('error creating '.$openId."\n");
                        }
                    }
                } else {
//                    debug('error fetching '.$openId."\n");
                }
            }
        }
    }

    public function fire_and_forget_post($url, $params = array())
    {
        // create POST string
        $post_string = http_build_query($params);

        // get URL segments
        $parts = parse_url($url);

        // workout port and open socket
        $port = isset($parts['port']) ? $parts['port'] : 80;

        $domain_to_use = $parts['host'];
        $port_to_use = $port;
        $mustUseProxy = false;

        //Decide if to use the proxy server or not
//        if(strlen(trim(Configure::read('proxy_ip')))!=0) {
//            $domain_to_use = Configure::read('proxy_ip');
//            $port_to_use = Configure::read('proxy_port');
//            $mustUseProxy=true;
//        }

        $fp = fsockopen($domain_to_use, $port_to_use, $errno, $errstr, 30);
        if (!$fp){
            return false;
        } else {
            // create output string
            $output  = "POST " .$mustUseProxy===true?$parts['host'].":".$port:'';
            $output.= "/".$parts['path'] . " HTTP/1.1\r\n";
            $output .= "Host: " . $parts['host'] . "\r\n";
            $output .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $output .= "Content-Length: " . strlen($post_string) . "\r\n";
            $output .= "Connection: Close\r\n\r\n";
            $output .= isset($post_string) ? $post_string : '';

            // send output to $url handle
            fwrite($fp, $output);
            fclose($fp);
        }

//        $fp = fopen('/tmp/fire_and_forget.txt','a');
//        fwrite($fp, "\n—————");
//        fwrite($fp, date('Y-m-d H:i:s'));
//        fwrite($fp, "—————\n");
//        fwrite($fp, "Url: ".$url."\n");
//        fwrite($fp, print_r($post_string,1));
//        fwrite($fp, "\n\n");
//        fclose($fp);
    }

    public function print_r_reverse($in) {
        $lines = explode("\n", trim(str_replace("\r",'',$in)));
        if (trim($lines[0]) != 'Array') {
            // bottomed out to something that isn't an array
            return $in;
        } else {
            // this is an array, lets parse it
            if (preg_match("/(\s{5,})\(/", $lines[1], $match)) {
                // this is a tested array/recursive call to this function
                // take a set of spaces off the beginning
                $spaces = $match[1];
                $spaces_length = strlen($spaces);
                $lines_total = count($lines);
                for ($i = 0; $i < $lines_total; $i++) {
                    if (substr($lines[$i], 0, $spaces_length) == $spaces) {
                        $lines[$i] = substr($lines[$i], $spaces_length);
                    }
                }
            }
            array_shift($lines); // Array
            array_shift($lines); // (
            array_pop($lines); // )
            $in = implode("\n", $lines);
            // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
            preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            $pos = array();
            $previous_key = '';
            $in_length = strlen($in);
            // store the following in $pos:
            // array with key = key of the parsed array's item
            // value = array(start position in $in, $end position in $in)
            foreach ($matches as $match) {
                $key = $match[1][0];
                $start = $match[0][1] + strlen($match[0][0]);
                $pos[$key] = array($start, $in_length);
                if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1;
                $previous_key = $key;
            }
            $ret = array();
            foreach ($pos as $key => $where) {
                // recursively see if the parsed out value is an array too
                $ret[$key] = $this->print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
            }
            return $ret;
        }
    }

}
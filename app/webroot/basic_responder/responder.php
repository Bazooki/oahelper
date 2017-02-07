<?php

    include 'functions.php';
    include 'config.php';

    //Define local signature values
    $array[] = $_GET['timestamp'];
    $array[] = $_GET['nonce'];
    //Defined in config
    $array[] = $token;

    //Formats array in correct order as mentioned in the wiki
    sort($array);
    $str = implode($array);

    //grabs php input
    $xmlInput = file_get_contents('php://input');

    //converts xml to object
    $xml = simplexml_load_string($xmlInput);


//Compares the local and API signatures
if (sha1($str) == $_GET['signature']){

    //sends 'echostr' to confirm that the local signature matches the signature from the API
    if(!empty($_GET['echostr'])){

        echo ($_GET['echostr']);
        die();
    }

    //debug code for if something went wrong
    else{

        //connects to db
        $dbHandle = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['db']);

        $content = strtolower($xml->Content);
        $msgType = strtolower($xml->MsgType);
        $event = strtolower($xml->Event);
        $eventKey = strtolower($xml->EventKey);




        if(!empty($dbHandle)){

            //logs the db connect event
            $errlog = fopen('errlog.txt', 'a');
            fwrite($errlog, 'Database accessed: '.date('Y-m-d H:i:s'). "\r\n");
            fclose($errlog);


            //Insert into db field xml_in
            $sql = "INSERT INTO xml_logs (openid,created,xml_in,msgtype,message) VALUES ('".$xml->openid."','".date('Y-m-d H:i:s')."','$xmlInput','$msgType','".$content."')";
            $x = $dbHandle->query($sql);

            //checks if query ran successfully
            if($x === TRUE){

                //gets last id handled
                $lastId = $dbHandle->insert_id;


//Switch handles media types and their associated keywords ------------------------------------------------------------


        switch($msgType) {

            //Respond specifically if  the user has sent text input
            case 'text':
                switch ($content) {
                    // Send a text message
                    case 'bob';
                        $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Hello Bob');
                        //removes whitespace
                        $response = preg_replace('/\s+/', ' ', $response);
                        break;
                    // Send a rich text message
                    case 'news';
                        $response = prepareRichTextMessage($xml->ToUserName, $xml->FromUserName, 'Kaboom.', '', 'http://s22.postimg.org/sb8rc1u0x/supernova_l.jpg', 'Supernova explodes - gas leak suspected', '', 'http://s12.postimg.org/gn83c1l3x/supernova_s.jpg', 'www.google.com');
                        //removes whitespace
                        $response = preg_replace('/\s+/', ' ', $response);
                        break;

                    default:
                        $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unrecognized keyword');
                }
            break;

            case 'image':
                $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: '. $msgType);
                break;

            case 'voice':
                $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: '. $msgType);
            break;

            case 'video':
                $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: '. $msgType);
                break;

            case 'location':
                $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: '. $msgType);
                break;

            case 'link':
                $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: '. $msgType);
                break;

            case 'event':
            // Handle subscription and click event situations
                switch ($event) {
                    case 'subscribe';
                        $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Welcome to our OA!');
                        break;
                    case 'location';
                        $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Location received');
                        break;
                    case 'click';
                        $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'you have clicked '. $eventKey);
                        break;
                    case 'scan';
                        $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'you have scanned a QR code');
                        break;

                    default:
                        $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unrecognized event');
                }
                break;

            default:
                $response = prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unrecognized input method');
        }

        echo $response;


//End switch------------------------------------------------------------------------------------------------------------

                //Insert into db field xml_out
                $return = $response;
                $errlog = fopen('errlog.txt', 'a');
                fwrite($errlog, $lastId."\r\n");
                fclose($errlog);

                //Insert xml data sent to user into db for debugging and close handler
                $sql = "UPDATE xml_logs SET xml_out = '$return' WHERE id = $lastId";
                $dbHandle->query($sql);

            }


//            Log the received data to a file

            $errlog = fopen('errlog.txt', 'a');
            fwrite($errlog, $content);
            fclose($errlog);

            $dbHandle->close();




        }
        //in case database cant be accessed: log it
        else{

            $errlog = fopen('errlog.txt', 'a');
            fwrite($errlog, 'Database NOT accessed: '.date('Y-m-d H:i:s'). "\r\n");
            fclose($errlog);

        }





    }

    //if sha values don't match
}else{

    die('access denied.');

}



<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class ResponderController extends AppController
{

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('XmlLog');

    /**
     * Displays a view
     *
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */


    public function index()
    {
        CakeLog::write('error', 'test');
        $this->layout = 'ajax';
        $token = Configure::read('token');

        //Define local signature values
        $array[] = $this->params->query['timestamp'];
        $array[] = $this->params->query['nonce'];
        //Defined in config
        $array[] = $token;


        //Formats array in correct order as mentioned in the wiki
        sort($array, SORT_STRING);
        $str = implode($array);

        //grabs php input
        $xmlInput = file_get_contents('php://input');

        //converts xml to object
        $xml = simplexml_load_string($xmlInput);


//Compares the local and API signatures
        if (sha1($str) == isset($this->params->query['signature'])) {

//            sends 'echostr' to confirm that the local signature matches the signature from the API
            if (!empty($this->params->query['echostr'])) {

                echo($this->params->query['echostr']);
                die();
            } //debug code for if something went wrong


            else if (isset($this->params->query['timestamp']) && isset($this->params->query['nonce'])) {


                $openId = strtolower($xml->openid);
                $content = strtolower($xml->Content);
                $msgType = strtolower($xml->MsgType);
                $event = strtolower($xml->Event);
                $eventKey = strtolower($xml->EventKey);

                $this->loadModel('Follower');
                $exists = $this->Follower->find('first', array('conditions' => array(
                    'openid' => $openId
                )));


                if(!empty($exists)){
                //get followerid and save to xmllog
                    $followerId = $exists['Follower']['id'];
                }else{
                //create a new follower, getid save to xmllog, populate follower (fire and forget) from api
                    $this->Follower->create();
                    $this->Follower->save(array('openid' => $openId));
                    $followerId = $this->Follower->id;
                }
                if (empty($exists['Follower']['nickname'])){
                    //thisfire and forget new function to update users(array(openid1),array(followerId1))
                    $this->WechatApi->fire_and_forget_post(Router::url(array('controller'=>'followers','action'=>'update_from_api',$openId),true));
                }


                $this->XmlLog->create();

                $saveInput = array(
                    'openid' => $openId,
                    'xml_in' => $xmlInput,
                    'msgtype' => $msgType,
                    'message' => $content,
                    'follower_id' => $followerId

                );



                $result = $this->XmlLog->save($saveInput);

//                die(print_r($result));

                //checks if query ran successfully
                if ($result) {

                    //gets last id handled
                    $lastId = $this->XmlLog->id;


//Switch handles media types and their associated keywords ------------------------------------------------------------


                    switch ($msgType) {



                        //Respond specifically if  the user has sent text input
                        case 'text':
                            switch ($content) {
                                // Send a text message
                                case 'bob';
                                    $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Hello Bob');
                                    //removes whitespace
                                    $response = preg_replace('/\s+/', ' ', $response);
                                    break;
                                // Send a rich text message
                                case 'news';
                                    $response = $this->WechatApi->prepareRichTextMessage($xml->ToUserName, $xml->FromUserName, 'Kaboom.', '', 'http://s22.postimg.org/sb8rc1u0x/supernova_l.jpg', 'Supernova explodes - gas leak suspected', '', 'http://s12.postimg.org/gn83c1l3x/supernova_s.jpg', Router::url(array('controller'=>'examples','action'=>'index'), true));
                                    //removes whitespace
                                    $response = preg_replace('/\s+/', ' ', $response);
                                    break;

                                default:
                                    $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unrecognized keyword');
                            }
                            break;

                        case 'image':
                            $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: ' . $msgType);
                            break;

                        case 'voice':
                            $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: ' . $msgType);
                            break;

                        case 'video':
                            $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: ' . $msgType);
                            break;

                        case 'location':
                            $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: ' . $msgType);
                            break;

                        case 'link':
                            $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unhandled input method: ' . $msgType);
                            break;

                        case 'event':
                            // Handle subscription and click event situations
                            switch ($event) {
                                case 'subscribe';
                                    $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Welcome to our OA!');
                                    break;
                                case 'location';
                                    $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Location received');
                                    break;
                                case 'click';
                                    $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'you have clicked ' . $eventKey);
                                    break;
                                case 'scan';
                                    $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'you have scanned a QR code');
                                    break;

                                default:
                                    $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unrecognized event');
                            }
                            break;

                        default:
                            $response = $this->WechatApi->prepareTextMessage($xml->ToUserName, $xml->FromUserName, 'Unrecognized input method');


                    }

                    $this->set('response', $response);
                    CakeLog::write('debug', print_r($response, 1));
//End switch------------------------------------------------------------------------------------------------------------

                    //Insert xml data sent to user into db for debugging and close handler
//                    $sql = "UPDATE xml_logs SET xml_out = '$return' WHERE id = $lastId";


                    $saveOutput = array(
                        'xml_out' => $response,
                        'id' => $lastId
                    );
//                    $this->XmlLog->id = $lastId;
                    $this->XmlLog->create();
                    $this->XmlLog->save($saveOutput);

                }else{

                    CakeLog::write('debug', 'errorsavingxmllog');
                }


            }

            //if sha values don't match
        } else {
            $response = 'access denied.';
            CakeLog::write('debug', $response);
            $this->set('response', $response);
//            debug($this->log('access denied.', 'whatnot'));
            die($response);

        }
    }
}
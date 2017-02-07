<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $theme = 'default';
    public $helpers = array('Form' => array(
        'div'=>false
    ),
        'Html', 'Cache');


    public $components = array(

        'WechatApi',

        'Paginator',

        'Session',

        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'admin_users',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'admin_users',
                'action' => 'index'
            ),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'AdminUser',
                    'controller' => 'admin_users','action' => 'login'
                )
            ),
            'loginAction' => array(
                'controller' => 'admin_users',
                'action' => 'login',
            ),
        )
        );



    public function isAuthorized($user){

        return false;

    }

    public function beforeFilter(){

        $this->viewClass = 'Theme';

        if (!$this->params['prefix'] == 'admin'){
            $this->Auth->allow();
//            $this->theme = 'default';
//            $this->theme = 'admin';
        }else{

            $this->theme = 'admin';
        }

    }




}

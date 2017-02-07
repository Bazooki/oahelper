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
class AdministratorsController extends AppController
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



    public function admin_index() {

        $accessToken = $this->WechatApi->getAccessToken();
        $this->set('accessToken', $accessToken);

        // Number of rows in xml_log table
        $totalRows = $this->XmlLog->find('count');
        $this->set('totalRows', $totalRows);

        if(isset($this->request->data['updateMenu'])) {
            if (isset($this->request->data['theMenu']) && !empty($this->request->data['theMenu'])){
                $newMenu = $this->WechatApi->print_r_reverse($this->request->data['theMenu']);
                $this->WechatApi->update_menu($newMenu);
            }
        }
        //grab menu data from db
        $theMenu = $this->WechatApi->get_menu();
        $this->set('theMenu',$theMenu);

        if(isset($this->request->data['submit'])) {
            $this->WechatApi->push($this->request->data['XmlLog']['FromUserName'], $this->request->data['XmlLog']['yourPush']);

        }

        //shows user push message rows----------------------------------------------------------------------------------

        $this->Paginator->settings = array(
            'limit' => 10
        );
        $tableData = $this->Paginator->paginate('XmlLog');

        $this->set('tableData',$tableData);

    }

    public function admin_theme(){



    }
}
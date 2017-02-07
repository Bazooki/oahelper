<?php
App::uses('AppController', 'Controller');
/**
 * AdminUsers Controller
 *
 * @property AdminUser $AdminUser
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class AdminUsersController extends AppController {


/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');


public function beforeFilter(){

	parent::beforeFilter();
	$this->Auth->allow('admin_login', 'admin_add');


}

	public function admin_login(){

		if ($this->request->is('post')){

			if($this->Auth->login()){

				return $this->redirect(array('controller' => 'admin_users', 'action' => 'dash'));

			}else{

				$this->Session->setFlash('Invalid username or password.');
			}
		}
	}


	public function admin_logout() {

		if($this->Auth->logout()) {

			return $this->redirect(array('controller' => 'admin_users', 'action' => 'login'));
		}
		else{

			$this->Session->setFlash('Could not log you out.');
		}
	}



/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->AdminUser->recursive = 0;
		$this->Paginator->settings = array(
			'limit' => 10
		);
		$this->set('adminUsers', $this->Paginator->paginate());

	}


	public function admin_dash() {
//		$this->WechatApi->refreshFollowers();
//		$this->WechatApi->get_followers();
//		$this->WechatApi->update_users_from_api();
		$this->AdminUser->recursive = 0;
		$this->set('adminUsers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->AdminUser->exists($id)) {
			throw new NotFoundException(__('Invalid admin user'));
		}
		$options = array('conditions' => array('AdminUser.' . $this->AdminUser->primaryKey => $id));
		$this->set('adminUser', $this->AdminUser->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->AdminUser->create();
			if ($this->AdminUser->save($this->request->data)) {
				$this->Session->setFlash(__('The admin user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The admin user could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->AdminUser->exists($id)) {
			throw new NotFoundException(__('Invalid admin user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->AdminUser->save($this->request->data)) {
				$this->Session->setFlash(__('The admin user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The admin user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AdminUser.' . $this->AdminUser->primaryKey => $id));
			$this->request->data = $this->AdminUser->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->AdminUser->id = $id;
		if (!$this->AdminUser->exists()) {
			throw new NotFoundException(__('Invalid admin user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->AdminUser->delete()) {
			$this->Session->setFlash(__('The admin user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The admin user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}


}

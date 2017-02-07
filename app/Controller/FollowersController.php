<?php
App::uses('AppController', 'Controller');
/**
 * Followers Controller
 *
 * @property Follower $Follower
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class FollowersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');



/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Follower->recursive = 0;
		$this->Paginator->settings = array(
			'limit' => 10
		);
		$this->set('followers', $this->Paginator->paginate());

	}

	public function admin_fetch_followers(){

		$response = $this->WechatApi->refreshFollowers();
		$this->Session->setFlash($response['result']['message']);

		$this->redirect(array('action' => 'index'));

	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Follower->exists($id)) {
			throw new NotFoundException(__('Invalid follower'));
		}
		$options = array('conditions' => array('Follower.' . $this->Follower->primaryKey => $id));
		$this->set('follower', $this->Follower->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {

		if ($this->request->is('post')) {
			$this->Follower->create();
			if ($this->Follower->save($this->request->data)) {
				$this->Session->setFlash(__('The follower has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The follower could not be saved. Please, try again.'));
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
		if (!$this->Follower->exists($id)) {
			throw new NotFoundException(__('Invalid follower'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Follower->save($this->request->data)) {
				$this->Session->setFlash(__('The follower has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The follower could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Follower.' . $this->Follower->primaryKey => $id));
			$this->request->data = $this->Follower->find('first', $options);
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
		$this->Follower->id = $id;
		if (!$this->Follower->exists()) {
			throw new NotFoundException(__('Invalid follower'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Follower->delete()) {
			$this->Session->setFlash(__('The follower has been deleted.'));
		} else {
			$this->Session->setFlash(__('The follower could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}


	public function update_from_api($openId=null){
		$this->WechatApi->update_followers_from_api(array($openId));
	}

}

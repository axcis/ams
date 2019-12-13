<?php

/**
 * SenderListController
 * @author takanori_gozu
 *
 */
class SenderList extends MY_Controller {
	
	/**
	 * Index
	 */
	public function index() {
		
		$this->load->model('sender/SenderListModel', 'model');
		$this->load->library('dao/SenderDao');
		
		$this->set('class_key', 'sender');
		$this->set('class_path', 'sender/Sender');
		
		$this->set('list', $this->model->get_list());
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		$this->set('no_search', '1'); //検索なし
		
		$this->view('sender/sender_list');
	}
}
?>
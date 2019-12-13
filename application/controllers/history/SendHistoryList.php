<?php

/**
 * SendHistoryListController
 * @author takanori_gozu
 *
 */
class SendHistoryList extends MY_Controller {
	
	/**
	 * Index
	 */
	public function index() {
		
		$this->load->model('history/SendHistoryListModel', 'model');
		$this->load->library('dao/SendHistoryDao');
		
		$this->set('class_key', 'history');
		$this->set('class_path', 'history/SendHistory');
		
		$this->set('list', $this->model->get_list());
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		$this->set('mail_group_map', $this->model->get_mail_group_map());
		
		$this->view('history/send_history_list');
	}
}
?>
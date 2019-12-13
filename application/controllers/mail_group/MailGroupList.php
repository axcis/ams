<?php

/**
 * MailGroupListController
 * @author takanori_gozu
 *
 */
class MailGroupList extends MY_Controller {
	
	/**
	 * Index
	 */
	public function index() {
		
		$this->load->model('mail_group/MailGroupListModel', 'model');
		$this->load->library('dao/MailGroupDao');
		
		$this->set('class_key', 'mail_group');
		$this->set('class_path', 'mail_group/MailGroup');
		
		$this->set('list', $this->model->get_list());
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		
		$this->view('mail_group/mail_group_list');
	}
	
	/**
	 * 検索
	 */
	public function search() {
		
		$this->load->model('mail_group/MailGroupListModel', 'model');
		$this->load->library('dao/MailGroupDao');
		
		$search = $this->get_attribute();
		
		$this->set('class_key', 'mail_group');
		$this->set('class_path', 'mail_group/MailGroup');
		
		$this->set('list', $this->model->get_list($search));
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		
		$this->view('mail_group/mail_group_list');
	}
}
?>
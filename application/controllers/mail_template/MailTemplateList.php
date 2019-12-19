<?php

/**
 * MailTemplateListController
 * @author takanori_gozu
 *
 */
class MailTemplateList extends MY_Controller {
	
	/**
	 * Index
	 */
	public function index() {
		
		$this->load->model('mail_template/MailTemplateListModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		
		$this->set('class_key', 'mail_template');
		$this->set('class_path', 'mail_template/MailTemplateList');
		
		$this->set('list', $this->model->get_list());
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		
		$this->view('mail_template/mail_template_list');
	}
	
	/**
	 * 検索
	 */
	public function search() {
		
		$this->load->model('mail_template/MailTemplateListModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		
		$search = $this->get_attribute();
		
		$this->set('class_key', 'mail_template');
		$this->set('class_path', 'mail_template/MailTemplateList');
		
		$this->set('list', $this->model->get_list($search));
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		
		$this->view('mail_template/mail_template_list');
	}
	
	/**
	 * 詳細
	 */
	public function detail($id) {
		
		$this->load->model('mail_template/MailTemplateListModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		
		$this->set('popup', '1');
		$this->set_attribute($this->model->get_info($id));
		
		$this->view('mail_template/mail_template_detail');
	}
}
?>
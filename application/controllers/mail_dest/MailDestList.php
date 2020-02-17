<?php

/**
 * MailDestListController
 * @author takanori_gozu
 *
 */
class MailDestList extends MY_Controller {
	
	/**
	 * Index
	 */
	public function index() {
		
		$this->load->model('mail_dest/MailDestListModel', 'model');
		$this->load->library('dao/MailDestDao');
		$this->load->library('dao/ExcludeGroupDao');
		
		$this->set('class_key', 'mail_dest');
		$this->set('class_path', 'mail_dest/MailDestList');
		
		$this->set('list', $this->model->get_list());
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		$this->set('exclude_group_map', $this->model->get_exclude_group_map());
		
		$this->view('mail_dest/mail_dest_list');
	}
	
	/**
	 * 検索
	 */
	public function search() {
		
		$this->load->model('mail_dest/MailDestListModel', 'model');
		$this->load->library('dao/MailDestDao');
		$this->load->library('dao/ExcludeGroupDao');
		
		$search = $this->get_attribute();
		
		$this->set('class_key', 'mail_dest');
		$this->set('class_path', 'mail_dest/MailDestList');
		
		$this->set('list', $this->model->get_list($search));
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		$this->set('exclude_group_map', $this->model->get_exclude_group_map());
		
		$this->view('mail_dest/mail_dest_list');
	}
	
	/**
	 * 詳細
	 */
	public function detail($id) {
		
		$this->load->model('mail_dest/MailDestListModel', 'model');
		$this->load->library('dao/MailDestDao');
		$this->load->library('dao/ExcludeGroupDao');
		
		$this->set('popup', '1');
		$this->set_attribute($this->model->get_info($id));
		
		$this->view('mail_dest/mail_dest_detail');
	}
}
?>
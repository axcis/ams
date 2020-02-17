<?php

/**
 * TopPageController
 * @author takanori_gozu
 *
 */
class TopPage extends MY_Controller {
	
	/**
	 * Index
	 */
	public function index() {
		
		$this->load->model('top/TopPageModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		$this->load->library('dao/MailDestDao');
		$this->load->library('dao/SenderDao');
		$this->load->library('dao/SendHistoryDao');
		
		$this->set('class_key', 'top');
		$this->set('class_path', 'TopPage');
		
		$this->set('list', $this->model->get_delivered_mail_list());
		$this->set('list_col', $this->model->get_list_col());
		
		$this->set('deliver_type_map', $this->model->get_deliver_type_map());
		
		$this->view('top/top_page');
	}
	
	/**
	 * 検索
	 */
	public function search() {
		
		$this->load->model('top/TopPageModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		$this->load->library('dao/MailDestDao');
		$this->load->library('dao/SenderDao');
		$this->load->library('dao/SendHistoryDao');
		
		$search = $this->get_attribute();
		
		$this->set('class_key', 'top');
		$this->set('class_path', 'TopPage');
		
		$this->set('list', $this->model->get_delivered_mail_list($search));
		$this->set('list_col', $this->model->get_list_col());
		
		$this->set('deliver_type_map', $this->model->get_deliver_type_map());
		
		$this->view('top/top_page');
	}
	
	/**
	 * 詳細
	 */
	public function detail($id) {
		
		$this->load->model('top/TopPageModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		$this->load->library('dao/MailDestDao');
		$this->load->library('dao/SenderDao');
		$this->load->library('dao/SendHistoryDao');
		
		$this->set_attribute($this->model->get_info($id));
		
		$this->view('top/send_history_detail');;
	}
}
?>
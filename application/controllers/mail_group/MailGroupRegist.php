<?php

/**
 * MailGroupRegistController
 * @author takanori_gozu
 *
 */
class MailGroupRegist extends MY_Controller {
	
	public function regist_input() {
		
		$this->set('action', 'regist');
		$this->set('class_path', 'mail_group/MailGroup');
		
		$this->view('mail_group/mail_group_input');
	}
	
	/**
	 * 新規登録
	 */
	public function regist() {
		
		$this->load->model('mail_group/MailGroupRegistModel', 'model');
		$this->load->library('dao/MailGroupDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('mail_group/mail_group_input');
			return;
		}
		
		$this->model->db_regist($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
	
	public function modify_input($id) {
		
		$this->load->model('mail_group/MailGroupRegistModel', 'model');
		$this->load->library('dao/MailGroupDao');
		
		$this->set('action', 'modify');
		$this->set('class_path', 'mail_group/MailGroup');
		
		$this->set_attribute($this->model->get_info($id));
		
		$this->view('mail_group/mail_group_input');
	}
	
	/**
	 * 更新
	 */
	public function modify() {
		
		$this->load->model('mail_group/MailGroupRegistModel', 'model');
		$this->load->library('dao/MailGroupDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('mail_group/mail_group_input');
			return;
		}
		
		$this->model->db_modify($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
}
?>
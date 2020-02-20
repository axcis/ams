<?php

/**
 * MailTemplateRegistController
 * @author takanori_gozu
 *
 */
class MailTemplateRegist extends MY_Controller {
	
	public function regist_input() {
		
		$this->set('action', 'regist');
		$this->set('class_path', 'mail_template/MailTemplate');
		
		$this->view('mail_template/mail_template_input');
	}
	
	/**
	 * 新規登録
	 */
	public function regist() {
		
		$this->load->model('mail_template/MailTemplateRegistModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('mail_template/mail_template_input');
			return;
		}
		
		$this->model->db_regist($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
	
	public function modify_input($id) {
		
		$this->load->model('mail_template/MailTemplateRegistModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		
		$this->set('action', 'modify');
		$this->set('class_path', 'mail_template/MailTemplate');
		
		$this->set_attribute($this->model->get_info($id));
		
		$this->view('mail_template/mail_template_input');
	}
	
	/**
	 * 更新
	 */
	public function modify() {
		
		$this->load->model('mail_template/MailTemplateRegistModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('mail_template/mail_template_input');
			return;
		}
		
		$this->model->db_modify($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
	
	/**
	 * 削除
	 */
	public function delete() {
		
		$this->load->model('mail_template/MailTemplateRegistModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		
		$id = $this->get('id');
		
		$this->model->db_delete($id);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
}
?>
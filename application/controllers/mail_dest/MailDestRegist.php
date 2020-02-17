<?php

/**
 * MailDestRegistController
 * @author takanori_gozu
 *
 */
class MailDestRegist extends MY_Controller {
	
	public function regist_input() {
		
		$this->load->model('mail_dest/MailDestRegistModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		
		$this->set('action', 'regist');
		$this->set('class_path', 'mail_dest/MailDest');
		$this->set('exclude_group_map', $this->model->get_exclude_group_map(false));
		
		$this->view('mail_dest/mail_dest_input');
	}
	
	/**
	 * 新規登録
	 */
	public function regist() {
		
		$this->load->model('mail_dest/MailDestRegistModel', 'model');
		$this->load->library('dao/MailDestDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			if (isset($input['mail_group'])) $this->set('mail_group_checked', $input['mail_group']);
			$this->load->library('dao/ExcludeGroupDao');
			$this->set('exclude_group_map', $this->model->get_exclude_group_map(false));
			$this->view('mail_dest/mail_dest_input');
			return;
		}
		
		$this->model->db_regist($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
	
	public function modify_input($id) {
		
		$this->load->model('mail_dest/MailDestRegistModel', 'model');
		$this->load->library('dao/MailDestDao');
		$this->load->library('dao/ExcludeGroupDao');
		
		$this->set('action', 'modify');
		$this->set('class_path', 'mail_dest/MailDest');
		$this->set('exclude_group_map', $this->model->get_exclude_group_map(false));
		
		$info = $this->model->get_info($id);
		
		$this->set_attribute($info);
		
		if (isset($info['mail_group_id'])) $this->set('mail_group_checked', explode(',', $info['mail_group_id']));
		
		$this->view('mail_dest/mail_dest_input');
	}
	
	/**
	 * 更新
	 */
	public function modify() {
		
		$this->load->model('mail_dest/MailDestRegistModel', 'model');
		$this->load->library('dao/MailDestDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			if (isset($input['mail_group'])) $this->set('mail_group_checked', $input['mail_group']);
			$this->load->library('dao/ExcludeGroupDao');
			$this->set('exclude_group_map', $this->model->get_exclude_group_map(false));
			$this->view('mail_dest/mail_dest_input');
			return;
		}
		
		$this->model->db_modify($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
}
?>
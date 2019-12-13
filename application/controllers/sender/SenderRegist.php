<?php

/**
 * SenderRegistController
 * @author takanori_gozu
 *
 */
class SenderRegist extends MY_Controller {
	
	public function regist_input() {
		
		$this->set('action', 'regist');
		$this->set('class_path', 'sender/Sender');
		
		$this->view('sender/sender_input');
	}
	
	/**
	 * 新規登録
	 */
	public function regist() {
		
		$this->load->model('sender/SenderRegistModel', 'model');
		$this->load->library('dao/SenderDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('sender/sender_input');
			return;
		}
		
		$this->model->db_regist($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
	
	public function modify_input($id) {
		
		$this->load->model('sender/SenderRegistModel', 'model');
		$this->load->library('dao/SenderDao');
		
		$this->set('action', 'modify');
		$this->set('class_path', 'sender/Sender');
		
		$this->set_attribute($this->model->get_info($id));
		
		$this->view('sender/sender_input');
	}
	
	/**
	 * 更新
	 */
	public function modify() {
		
		$this->load->model('sender/SenderRegistModel', 'model');
		$this->load->library('dao/SenderDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('sender/sender_input');
			return;
		}
		
		$this->model->db_modify($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
}
?>
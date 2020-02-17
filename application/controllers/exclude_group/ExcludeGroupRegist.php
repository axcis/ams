<?php

/**
 * ExcludeGroupRegistController
 * @author takanori_gozu
 *
 */
class ExcludeGroupRegist extends MY_Controller {
	
	public function regist_input() {
		
		$this->set('action', 'regist');
		$this->set('class_path', 'exclude_group/ExcludeGroup');
		
		$this->view('exclude_group/exclude_group_input');
	}
	
	/**
	 * 新規登録
	 */
	public function regist() {
		
		$this->load->model('exclude_group/ExcludeGroupRegistModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('exclude_group/exclude_group_input');
			return;
		}
		
		$this->model->db_regist($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
	
	public function modify_input($id) {
		
		$this->load->model('exclude_group/ExcludeGroupRegistModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		
		$this->set('action', 'modify');
		$this->set('class_path', 'exclude_group/ExcludeGroup');
		
		$this->set_attribute($this->model->get_info($id));
		
		$this->view('exclude_group/exclude_group_input');
	}
	
	/**
	 * 更新
	 */
	public function modify() {
		
		$this->load->model('exclude_group/ExcludeGroupRegistModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('exclude_group/exclude_group_input');
			return;
		}
		
		$this->model->db_modify($input);
		
		$this->redirect_js(base_url(). $this->get('class_path'). 'List');
	}
}
?>
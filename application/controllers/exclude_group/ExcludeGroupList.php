<?php

/**
 * ExcludeGroupListController
 * @author takanori_gozu
 *
 */
class ExcludeGroupList extends MY_Controller {
	
	/**
	 * Index
	 */
	public function index() {
		
		$this->load->model('exclude_group/ExcludeGroupListModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		
		$this->set('class_key', 'exclude_group');
		$this->set('class_path', 'exclude_group/ExcludeGroup');
		
		$this->set('list', $this->model->get_list());
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		
		$this->view('exclude_group/exclude_group_list');
	}
	
	/**
	 * 検索
	 */
	public function search() {
		
		$this->load->model('exclude_group/ExcludeGroupListModel', 'model');
		$this->load->library('dao/ExcludeGroupDao');
		
		$search = $this->get_attribute();
		
		$this->set('class_key', 'exclude_group');
		$this->set('class_path', 'exclude_group/ExcludeGroup');
		
		$this->set('list', $this->model->get_list($search));
		$this->set('list_col', $this->model->get_list_col());
		$this->set('link', $this->model->get_link());
		
		$this->view('exclude_group/exclude_group_list');
	}
}
?>
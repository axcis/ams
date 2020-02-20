<?php

/**
 * MailTemplateRegistModel
 * @author takanori_gozu
 *
 */
class MailTemplateRegistModel extends MY_Model {
	
	/**
	 * 詳細
	 */
	public function get_info($id) {
		
		$this->set_table(MailTemplateDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_where(MailTemplateDao::COL_ID, $id);
		
		return $this->do_select_info();
	}
	
	/**
	 * バリデーション
	 */
	public function validation($input) {
		
		$template_name = $input['template_name'];
		$subject = $input['subject'];
		$discription = $input['discription'];
		
		$msgs = array();
		
		//未入力チェック
		if (trim($template_name) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('template_name')));
		if (trim($subject) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('subject')));
		if (trim($discription) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('discription')));
		
		if ($msgs != null) return $msgs;
		
		//文字列長チェック
		if (mb_strlen(trim($template_name)) > 50) $msgs[] = $this->lang->line('err_max_length', array($this->lang->line('template_name'), 50));
		if (mb_strlen(trim($subject)) > 100) $msgs[] = $this->lang->line('err_max_length', array($this->lang->line('subject'), 100));
		
		return $msgs;
	}
	
	/**
	 * 新規登録
	 */
	public function db_regist($input) {
		
		$this->set_table(MailTemplateDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_col_val(MailTemplateDao::COL_TEMPLATE_NAME, $input['template_name']);
		$this->add_col_val(MailTemplateDao::COL_SUBJECT, $input['subject']);
		$this->add_col_val(MailTemplateDao::COL_DISCRIPTION, $input['discription']);
		
		$this->do_insert();
	}
	
	/**
	 * 更新
	 */
	public function db_modify($input) {
		
		$this->set_table(MailTemplateDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_col_val(MailTemplateDao::COL_TEMPLATE_NAME, $input['template_name']);
		$this->add_col_val(MailTemplateDao::COL_SUBJECT, $input['subject']);
		$this->add_col_val(MailTemplateDao::COL_DISCRIPTION, $input['discription']);
		
		$this->add_where(MailTemplateDao::COL_ID, $input['id']);
		
		$this->do_update();
	}
	
	/**
	 * 削除
	 */
	public function db_delete($id) {
		
		$this->set_table(MailTemplateDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_where(MailTemplateDao::COL_ID, $id);
		
		$this->do_delete();
	}
}
?>
<?php

/**
 * SenderRegistModel
 * @author takanori_gozu
 *
 */
class SenderRegistModel extends MY_Model {
	
	/**
	 * 詳細
	 */
	public function get_info($id) {
		
		$this->set_table(SenderDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_where(SenderDao::COL_ID, $id);
		
		return $this->do_select_info();
	}
	
	/**
	 * バリデーション
	 */
	public function validation($input) {
		
		$sender_name = $input['sender_name'];
		$mail_address = $input['mail_address'];
		
		$msgs = array();
		
		//未入力チェック
		if (trim($sender_name) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('sender_name')));
		if (trim($mail_address) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('mail_address')));
		
		if ($msgs != null) return $msgs;
		
		//文字列長チェック
		if (mb_strlen(trim($sender_name)) > 100) $msgs[] = $this->lang->line('err_max_length', array($this->lang->line('sender_name'), 100));
		if (mb_strlen(trim($mail_address)) > 200) $msgs[] = $this->lang->line('err_max_length', array($this->lang->line('mail_address'), 200));
		
		//形式チェック
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail_address)) {
			$msgs[] = $this->lang->line('err_regex_match', array($this->lang->line('mail_address')));
		}
		
		return $msgs;
	}
	
	/**
	 * 新規登録
	 */
	public function db_regist($input) {
		
		$this->set_table(SenderDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_col_val(SenderDao::COL_SENDER_NAME, $input['sender_name']);
		$this->add_col_val(SenderDao::COL_MAIL_ADDRESS, $input['mail_address']);
		
		$this->do_insert();
	}
	
	/**
	 * 更新
	 */
	public function db_modify($input) {
		
		$this->set_table(SenderDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_col_val(SenderDao::COL_SENDER_NAME, $input['sender_name']);
		$this->add_col_val(SenderDao::COL_MAIL_ADDRESS, $input['mail_address']);
		
		$this->add_where(SenderDao::COL_ID, $input['id']);
		
		$this->do_update();
	}
}
?>
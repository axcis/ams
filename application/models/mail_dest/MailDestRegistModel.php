<?php

/**
 * MailDestRegistModel
 * @author takanori_gozu
 *
 */
class MailDestRegistModel extends MailDestBaseModel {
	
	/**
	 * 詳細
	 */
	public function get_info($id) {
		
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_where(MailDestDao::COL_ID, $id);
		
		return $this->do_select_info();
	}
	
	/**
	 * バリデーション
	 */
	public function validation($input) {
		
		$dest_company_name = $input['dest_company_name'];
		$dest_name = $input['dest_name'];
		$mail_address = $input['mail_address'];
		$exclude_group_id = $input['exclude_group_id'];
		
		$msgs = array();
		
		//未入力チェック
		if (trim($dest_company_name) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('dest_company_name')));
		if (trim($dest_name) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('dest_name')));
		if (trim($mail_address) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('mail_address')));
		if (trim($exclude_group_id) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('exclude_group')));
		
		if ($msgs != null) return $msgs;
		
		//文字列長チェック
		if (mb_strlen(trim($dest_company_name)) > 100) $msgs[] = $this->lang->line('err_max_length', array($this->lang->line('dest_company_name'), 100));
		if (mb_strlen(trim($dest_name)) > 100) $msgs[] = $this->lang->line('err_max_length', array($this->lang->line('dest_name'), 100));
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
		
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_col_val(MailDestDao::COL_DEST_COMPANY_NAME, $input['dest_company_name']);
		$this->add_col_val(MailDestDao::COL_DEST_NAME, $input['dest_name']);
		$this->add_col_val(MailDestDao::COL_MAIL_ADDRESS, $input['mail_address']);
		$this->add_col_val(MailDestDao::COL_EXCLUDE_GROUP_ID, $input['exclude_group_id']);
		
		$this->do_insert();
	}
	
	/**
	 * 更新
	 */
	public function db_modify($input) {
		
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_col_val(MailDestDao::COL_DEST_COMPANY_NAME, $input['dest_company_name']);
		$this->add_col_val(MailDestDao::COL_DEST_NAME, $input['dest_name']);
		$this->add_col_val(MailDestDao::COL_MAIL_ADDRESS, $input['mail_address']);
		$this->add_col_val(MailDestDao::COL_EXCLUDE_GROUP_ID, $input['exclude_group_id']);
		
		$this->add_where(MailDestDao::COL_ID, $input['id']);
		
		$this->do_update();
	}
}
?>
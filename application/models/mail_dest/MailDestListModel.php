<?php

/**
 * MailDestListModel
 * @author takanori_gozu
 *
 */
class MailDestListModel extends MailDestBaseModel {
	
	/**
	 * 一覧
	 */
	public function get_list($search = null) {
		
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailDestDao::COL_ID);
		$this->add_select(MailDestDao::COL_DEST_COMPANY_NAME);
		$this->add_select(MailDestDao::COL_DEST_NAME);
		$this->add_select(MailDestDao::COL_MAIL_ADDRESS);
		
		if ($search != null) {
			$this->set_search_like($search, MailDestDao::COL_DEST_NAME, 'search_dest_name');
		}
		
		if (isset($search['search_mail_group']) && $search['search_mail_group'] != '') {
			$mail_group_id = $search['search_mail_group'];
			$this->add_where_statement("FIND_IN_SET($mail_group_id, mail_group_id)");
		}
		
		return $this->do_select();
	}
	
	/**
	 * 項目名
	 */
	public function get_list_col() {
		
		$list_cols = array();
		
		$list_cols[] = array('width' => 70, 'value' => '編集');
		$list_cols[] = array('width' => 70, 'value' => 'ID');
		$list_cols[] = array('width' => 150, 'value' => '会社名');
		$list_cols[] = array('width' => 150, 'value' => '宛先名');
		$list_cols[] = array('width' => 150, 'value' => 'メールアドレス');
		$list_cols[] = array('width' => 120, 'value' => '詳細');
		
		return $list_cols;
	}
	
	/**
	 * リンク
	 */
	public function get_link() {
		
		$link_list = array();
		
		$link_list[] = array('url' => 'mail_dest/MailDestRegist/regist_input', 'class' => 'far fa-edit', 'value' => '登録');
		$link_list[] = array('url' => 'mail_dest/MailDestBulkRegist/regist_input', 'class' => 'far fa-edit', 'value' => '一括登録');
		
		return $link_list;
	}
	
	/**
	 * 詳細
	 */
	public function get_info($id) {
		
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_where(MailDestDao::COL_ID, $id);
		
		$info = $this->do_select_info();
		
		if ($info[MailDestDao::COL_MAIL_GROUP_ID] == null) return $info;
		
		$mail_group_ids = explode(',', $info[MailDestDao::COL_MAIL_GROUP_ID]);
		$mail_group_map = $this->get_mail_group_map(false);
		
		$group_info = array();
		
		foreach ($mail_group_ids as $group_id) {
			$group_info[] = $mail_group_map[$group_id];
		}
		
		$info[MailDestDao::COL_MAIL_GROUP_ID] = $group_info;
		
		return $info;
	}
}
?>
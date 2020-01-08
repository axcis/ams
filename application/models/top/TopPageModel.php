<?php

/**
 * TopPageModel
 * @author takanori_gozu
 *
 */
class TopPageModel extends MY_Model {
	
	/**
	 * 配信タイプ
	 * (グループ配信 or スポット配信)
	 */
	public function get_deliver_type_map($no_select_show = true) {
		
		$map = array();
		
		if ($no_select_show) $map[''] = '配信タイプを選択';
		$map['1'] = 'グループ配信';
		$map['2'] = 'スポット配信';
		
		return $map;
	}
	
	/**
	 * 送信者
	 */
	public function get_sender_map() {
		
		$this->set_table(SenderDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(SenderDao::COL_ID);
		$this->add_select_as(SenderDao::COL_SENDER_NAME, 'name');
		
		return $this->key_value_map($this->do_select());
	}
	
	/**
	 * グループ名
	 */
	public function get_group_name($group_id) {
		
		$this->set_table(MailGroupDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailGroupDao::COL_GROUP_NAME);
		$this->add_where(MailGroupDao::COL_ID, $group_id);
		
		$info = $this->do_select_info();
		
		return $info[MailGroupDao::COL_GROUP_NAME];
	}
	
	/**
	 * 宛先
	 */
	public function get_dest_names($dest_ids) {
		
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailDestDao::COL_DEST_COMPANY_NAME);
		$this->add_select(MailDestDao::COL_DEST_NAME);
		
		$this->add_where_in(MailDestDao::COL_ID, $dest_ids);
		
		$list = $this->do_select();
		$map = array();
		
		foreach ($list as $row) {
			$map[] = $row[MailDestDao::COL_DEST_COMPANY_NAME]. ' '. $row[MailDestDao::COL_DEST_NAME];
		}
		
		return $map;
	}
	
	/**
	 * 配信済みメール一覧
	 */
	public function get_delivered_mail_list($search = null) {
		
		$this->set_table(SendHistoryDao::TABLE_NAME, self::DB_TRAN);
		
		$this->add_select(SendHistoryDao::COL_ID);
		$this->add_select(SendHistoryDao::COL_SEND_TYPE);
		$this->add_select(SendHistoryDao::COL_SEND_TIME);
		$this->add_select(SendHistoryDao::COL_SUBJECT);
		
		if ($search != null) {
			$this->set_search($search, SendHistoryDao::COL_SEND_TYPE, 'search_send_type');
		}
		
		//最新10件を取得する
		$this->add_order(SendHistoryDao::COL_SEND_TIME, self::ORDER_DESC);
		$this->add_limit(10);
		
		$list = $this->do_select();
		
		$deliver_type_map = $this->get_deliver_type_map(false);
		
		foreach ($list as &$row) {
			$row[SendHistoryDao::COL_SEND_TYPE] = $deliver_type_map[$row[SendHistoryDao::COL_SEND_TYPE]];
		}
		
		return $list;
	}
	
	/**
	 * 項目名
	 */
	public function get_list_col() {
		
		$list_cols = array();
		
		$list_cols[] = array('width' => 70, 'value' => 'ID');
		$list_cols[] = array('width' => 150, 'value' => '配信タイプ');
		$list_cols[] = array('width' => 150, 'value' => '配信日時');
		$list_cols[] = array('width' => 300, 'value' => '件名');
		$list_cols[] = array('width' => 120, 'value' => '詳細');
		
		return $list_cols;
	}
	
	/**
	 * 詳細
	 */
	public function get_info($id) {
		
		$this->set_table(SendHistoryDao::TABLE_NAME, self::DB_TRAN);
		
		$this->add_select(SendHistoryDao::COL_ID);
		$this->add_select(SendHistoryDao::COL_SEND_TYPE);
		$this->add_select_as(SendHistoryDao::COL_SEND_TYPE, 'type');
		$this->add_select(SendHistoryDao::COL_SEND_TIME);
		$this->add_select(SendHistoryDao::COL_SENDER_ID);
		$this->add_select(SendHistoryDao::COL_MAIL_DEST_IDS);
		$this->add_select(SendHistoryDao::COL_MAIL_GROUP_ID);
		$this->add_select(SendHistoryDao::COL_SUBJECT);
		$this->add_select(SendHistoryDao::COL_DISCRIPTION);
		
		$this->add_where(SendHistoryDao::COL_ID, $id);
		
		$info = $this->do_select_info();
		
		$deliver_type_map = $this->get_deliver_type_map(false);
		$sender_map = $this->get_sender_map();
		
		switch ($info[SendHistoryDao::COL_SEND_TYPE]) {
			case '1':
				//グループ配信
				$group_name = $this->get_group_name($info[SendHistoryDao::COL_MAIL_GROUP_ID]);
				$info[SendHistoryDao::COL_SEND_TYPE] = $deliver_type_map[$info[SendHistoryDao::COL_SEND_TYPE]];
				$info[SendHistoryDao::COL_SENDER_ID] = $sender_map[$info[SendHistoryDao::COL_SENDER_ID]];
				$info[SendHistoryDao::COL_MAIL_GROUP_ID] = $group_name;
				$info['refer_path'] = 'group_mail/GroupMailSend';
				break;
			case '2':
				//スポット配信
				$dest_names = $this->get_dest_names($info[SendHistoryDao::COL_MAIL_DEST_IDS]);
				$info[SendHistoryDao::COL_SEND_TYPE] = $deliver_type_map[$info[SendHistoryDao::COL_SEND_TYPE]];
				$info[SendHistoryDao::COL_SENDER_ID] = $sender_map[$info[SendHistoryDao::COL_SENDER_ID]];
				$info[SendHistoryDao::COL_MAIL_DEST_IDS] = $dest_names;
				$info['refer_path'] = 'spot_mail/SpotMailSend';
				break;
		}
		
		return $info;
	}
}
?>
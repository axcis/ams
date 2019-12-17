<?php

/**
 * メール配信履歴テーブル定義ファイル
 * @author takanori_gozu
 *
 */
class SendHistoryDao {
	
	const TABLE_NAME = 'send_history';
	
	const COL_ID = 'id';
	const COL_SEND_TYPE = 'send_type';
	const COL_SEND_TIME = 'send_time';
	const COL_SENDER_ID = 'sender_id';
	const COL_SEND_MAIL_ADDRESS = 'send_mail_address';
	const COL_MAIL_GROUP_ID = 'mail_group_id';
	const COL_SUBJECT = 'subject';
	const COL_DISCRIPTION = 'discription';
	const COL_ATTACH_FILE_NAME = 'attach_file_name';
}
?>
<?php

/**
 * 未配信メール管理テーブル定義ファイル
 * @author takanori_gozu
 *
 */
class UndeliveredMailDao {
	
	const TABLE_NAME = 'undelivered_mail';
	
	const COL_ID = 'id';
	const COL_MAIL_TYPE = 'mail_type';
	const COL_SEND_TYPE = 'send_type';
	const COL_SEND_TIME = 'send_time';
	const COL_SEND_FROM = 'send_from';
	const COL_SEND_MAIL_ADDRESS = 'send_mail_address';
	const COL_MAIL_GROUP_ID = 'mail_group_id';
	const COL_SUBJECT = 'subject';
	const COL_DISCRIPTION = 'discription';
	const COL_ATTACH_FILE_NAME = 'attach_file_name';
	const COL_SEND_SIGN = 'send_sign';
	const COL_DEL_SIGN = 'del_sign';
}
?>
<?php

/**
 * 宛先マスタテーブル定義ファイル
 * @author takanori_gozu
 *
 */
class MailDestDao {
	
	const TABLE_NAME = 'mail_dest';
	
	const COL_ID = 'id';
	const COL_DEST_COMPANY_NAME = 'dest_company_name';
	const COL_DEST_NAME = 'dest_name';
	const COL_MAIL_ADDRESS = 'mail_address';
	const COL_EXCLUDE_GROUP_ID = 'exclude_group_id';
}
?>
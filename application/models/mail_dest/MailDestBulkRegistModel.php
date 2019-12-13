<?php

/**
 * MailDestBulkRegistModel
 * @author takanori_gozu
 *
 */
class MailDestBulkRegistModel extends MailDestBaseModel {
	
	/**
	 * バリデーション
	 */
	public function validation($file_name) {
		
		$exts = array('csv', 'CSV'); //拡張子チェック用
		$msgs = array();
		
		$before_len = mb_strlen($file_name);
		$after_len = mb_strlen(mb_convert_encoding(mb_convert_encoding($file_name, 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'));
		
		//ファイルサイズチェック
		if ($_FILES["up_file"]["error"] == 1 || $_FILES["up_file"]["error"] == 2) {
			//1…php.iniで設定されているupload_max_sizeを超えている場合に返される
			//2…htmlのhiddenで持っているMAX_FILE_SIZEを超えている場合に返される
			$msgs[] = $this->lang->line('err_file_bigger', array('2MB'));
		}
		
		if ($before_len != $after_len) {
			//環境依存文字対応
			$msgs[] = $this->lang->line('err_file_upload_env_character');
		}
		
		//拡張子チェック
		$arr = explode(".", $file_name);
		$ext = $arr[1];
		if (!in_array($ext, $exts)) {
			$msgs[] = $this->lang->line('err_unmatch_ext', array('csv'));
		}
		
		return $msgs;
	}
	
	/**
	 * tmpへアップロードする
	 */
	public function file_upload($file_name) {
		
		$this->load->model('common/FileOperationModel', 'file');
		
		$upload_dir = 'tmp/';
		
		$result = $this->file->upload($upload_dir, $_FILES["up_file"]["tmp_name"], mb_convert_encoding($file_name, 'SJIS', 'UTF-8'));
		
		if ($result) {
			//パーミッションを変更(最後に消せるようにしておく)
			chmod($upload_dir. $file_name, 0644);
		}
		
		return $result;
	}
	
	/**
	 * csvの読み込み・データチェック
	 */
	public function read_csv($file_name, &$err) {
		
		$path = 'tmp/'. $file_name;
		
		//文字化け対策
		file_put_contents($path, mb_convert_encoding(file_get_contents($path), 'UTF-8', 'SJIS'));
		
		$file = new SplFileObject($path);
		$file->setFlags(SplFileObject::READ_CSV);
		
		$csv_data = array();
		
		foreach ($file as $line) {
			$this->line_validation($line, $err); //csvファイルのチェック
			$csv_data[] = $line;
		}
		
		return $csv_data;
	}
	
	/**
	 * Csvファイルのデータチェック
	 */
	private function line_validation(&$line, &$err) {
		
		$dest_company_name = $line[0];
		$dest_name = $line[1];
		$mail_address = $line[2];
		$mail_group_name = isset($line[3]) && $line[3] != '' ? $line[3] : '';
		
		$line[4] = '0';
		
		//未入力チェック
		if (trim($dest_company_name) == '') $line[4] = '1';
		if (trim($dest_name) == '') $line[4] = '1';
		if (trim($mail_address) == '') $line[4] = '1';
		
		if ($line[4] == '1') {
			$err = '1';
			return;
		}
		
		//文字列長チェック
		if (mb_strlen(trim($dest_company_name)) > 100) $line[4] = '1';
		if (mb_strlen(trim($dest_name)) > 100) $line[4] = '1';
		if (mb_strlen(trim($mail_address)) > 200) $line[4] = '1';
		
		if ($line[4] == '1') {
			$err = '1';
			return;
		}
		
		//形式チェック
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail_address)) {
			$line[4] = '1';
			$err = '1';
			return;
		}
		
		//TODO: メール送信グループの登録有無チェック
	}
	
	/**
	 * データ項目名
	 */
	public function get_list_col() {
		
		$list_cols = array();
		
		$list_cols[] = array('width' => 150, 'value' => '会社名');
		$list_cols[] = array('width' => 150, 'value' => '宛先名');
		$list_cols[] = array('width' => 150, 'value' => 'メールアドレス');
		$list_cols[] = array('width' => 120, 'value' => '送信グループ');
		
		return $list_cols;
	}
	
	/**
	 * 一括登録
	 */
	public function bulk_regist($data) {
		
		$insert_datas = array();
		
		foreach ($data as $info) {
			$dest_company_name = $info[0];
			$dest_name = $info[1];
			$mail_address = $info[2];
			//TODO: メールグループの登録方法
			
			$insert_datas[] = array(
					MailDestDao::COL_DEST_COMPANY_NAME => $dest_company_name,
					MailDestDao::COL_DEST_NAME => $dest_name,
					MailDestDao::COL_MAIL_ADDRESS => $mail_address
			);
		}
		
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		$this->do_bulk_insert($insert_datas);
	}
}
?>
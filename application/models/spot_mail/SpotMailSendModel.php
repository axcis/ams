<?php

/**
 * SpotMailSendModel
 * @author takanori_gozu
 *
 */
class SpotMailSendModel extends MY_Model {
	
	/**
	 * 送信者
	 */
	public function get_sender_map($no_select_show = true) {
		
		$this->set_table(SenderDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(SenderDao::COL_ID);
		$this->add_select_as(SenderDao::COL_SENDER_NAME, 'name');
		
		$list = $this->do_select();
		
		$map = array();
		
		if ($no_select_show) $map[''] = '送信者を選択';
		$map += $this->key_value_map($list);
		
		return $map;
	}
	
	/**
	 * 宛先
	 */
	public function get_mail_dest_map($no_select_show = true) {
		
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailDestDao::COL_ID);
		$this->add_select(MailDestDao::COL_DEST_COMPANY_NAME);
		$this->add_select(MailDestDao::COL_DEST_NAME);
		
		$list = $this->do_select();
		
		$map = array();
		
		$map[''] = '宛先を選択';
		foreach ($list as $row) {
			$key = $row[MailDestDao::COL_ID];
			$value = $row[MailDestDao::COL_DEST_COMPANY_NAME]. '：'. $row[MailDestDao::COL_DEST_NAME];
			$map[$key] = $value;
		}
		
		return $map;
	}
	
	/**
	 * メールテンプレート
	 */
	public function get_mail_template_map($no_select_show = true) {
		
		$this->set_table(MailTemplateDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailTemplateDao::COL_ID);
		$this->add_select_as(MailTemplateDao::COL_TEMPLATE_NAME, 'name');
		
		$list = $this->do_select();
		
		$map = array();
		
		if ($no_select_show) $map[''] = 'テンプレートを選択';
		$map += $this->key_value_map($list);
		
		return $map;
	}
	
	/**
	 * テンプレート取得
	 */
	public function get_template_info($template_id) {
		
		$this->set_table(MailTemplateDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailTemplateDao::COL_SUBJECT);
		$this->add_select(MailTemplateDao::COL_DISCRIPTION);
		
		$this->add_where(MailTemplateDao::COL_ID, $template_id);
		
		return $this->do_select_info();
	}
	
	/**
	 * 参照配信用
	 */
	public function get_history_info($id) {
		
		$this->set_table(SendHistoryDao::TABLE_NAME, self::DB_TRAN);
		
		$this->add_where(SendHistoryDao::COL_ID, $id);
		
		return $this->do_select_info();
	}
	
	/**
	 * バリデーション
	 */
	public function validation($input) {
		
		$sender_id = $input['sender_id'];
		$mail_dest_id = $input['mail_dest_id'];
		$subject = $input['subject'];
		$discription = $input['discription'];
		
		$msgs = array();
		
		//未入力・未選択チェック
		if (trim($sender_id) == '') $msgs[] = $this->lang->line('err_not_select', array($this->lang->line('sender')));
		if (trim($mail_dest_id) == '') $msgs[] = $this->lang->line('err_not_select', array($this->lang->line('dest')));
		if (trim($subject) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('subject')));
		if (trim($discription) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('discription')));
		
		if ($msgs != null) return $msgs;
		
		//長さチェック
		if (mb_strlen(trim($subject)) > 100) $msgs[] = $this->lang->line('err_max_length', array($this->lang->line('subject'), 100));
		
		if ($msgs != null) return $msgs;
		
		if (isset($_FILES['up_file'])) {
			$file_total_size = 0;
			for($i = 0; $i < count($_FILES["up_file"]["name"]); $i++ ){
				$file_name = mb_convert_encoding($_FILES["up_file"]["name"][$i], 'SJIS', 'UTF-8');
				$before_len = mb_strlen($_FILES["up_file"]["name"][$i]);
				$after_len = mb_strlen(mb_convert_encoding($file_name, 'UTF-8', 'SJIS'));
				if ($before_len != $after_len) {
					$msgs[] = $this->lang->line('環境依存文字を含むファイル名は添付できません。');
					break;
				}
				if ($_FILES["up_file"]["error"][$i] == 1 || $_FILES["up_file"]["error"][$i] == 2) {
					$msgs[] = $this->lang->line('err_file_bigger', array('3MB'));
					break;
				}
				$file_total_size += $_FILES["up_file"]['size'][$i];
			}
			if ($file_total_size > 3145728) $msgs[] = $this->lang->line('ファイルの総合計サイズは3MBまでです。');
		}
		
		return $msgs;
	}
	
	/**
	 * メール送信
	 */
	public function mail_send($input) {
		
		$upload_dir = "tmp/";
		
		//念のため
		$attaches = glob($upload_dir. '*');
		foreach ($attaches as $attach) {
			unlink($attach);
		}
		
		//一旦ファイルをアップロードする
		if (isset($_FILES['up_file'])) {
			$this->load->model('common/FileOperationModel', 'file');
			for($i = 0; $i < count($_FILES["up_file"]["name"]); $i++ ){
				$this->file->upload($upload_dir, $_FILES["up_file"]["tmp_name"][$i], mb_convert_encoding($_FILES["up_file"]["name"][$i], "ISO-2022-JP", "UTF-8" ));
			}
		}
		
		//送信者情報を取得
		$this->set_table(SenderDao::TABLE_NAME, self::DB_MASTER);
		$this->add_select(SenderDao::COL_MAIL_ADDRESS);
		$this->add_select(SenderDao::COL_SENDER_NAME);
		
		$this->add_where(SenderDao::COL_ID, $input['sender_id']);
		
		$sender_info = $this->do_select_info();
		
		//宛先情報を取得
		$this->set_table(MailDestDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailDestDao::COL_MAIL_ADDRESS);
		$this->add_select(MailDestDao::COL_DEST_COMPANY_NAME);
		$this->add_select(MailDestDao::COL_DEST_NAME);
		
		$this->add_where(MailDestDao::COL_ID, $input['mail_dest_id']);
		
		$dest_info = $this->do_select_info();
		
		$this->load->model('common/SendMailModel', 'mail');
		
		$this->mail->initialize($this->lang->line('mail_config'));
		$this->mail->from($sender_info[SenderDao::COL_MAIL_ADDRESS], $sender_info[SenderDao::COL_SENDER_NAME]);
		$this->mail->to($dest_info[MailDestDao::COL_MAIL_ADDRESS]);
		$this->mail->subject($input['subject']);
		$message = str_replace("{% dest_company %}", $dest_info[MailDestDao::COL_DEST_COMPANY_NAME], $input['discription']);
		$message = str_replace("{% dest_name %}", $dest_info[MailDestDao::COL_DEST_NAME], $message);
		$this->mail->message($message);
		$attaches = glob($upload_dir. '*');
		foreach ($attaches as $attach) {
			$this->mail->attach($attach, '', basename($attach));
		}
		$this->mail->send();
		
		//アップロードしたファイルを削除
		$attaches = glob($upload_dir. '*');
		foreach ($attaches as $attach) {
			unlink($attach);
		}
	}
	
	/**
	 * DB登録
	 */
	public function db_regist($input) {
		
		$this->set_table(SendHistoryDao::TABLE_NAME, self::DB_TRAN);
		
		$this->add_col_val(SendHistoryDao::COL_SEND_TYPE, '2'); //個別配信で固定
		$this->add_col_val(SendHistoryDao::COL_SEND_TIME, date('Y/m/d H:i:s'));
		$this->add_col_val(SendHistoryDao::COL_SENDER_ID, $input['sender_id']);
		$this->add_col_val(SendHistoryDao::COL_MAIL_DEST_ID, $input['mail_dest_id']);
		$this->add_col_val(SendHistoryDao::COL_SUBJECT, $input['subject']);
		$this->add_col_val(SendHistoryDao::COL_DISCRIPTION, $input['discription']);
		if (isset($_FILES['up_file'])) {
			$this->add_col_val(SendHistoryDao::COL_ATTACH_FILE_NAME, implode(',', $_FILES['up_file']['name']));
		}
		
		$this->do_insert();
	}
}
?>
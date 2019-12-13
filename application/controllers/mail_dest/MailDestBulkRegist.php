<?php

/**
 * MailDestBulkRegistController
 * @author takanori_gozu
 *
 */
class MailDestBulkRegist extends MY_Controller {
	
	public function regist_input() {
		
		$this->view('mail_dest/mail_dest_bulk_input');
	}
	
	/**
	 * 確認
	 */
	public function confirm() {
		
		$this->load->model('mail_dest/MailDestBulkRegistModel', 'model');
		
		$file_name = $_FILES['up_file']['name'];
		
		$msgs = $this->model->validation($file_name);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->view('mail_dest/mail_dest_bulk_input');
			return;
		}
		
		$ret = $this->model->file_upload($file_name);
		
		if ($ret == false) {
			$this->set_err_info(array("ファイルのアップロードに失敗しました。"));
			$this->view('mail_dest/mail_dest_bulk_input');
			return;
		}
		
		$csv_err = '0';
		
		//csvを読み込んで、中身をチェックする
		$data = $this->model->read_csv($file_name, $csv_err);
		
		$this->set('csv_data', $data);
		$this->set('err', $csv_err);
		$this->set('list_col', $this->model->get_list_col());
		
		//ファイルそのものは削除する
		unlink('./tmp/'. $file_name);
		
		$this->view('mail_dest/mail_dest_bulk_input_confirm');
	}
	
	/**
	 * 一括登録
	 */
	public function bulk_regist() {
		
		$this->load->model('mail_dest/MailDestBulkRegistModel', 'model');
		$this->load->library('dao/MailDestDao');
		$this->load->library();
		
		$data = json_decode($this->get('csv_data'));
		
		//取り込み時にデータチェックを実施しているので登録のみ
		$this->model->bulk_regist($data);
		
		$this->redirect_js(base_url(). 'mail_dest/MailDestList');
	}
}
?>
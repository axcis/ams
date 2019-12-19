<?php

/**
 * SpostMailSendController
 * @author takanori_gozu
 *
 */
class SpotMailSend extends MY_Controller {
	
	/**
	 * Index
	 */
	public function index() {
		
		$this->load->model('spot_mail/SpotMailSendModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		$this->load->library('dao/SenderDao');
		$this->load->library('dao/MailDestDao');
		
		$this->set('action', 'send');
		
		$this->set('mail_dest_map', $this->model->get_mail_dest_map());
		$this->set('mail_sender_map', $this->model->get_sender_map());
		$this->set('mail_template_map', $this->model->get_mail_template_map());
		
		$this->view('spot_mail/spot_mail_send_input');
	}
	
	/**
	 * テンプレート選択時
	 */
	public function select() {
		
		$this->load->model('spot_mail/SpotMailSendModel', 'model');
		$this->load->library('dao/MailTemplateDao');
		
		$template_id = $this->get('template_id');
		
		$info = $this->model->get_template_info($template_id);
		
		echo json_encode($info);
	}
	
	/**
	 * 配信
	 */
	public function send() {
		
		$this->load->model('spot_mail/SpotMailSendModel', 'model');
		$this->load->library('dao/SenderDao');
		$this->load->library('dao/MailDestDao');
		$this->load->library('dao/SendHistoryDao');
		
		$input = $this->get_attribute();
		
		$msgs = $this->model->validation($input);
		
		if ($msgs != null) {
			$this->set_err_info($msgs);
			$this->load->library('dao/MailTemplateDao');
			$this->set('mail_dest_map', $this->model->get_mail_dest_map());
			$this->set('mail_sender_map', $this->model->get_sender_map());
			$this->set('mail_template_map', $this->model->get_mail_template_map());
			$this->view('spot_mail/spot_mail_send_input');
			return;
		}
		
		//メール送信
		$this->model->mail_send($input);
		
		//送信情報をDBに登録する
		$this->model->db_regist($input);
		
		$this->show_dialog($this->lang->line('sended'));
		$this->redirect_js(base_url(). 'TopPage');
	}
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Email_sending extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email');
	}
	
	// SMTP email setting here
	public function setting_smtp()
	{
		$permission=FALSE;
		
		if($permission==TRUE)
		{
			$config['protocol']    	= 'smtp';
			$config['smtp_host']    = 'ssl://smtp.gmail.com';
			$config['smtp_port']    = '465';
			$config['smtp_timeout'] = '7';
			$config['smtp_user']    = 'webwing.test@gmail.com';
			$config['smtp_pass']    = '';
			$config['charset']    	= 'utf-8';
			$config['newline']    	= "\r\n";
			$config['mailtype'] 	= 'html'; // or html
			$config['validation'] 	= TRUE; // bool whether to validate email or not  
			$this->email->initialize($config);	
		}
	}
	
	public function sendmail($info_arr,$other_info){	
	
		$email_subj = $this->master->getRecords('outgoing_emails',array('file_name'=>$info_arr['view']));
		
		if(count($email_subj) > 0){
			$subject = $email_subj[0]['subject'];
			$email_view = 'standard-email-template';
		}else{
			$subject = $info_arr['subject'];
			$email_view = $info_arr['view'];
		}

		$this->setting_smtp();
		$this->email->set_newline("\r\n");
		$this->email->from($info_arr['from'],"Order System");
		$this->email->to($info_arr['to']);
		//$this->email->subject($info_arr['subject']);
		$this->email->subject($subject);
		$this->email->set_mailtype("html");
		$data['base_url']=base_url();
		//$this->email->message($this->load->view('email/'.$info_arr['view'],$other_info,true)); 
		$this->email->message($this->load->view('emails/'.$email_view,$other_info,true)); 
		$this->email->send();
		return true;
		
	}
}
?>
<?php

define('MAIL_SERVER_ADDR','localhost');

class message_provider {
	var $title = "Messages";
	var $icon_class = 'message';
	
	var $to_addr = array();
	var $from_addr = array();
	var $cc_addr = array();
	var $bcc_addr = array();
	var $path;

	function start(&$providers = false) {
		$this->providers = &$providers;
		return true;
	}

	function send_email($from_name, $from_address) {
		require_once('email'.DIRECTORY_SEPARATOR.'class.phpmailer.php');
		$this->from_addr = array($from_address, $from_name);
	}
	
	function copy_to($to_name, $to_address) {
		$this->cc_addr[] = array($to_address,$to_name);
	}
	
	function blind_copy_to($to_name, $to_address) {
		$this->bcc_addr[] = array($to_address,$to_name);
	}
	
	function add_address($to_name, $to_address) {
		$this->to_addr[] = array($to_address,$to_name);
	}
	
	function reply_to($to_name, $to_address) {
		$this->reply_addr = array($to_address, $to_name);
	}

	function send_message($template, $subject = false) {
		$phpmailer = new PHPMailer();
		$phpmailer->SMTPDebug = (ENABLE_DEBUGGING == 'true' ? true : false);
		$phpmailer->Encoding = 'quoted-printable';
		$phpmailer->CharSet = 'utf8';
		$phpmailer->Hostname = HTTP_HOST;
		$phpmailer->Mailer = MAIL_SERVICE;
		$phpmailer->FromName = $this->from_addr[1];
		$phpmailer->Sender = $phpmailer->From = $this->from_addr[0];
		$phpmailer->AddReplyTo($this->reply_addr[0], $this->reply_addr[1]);

		foreach ($this->to_addr as $address) $phpmailer->AddAddress($address[0], $address[1]);
		foreach ($this->cc_addr as $address) $phpmailer->AddCC($address[0], $address[1]);
		foreach ($this->bcc_addr as $address) $phpmailer->AddBCC($address[0], $address[1]);

		$this->providers->template->assign('subject', $subject);
		$this->providers->template->assign('to', array('name'=>$this->to_addr[0][1],'address'=>$this->to_addr[0][0]));
		$this->providers->template->assign('from', array('name'=>$this->from_addr[1],'address'=>$this->from_addr[0]));
		$this->providers->template->assign('message', array('to_name'=>$this->to_addr[0][1], 'to_addr'=>$this->to_addr[0][0], 'from_name'=>$this->from_addr[1],'from_addr'=>$this->from_addr[0]));
		$this->providers->template->unregister_outputfilter('output');

		switch (MAIL_FORMAT) {
			case 'plain text':	$phpmailer->IsHTML(false);
								$phpmailer->Body = $this->providers->template->fetch($template.'.txt');
								break;
			case 'html':		$phpmailer->IsHTML(true);
								$phpmailer->Body = $this->providers->template->fetch($template.'.html');
								break;
			default:			$phpmailer->IsHTML(true);
								$phpmailer->Body = $this->providers->template->fetch($template.'.html');
								$phpmailer->AltBody = $this->providers->template->fetch($template.'.txt');
								break;
		}

		$phpmailer->Subject = trim($subject) ? $subject : $this->providers->template->_tpl_vars['subject'];


		if (MAIL_SERVICE == 'smtp') {
			$phpmailer->SMTPKeepAlive = false;
			$servers = explode(',',MAIL_SERVER_ADDR);
			foreach ($servers as $server) {
				preg_match("/([^:]+)[:]([^@]+)[@](.*)/",$server,$server_info);
				if ($server_info[3]) {
					$phpmailer->Host = $server_info[3];
					$phpmailer->Username = $server_info[1];
					$phpmailer->Password = $server_info[2];
					$phpmailer->SMTPAuth = true;
				} else {
					$phpmailer->Host = $server_info[1];
					$phpmailer->Username = $mail->Password = '';
					$phpmailer->SMTPAuth = false;
				}
				if ($phpmailer->send()) return true;
			}
		} elseif ($phpmailer->send()) return true;

		return false;
	}
}
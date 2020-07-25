<?php

class Email
{
    public
        $my_mail,
        $from = '',
        $to,
        $replay,
        $subject = '',
        $message = '',
        $convert_html;


    function send(){
        if(empty($this->replay)){
            $this->replay = $this->my_mail;
        }
        if (is_array($this->to)) {
            $to  = key($this->to[0]) ;
            $to .= $this->to[0];
        } else {
            $to  = $this->to;
        }
		if(empty($this->from)){
			$this->from = $this->my_mail;
		}

        $subject = $this->subject;
        $message = $this->message;
        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: ".$this->from." <".$this->my_mail.">\r\n";
        $headers .= "Reply-To: ".$this->replay."\r\n";

        if(!mail($to, $subject, $message, $headers)){
            echo localisation::txt('ошибка отправки письма');
            return false;
        }
        return true;
    }


}
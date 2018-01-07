<?php
use PHPMailer\PHPMailer;

class Mail extends PHPMailer\PHPMailer {
    // Set default variables for all new objects
    public $Host     = 'smtp.gmail.com';
    public $Mailer   = 'smtp';
    public $SMTPAuth = true;
    public $Username = '';
    public $Password = '';
    public $SMTPSecure = 'tls';
    public $WordWrap = 75;

    public function __construct($Username, $Password){
        $this->Username = $Username;
        $this->Password = $Password;
    }

    public function subject($subject) {
        $this->Subject = $subject;
    }

    public function body($body) {
        $this->Body = $body;
    }

    public function send() {
        $this->AltBody = strip_tags(stripslashes($this->Body))."\n\n";
        $this->AltBody = str_replace("&nbsp;", "\n\n", $this->AltBody);
        return parent::send();
    }
}
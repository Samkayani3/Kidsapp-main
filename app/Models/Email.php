<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    public $to;
    public $cc;
    public $bcc;
    public $subject;
    public $view;
    public $body;

    private function get_header() {
        return view('emails/header');
    }

    private function get_footer() {
        return view('emails/footer');
    }

    private function headers($fromName, $from, $cc='', $bcc='') {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: '.$fromName.'<'.$from.'>' . "\r\n";
        if($cc) {
            $headers .= 'Cc: '.$cc . "\r\n";
        }
        if($bcc) {
            $headers .= 'Bcc: '.$bcc . "\r\n";
        }
        return $headers;
    }

    public function to($to) {
        $this->to = $to;
    }
    public function cc($cc) {
        $this->cc = $cc;
    }
    public function bcc($bcc) {
        $this->bcc = $bcc;
    }
    public function subject($subject='') {
        $this->subject = $subject;
    }

    public function send() {
        $to = $this->to;
        $from_name = env('MAIL_FROM_NAME');
        $from_mail = env('MAIL_FROM_ADDRESS');
        $subject = $this->subject;
        $body = $this->body;

        $headers = $this->headers($from_name,$from_mail);

        return mail($to, $subject, $body, $headers);
    }

    public function send_user_activation_mail($name, $mail, $auth_token) {
        $this->to($mail);
        $this->subject('Account activation');
        $this->body = view('emails/register-activation-code',[
            'display_name' => $name,
            'auth_token' => $auth_token
        ]);

        $this->send();
    }

    public function send_user_reset_mail($mail, $url) {
        $this->to($mail);
        $this->subject('Reset Password');
        $this->body = view('emails/reset-email-link', [
            'display_email' => $mail,
            'reset_url' => $url,
        ]);

        $this->send();
    }

}

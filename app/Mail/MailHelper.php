<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;
use DB;

class MailHelper extends Mailable{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $objDemo;

    public function __construct($Demo){
        $this->objDemo = $Demo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        if($this->objDemo->type == 'basic_carepack_confirm'){
            //send mail when patient confirmed caregiver, mail about Basic Care Pack and Confirmation
            $objDemo = $this->objDemo;
            return $this->from($this->objDemo->mail_from, $this->objDemo->mail_from_name)->subject($this->objDemo->subject)->view('mail.basic_mail_template', compact('objDemo'));
        }else if($this->objDemo->type == 'password_reset_mail'){
          //send password reset mail from caregiver create form
            $objDemo = $this->objDemo;
            return $this->from($this->objDemo->mail_from, $this->objDemo->mail_from_name)->subject($this->objDemo->subject)->view('mail.basic_carepack_confirmed', compact('objDemo'));
        }else if($this->objDemo->type == 'password_on_mail'){
          //send password reset mail from caregiver create form
            $objDemo = $this->objDemo;
            return $this->from($this->objDemo->mail_from, $this->objDemo->mail_from_name)->subject($this->objDemo->subject)->view('mail.password_on_mail', compact('objDemo'));
        }
    }

    /*public function basic_email() {
        $data = array('name'=>"Virat Gandhi");

        Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject('Laravel Basic Testing Mail');
            $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "Basic Email Sent. Check your inbox.";
   }

   public function html_email() {
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject('Laravel HTML Testing Mail');
            $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }*/
}

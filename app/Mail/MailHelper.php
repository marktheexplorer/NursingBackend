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
        }else if($this->objDemo->type == 'contact_us_mail'){
          //send after contact us
          $objDemo = $this->objDemo;
          return $this->from($this->objDemo->mail_from, $this->objDemo->mail_from_name)->subject($this->objDemo->subject)->view('mail.contact_us_mail', compact('objDemo'));          
        }
    }
}

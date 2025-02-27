<?php

// namespace App\Mail;

// use App\Models\PersonUserModel;
// use Illuminate\Bus\Queueable;
// use Illuminate\Mail\Mailable;
// use Illuminate\Queue\SerializesModels;

// class VerifyEmail extends Mailable
// {
//     use Queueable, SerializesModels;

//     public $user;
//     public $emailMessage; // Renomeamos a variável para evitar conflitos

//     public function __construct(PersonUserModel $user)
//     {
//         $this->user = $user;

//         $this->emailMessage = "Oi, {$this->user->person->name},\n\n";
//         $this->emailMessage .= "Vimos que você cadastrou seu e-mail {$this->user->email} em nosso sistema. ";
//         $this->emailMessage .= "Para que tudo funcione corretamente, precisamos que você clique no link abaixo para validar:\n\n";
//         $this->emailMessage .= "https://30semanas.test/api/validate_email/{$this->user->verification_token}\n\n";
//         $this->emailMessage .= "Caso não tenha sido você, basta desconsiderar este e-mail.";
//     }

//     public function build()
//     {

//         // dd($this->emailMessage);


//         return $this->subject('Confirmação de E-mail')
//                     ->view('emails.raw_text')
//                     ->with(['emailMessage' => $this->emailMessage]);
//     }
// }


namespace App\Mail;

use App\Models\PersonUserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationLink;

    public function __construct(PersonUserModel $user)
    {
        $this->user = $user;
        $this->verificationLink = "https://sandbox.30semanas.test/api/validate_email/{$user->verification_token}";
    }

    public function build()
    {
        return $this->subject('Confirmação de E-mail')
                    ->view('emails.verify_email')
                    ->with([
                        'user' => $this->user,
                        'verificationLink' => $this->verificationLink,
                    ]);
    }
}

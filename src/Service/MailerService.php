<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService{
    // $mailer;
    public function __construct(private MailerInterface $mailer){
          //$mailer=$mailer;
    }

    public function sendEmail($to='you@example.com',$subject='Time for Symfony Mailer!',$content='<p>See Twig integration for better HTML integration!</p>'): void
    {
        $email = (new Email())
            ->from('mailtrap@serignedomain.com')
            ->to($to)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

        $this->mailer->send($email);

        // ...
    }
}
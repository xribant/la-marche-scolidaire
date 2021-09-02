<?php
// src/Services/Mailer.php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    public function sendEmail(MailerInterface $mailer, $from, $recipient, $subject, $message): Response
    {
        $email = (new Email())
            ->from($from)
            ->to($recipient)
            ->subject($subject)
            ->html($message);

        $mailer->send($email);
    }
}
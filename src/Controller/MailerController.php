<?php

namespace App\Controller;

use PharIo\Manifest\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MailerController extends AbstractController
{
    public function sendEmail(MailerInterface  $mailer)
    {
        $email = (new Email())
            ->from('dimitri.guillon331@gmail.com')
            ->to('dimitri.guillon331@gmail.com')
            ->subject('Test')
            ->html('<p> Test </p>');
        $mailer->send($email);

        return new Response('Email sent');
    }
}

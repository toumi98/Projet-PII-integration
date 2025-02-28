<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendTestEmail(string $toEmail): void
    {
        $email = (new Email())
            ->from('noreply@example.com')
            ->to($toEmail)
            ->subject('Test Ethereal Email')
            ->html('<p>Ceci est un test d\'email envoyé via Ethereal.</p>');

        $this->mailer->send($email);
    }
}

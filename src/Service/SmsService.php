<?php

namespace App\Service;

use Twilio\Rest\Client;

class SmsService
{
    private Client $twilio;
    private string $twilioFrom;

    public function __construct(string $twilioSid, string $twilioToken, string $twilioFrom)
    {
        $this->twilio = new Client($twilioSid, $twilioToken);
        $this->twilioFrom = $twilioFrom;
    }

    public function sendSms(string $to, string $message): void
    {
        try {
            $this->twilio->messages->create(
                $to,
                [
                    'from' => $this->twilioFrom,
                    'body' => $message
                ]
            );
        } catch (\Exception $e) {
            // Gérer l'erreur (log ou afficher un message)
            error_log("Erreur d'envoi SMS : " . $e->getMessage());
        }
    }
}

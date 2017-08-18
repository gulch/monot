<?php

declare(strict_types=1);

namespace Monot\Notification;

use Monot\Contract\Notification;

class TelegramNotification implements Notification
{
    const API_URL_QUERY = 'https://api.telegram.org/bot{TOKEN}/SendMessage';

    private $token;
    private $chat_id;

    public function __construct(string $token, string $chat_id)
    {
        $this->token = $token;
        $this->chat_id = $chat_id;
    }

    public function notify(string $text): bool
    {
        return $this->sendByCurl($text);
    }

    private function sendByCurl(string $text): bool
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => str_replace('{TOKEN}', $this->token, self::API_URL_QUERY),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => http_build_query([
                'text' => $text,
                'chat_id' => $this->chat_id,
            ])
        ]);

        $response = curl_exec($ch);

        if ($error = curl_error($ch)) {
            throw new \RuntimeException(curl_error($ch));
        }

        $data = json_decode($response, true);

        if ($data['ok'] === false) {
            throw new \ErrorException('Telegram API error: ' . $data['description']);
        }

        return true;
    }
}
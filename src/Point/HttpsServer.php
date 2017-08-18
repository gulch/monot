<?php

namespace Monot\Point;

use Monot\Contract\Point;
use Monot\Contract\Notification;

class HttpsServer implements Point
{
    const DEFAULT_PORT = 443;
    const SUCCESS_CODE = 200;

    private $host;
    private $timeout;
    private $notifier;

    /**
     * HttpsServer constructor.
     *
     * @param Notification $notifier
     * @param string $host (domain)
     * @param int $timeout (in seconds)
     */
    public function __construct(Notification $notifier, string $host, int $timeout = 5)
    {
        $this->host = $host;
        $this->timeout = $timeout;
        $this->notifier = $notifier;
    }

    public function getTarget(): string
    {
        return 'https://' . $this->host;
    }

    public function check(): bool
    {
        $message = $this->checkByCurl();

        /* if message is empty, check is passed */
        if ($message === '') {
            return true;
        }

        $this->notifier->notify($message);

        return false;
    }

    /**
     * @return string (text of error message or '' string)
     */
    private function checkByCurl(): string
    {
        $message = '';

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->getTarget(),
            CURLOPT_PORT => self::DEFAULT_PORT,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $message = 'Http server is not available: ' . curl_error($ch);
        }

        if ($error = curl_error($ch)) {
            $message = 'Error: ' . $error;
        }

        $http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code !== self::SUCCESS_CODE) {
            $message = "Http server {$this->host} return code {$http_code} when expecting " . self::SUCCESS_CODE;
        }

        curl_close($ch);

        return $message;
    }
}
<?php

namespace Monot\Point;

use Monot\Contract\Point;

class HttpServer implements Point
{
    const DEFAULT_PORT = 80;
    const SUCCESS_CODE = 200;

    private $host;
    private $timeout;

    /**
     * HttpServer constructor.
     * @param string $host (domain or IP)
     * @param int $timeout (in seconds)
     */
    public function __construct(string $host, int $timeout)
    {
        $this->host = $host;
        $this->timeout = $timeout;
    }

    public function getTarget(): string
    {
        return 'http://' . $this->host . ':' . self::DEFAULT_PORT;
    }

    public function check(): void
    {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('cURL extension not loaded. This library requires cURL.');
        }

        $curl = curl_init($this->getTarget());

        curl_setopt_array($curl, [
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => false,
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            throw new \RuntimeException('Http server is not available: ' . curl_error($curl));
        }

        if ($error = curl_error($curl)) {
            throw new \ErrorException('Error: ' . $error, 0, E_USER_ERROR);
        }

        $http_code = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($http_code !== self::SUCCESS_CODE) {
            throw new \ErrorException(
                "Http server {$this->host} return code {$http_code} when expecting " . self::SUCCESS_CODE,
                $http_code,
                E_USER_WARNING
            );
        }

        curl_close($curl);
    }
}
<?php

/*
 * This file is part of the "Kata 1" package.
 *
 * Copyright (c) Daniel González
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Daniel González <daniel@desarrolla2.com>
 */

namespace Component\Http;

/**
 * Response.
 */
class Response
{
    const HTTP_VERSION = 1.1;
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_MOVED_PERMANENTLY = 301;
    const HTTP_FOUND = 302;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * @var array
     */
    public static $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        301 => 'Moved Permanently',
        302 => 'Found',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    ];

    /**
     * @var array;
     */
    protected $headers;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $content;

    /**
     * @param string $content
     * @param int    $status
     * @param array  $headers
     */
    public function __construct($content = '', $status = self::HTTP_OK, array $headers = [])
    {
        $this->addHeader('Content-Type', 'text/html; charset=UTF-8');
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }
        $this->statusCode = $status;
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$this->normalizeHeaderName($key)] = $value;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getHeader($key)
    {
        $key = $this->normalizeHeaderName($key);
        if (isset($this->headers[$key])) {
            return $this->headers[$key];
        }
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    public function send()
    {
        $statusText = isset(self::$statusTexts[$this->statusCode]) ? self::$statusTexts[$this->statusCode] : '';
        header(sprintf('HTTP/%s %s %s', self::HTTP_VERSION, $this->statusCode, $statusText), true, $this->statusCode);
        foreach ($this->headers as $name => $value) {
            header($name.': '.$value);
        }

        echo $this->content;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function normalizeHeaderName($key)
    {
        return ucwords(str_replace('_', '-', strtolower($key)), '-');
    }
}

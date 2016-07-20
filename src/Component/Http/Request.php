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
 * Request.
 */
class Request
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $get;

    /**
     * @var array
     */
    protected $post;

    /**
     * @var array
     */
    protected $server;

    /**
     * @var array
     */
    protected $cookie;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var string
     */
    protected $uri = '/';

    /**
     * @param string $method
     * @param array  $get
     * @param array  $post
     * @param array  $server
     * @param array  $cookie
     * @param array  $headers
     */
    public function __construct(
        $method,
        array $get = [],
        array $post = [],
        array $server = [],
        array $cookie = [],
        array $headers = []
    ) {
        $this->method = $method;
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->cookie = $cookie;
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        $path = $this->get('server', 'REQUEST_URI');
        $parsed = parse_url($path);
        if (isset($parsed['path'])) {
            $this->uri = $parsed['path'];
        }
    }

    public static function createFromGlobals()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return new static($method, $_GET, $_POST, $_SERVER, $_COOKIE, $headers);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $source
     * @param string $key
     * @param mixed  $default
     */
    public function get($source, $key, $default = false)
    {
        if (!in_array($source, ['get', 'post', 'server', 'cookie'])) {
            return;
        }
        $data = $this->$source;
        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getHeader($key)
    {
        $key = $this->normalizeHeaderName($key);
        if (isset($this->headers[$key])) {
            return $this->headers[$key];
        }
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
     * @return string
     */
    protected function normalizeHeaderName($key)
    {
        return ucwords(str_replace('_', '-', strtolower($key)), '-');
    }
}

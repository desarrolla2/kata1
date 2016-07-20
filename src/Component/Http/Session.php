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

use App\Model\User;

/**
 * Session.
 */
class Session
{
    /**
     * @param array $options
     * @param int   $timeout
     */
    public function __construct($options, $timeout = 3600)
    {
        if (php_sapi_name() != 'cli') {
            session_cache_limiter('');
            ini_set('session.use_cookies', 1);
            session_start($options);
        }
        $lastTime = $this->get('app.last.time', false);
        if ($lastTime + $timeout < time()) {
            $_SESSION = [];
        }
        $this->set('app.last.time', time());
    }

    public function close()
    {
        $_SESSION = [];
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->set('app.user', $user);
    }

    /**
     * @return User|false
     */
    public function getUser()
    {
        return $this->get('app.user', null);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param string $key
     * @param mixed  $default
     */
    public function get($key, $default = false)
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $_SESSION[$key];
    }
}

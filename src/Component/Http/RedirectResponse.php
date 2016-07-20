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
 * RedirectResponse.
 */
class RedirectResponse extends Response
{
    /**
     * @param string $url
     * @param int    $status
     * @param array  $headers
     */
    public function __construct($url, $status = 302, $headers = [])
    {
        parent::__construct('', $status, array_merge(['location' => $url, $headers]));
    }
}

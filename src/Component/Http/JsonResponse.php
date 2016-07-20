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
 * JsonResponse.
 */
class JsonResponse extends Response
{
    /**
     * @param string $content
     * @param int    $status
     * @param array  $headers
     */
    public function __construct($content = '', $status = self::HTTP_OK, array $headers = [])
    {
        parent::__construct(
            json_encode($content),
            $status,
            array_merge(
                [
                    'content-type' => 'application/json',
                ],
                $headers
            )
        );
    }
}

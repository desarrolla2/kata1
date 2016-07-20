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

namespace App\Negociation\Handler;

use Negotiation\Negotiator;



use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Http\Response;

/**
 * ResponseHandler.
 */
class ResponseHandler
{
    /**
     * @var Negotiator
     */
    protected $negotiator;

    /**
     * @param Negotiator $negotiator
     */
    public function __construct(Negotiator $negotiator)
    {
        $this->negotiator = $negotiator;
    }

    /**
     * @param Request $request
     * @param array   $data
     * @param int     $status
     *
     * @return Response
     */
    public function negociate(Request $request, array $data = [], $status = Response::HTTP_OK)
    {
        $acceptHeader = $request->getHeader('Accept') ? $request->getHeader('Accept') : 'application/json';
        $priorities = ['application/json', 'text/html; charset=UTF-8'];
        $mediaType = $this->negotiator->getBest($acceptHeader, $priorities);
        $value = $mediaType->getValue();
        if ($value == 'text/html; charset=UTF-8') {
            return new Response(print_r($data, true), $status);
        }

        return new JsonResponse($data, $status);
    }
}

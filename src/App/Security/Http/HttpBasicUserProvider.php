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

namespace App\Security\Http;

use Component\Http\Request;

use App\Repository\UserRepository;

/**
 * HttpBasicUserProvider.
 */
class HttpBasicUserProvider
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * HttpBasicUserProvider constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request|null $request
     *
     * @return \App\Model\User|false
     */
    public function getUser(Request $request = null)
    {
        if (!$request) {
            return;
        }
        $token = base64_decode($request->getHeader('Authorization'));
        if (!$token) {
            return;
        }
        if (!strpos($token, ':')) {
            return;
        }
        list($name, $password) = explode(':', $token);

        return $this->repository->findByNameAndPassword($name, $password);
    }
}

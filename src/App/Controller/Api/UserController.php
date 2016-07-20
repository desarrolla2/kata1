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

namespace App\Controller\Api;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Http\Response;

use App\Controller\AppController;
use App\Model\User;
use App\Repository\UserRepository;

/**
 * UserController.
 */
class UserController extends AppController
{
    /**
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $users = $this->app['app.repository.user']->findAll();
        $data = ['items' => [], 'total' => count($users)];
        /** @var User $user */
        foreach ($users as $user) {
            $data['items'][] = [
                'name' => $user->getName(),
                'roles' => $user->getRoles(),
                'links' => ['rel' => 'self', 'href' => sprintf('/api/users/%s', $user->getName())],
            ];
        }

        return $this->app['api.response.negociator']->negociate($request, $data);
    }

    /**
     * @param Request $request
     * @param array   $parameters
     *
     * @return JsonResponse
     */
    public function getAction(Request $request, $parameters)
    {
        $user = $this->app['app.repository.user']->findByName($parameters['name']);
        if (!$user) {
            return $this->app['api.response.negociator']->negociate(
                $request,
                ['error' => 'not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->app['api.response.negociator']->negociate(
            $request,
            [
                'name' => $user->getName(),
                'roles' => $user->getRoles(),
                'links' => ['rel' => 'self', 'href' => sprintf('/api/users/%s', $user->getName())],
            ]
        );
    }

    /**
     * @param Request $request
     * @param array   $parameters
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, $parameters)
    {
        /** @var UserRepository $repository */
        $repository = $this->app['app.repository.user'];
        $user = new User($parameters['name'], $request->get('post', 'roles', []));
        $repository->updateUser($user, $request->get('post', 'password'));

        return $this->app['api.response.negociator']->negociate(
            $request,
            [
                'name' => $user->getName(),
                'roles' => $user->getRoles(),
                'links' => ['rel' => 'self', 'href' => sprintf('/api/users/%s', $user->getName())],
            ]
        );
    }

    /**
     * @param Request $request
     * @param array   $parameters
     *
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $parameters)
    {
        /** @var UserRepository $repository */
        $repository = $this->app['app.repository.user'];
        $repository->removeUser($parameters['name']);

        return $this->app['api.response.negociator']->negociate(
            $request,
            []
        );
    }
}

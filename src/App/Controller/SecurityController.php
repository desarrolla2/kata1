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

namespace App\Controller;

use Component\Http\RedirectResponse;
use Component\Http\Request;
use Component\Http\Session;

use App\Repository\UserRepository;

/**
 * SecurityController.
 */
class SecurityController extends AppController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        /* @var Session */
        $session = $this->app['app.session'];
        $user = $session->getUser();
        if ($user) {
            return new RedirectResponse('/');
        }
        if ($request->getMethod() == 'POST') {
            /* @var UserRepository */
            $repository = $this->app['app.repository.user'];

            $user = $repository->findByNameAndPassword(
                $request->get('post', 'name', false),
                $request->get('post', 'password', false)
            );
            if (!$user) {
                return $this->render('Login\\index.html.twig', ['error' => 'Name or password invalid']);
            }
            $session->setUser($user);
            if ($session->has('app.redirect_on_login')) {
                return new RedirectResponse($session->get('app.redirect_on_login'));
            }

            return new RedirectResponse('/');
        }

        return $this->render('Login\\index.html.twig', []);
    }

    /**
     * @return RedirectResponse
     */
    public function logoutAction()
    {
        /* @var Session */
        $session = $this->app['app.session'];
        $session->close();

        return new RedirectResponse('/login');
    }
}

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

namespace App;

use Negotiation\Negotiator;
use Pimple\Container;



use Component\Firewall\Firewall;
use Component\Http\JsonResponse;
use Component\Http\RedirectResponse;
use Component\Http\Request;
use Component\Http\Response;
use Component\Http\Session;

use App\Controller\Api\UserController;
use App\Controller\DefaultController;
use App\Controller\PageController;
use App\Controller\SecurityController;
use App\Negociation\Handler\ResponseHandler;
use App\Repository\UserRepository;
use App\Security\Http\HttpBasicUserProvider;

/**
 * Application.
 */
class Application extends Container
{
    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);
        $this['debug'] = false;
        $this['app.cache.dir'] = __DIR__.'/../../var/cache';
        $this['app.views.dir'] = __DIR__.'/../../views';
        $this['app.session'] = function () {
            return new Session(
                [
                    'cookie_lifetime' => 5 * 60,
                ], 5 * 60
            );
        };
        $this['app.user'] = $this->factory(
            function ($app) {
                return $app['app.session']->getUser();
            }
        );
        $this['api.provider.user'] = function ($app) {
            return new HttpBasicUserProvider($app['app.repository.user']);
        };
        $this['api.user'] = $this->factory(
            function ($app) {
                return $this['api.provider.user']->getUser($app['app.request']);
            }
        );
        $this['twig.loader'] = function ($app) {
            return new \Twig_Loader_Filesystem($app['app.views.dir']);
        };
        $this['twig'] = function ($app) {
            $twig = new \Twig_Environment(
                $app['twig.loader'], [
                    'debug' => $app['debug'],
                    'cache' => $this['app.cache.dir'].'/twig',
                ]
            );

            return $twig;
        };
        $this['app.repository.user'] = function () {
            return new UserRepository();
        };
        $this['app.controller.default'] = function ($app) {
            return new DefaultController($app);
        };
        $this['app.controller.security'] = function ($app) {
            return new SecurityController($app);
        };
        $this['app.controller.page'] = function ($app) {
            return new PageController($app);
        };
        $this['app.controller.api.user'] = function ($app) {
            return new UserController($app);
        };
        $this['api.response.negociator'] = function () {
            return new ResponseHandler(new Negotiator());
        };
        $this['app.firewall'] = function () {
            $firewall = new Firewall();
            $firewall->addRoute([], '/page/1', 'ROLE_PAGE_1');
            $firewall->addRoute([], '/page/2', 'ROLE_PAGE_2');
            $firewall->addRoute([], '/page/3', 'ROLE_PAGE_3');

            return $firewall;
        };
        $this['api.firewall'] = function () {
            $firewall = new Firewall();
            $firewall->addRoute('GET', '/api', 'ROLE_API_READ');

            return $firewall;
        };
        $this['app.router'] = function ($app) {
            return \FastRoute\simpleDispatcher(
                function (\FastRoute\RouteCollector $router) {
                    $router->addRoute(['GET'], '/', ['default', 'index']);
                    $router->addRoute(['GET', 'POST'], '/login', ['security', 'index']);
                    $router->addRoute(['GET'], '/logout', ['security', 'logout']);
                    $router->addRoute(['GET'], '/page/{page:[1-3]}', ['page', 'index']);
                    $router->addRoute(['GET'], '/api/users', ['api.user', 'list']);
                    $router->addRoute(['GET'], '/api/users/{name}', ['api.user', 'get']);
                    $router->addRoute(['POST', 'PUT'], '/api/users/{name}', ['api.user', 'update']);
                    $router->addRoute(['DELETE'], '/api/users/{name}', ['api.user', 'delete']);
                },
                [
                    'cacheFile' => $app['app.cache.dir'].'/route',
                    'cacheDisabled' => $app['debug'],
                ]
            );
        };
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        $this['app.request'] = $request;
        if ($this->isRequestApi($request->getUri())) {
            $isGranted = $this['api.firewall']->isGranted($request->getMethod(), $request->getUri(), $this['api.user']);
            if (!$isGranted) {
                return new JsonResponse(
                    ['code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Unauthorized'],
                    Response::HTTP_UNAUTHORIZED
                );
            }
        } else {
            $isGranted = $this['app.firewall']->isGranted($request->getMethod(), $request->getUri(), $this['app.user']);
            if (!$isGranted) {
                if ($this['app.user']) {
                    return new Response(
                        $this['twig']->render(
                            'Error\\index.html.twig',
                            ['code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Unauthorized']
                        ),
                        Response::HTTP_UNAUTHORIZED
                    );
                }
                $this['app.session']->set('app.redirect_on_login', $request->getUri());

                return new RedirectResponse('/login');
            }
        }

        try {
            $routeInfo = $this['app.router']->dispatch($request->getMethod(), $request->getUri());
            switch ($routeInfo[0]) {
                case \FastRoute\Dispatcher::NOT_FOUND:
                    return new Response(
                        $this['twig']->render(
                            'Error\\index.html.twig',
                            [
                                'code' => 404,
                                'message' => 'Not Found',
                            ]
                        ),
                        Response::HTTP_NOT_FOUND,
                        ['Content-Type' => 'text/html; charset=UTF-8']
                    );
                case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                    return new Response(
                        $this['twig']->render(
                            'Error\\index.html.twig',
                            [
                                'code' => 405,
                                'message' => 'Not Allowed',
                            ]
                        ),
                        Response::HTTP_METHOD_NOT_ALLOWED,
                        ['Content-Type' => 'text/html; charset=UTF-8']
                    );
                case \FastRoute\Dispatcher::FOUND:
                default:
                    $handler = $routeInfo[1];
                    $controller = 'app.controller.'.$handler[0];
                    $method = $handler[1].'Action';
                    $this['twig']->addGlobal('app_user', $this['app.user']);

                    return $this[$controller]->$method($request, $routeInfo[2]);
            }
        } catch (\Exception $e) {
            $message = $this['debug'] ? $e->getMessage() : 'Internal Server Error';

            return new Response(
                $this['twig']->render(
                    'Error\\index.html.twig',
                    ['code' => 500, 'message' => $message]
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isRequestApi($path)
    {
        return substr($path, 0, 4) === '/api';
    }
}

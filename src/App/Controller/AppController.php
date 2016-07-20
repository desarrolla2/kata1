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

use Component\Http\Response;

use App\Application;

/**
 * AppController.
 */
abstract class AppController
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $templateName
     * @param array  $vars
     *
     * @return Response
     */
    public function render($templateName, array $vars = [])
    {
        return new Response(
            $this->app['twig']->render($templateName, $vars),
            Response::HTTP_OK
        );
    }
}

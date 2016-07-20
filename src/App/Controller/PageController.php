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

use Component\Http\Request;
use Component\Http\Response;

/**
 * PageController.
 */
class PageController extends AppController
{
    /**
     * @param Request $request
     * @param array   $parameters
     *
     * @return Response
     */
    public function indexAction(Request $request, array $parameters)
    {
        return $this->render(
            'Page\\index.html.twig',
            [
                'name' => 'Page '.$parameters['page'],
            ]
        );
    }
}

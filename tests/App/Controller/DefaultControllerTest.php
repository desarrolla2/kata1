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

namespace Tests\App\Controller;

use Tests\App\ApplicationTestCase;

use Component\Http\Response;

/**
 * DefaultControllerTest.
 */
class DefaultControllerTest extends ApplicationTestCase
{
    public function testIndex()
    {
        $response = $this->get('/');
        $this->assertOk($response);
        $this->assertResponseIsHtml($response);
    }

    public function testIndexWithBadMethod()
    {
        $response = $this->post('/');
        $this->assertStatus($response, Response::HTTP_METHOD_NOT_ALLOWED);
        $this->assertResponseIsHtml($response);
    }

    public function testPageNotFound()
    {
        $response = $this->get('/this-page-not-exist');
        $this->assertStatus($response, Response::HTTP_NOT_FOUND);
        $this->assertResponseIsHtml($response);
    }
}

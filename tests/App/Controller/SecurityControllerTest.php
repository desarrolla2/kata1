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

namespace Tests\App;

namespace Tests\App\Controller;

use Tests\App\ApplicationTestCase;

/**
 * SecurityControllerTest.
 */
class SecurityControllerTest extends ApplicationTestCase
{
    /**
     * @return array
     */
    public function dataProviderForTestIndex()
    {
        return [['admin', 'admin'], ['user1', 'user1']];
    }

    /**
     * @dataProvider dataProviderForTestIndex
     *
     * @param string $name
     * @param string $password
     */
    public function testIndex($name, $password)
    {
        $response = $this->login($name, $password);
        $this->assertRedirect($response);
        $redirect = $response->getHeader('Location');
        $this->assertEquals('/', $redirect);

        $response = $this->get($redirect);
        $this->assertResponseIsHtml($response);
        $this->assertResponseHas($response, 'Wellcome');
        $this->assertResponseHas($response, $name);

        $this->logout();
    }

    /**
     * @dataProvider dataProviderForTestIndex
     *
     * @param string $name
     * @param string $password
     */
    public function testIndexWithRedirection($name, $password)
    {
        $target = '/page/1';
        $response = $this->get($target);
        $this->assertRedirect($response);
        $redirect = $response->getHeader('Location');
        $this->assertEquals('/login', $redirect);

        $response = $this->login($name, $password);
        $this->assertRedirect($response);
        $redirect = $response->getHeader('Location');
        $this->assertEquals($target, $redirect);

        $response = $this->get($redirect);
        $this->assertResponseIsHtml($response);
        $this->assertResponseHas($response, 'Wellcome');
        $this->assertResponseHas($response, $name);

        $this->logout();
    }

    public function testIndexWithBadCredentials()
    {
        $response = $this->login('user', 'not-exist');
        $this->assertOk($response);
        $this->assertResponseIsHtml($response);
        $this->assertResponseHas($response, 'Login Page');
    }
}

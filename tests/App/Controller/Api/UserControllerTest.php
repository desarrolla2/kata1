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

namespace Tests\App\Controller\Api;

use Tests\App\ApplicationTestCase;

use Component\Http\Response;

/**
 * UserControllerTest.
 */
class UserControllerTest extends ApplicationTestCase
{
    /**
     * @return array
     */
    public function dataProviderForTest()
    {
        return [['user1', base64_encode('user1:user1')], ['admin', base64_encode('admin:admin')]];
    }

    /**
     * @dataProvider dataProviderForTest
     *
     * @param string $user
     */
    public function testWithoutPermission($user)
    {
        $response = $this->get(sprintf('/api/users/%s', $user));
        $this->assertStatus($response, Response::HTTP_UNAUTHORIZED);
        $this->assertResponseIsJson($response);
    }

    /**
     * @dataProvider dataProviderForTest
     *
     * @param string $user
     */
    public function testIndex($user, $token)
    {
        $response = $this->get('/api/users', [], ['Authorization' => $token]);
        $this->assertOk($response);
        $this->assertResponseIsJson($response);
        $this->assertJsonHas($response, 'items');
        $this->assertJsonHas($response, 'total');
    }

    /**
     * @dataProvider dataProviderForTest
     *
     * @param string $user
     */
    public function testIndexAcceptHtml($user, $token)
    {
        $response = $this->get('/api/users', [], ['Authorization' => $token, 'Accept' => 'text/html; charset=UTF-8']);
        $this->assertOk($response);
        $this->assertResponseIsHtml($response);
        $this->assertResponseHas($response, 'items');
        $this->assertResponseHas($response, 'total');
    }

    /**
     * @return array
     */
    public function dataProviderAdminUser()
    {
        return [['admin', base64_encode('admin:admin')]];
    }

    /**
     * @dataProvider dataProviderAdminUser
     *
     * @param string $user
     * @param string $token
     */
    public function testGet($user, $token)
    {
        $response = $this->get(sprintf('/api/users/%s', $user), [], ['Authorization' => $token]);
        $this->assertOk($response);
        $this->assertResponseIsJson($response);
        $this->assertJsonHas($response, 'name');
        $this->assertJsonHas($response, 'roles');
        $this->assertJsonHas($response, 'links');
    }

    /**
     * @dataProvider dataProviderAdminUser
     *
     * @param string $user
     * @param string $token
     */
    public function testUpdate($user, $token)
    {
        $response = $this->post(
            sprintf('/api/users/%s', $user),
            [
                'roles' => [],
                'password' => '123',
            ],
            ['Authorization' => $token]
        );
        $this->assertOk($response);
        $this->assertResponseIsJson($response);
        $this->assertJsonHas($response, 'name');
        $this->assertJsonHas($response, 'roles');
        $this->assertJsonHas($response, 'links');
    }

    /**
     * @dataProvider dataProviderAdminUser
     *
     * @param string $user
     * @param string $token
     */
    public function testDelete($user, $token)
    {
        $response = $this->delete(sprintf('/api/users/%s', $user), ['Authorization' => $token]);
        $this->assertOk($response);
    }
}

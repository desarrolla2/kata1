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

use Component\Http\Request;
use Component\Http\Response;

use App\Application;

abstract class ApplicationTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \App\Application
     */
    protected $app;

    public function setUp()
    {
        $this->app = new Application();
        $this->app['debug'] = true;
    }

    protected function tearDown()
    {
        $this->app = null;
    }

    /**
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     */
    protected function get($path, array $parameters = [], array $headers = [])
    {
        $request = new Request('GET', $parameters, [], ['REQUEST_URI' => $path], [], $headers);

        return $this->app->handle($request);
    }

    /**
     * @param string $path
     * @param array  $parameters
     */
    protected function post($path, $parameters = [])
    {
        $request = new Request('POST', [], $parameters, ['REQUEST_URI' => $path]);

        return $this->app->handle($request);
    }

    /**
     * @param string $path
     * @param array  $parameters
     */
    protected function delete($path)
    {
        $request = new Request('POST', [], [], ['REQUEST_URI' => $path]);

        return $this->app->handle($request);
    }

    /**
     * @param string $name
     * @param string $password
     *
     * @return Response
     */
    protected function login($name, $password)
    {
        $response = $this->get('/login');
        $this->assertOk($response);
        $this->assertResponseIsHtml($response);
        $this->assertResponseHas($response, 'Login Page');

        return $this->post('/login', ['name' => $name, 'password' => $password]);
    }

    /**
     * @return Response
     */
    protected function logout()
    {
        $response = $this->get('/logout');
        $this->assertRedirect($response);
        $redirect = $response->getHeader('Location');
        $this->assertEquals('/login', $redirect);

        return $response;
    }

    /**
     * @param Response $response
     * @param int      $status
     */
    protected function assertStatus(Response $response, $status)
    {
        $this->assertEquals(
            $status,
            $response->getStatusCode(),
            $response->getContent()
        );
    }

    /**
     * @param Response $response
     */
    protected function assertOk(Response $response)
    {
        $this->assertStatus($response, Response::HTTP_OK);
    }

    /**
     * @param Response $response
     */
    protected function assertRedirect(Response $response)
    {
        $this->assertStatus($response, Response::HTTP_FOUND);
    }

    /**
     * @param Response $response
     */
    protected function assertRedirectTo(Response $response, $location)
    {
        $this->assertStatus($response, Response::HTTP_FOUND);
        $this->assertEquals($location, $response->getHeader('Location'));
    }

    /**
     * @param Response $response
     * @param string   $text
     */
    protected function assertResponseHas(Response $response, $text)
    {
        $this->assertContains(
            $text,
            $response->getContent(),
            'Not found " {'.$text.'} "'
        );
    }

    /**
     * @param Response $response
     * @param string   $text
     */
    protected function assertResponseHasNot(Response $response, $text)
    {
        $this->assertNotContains($text, $response->getContent(), 'Found not expected "'.$text.'"');
    }

    /**
     * @param Response $response
     */
    protected function assertResponseIsHtml(Response $response)
    {
        $this->assertSame('text/html; charset=UTF-8', $response->getHeader('Content-Type'));
    }

    /**
     * @param Response $response
     */
    protected function assertResponseIsJson(Response $response)
    {
        $this->assertSame('application/json', $response->getHeader('Content-Type'));
    }

    /**
     * @param Response $response
     * @param string   $key
     */
    protected function assertJsonHas(Response $response, $key)
    {
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey($key, $data);
    }
}

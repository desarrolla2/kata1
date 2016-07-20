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

namespace Tests\Component\Htpp;

use Component\Http\Request;

/**
 * RequestTest.
 */
class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateFromGlobals()
    {
        $request = Request::createFromGlobals();
        $this->assertEquals(false, $request->get('server', 'REMOTE_ADDR', false));
        $this->assertEquals(false, $request->get('server', 'HTTP_USER_AGENT', false));
    }

    /**
     * @dataProvider dataProviderForTestMethodAndUri
     *
     * @param string $method
     * @param string $uri
     */
    public function testMethodAndUri($method, $uri)
    {
        $request = new Request($method, [], [], ['REQUEST_URI' => $uri]);
        $this->assertEquals($method, $request->getMethod());
    }

    public function dataProviderForTestMethodAndUri()
    {
        return [
            ['GET', '/'],
            ['POST', '/login'],
        ];
    }

    /**
     * @dataProvider dataProviderForTestParameters
     *
     * @param array  $parameters
     * @param string $key
     * @param string $value
     */
    public function testGetParameters($parameters, $key, $value)
    {
        $request = new Request('', $parameters);
        $this->assertEquals($value, $request->get('get', $key));
    }

    /**
     * @dataProvider dataProviderForTestParameters
     *
     * @param array  $parameters
     * @param string $key
     * @param string $value
     */
    public function testPostParameters($parameters, $key, $value)
    {
        $request = new Request('', [], $parameters);
        $this->assertEquals($value, $request->get('post', $key));
    }

    /**
     * @dataProvider dataProviderForTestParameters
     *
     * @param array  $parameters
     * @param string $key
     * @param string $value
     */
    public function testServerParameters($parameters, $key, $value)
    {
        $request = new Request('', [], [], $parameters);
        $this->assertEquals($value, $request->get('server', $key));
    }

    /**
     * @dataProvider dataProviderForTestParameters
     *
     * @param array  $parameters
     * @param string $key
     * @param string $value
     */
    public function testCookieParameters($parameters, $key, $value)
    {
        $request = new Request('', [], [], [], $parameters);
        $this->assertEquals($value, $request->get('cookie', $key));
    }

    public function dataProviderForTestParameters()
    {
        return [
            [['foo' => 'bar'], 'foo', 'bar'],
            [['foo' => 'bar'], 'bar', false],
        ];
    }
}

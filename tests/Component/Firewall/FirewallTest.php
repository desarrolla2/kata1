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

namespace Tests\Component\Firewall;

use Component\Firewall\Firewall;
use Component\Firewall\HasRolesInterface;

use App\Model\User;

/**
 * FirewallTest.
 */
class FirewallTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Firewall
     */
    protected $firewall;

    /**
     * @var HasRolesInterface
     */
    protected $user1;

    /**
     * @var HasRolesInterface
     */
    protected $admin;

    /**
     * @var null
     */
    protected $none = null;

    protected function setUp()
    {
        $this->firewall = new Firewall();
        $this->firewall->addRoute(['GET', 'POST'], '/this-route-not-used', 'THIS_ROLE_NOT_USED');
        $this->firewall->addRoute([], '/this-other-route-not-used', 'THIS_ROLE_NOT_USED');
        $this->user = new User('', ['USER_1']);
        $this->admin = new User('', [Firewall::ROLE_ADMIN]);
    }

    public function testWithAccess()
    {
        $this->assertTrue($this->firewall->isGranted('GET', '/', $this->none));
        $this->assertTrue($this->firewall->isGranted('GET', '/', $this->user1));
        $this->assertTrue($this->firewall->isGranted('GET', '/', $this->admin));
    }

    public function testWithUserAccess()
    {
        $this->firewall->addRoute('GET', '/private', 'USER_1');
        $this->assertFalse($this->firewall->isGranted('GET', '/private', $this->none));
        $this->assertTrue($this->firewall->isGranted('GET', '/private', $this->user));
        $this->assertTrue($this->firewall->isGranted('GET', '/private', $this->admin));
    }

    public function testWithAdminAccess()
    {
        $this->firewall->addRoute('GET', '/private', 'USER_2');
        $this->assertFalse($this->firewall->isGranted('GET', '/private', $this->none));
        $this->assertFalse($this->firewall->isGranted('GET', '/private', $this->user));
        $this->assertTrue($this->firewall->isGranted('GET', '/private', $this->admin));
    }

    public function testWithUserAccessForMethod()
    {
        $this->firewall->addRoute('POST', '/private', 'USER_2');
        $this->assertFalse($this->firewall->isGranted('POST', '/private', $this->user));
        $this->assertTrue($this->firewall->isGranted('GET', '/private', $this->user));
    }
}

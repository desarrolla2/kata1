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

namespace App\Model;

use Component\Firewall\HasRolesInterface;

/**
 * User.
 */
class User implements HasRolesInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @param string $name
     * @param array  $roles
     */
    public function __construct($name, array $roles)
    {
        $this->name = $name;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}

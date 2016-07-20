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

namespace Component\Firewall;

/**
 * Interface HasRolesInterface.
 */
interface HasRolesInterface
{
    /**
     * @return array
     */
    public function getRoles();
}

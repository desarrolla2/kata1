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
 * Firewall.
 */
class Firewall
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param string|array $methods
     * @param string       $path
     * @param string       $role
     */
    public function addRoute($methods, $path, $role)
    {
        $this->routes[strtolower($path)] = [
            'path' => strtolower($path),
            'role' => $role,
            'methods' => is_array($methods) ? $methods : [$methods],
        ];
    }

    /**
     * @param string                 $method
     * @param string                 $uri
     * @param HasRolesInterface|null $user
     *
     * @return bool
     */
    public function isGranted($method, $uri, HasRolesInterface $user = null)
    {
        foreach ($this->getRoutes() as $route) {
            if ($this->isUriInRoutes($method, $uri, $route)) {
                if (!$this->isGrantedRoute($method, $uri, $route, $user)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param string                 $method
     * @param string                 $path
     * @param array                  $route
     * @param HasRolesInterface|null $user
     *
     * @return bool
     */
    protected function isGrantedRoute($method, $path, $route, HasRolesInterface $user = null)
    {
        if (!$user) {
            return false;
        }
        if ($this->hasRole($user, self::ROLE_ADMIN)) {
            return true;
        }
        if ($this->hasRole($user, $route['role'])) {
            return true;
        }

        return false;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $route
     *
     * @return bool
     */
    protected function isUriInRoutes($method, $uri, $route)
    {
        if (substr(strtolower($uri), 0, strlen($route['path'])) != $route['path']) {
            return false;
        };
        if (!$route['methods']) {
            return true;
        }
        foreach ($route['methods'] as $routeMethod) {
            if ($routeMethod === $method) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param HasRolesInterface $user
     * @param string            $role
     *
     * @return bool
     */
    protected function hasRole(HasRolesInterface $user, $role)
    {
        return in_array($role, $user->getRoles());
    }

    /**
     * @return array
     */
    protected function getRoutes()
    {
        return $this->routes;
    }
}

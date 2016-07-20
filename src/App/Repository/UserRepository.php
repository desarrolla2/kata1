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

namespace App\Repository;

use Component\Firewall\Firewall;

use App\Model\User;

/**
 * UserRepository.
 */
class UserRepository
{
    /**
     * @var array
     */
    protected $database = [
        'user1' => [
            'password' => '$2y$11$08Snn524ll7Il70xB6aYS.T2kYt57owRzePDYQ.J78vjDjAj22WA6',
            'roles' => ['ROLE_PAGE_1', 'ROLE_API_READ'],
        ],
        'user2' => [

            'password' => '$2y$11$08Snn524ll7Il70xB6aYS.wVlOEQAdvMt4dOE52YSVJo6LhSPebcO',
            'roles' => ['ROLE_PAGE_2', 'ROLE_API_READ'],
        ],
        'user3' => [
            'password' => '$2y$11$08Snn524ll7Il70xB6aYS.VtYPqIhrXpzIw1nsbSkmOGA8nvTjdxS',
            'roles' => ['ROLE_PAGE_3', 'ROLE_API_READ'],
        ],
        'admin' => [
            'password' => '$2y$11$08Snn524ll7Il70xB6aYS.8aU.gMxuV0NqHQAlvXz3th85.1zjI5C',
            'roles' => [Firewall::ROLE_ADMIN],
        ],
    ];

    /**
     * @return array
     */
    public function findAll()
    {
        $users = [];
        foreach ($this->database as $key => $user) {
            $users[] = new User($key, $user['roles']);
        }

        return $users;
    }

    /**
     * @param string $name
     *
     * @return User|bool
     */
    public function findByName($name)
    {
        if (!isset($this->database[$name])) {
            return false;
        }

        return new User($name, $this->database[$name]['roles']);
    }

    /**
     * @param string $name
     * @param string $password
     *
     * @return User|bool
     */
    public function findByNameAndPassword($name, $password)
    {
        if (!isset($this->database[$name])) {
            return false;
        }
        $data = $this->database[$name];

        if (!password_verify($password, $data['password'])) {
            return false;
        }

        return new User($name, $data['roles']);
    }

    /**
     * Sorry this changes will be not persisted.
     *
     * @param User   $user
     * @param string $password
     */
    public function updateUser(User $user, $password)
    {
        $this->database[$user->getName()] = [
            'password' => $this->encode($password),
            'roles' => $user->getRoles(),
        ];
    }

    /**
     * Sorry this changes will be not persisted.
     *
     * @param string $name
     */
    public function removeUser($name)
    {
        unset($this->database[$name]);
    }

    /**
     * @param string $password
     *
     * @return bool|string
     */
    protected function encode($password)
    {
        return password_hash(
            $password,
            PASSWORD_BCRYPT,
            [
                'cost' => 11,
                'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
            ]
        );
    }
}

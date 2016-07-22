<?php
/*
 * This file is part of the scribd.technical.test package.
 *
 * (c) Daniel González <daniel@desarrolla2.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require __DIR__.'/../vendor/autoload.php';

use Component\Http\Request;
use App\Application;

$request = Request::createFromGlobals();
$app = new Application();
$app['debug'] = true;
$response = $app->handle($request);
$response->send();

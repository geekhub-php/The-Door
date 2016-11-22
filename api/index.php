<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;

$app = new Silex\Application();


$app->GET('/users', function(Application $app, Request $request) {
    $api_key = $request->get('api_key');

    return new JsonResponse([
        ['10-B4-B1-0C-AA-4C', 'ZHORB39mUQwqWDRL9vGl'],
        ['1E-09-8C-86-E1-09', 'KTRZ02mTDiJ6oofu1Ao0'],
        ['B9-49-A9-55-4D-9D', '5stZz3ntLaXrsvJ6JKiW'],
        ['CE-02-01-8F-E3-7D', 'lge7cwcozZ9F7zzCgkSU'],
        ['60-F1-97-45-CB-FA', '4UFAAnqwCEUtjDdWe7O2'],
        ['A3-3C-F9-CF-66-0F', 'c1BQ5cXazu2yiuFuG48M'],
        ['D1-70-2D-EE-7E-DC', 'q9GsEGyZJs2FQ51RhUGa'],
        ['E3-AD-E0-81-23-89', 'kzSlbyqQ6WtpDHYUJ5PT'],
    ]);
    });
$app->run();

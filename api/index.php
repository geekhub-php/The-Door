<?php
require_once __DIR__ . '/../vendor/autoload.php';

const DEMO_API_KEY = '123456';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Application;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => realpath(__DIR__.'/app.db'),
    ),
));

$app->before(function (Request $request, Application $app) {
    $apiKey = $request->headers->get('api-key');
    if ($apiKey !== DEMO_API_KEY) {
        return new Response(null, 403);
    }
});


$app->get('/users', function(Application $app, Request $request) {
    $offset = $request->get('offset', 0);
    $length = $request->get('length', 10);

    $users['users'] = [
        ['mac' => '90-E6-BA-EE-40-B1', 'key' => '8TYF3VmStnMy'],
        ['mac' => '00-26-37-BD-39-42', 'key' => 'yTstbJQlQXFu'],
        ['mac' => '0A-00-27-00-00-14', 'key' => '4xLA0donWu2z'],
        ['mac' => '60-6C-66-24-5C-CB', 'key' => 'PScg7HYQntC7'],
        ['mac' => '60-6C-66-24-5C-CA', 'key' => 'BhYQuryrUfze'],
        ['mac' => '60-6C-66-24-5C-CE', 'key' => 'lDX6BsMg5U6u'],
        ['mac' => '777 94-DB-C9-0E-0A-FC', 'key' => '123456789012'],
        ['mac' => '', 'key' => 'hellohello10'],
        ['mac' => '', 'key' => 'hellohello11'],
        ['mac' => '', 'key' => 'hellohello12'],

        ['mac' => '10-B4-B1-0C-AA-4C', 'key' => 'ZHORB39mUQwqWDRL9vGl'],
        ['mac' => '1E-09-8C-86-E1-09', 'key' => 'KTRZ02mTDiJ6oofu1Ao0'],
        ['mac' => 'B9-49-A9-55-4D-9D', 'key' => '5stZz3ntLaXrsvJ6JKiW'],
        ['mac' => 'CE-02-01-8F-E3-7D', 'key' => 'lge7cwcozZ9F7zzCgkSU'],
        ['mac' => '60-F1-97-45-CB-FA', 'key' => '4UFAAnqwCEUtjDdWe7O2'],
        ['mac' => 'A3-3C-F9-CF-66-0F', 'key' => 'c1BQ5cXazu2yiuFuG48M'],
        ['mac' => 'D1-70-2D-EE-7E-DC', 'key' => 'q9GsEGyZJs2FQ51RhUGa'],
        ['mac' => 'E3-AD-E0-81-23-89', 'key' => 'kzSlbyqQ6WtpDHYUJ5PT'],
    ];

    $users['length'] = $length;
    $users['offset'] = $offset;
    $users['total']  = count($users['users']);

    $users['users'] = array_slice($users['users'], $offset, $length);

    return new JsonResponse(
        $users,
        200,
        ['Access-Control-Allow-Origin' => '*']
    );
});

$app->post('/access-log', function(Application $app, Request $request) {
    $access = json_decode($request->getContent(), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return new JsonResponse(['errors' => [
            'http_body' => 'invalid JSON',
        ]], 400);
    }

    $accessLogConstraint = new Assert\Collection([
        'queue' => new Assert\All(
            new Assert\Collection([
                'mac' => [new Assert\NotBlank(), new Assert\Type('string')],
                'key' => [new Assert\NotBlank(), new Assert\Type('string')],
                'timestamp' => [new Assert\NotBlank(), new Assert\Type('integer')],
            ])
        ),
    ]);

    $errors = $app['validator']->validate($access, $accessLogConstraint);

    if (count($errors) > 0) {
        $response = [];
        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $response['errors'] = [$error->getPropertyPath() => $error->getMessage()];
        }

        return new JsonResponse($response, 400);
    }

    return new Response(null, 201, ['Access-Control-Allow-Origin' => '*']);
});


$app->get('/status', function(Application $app, Request $request) {
    $now = new \DateTime();
    $dbUpdate = new \DateTime('-1 day');
    $dbUpdate->setTime(12, 34);
    $open = new \DateTime('-1 day');
    $open->setTime(18, 30);
    $lock = true;

    return new JsonResponse(
        [
            'timestamp' => $now->format('U'),
            'db_update' => $dbUpdate->format('U'),
            'open'      => $open->format('U'),
            'lock'      => $lock,
        ]
    );
});
$app->run();

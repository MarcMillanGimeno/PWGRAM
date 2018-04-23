<?php
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../../src/View/termplates',
    'debug' => true,
));

$app->register(new Silex\Provider\AssetServiceProvider(),array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
        'css' => array('base_path' => '/assets/css'),
        'js' => array('base_path' => '/assets/js'),
        'images' => array('base_urls' => array('http://silexapp.dev/assets/images')),
    ),
));

$app->register(new \SilexApp\Providers\HelloServiceProvider(),array(
    'hello.default_name' => 'MARC',
));

//Otroooo

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(

    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'pwgramdb',
        'user' => 'root',
        'password' => ''
    ),
));

$app->register(new FormServiceProvider());
$app->register(new \Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new SilexApp\Providers\RegisterUserProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new \SilexApp\Providers\HomeProvider(), array(
    )
);
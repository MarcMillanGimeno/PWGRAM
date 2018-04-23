<?php
/**
 * Created by PhpStorm.
 * User: Usuari
 * Date: 19/04/2017
 * Time: 16:16
 */

namespace SilexApp\Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;

class HelloServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app){
        $app['hello'] = $app->protect(function ($name) use ($app){
            $default = $app['hello.default_name'] ? $app['hello.default_name']:'';

            $name = $name ?: $default;

            return $app['twig']->render('hello.twig', array(

                'user' => $name,
                'app' => [
                    'name'=>$app['app.name']
                ]
            ));
        });
    }
}
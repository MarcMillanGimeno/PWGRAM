<?php
/**
 * Created by PhpStorm.
 * User: Usuari
 * Date: 19/04/2017
 * Time: 13:37
 */

namespace SilexApp\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelloController{

    public function indexAction(Application $app, Request $request, $name)
    {
        $content = $app['twig']->render('hello.twig', ['user' => $name]);
        $response = new Response();
        $response->setStatusCode($response::HTTP_OK);
        $response->headers->set('Content-type', 'text/html');
        $response->setContent($content);
        return $response;
    }

    public function addAction(Application $app,$num1,$num2){
        return "the result is: " . $app['calc']->add($num1,$num2);
    }
}
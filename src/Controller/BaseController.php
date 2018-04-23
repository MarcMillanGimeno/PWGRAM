<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 07/05/2017
 * Time: 10:37
 */

namespace SilexApp\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PassordType;


class BaseController{

    public function GoHome(Application $app, Request $request){
        return $app['home']->GoHomeAction($app, $request);
    }

    public function loggedUser(Application $app, Request $request){
        return $app['home']->LoginRequest($app, $request);
    }

    public function logOutUser(Application $app ){
        return $app['home']->LoginOut($app);
    }
    public function adminAction(Application $app){
    }
}
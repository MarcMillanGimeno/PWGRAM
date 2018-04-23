<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 12/05/2017
 * Time: 9:26
 */

namespace SilexApp\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController
{
    public function EditProfileAction(Application $app, Request $request)
    {

        return $app['profile']->EditProfileAction($app, $request);
    }

    public function ConfirmProfileAction(Application $app, Request $request)
    {
        return $app['profile']->ConfirmProfileAction($app, $request);

    }

    public function ViewProfileAction(Application $app, Request $request, $name)
    {

        return $app['profile']->ViewProfileAction($app, $request, $name);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 12/05/2017
 * Time: 9:29
 */

namespace SilexApp\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController
{
    public function EditImageAction(Application $app, Request $request, $id)
    {

        return $app['image']->EditImageAction($app, $request, $id);
    }

    public function InsertImageAction(Application $app, Request $request)
    {

        return $app['image']->InsertImageAction($app, $request);
    }

    public function NewImageAction(Application $app, Request $request)
    {

        return $app['image']->NewImageAction($app, $request);
    }

    public function UpdateImageAction(Application $app, Request $request, $id)
    {

        return $app['image']->UpdateImageAction($app, $request, $id);
    }

    public function RemoveImageAction(Application $app, Request $request, $id){

        return $app['image']->RemoveImageAction($app, $request, $id);
    }

    public function ViewImageAction(Application $app, Request $request, $id)
    {
        return $app['image']->ViewImageAction($app, $request, $id);
    }
}
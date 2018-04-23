<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 13/05/2017
 * Time: 8:56
 */

namespace SilexApp\Model\Services;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints as Assert;

class HomeService{

    public function GoHomeAction(Application $app,Request $request){
        $logged = false;
        $username = "";
        $image_path = null;
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');

            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
        }
        $imagesEmpty = 0;
        $imagesMoreViews = $app['db']->fetchAll(
            'SELECT * FROM image WHERE private = 0 ORDER BY visits DESC'
        );
        $imagesRecents = $app['db']->fetchAll(
            'SELECT * FROM image WHERE private = 0 ORDER BY created_at DESC '
        );
        $users = $app['db']->fetchAll(
            'SELECT username, id FROM user'
        );
        if($imagesMoreViews != null){
            $imagesEmpty = 1;
        }
        $mess = "";


        $coments = $app['db']->fetchall('SELECT * FROM comments');

        $content = $app['twig']->render('home.twig',['logged' => $logged ,
                'username' => $username,
                'message' => $mess,
                'image_rute' => $image_path,
                'imagesMoreViews' => $imagesMoreViews,
                'imagesRecents' => $imagesRecents,
                'hihaimages' => $imagesEmpty,
                'users' => $users,
                'hihacomment' => true , 'comentari1' => $coments,'nComents'=>3]
        );
        $response = new Response();
        $response->setStatusCode($response::HTTP_OK);
        $response->headers->set('Content-type', 'text/html');
        $response->setContent($content);
        return $response;
    }
    public function LoginRequest(Application $app, Request $request){
        $myname = $request->request->get('name');
        $mypass = $request->request->get('passwordUser');
        $posicion = strrpos($myname, "@");
        $stateActivation = 1;
        if ($posicion === false) {
            $result = $app['db']->fetchColumn(
                'SELECT username FROM user WHERE username = ? AND password = ?',
                array($myname, $mypass)
            );
        }else{
            $isEmail = true;
            $result = $app['db']->fetchColumn(
                'SELECT username FROM user WHERE email = ? AND password = ?',
                array($myname, $mypass)
            );
        }
        if($result != null){

            if (!$isEmail) {
                $stateActivation = $app['db']->fetchColumn(
                    'SELECT active FROM user WHERE username = ?',
                    array($myname)
                );
            }else{
                $stateActivation = $app['db']->fetchColumn(
                    'SELECT active FROM user WHERE email = ?',
                    array($myname)
                );
            }
        }
        if((!$result) || ($stateActivation == 0)){
            if( $stateActivation == 0){
                $mess = "Tu cuenta no está activada.";
            }else {
                $mess = "User or pass incorrect";
            }

            return $app['twig']->render('home.twig',['logged' => false,
                    'username' => null,
                    'message' => $mess,
                    'image_rute' => null,
                    'hihacomment' => false , 'comentari1' => null,'nComents'=>0]
            );
        }else{
            $app['session']->set('nameUser', $result);
            $url = '/';
            return new RedirectResponse($url);
        }
    }
    public function LoginOut(Application $app ){
        if($app['session']->has('nameUser')){
            $app['session']->remove('nameUser');
        }
        $url = '/';
        return new RedirectResponse($url);
    }
}
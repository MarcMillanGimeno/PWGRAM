<?php
/**
 * Created by PhpStorm.
 * User: Usuari
 * Date: 15/05/2017
 * Time: 8:44
 */

namespace SilexApp\Model\Services;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints as Assert;

class CommentsService{

    public function image(){

    }

    public function createCommentSeccion(Application $app, Request $request){
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

            $commentsUser = $app['db']->fetchAll(
                'SELECT * FROM comments WHERE username = ?',
                array($username)
            );
            $mess = "";
            if(!commentsUser){
                $mess = "You have not commented yet";
                $thereAreComments = false;
            }else{
                $thereAreComments = true;
                $images = $app['db']->fetchAll(
                    'SELECT * FROM image'
                );
            }
            $content = $app['twig']->render('commentsSite.twig',['logged' => $logged ,
                    'username' => $username,
                    'message' => $mess,
                    'image_rute' => $image_path,
                    'comments' => $commentsUser,
                    'hihacomments' => $thereAreComments,
                    'allImages' => $images]
            );
            $response = new Response();
            $response->setStatusCode($response::HTTP_OK);
            $response->headers->set('Content-type', 'text/html');
            $response->setContent($content);
            return $response;
        }else {
            return $app['twig']->render('error.twig', [
                'message' => "User not logged",
                'logged' => $logged , 'username' => $username
            ]);
        }
    }
    public function deleteComment(Application $app, Request $request, $name)
    {
        $logged = false;
        $username = "";
        $image_path = null;
        if ($app['session']->has('nameUser')) {
            $logged = true;
            $username = $app['session']->get('nameUser');
            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
        }
        $mess = "";
        $commentToDelete = $app['db']->fetchall('SELECT * FROM comments WHERE id = ? AND username = ?',
                array($name, $username)
            );
        if(!$commentToDelete){
            return $app['twig']->render('error.twig', [
                'message' => "Comment not found or you haven't permits to delete this comment",
                'logged' => $logged, 'username' => $username
            ]);
        }else{
            $app['db']->executeUpdate(
                'DELETE FROM comments WHERE id = ? AND username = ?',
                array($name, $username)
            );
        }
        $url = '/Comments';
        return new RedirectResponse($url);
    }
    public function editComment(Application $app, Request $request, $name){
    }
}
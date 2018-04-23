<?php
/**
 * Created by PhpStorm.
 * User: Usuari
 * Date: 15/05/2017
 * Time: 8:43
 */

namespace SilexApp\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\PassordType;

class CommentsController{

    public function addComment(Application $app){

        $comment = "SELECT * FROM comments WHERE id_image = ?";
        $image = $app['db']->fetchall($comment, array(1));
        if(!$image){
            return $app['twig']->render('comments.twig' ,['hihacomment' => false , 'comentari1' => null,'nComents'=>3,'nComents2'=>3]);
        }else{
            return $app['twig']->render('comments.twig' ,['hihacomment' => true , 'comentari1' => $image,'nComents'=>3,'nComents2'=>3]);
        }

    }

    public  function newCommentHome(Application $app, Request $request){
        $response = new Response();
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');
        }


        $user = "SELECT id FROM user WHERE username= ?";
        $id_user = $app['db']->fetchcolumn($user, array($username));


        if($user){
            $app['db']->insert('comments', [
                    'text' => $request->get('comment'),
                    'id_usuari' => $id_user,
                    'id_image' => $request->get("imageGET"),
                    'username'=>$username
                ]
            );

            echo "SI VA EL ADD";

            $sql = "UPDATE image SET comments = comments + 1 WHERE id =? ";
            $app['db']->executeUpdate($sql, array($request->get("imageGET")));

        }else{

            echo "NO VA EL ADD";

        }

        $image_path = $app['db']->fetchColumn(
            'SELECT img_path FROM user WHERE username = ?',
            array($username)
        );

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
        echo "AQUI  VA";

        $response->setStatusCode($response::HTTP_OK);
    echo "AQUI NO VA";
        $response->headers->set('Content-type', 'text/html');
        $response->setContent($content);

        return $response;
    }
    public function moreComments(Application $app, $nComments){

        $nComments = $nComments +3;
        $comment = "SELECT * FROM comments WHERE id_image = ?";
        $image = $app['db']->fetchall($comment, array(1));
        if(!$image){
            return $app['twig']->render('comments.twig' ,['hihacomment' => false , 'comentari1' => null,'nComents'=>3,'nComents2'=>$nComments]);
        }else{
            return $app['twig']->render('comments.twig' ,['hihacomment' => true , 'comentari1' => $image,'nComents'=>$nComments,'nComents2'=>$nComments]);
        }

    }
    public function CreateCommentsSite(Application $app, Request $request){
        return $app['comments']->createCommentSeccion($app, $request);
    }
    public function DeleteComment(Application $app, Request $request, $name){
        return $app['comments']->deleteComment($app, $request, $name);
    }
    public function EditComment(Application $app, Request $request, $name){
        return $app['comments']->editComment($app, $request);
    }
}
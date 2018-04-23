<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 12/05/2017
 * Time: 9:39
 */

namespace SilexApp\Model\Services;

use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageService
{
    public function InsertImageAction(Application $app, Request $request)
    {
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');

            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
        }

        if(!$logged){
            return $app['twig']->render('error.twig', [
                'message' => "User not logged",
                'logged' => $logged , 'username' => $username
            ]);
        }
        return $app['twig']->render('image_edit.twig',['logged' => $logged , 'username' => $username,
            'message' => null, 'insert' => true, 'img_id' => null,
            'title' => null, 'priv' => null, 'image_rute' => $image_path,
            'msg' => null]);
    }

    public function NewImageAction(Application $app, Request $request)
    {

        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');

            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
        }
        if(!$logged){
            return $app['twig']->render('error.twig', [
                'message' => "User not logged",
                'logged' => $logged , 'username' => $username
            ]);
        }
        $title = $request->get('title');

        if(strlen($title) <= 1 || strlen($title) > 20){
            return $app['twig']->render('image_edit.twig',['logged' => $logged , 'username' => $username,
                'message' => null, 'insert' => true, 'img_id' => null,
                'title' => null, 'priv' => null, 'image_rute' => $image_path,
                'msg' => "Title value Incorrect (Size)"]);
        }
        if(!is_null($request->get('check_private'))){
            $private = 1;
        }else{
            $private = 0;
        }
        $file_img = $request->files->get('file-input');

        if($file_img == ""){
            return $app['twig']->render('image_edit.twig',['logged' => $logged , 'username' => $username,
                'message' => null, 'insert' => true, 'img_id' => null,
                'title' => null, 'priv' => null, 'image_rute' => $image_path,
                'msg' => "Image value Incorrect"]);
        }
        $file = uniqid() . "." . $file_img->getClientOriginalExtension();
        $path = "assets/images/".$username;
        $file_img->move($path, $file);

        $result = $app['db']->fetchColumn(
            'SELECT id FROM user WHERE username = ?',
            array($username)
        );
        $app['db']->insert('image', ['user_id' => $result,
                'title' => $title,
                'img_path' => $file,
                'visits' => 0,
                'private' => $private,
                'created_at' => date("Y-m-d H:i:s"),
            ]
        );
        return new RedirectResponse("/Profile/".$username);
    }

    public function EditImageAction(Application $app, Request $request, $id)
    {
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');
            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
        }

        if(!$logged){
            return $app['twig']->render('error.twig', [
                'message' => "User not logged",
                'logged' => $logged , 'username' => $username
            ]);
        }

        $img = $app['db']->fetchAssoc(
            'SELECT * FROM image WHERE id = ?',
            array($id)
        );

        if(!$img){
            return $app['twig']->render('error.twig', [
                'message' => "Image not found",
                'logged' => $logged , 'username' => $username
            ]);
        }else{
            return $app['twig']->render('image_edit.twig',['logged' => $logged , 'username' => $username, 'message' => null,
                'insert' => false, 'img_id' => $id, 'image'=>$img, 'image_rute' => $image_path, 'msg' => null]);
        }

    }

    public function UpdateImageAction(Application $app, Request $request, $id)
    {
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');

            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
        }
        $old_img = $app['db']->fetchColumn(
            'SELECT img_path FROM image WHERE id = ?',
            array($id)
        );
        if(!$logged){
            return $app['twig']->render('error.twig', [
                'message' => "User not logged",
                'logged' => $logged , 'username' => $username
            ]);
        }
        $title = $request->get('title');
        if(strlen($title) <= 1 || strlen($title) > 20){
            return $app['twig']->render('image_edit.twig',['logged' => $logged , 'username' => $username,
                'message' => null, 'insert' => true, 'img_id' => null,
                'title' => null, 'priv' => null, 'image_rute' => $image_path,
                'msg' => "Title value Incorrect (Size)"]);
        }
        if(!is_null($request->get('check_private'))){
            $private = 1;
        }else{
            $private = 0;
        }

        $file_img = $request->files->get('file-input');

        if(is_null($file_img)){
            $app['db']->executeUpdate(
                'UPDATE image SET title=?, private=? WHERE id = ?',
                array($title,$private,$id)
            );
        }else{

            $old_path = 'C:\xampp\htdocs\Projectes_Web\PWGRAM\web\assets\images/'.$username.'/'.$old_img;
            exec("rm $old_path");

            $file = uniqid() . "." . $file_img->getClientOriginalExtension();
            $path = "assets/images/".$username;
            $file_img->move($path, $file);

            $result = $app['db']->fetchColumn(
                'SELECT id FROM user WHERE username = ?',
                array($username)
            );

            $app['db']->executeUpdate(
                'UPDATE image SET title=?, private=?, img_path = ?  WHERE id = ? AND user_id=?',
                array($title,$private,$file,$id,$result)
            );
        }
        return new RedirectResponse("/Profile/".$username);
    }

    public function RemoveImageAction(Application $app, Request $request, $id){

        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');

            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
        }
        if(!$logged){
            return $app['twig']->render('error.twig', [
                'message' => "User not logged",
                'logged' => $logged , 'username' => $username
            ]);
        }
        $exists = $app['db']->fetchColumn(
            'SELECT title FROM image WHERE id = ?',
            array($id)
        );
        if(!$exists){
            return $app['twig']->render('error.twig', [
                'message' => "Image does not exists",
                'logged' => $logged , 'username' => $username
            ]);
        }
        $image_del = $app['db']->fetchColumn(
            'SELECT img_path FROM image WHERE id = ?',
            array($id)
        );
        $old_path = 'C:\xampp\htdocs\Projectes_Web\PWGRAM\web\assets\images/'.$username.'/'.$image_del;
        exec("rm $old_path");
        $app['db']->executeUpdate(
            'DELETE FROM image WHERE id = ?',
            array($id)
        );
        return new RedirectResponse("/Profile/".$username);

    }

    public function ViewImageAction(Application $app, Request $request, $id)
    {
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');

            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
        }

        $image = $app['db']->fetchAssoc(
            'SELECT * FROM image WHERE id = ?',
            array($id)
        );
        if(!$image){
            return $app['twig']->render('error.twig', [
                'message' => "Image does not exists",
                'logged' => $logged , 'username' => $username
            ]);
        }
        $visits = $image["visits"]+1;
        $app['db']->executeUpdate(
            'UPDATE image SET visits=? WHERE id = ?',
            array($visits,$id)
        );
        $user = $app['db']->fetchAssoc(
            'SELECT * FROM user WHERE id = ?',
            array($image["user_id"])
        );

        $date2 = date('Y-m-d H:i:s');

        $datetime1 = date_create($image["created_at"]);
        $datetime2 = date_create($date2);

        $interval = $datetime1->diff($datetime2);

        $diff = $interval->format("%a");

        return $app['twig']->render('image_view.twig', [
            'logged' => $logged , 'username' => $username, 'image_rute' => $image_path, 'image' => $image,
            'user' => $user, 'time' => $diff
        ]);
    }
}
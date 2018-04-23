<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 12/05/2017
 * Time: 9:40
 */

namespace SilexApp\Model\Services;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ProfileService
{
    public function EditProfileAction(Application $app, Request $request)
    {
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');
        }

        if(!$logged){
            return $app['twig']->render('error.twig', [
                'message' => "You are not logged yet",
                'logged' => $logged , 'username' => $username
            ]);
        }
        $image_path = $app['db']->fetchColumn(
            'SELECT img_path FROM user WHERE username = ?',
            array($username)
        );
        $user = $app['db']->fetchAssoc(
            'SELECT * FROM user WHERE username = ?',
            array($username)
        );

        return $app['twig']->render('profile_edit.twig',['logged' => $logged , 'username' => $username, 'message' => null,
            'image_rute' => $image_path, 'user' => $user, 'msg' => null]);
    }
    public function ConfirmProfileAction(Application $app, Request $request)
    {
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');
        }
        if(!$logged){
            return $app['twig']->render('error.twig', [
                'message' => "You are not logged yet",
                'logged' => $logged , 'username' => $username
            ]);
        }

        $image_path = $app['db']->fetchColumn(
            'SELECT img_path FROM user WHERE username = ?',
            array($username)
        );
        $user = $app['db']->fetchAssoc(
            'SELECT * FROM user WHERE username = ?',
            array($username)
        );
        $bdate = $request->get('bdate');
        $user_name = $request->get('user_name');
        $switch = $request->get('filled-in-box');

        $now = date('Y-m-d');

        if($bdate == "" || $bdate >= $now){

            return $app['twig']->render('profile_edit.twig',['logged' => $logged , 'username' => $username, 'message' => null,
                'image_rute' => $image_path, 'user' => $user, 'msg' => 'Wrong birthdate']);
        }

        if(strlen($user_name) == 0 || strlen($user_name) > 20 || !ctype_alnum($user_name)){
            return $app['twig']->render('profile_edit.twig',['logged' => $logged , 'username' => $username, 'message' => null,
                'image_rute' => $image_path, 'user' => $user, 'msg' => 'Wrong User name']);
        }
        $result = $app['db']->fetchColumn(
            'SELECT username FROM user WHERE username = ?',
            array($user_name)
        );
        if($result != false && $username != $user_name){
            return $app['twig']->render('profile_edit.twig',['logged' => $logged , 'username' => $username, 'message' => null,
                'image_rute' => $image_path, 'user' => $user, 'msg' => 'User Name already exists']);
        }
        $file_img = $request->files->get('file-input');

        if(is_null($file_img)){
            $file = $user["img_path"];

        }else{
            $old_path = 'C:\xampp\htdocs\Projectes_Web\PWGRAM\web\assets\images/'.$username.'/'.$image_path;
            exec("rm $old_path");

            $file = uniqid() . "." . $file_img->getClientOriginalExtension();

            $path = "assets/images/".$username;
            $file_img->move($path, $file);

        }
        $pwd = $user["password"];

        if($switch == "on"){

            $pwd = $request->get('password');
            $pwd_rpt = $request->get('password_repeat');

            if($pwd == "" || $pwd_rpt == ""){
                return $app['twig']->render('profile_edit.twig',['logged' => $logged , 'username' => $username, 'message' => null,
                    'image_rute' => $image_path, 'user' => $user, 'msg' => 'Password Value Incorrect (Empty)']);
            }
            if($pwd != $pwd_rpt){
                return $app['twig']->render('profile_edit.twig',['logged' => $logged , 'username' => $username, 'message' => null,
                    'image_rute' => $image_path, 'user' => $user, 'msg' => 'Password Value Incorrect']);
            }
            if(strlen($pwd) < 6 || strlen($pwd) > 12 || !ctype_alnum($pwd)){
                return $app['twig']->render('profile_edit.twig',['logged' => $logged , 'username' => $username, 'message' => null,
                    'image_rute' => $image_path, 'user' => $user, 'msg' => 'Password Value Incorrect']);
            }
        }

        $app['db']->executeUpdate(
            'UPDATE user SET username=?, birthdate=?, password=?, img_path = ? WHERE id = ?',
            array($user_name,$bdate,$pwd,$file, $user["id"])
        );

        $oldname = 'C:\xampp\htdocs\Projectes_Web\PWGRAM\web\assets\images/'.$username;

        $newname = 'C:\xampp\htdocs\Projectes_Web\PWGRAM\web\assets\images/'.$user_name;

        exec("mv $oldname $newname");
        $app['session']->set('nameUser', $user_name);

        return new RedirectResponse("/Profile/".$user_name);

    }
    public function ViewProfileAction(Application $app, Request $request, $name)
    {
        $own = false;
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');
        }
        $result = $app['db']->fetchColumn(
            'SELECT username FROM user WHERE username = ?',
            array($name)
        );
        if(!$result){
            return $app['twig']->render('error.twig', [
                'message' => "Username Incorrect",
                'logged' => $logged , 'username' => $username
            ]);
        }
        $image_path = $app['db']->fetchColumn(
            'SELECT img_path FROM user WHERE username = ?',
            array($username)
        );

        $image_profile = $app['db']->fetchColumn(
            'SELECT img_path FROM user WHERE username = ?',
            array($name)
        );

        if($name == $username){
            $own = true;
        }else{
            $own = false;
        }
        $id = $app['db']->fetchColumn(
            'SELECT id FROM user WHERE username = ?',
            array($name)
        );
        $array_img = $app['db']->fetchall(
            'SELECT img_path FROM image WHERE user_id = ? ORDER BY created_at ASC ',
            array($id)
        );
        $array_titles = $app['db']->fetchall(
            'SELECT title FROM image WHERE user_id = ?',
            array($id)
        );
        $array_ids = $app['db']->fetchall(
            'SELECT id FROM image WHERE user_id = ?',
            array($id)
        );
        $num = count($array_img);

        return $app['twig']->render('profile.twig', [
            'own' => $own,
            'logged' => $logged , 'username' => $username, 'profile_name' => $name, 'message' => null,
            'image_rute' => $image_path, 'images' => $array_img, 'num' => $num, 'titles' => $array_titles,
            'ids' => $array_ids, 'image_profile' => $image_profile]);
    }
}
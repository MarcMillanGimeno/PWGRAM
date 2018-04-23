<?php

/**
 * Created by PhpStorm.
 * User: Usuari
 * Date: 19/04/2017
 * Time: 10:43
 */
use Silex\Application;
$app = new Application();
$app['app.name']='SilexApp';
$app['calc'] = function (){
    return new \SilexApp\Model\Services\Calculator();
};
$app['image'] = function (){
    return new \SilexApp\Model\Services\ImageService();
};
$app['register'] = function (){
    return new \SilexApp\Model\Services\Register();
};
$app['profile'] = function (){
    return new \SilexApp\Model\Services\ProfileService();
};
$app['home'] = function (){
  return new \SilexApp\Model\Services\HomeService();
};

$app['comments'] = function (){
    return new \SilexApp\Model\Services\CommentsService();
};
return $app;
<?php
/*
use Silex\Application;
use SilexApp\Controller;

$app->get('/hello/{name}',function($name) use ($app){
   $x =  new Controller\HelloController();

    return $x->indexAction($app,$name);
});
*/

$app->get('/hello/{name}','SilexApp\\Controller\\HelloController::indexAction');

$app->get('/register','SilexApp\\Controller\\RegisterController::registerAction');
$app->match('/activeUser','SilexApp\\Controller\\RegisterController::activeAction');
$app->match('/registerUser','SilexApp\\Controller\\RegisterController::registerUserAction');
$app->match('/imagesView','SilexApp\\Controller\\CommentsController::addComment');
$app->match('/newComment/home/{imageGET}','SilexApp\\Controller\\CommentsController::newCommentHome');
$app->get('/imagesView/{nComments}','SilexApp\\Controller\\CommentsController::moreComments');
$app->get('/add/{num1}/{num2}','SilexApp\\Controller\\HelloController::addAction');
$app->get('/users/get/{id}','SilexApp\Controller\UserController::getAction');
$app->match('/users/add','SilexApp\Controller\UserController::postAction');
$app->get('/image/{name}','SilexApp\\Controller\\ImageController::indexAction');

// SESSION
$before = function($request, $app){
    if(!$app['session']->has('name')){
        $response = new \Symfony\Component\HttpFoundation\Response();
        $content = $app['twig']->render('error.twig', [
           'message' => 'You must be logged'
        ]);
        $response->setContent($content);
        $response->setStatusCode(\Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN);
        return $response;
    }
};

$app->get('/admin','SilexApp\Controller\BaseController::adminAction')->before($before);

$app->get('/home','SilexApp\Controller\BaseController::GoHome');
$app->match('/','SilexApp\Controller\BaseController::GoHome');

$app->match('/logIn', 'SilexApp\Controller\BaseController::loggedUser');
$app->match('/logOut', 'SilexApp\Controller\BaseController::logOutUser');

$app->match('/signIn', 'SilexApp\Controller\RegisterController::registerUserAction');

$app->match('/Profile/{name}', 'SilexApp\Controller\ProfileController::ViewProfileAction');
$app->match('/ProfileEdit','SilexApp\\Controller\\ProfileController::EditProfileAction');
$app->match('/ConfirmProfile','SilexApp\\Controller\\ProfileController::ConfirmProfileAction');

$app->match('/ImageEdit/{id}', 'SilexApp\Controller\ImageController::EditImageAction');
$app->match('/ImageInsert', 'SilexApp\Controller\ImageController::InsertImageAction');
$app->match('/NewImage', 'SilexApp\Controller\ImageController::NewImageAction');
$app->match('/UpdateImage/{id}', 'SilexApp\Controller\ImageController::UpdateImageAction');
$app->match('/RemoveImage/{id}', 'SilexApp\Controller\ImageController::RemoveImageAction');
$app->match('/ViewImage/{id}', 'SilexApp\Controller\ImageController::ViewImageAction');

$app->match('/Comments', 'SilexApp\Controller\CommentsController::CreateCommentsSite');
$app->match('/DeleteComment/{name}', 'SilexApp\Controller\CommentsController::DeleteComment');
$app->match('/EditComment/{id}', 'SilexApp\Controller\CommentsController::EditComment');
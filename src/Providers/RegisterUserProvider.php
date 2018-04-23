<?php
/**
 * Created by PhpStorm.
 * User: Usuari
 * Date: 26/04/2017
 * Time: 17:18
 */

namespace SilexApp\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class RegisterUserProvider implements ServiceProviderInterface
{
    public function register(Container $app){

        $app['prova'] = $app->protect(function () use ($app){
            $logged = false;
            $username = "";
            if ($app['session']->has('nameUser')){
                $logged = true;
                $username = $app['session']->get('nameUser');
            }
            $mess = "";

            return $app['twig']->render('register.twig', ['logged' => $logged , 'username' => $username, 'message' => $mess]);
        });
    }

    public function  registerUser(Application $app)
    {

        $app['registerUser'] = $app->protect(function ($request)use($app){

            $response = new Response();
            $data = array(
                'name' => 'Your name',
                'email' => 'Your email',
                'password' =>'Your password',
                'image'=>'Your image',
                'birthday'=>'Your birthday',
            );

            $form = $app['form.factory']->createBuilder(FormType::class, $data)
                ->add('name', TextType::class, array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 20)))
                ))
                ->add('email', TextType::class, array(
                    'constraints' => new Assert\Email()
                ))
                ->add('password', TextType::class,array(
                    'constrains' => array(new Assert\NotBlank(),new Assert\Length(array('min' =>6, 'max'=>12)))
                ))
                ->add('image', TextType::class,array(
                    'constrains' => array(new Assert\ImageValidator(), new Assert\NotBlank())
                ))
                ->add('birthday', TextType::class,array(
                    'constrains' => array(new Assert\NotBlank(),new Assert\DateValidator())
                ))
                ->add('submit', SubmitType::class, [
                    'label' => 'Add user',
                ])
                ->getForm();

            /** @var Form $form */
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var @var Form $data */
                // Validate
                $data = $form->getData();
                try {
                    $app['db']->insert('user', [
                            'username' => $data['name'],
                            'email' => $data['email'],
                            'birthdate' => $data['birthday'],
                            'password' => $data['password'],
                            'img_path' => $data['image']
                        ]
                    );
                    $lastInsertedId = $app['db']->fetchAssoc('SELECT id FROM user ORDER BY id DESC LIMIT 1');
                    $id = $lastInsertedId['id'];
                    $url = '/register' . $id;
                    return new RedirectResponse($url);
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                    $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                    $content = $app['twig']->render('register.twig', [
                        'form' => $form->createView(),
                        'errors' => [
                            'unexpected' => 'An error has occurred, please try it again later'
                        ]
                    ]);

                    $response->setContent($content);
                    return $response;
                }
            }

            $response->setStatusCode(Response::HTTP_OK);
            $content = $app['twig']->render('register.twig', array('form' =>  $form->createView()));
            //$content = $app['twig']->render('user.add.twig');
            $response->setContent($content);

            return $response;
         });
    }
}
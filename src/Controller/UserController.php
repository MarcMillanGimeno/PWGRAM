<?php
/**
 * Created by PhpStorm.
 * User: Usuari
 * Date: 19/04/2017
 * Time: 18:56
 */

namespace SilexApp\Controller;
use Exception;
use Silex\Application;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserController{
    public function getAction(Application $app, $id)
    {
        $sql = "SELECT * FROM user WHERE id = ?";
        $user = $app['db']->fetchAssoc($sql, array((int)$id));
        $response = new Response();
        if (!$user) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $content = $app['twig']->render('error.twig', [
                    'message' => 'User not found'
                ]
            );
        } else {
            $response->setStatusCode(Response::HTTP_OK);
            $content = $app['twig']->render('user.twig', [
                    'user' => $user
                ]
            );
        }
        $response->setContent($content);
        return $response;
    }
    public function postAction(Application $app, Request $request){

        $response = new Response();
        $data = array(
            'name' => 'Your name',
            'email' => 'Your email',
        );

        $form = $app['form.factory']->createBuilder(FormType::class, $data)
            ->add('name', TextType::class, array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->add('email', TextType::class, array(
                'constraints' => new Assert\Email()
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
                        'email' => $data['email']
                    ]
                );
                $lastInsertedId = $app['db']->fetchAssoc('SELECT id FROM user ORDER BY id DESC LIMIT 1');
                $id = $lastInsertedId['id'];
                $url = '/users/get/' . $id;
                return new RedirectResponse($url);
            } catch (Exception $e) {
                var_dump($e->getMessage());
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                $content = $app['twig']->render('user.add.twig', [
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
        $content = $app['twig']->render('user.add.twig', array('form' =>  $form->createView()));
        //$content = $app['twig']->render('user.add.twig');
        $response->setContent($content);

        return $response;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Usuari
 * Date: 11/05/2017
 * Time: 8:42
 */

namespace SilexApp\Model\Services;


use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class Register{

    public function register($app){
        $logged = false;
        $username = "";
        if ($app['session']->has('name')){
            $logged = true;
            $username = $app['session']->get('nameUser');
        }
        $mess = "";

        return $app['twig']->render('register.twig', ['logged' => $logged , 'username' => $username, 'message' => $mess]);
    }

    public function activeUser($app){

       $name =  $app['session']->get('nameUser');
       echo "NOMBRE ".$name;


        $response = new Response();
        $sql = "SELECT * FROM user WHERE username = ?";
        $user = $app['db']->fetchAssoc($sql, array($name));

        if ($user) {
            $active = 1;
            $sql = "UPDATE user SET active=$active WHERE username ='".$name."' ";
            $users = $app['db']->executeUpdate($sql, array($active, $name));
            if($users){
                $mypass = "SELECT password FROM user WHERE username = ?";
                $result = $app['db']->fetchColumn(
                    'SELECT username FROM user WHERE username = ? AND password = ?',
                    array($name, $mypass)
                );

                $image_path = $app['db']->fetchColumn(
                    'SELECT img_path FROM user WHERE username = ?',
                    array($name)
                );
                if(!$result){
                    $logged = false;


                    if ($app['session']->has('nameUser')){
                        $logged = true;
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
                            'username' => $name,
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

            }
        }else{

            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $logged = false;
            $username = "";
            if ($app['session']->has('nameUser')) {
                $logged = true;
                $username = $app['session']->get('nameUser');
            }

            $image_path = $app['db']->fetchColumn(
                'SELECT img_path FROM user WHERE username = ?',
                array($username)
            );
            $content = $app['twig']->render('error.twig', [
                'message' => "AVTIVACIÓN DE USURARIO INCORRECTA",
                'logged' => $logged , 'username' => $username,'image_rute' => $image_path
            ]);

            $response->setContent($content);
            return $response;
        }
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $logged = false;
        $username = "";

        $image_path = $app['db']->fetchColumn(
            'SELECT img_path FROM user WHERE username = ?',
            array($username)
        );
        if ($app['session']->has('nameUser')) {
            $logged = true;
            $username = $app['session']->get('nameUser');
        }
        $content = $app['twig']->render('error.twig', [
            'message' => "AVTIVACIÓN DE USURARIO INCORRECTA 2",
            'logged' => $logged , 'username' => $username,
            'image_rute' => $image_path
        ]);

        $response->setContent($content);
        return $response;
        setcookie("cookie_name",'',time()-100);
    }
    private function sendMail($username,$destination){

        $mail = new \PHPMailer();
        $mail-> IsSMTP();
        //$mail->SMTPDebug = 2;
//permite modo debug para ver mensajes de las cosas que van ocurriendo
//Debo de hacer autenticación SMTP
        $mail->SMTPAuth=true;
        $mail->SMTPSecure = "ssl";
//indico el servidor de Gmail para SMTP
        $mail->Host="smtp.gmail.com";
//indico el puerto que usa Gmail
        $mail->Port=465;

//indico un usuario / clave de un usuario de gmail
        $mail->Username ="pwgram2@gmail.com";
        $mail->Password="lossosis";
        $mail->SetFrom('pwgram2@gmail.com','LOS SOSIS');
        $mail->addReplyTo('pwgram2@gmail.com','LOS SOSIS');
        $mail->Subject="ACTIVAR USUARI PWGRAM";
        $mail->msgHTML("enllaç per activar usuari: ". $username);
//indico destinatario
       // $address = "albertbosch95@gmail.com";
        $mail->addAddress($destination,"ALBERT");
        if(!$mail->send()) {
            echo "Error al enviar: " . $mail->ErrorInfo;
        }else {
            echo "Mensaje enviado!";
        }
    }
    private function upUserToDataBase($data,$file_name,$form,$file,$app,$response){

        echo "HA ENTRAT";

        echo "POR AQUI";
        $path = "assets/images/albert";
        $file_name->move($path, $file);
        //session_get_cookie_params()
        setcookie("cookie_name", $data['name']);
        $app['db']->insert('user', [
                'username' => $data['name'],
                'email' => $data['email'],
                'birthdate' => $data['birthday']->format('Y-m-d'),
                'password' => $data['password'],
                'img_path' => $file,
            ]
        );

        $this->sendMail($data['name'],$data['email']);

        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');
        }
        $content = $app['twig']->render('register.twig', [
            'form' => $form->createView(),
            'errorImage' => null,
            'okRegister' => true,
            'message' => null,
            'logged' => $logged , 'username' => $username
        ]);

        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($content);
        return $response;
    }
    public function AddUser(Application $app, Request $request){

        $response = new Response();
        $data = array(
            'name' => 'Albert',
            'email' => 'test@test.com',
            'password' =>'Your password',
            'birthday'=> new \DateTime(),
        );
        $form = $app['form.factory']->createBuilder(FormType::class, $data)

            ->add('name', TextType::class, array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('max' => 20))),

            ))
            ->add('email', TextType::class, array(
                'constraints' => new Assert\Email()
            ))
            ->add('password', RepeatedType::class,array(
                'constraints' => array(new Assert\NotBlank(),new Assert\Length(array('min' =>6, 'max'=>12))),
                'type' => PasswordType::class,
                'invalid_message' => 'Les contrasenyes no coincideixen',
                'first_options' =>array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))

              ->add('birthday', DateType::class, array(
                  'label' => 'DATE',
                  'widget' => 'single_text',
                  // this is actually the default format for single_text
                  'format' => 'yyyy-MM-dd',
              ))



            ->add('submit', SubmitType::class, [
                'label' => 'Add user',
            ])
            ->getForm();

        /** @var Form $form */
        $form->handleRequest($request);


        $file_name =  $request->files->get('img_path');
        if ($form->isValid()){
            if (is_null($file_name)) {
                $file_name = 'assets/images/profile.jpg';
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);

            }else{

                $file = uniqid() . "." . $file_name->getClientOriginalExtension();
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);

            }

                /** @var @var Form $data */
                // Validate
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);


            $data = $form->getData();

                try {

                    $sql = "SELECT * FROM user WHERE username = ?";
                    $user = $app['db']->fetchAssoc($sql, array($data['name']));

                    if (!$user) {

                       return $this->upUserToDataBase($data,$file_name,$form,$file,$app,$response);

                    } else {
                        $response->setStatusCode(Response::HTTP_OK);
                        $logged = false;
                        $username = "";
                        if ($app['session']->has('nameUser')){
                            $logged = true;
                            $username = $app['session']->get('nameUser');
                        }
                        $content = $app['twig']->render('register.twig', [
                            'logged' => $logged , 'username' => $username,
                            'message' => 'usaria ja existent',

                            'form' => $form->createView(),
                            'errorImage' => "error",
                            'okRegister' => null,
                        ]);

                        // $content = $app['twig']->render('user.add.twig');
                        $response->setContent($content);

                        return $response;
                    }
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                    $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                    $logged = false;
                    $username = "";
                    if ($app['session']->has('nameUser')) {
                        $logged = true;
                        $username = $app['session']->get('nameUser');
                    }
                    $content = $app['twig']->render('error.twig', [
                        'message' => $e->getMessage(),
                        'logged' => $logged , 'username' => $username
                    ]);

                    $response->setContent($content);
                    return $response;
                }



    }

        $response->setStatusCode(Response::HTTP_OK);
        $logged = false;
        $username = "";
        if ($app['session']->has('nameUser')){
            $logged = true;
            $username = $app['session']->get('nameUser');
        }

        $content = $app['twig']->render('register.twig', [
            'logged' => $logged , 'username' => $username,
            'message' => null,

        'form' => $form->createView(),
            'errorImage' => "error",
            'okRegister' => null,
        ]);

        // $content = $app['twig']->render('user.add.twig');
        $response->setContent($content);

        return $response;

    }

}
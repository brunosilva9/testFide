<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;



class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        // Verificar si el usuario ya está autenticado
        if ($this->getUser()) {
            return $this->redirectToRoute('welcome');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        // ...
        return $this->render('auth/login.html.twig', [
            'error' => $error,
        ]);
    }
    /**
     * @Route("/signup", name="signup")
     */
    public function signupAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Maneja el formulario de registro aquí

        return $this->render('auth/signup.html.twig');
    }
    /**
     * @Route("/welcome", name="welcome")
     */
    public function welcomeAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Comprueba si el usuario está autenticado
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        // Obtén el nombre de usuario del token de autenticación
        $username = $this->getUser()->getUsername();

        // Renderiza la plantilla de bienvenida
        return $this->render('default/welcome.html.twig', [
            'username' => $username,
        ]);
    }
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Obtén los datos del formulario de registro
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        // Crea una nueva instancia de User
        $user = new User();
        $user->setUsername($username);
        $user->setIsAdmin(false);

        // Codifica la contraseña
        $encodedPassword = $passwordEncoder->encodePassword($user, $password);
        $user->setPassword($encodedPassword);

        // Guarda el nuevo usuario en la base de datos
        $entityManager = $this->getDoctrine()->getManager();
        try {
            $entityManager->persist($user);
            $entityManager->flush();

            // Resto del código después de la inserción exitosa

        } catch (\Exception $e) {
            // Manejo de la excepción
            echo 'Error al guardar el usuario: ' . $e->getMessage();
        }


        // Redirige a la página de inicio de sesión después del registro exitoso
        #return $this->redirectToRoute('login');
        return $this->redirectToRoute('login');
    }
    /**
     * @Route("/authenticate", name="authenticate", methods={"POST"})
     */
    public function authenticateAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, AuthenticationManagerInterface $authenticationManager)
    {
        // Obtén los datos del formulario de inicio de sesión
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        // Busca al usuario en la base de datos por su nombre de usuario
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->findOneBy(['userName' => $username]);
        #$pass = $entityManager->getRepository(User::class)->findOneBy(['password' => $password]);
        #var_dump($user);
        #die();
        #$user = $entityManager->getRepository(User::class);


        // Verifica si el usuario existe y la contraseña es correcta
        // Verifica si el usuario existe y la contraseña es correcta
        if (!$user || !$passwordEncoder->isPasswordValid($user, $password)) {
            // Redirige de nuevo al formulario de inicio de sesión con un mensaje de error
            return $this->redirectToRoute('login', ['error' => 'Invalid credentials']);
        }

        try {
            // Autentica al usuario y establece su identidad en la sesión
            $token = new UsernamePasswordToken($user, null, 'main', $user->getIsAdmin() ? ['ROLE_ADMIN'] : ['ROLE_USER']);
            $this->get('security.token_storage')->setToken($token);
            #var_dump($token);
            #die();
            return $this->redirectToRoute('welcome');


            // Resto del código después de la autenticación exitosa
        } catch (AuthenticationException $exception) {
            // Maneja cualquier excepción de autenticación que pueda ocurrir
            return $this->redirectToRoute('login', ['error' => 'Authentication error']);
        }


        // Inicia la sesión del usuario
        // ...

        // Redirige a la página de inicio después de iniciar sesión exitosamente
        # return $this->redirectToRoute('/homepage');
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        $this->get('security.token_storage')->setToken(null);
        $this->get('session')->invalidate();

        // Redirigir al formulario de inicio de sesión
        return new RedirectResponse($this->generateUrl('login'));
    }


}
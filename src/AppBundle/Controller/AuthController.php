<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use AppBundle\Entity\User;
use AppBundle\Entity\Pet;
use AppBundle\Entity\Owner;

class AuthController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(AuthenticationUtils $authenticationUtils)
    {
        // replace this example code with whatever you need
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
        $entityManager = $this->getDoctrine()->getManager();

        $pets = $entityManager->getRepository(Pet::class)->findAll();

        // Renderiza la plantilla de bienvenida con la lista de mascotas
        return $this->render('default/welcome.html.twig', [
            'username' => $username,
            'pets' => $pets,
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

        // Verifica si el usuario existe y la contraseña es correcta
        if (!$user || !$passwordEncoder->isPasswordValid($user, $password)) {
            // Redirige de nuevo al formulario de inicio de sesión con un mensaje de error
            return $this->redirectToRoute('login', ['error' => 'Invalid credentials']);
        }

        try {
            // Autentica al usuario y establece su identidad en la sesión
            $token = new UsernamePasswordToken($user, null, 'main', $user->getIsAdmin() ? ['ROLE_ADMIN'] : ['ROLE_USER']);
            $this->get('security.token_storage')->setToken($token);
            return $this->redirectToRoute('welcome');


            // Resto del código después de la autenticación exitosa
        } catch (AuthenticationException $exception) {
            // Maneja cualquier excepción de autenticación que pueda ocurrir
            return $this->redirectToRoute('login', ['error' => 'Authentication error']);
        }


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
    /**
     * @Route("/add-pet", name="add_pet", methods={"POST"})
     */
    public function addPet(Request $request)
    {
        // Obtén los datos del formulario de agregar mascota
        $chipNumber = $request->request->get('chipNumber');
        $type = $request->request->get('type');
        $name = $request->request->get('name');
        $lastName = $request->request->get('lastName');
        $sex = $request->request->get('sex');
        $color = $request->request->get('color');
        $dateOfBirth = new \DateTime($request->request->get('dateOfBirth'));
        $neutered = isset($_POST['neutered']) ? true : false;
        $humanRut = $request->request->get('humanRut');
        $observations = $request->request->get('observations');

        // Crea una nueva instancia de Pet
        $pet = new Pet();
        $pet->setChipNumber($chipNumber);
        $pet->setType($type);
        $pet->setName($name);
        $pet->setLastName($lastName);
        $pet->setSex($sex);
        $pet->setColor($color);
        $pet->setDateOfBirth($dateOfBirth);
        $pet->setNeutered($neutered);
        $pet->setHumanRut($humanRut);
        $pet->setObservations($observations);


        // Guarda la nueva mascota en la base de datos
        $entityManager = $this->getDoctrine()->getManager();
        try {
            $entityManager->persist($pet);
            $entityManager->flush();
            return $this->redirectToRoute('welcome');

            // Resto del código después de la inserción exitosa

        } catch (\Exception $e) {
            // Manejo de la excepción
            echo 'Error al guardar la mascota: ' . $e->getMessage();
            return $this->redirectToRoute('welcome');
        }
    }
    /**
     * @Route("/add-owner", name="add_owner", methods={"POST"})
     */
    public function addOwner(Request $request)
    {
        // Obtén los datos del formulario para agregar propietario
        $name = $request->request->get('name');
        $lastName = $request->request->get('lastName');
        $rut = $request->request->get('rut'); // todo  clean cache
        ## todo Validar Rut
        $owner = new Owner();
        $owner->setName($name);
        $owner->setLastName($lastName);
        $owner->setRut($rut);

        // Guarda el nuevo propietario en la base de datos
        $entityManager = $this->getDoctrine()->getManager();

        try {
            $entityManager->persist($owner);
            $entityManager->flush();
            return $this->redirectToRoute('welcome');

        } catch (\Exception $e) {
            // Manejo de la excepción
            echo 'Error al guardar el propietario: ' . $e->getMessage();
            return $this->redirectToRoute('welcome');
        }
    }

    /**
     * @Route("/deletePet", name="deletePet", methods={"POST"})
     */
    public function deletePet(Request $request, $id)
{
   
    $entityManager = $this->getDoctrine()->getManager();
    $pet = $entityManager->getRepository(Pet::class)->find($id);
    try {
        $entityManager->remove($pet);
        $entityManager->flush();
    } catch (\Exception $e) {
        // Manejo de la excepción
        echo 'Error al eliminar la mascota: ' . $e->getMessage();
        return $this->redirectToRoute('welcome');
    }

    return $this->redirectToRoute('welcome');
}

}
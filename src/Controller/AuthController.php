<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserInterface;
use Pimcore\Model\DataObject\User;
use Pimcore\Model\DataObject\Service;

#[Route('/auth', name: 'simple_auth_')]

class AuthController extends FrontendController
{
    #[Route('/login', name: 'login')]
    public function defaultAction(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        ?UserInterface $user = null
    ): Response {

        // Redirection si l'utilisateur est déjà connecté
        if ($user && $this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('dashboard');
        }

        // Récupération des erreurs de login et du dernier username
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    ////////////////////////////// register///////////////////////
    #[Route('/register', name: 'register')]

    public function registerAction(Request $request): Response
    {

        if ($request->isMethod('POST')) {
            $password = $request->get(key: 'password');
            $passwordconfirm = $request->get(key: 'passwordConfirm');
            $username = $request->get(key: 'email');

            if ($password == '' || $passwordconfirm == '') {
                $this->addFlash(type: 'error', message: 'password or confirm password cannot be empty');
                return $this->redirectToRoute('simple_auth_register');
            }

            if ($password != $passwordconfirm) {
                $this->addFlash(type: 'error', message: 'password dont match');
                return $this->redirectToRoute('simple_auth_register');
            }
            $user = User::getByUsername($username, limit: 1);
            if ($user instanceof User) {
                $this->addFlash(type: 'error', message: 'User already exists');
            }

            if (!$request->getSession()->getFlashBag()->has('error')) {
                $user = new User();

                $user->setParentId(parentId: 2);
                $user->setPublished(published: true);
                $user->setKey(Service::getValidKey($username, type: 'object'));
                $user->setUsername($username);
                $user->setPassword($password);
                $user->save();

                $this->addFlash(type: 'success', message: 'User registered successfully');
            }
            return $this->redirectToRoute('simple_auth_login');
        }
        return $this->render(view: 'auth/register.html.twig');
    }
    ////////////////////////////// logout///////////////////////

    #[Route('/logout', name: 'logout')]

    public function logoutAction()
    {

        return $this->redirectToRoute('simple_auth_login');
    }
}

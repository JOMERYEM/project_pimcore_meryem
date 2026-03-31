<?php

namespace App\Controller;

use Pimcore\Model\DataObject\User;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Pimcore\Model\DataObject\University;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends FrontendController
{
    #[Route('auth/dashboard', name: 'dashboard')]

    public function defaultAction(Request $request): Response
    {

        return $this->render("default/default.html.twig");
    }

    #[Route('/universities', name: 'universities_list')]
    public function list(): Response
    {
        $list = new University\Listing();
        
        return $this->render('university/list.html.twig', [
            'universities' => $list,
             
        ]);
    }

    #[Route('/api/universities', methods: ['GET'])]
    public function api(): JsonResponse
    {
        $list = new University\Listing();
        $data = [];

        foreach ($list as $uni) {
            $data[] = [
                'name' => $uni->getName(),
                'country' => $uni->getCountry(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/users', methods: ['GET'])]
    public function api2(): JsonResponse
    {
        $list = new User\Listing();       
        $data = [];

        foreach ($list as $uni) {
            $data[] = [
                'email' => $uni->getUsername(),
                'password' => $uni->getPassword(),
            ];
        }

        return $this->json($data);
    }
}


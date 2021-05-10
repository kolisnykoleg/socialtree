<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $host = $request->getHost();
        $username = $host;

        $user = $userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            return new Response('Not Found', Response::HTTP_NOT_FOUND);
        }

        $links = $user->getLinks();

        $settings = $user->getSettings()->getItem();

        return $this->render(
            'home/index.html.twig',
            [
                'username' => $username,
                'links' => $links->toArray(),
                'settings' => $settings,
            ]
        );
    }
}

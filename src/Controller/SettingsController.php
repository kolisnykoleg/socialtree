<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings/update", name="settings_update", methods={"POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        $settings = new Settings();
        $settings->setItem($request->toArray());

        $user = $this->getUser();
        $user->setSettings($settings);

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('');
    }
}

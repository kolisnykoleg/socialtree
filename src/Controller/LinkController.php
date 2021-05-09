<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\Settings;
use App\Entity\User;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/link")
 */
class LinkController extends AbstractController
{
    /**
     * @Route("/", name="link_index", methods={"GET"})
     */
    public function index(LinkRepository $linkRepository, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();

        $username = $user->getUsername();

        $links = $user->getLinks()->toArray();
        $linksJson = $serializer->serialize(
            $links,
            'json',
            [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']
            ]
        );

        $settings = $user->getSettings()->getItem();

        return $this->render(
            'link/index.html.twig',
            [
                'username' => $username,
                'links_json' => $linksJson,
                'settings' => $settings,
            ]
        );
    }

    /**
     * @Route("/new", name="link_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $link = new Link();
        $link->setPosition(-1);
        $link->setUser($this->getUser());
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('link_index');
        }

        return $this->render(
            'link/new.html.twig',
            [
                'link' => $link,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="link_delete", methods={"POST"})
     */
    public function delete(Request $request, Link $link): Response
    {
        if (!$this->checkUser($link)) {
            return new Response('Forbidden', 403);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($link);
        $entityManager->flush();

        return new Response('');
    }

    /**
     * @Route("/{id}/update", name="link_update", methods={"POST"})
     */
    public function update(Request $request, Link $link): Response
    {
        if (!$this->checkUser($link)) {
            return new Response('Forbidden', 403);
        }

        $data = $request->toArray();
        $link->setTitle($data['title']);
        $link->setUrl($data['url']);
        $link->setPosition($data['position']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($link);
        $entityManager->flush();

        return new Response('');
    }

    private function checkUser(Link $link): bool
    {
        return $link->getUser() == $this->getUser();
    }
}

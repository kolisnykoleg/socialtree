<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\User;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/link")
 */
class LinkController extends AbstractController
{
    /**
     * @Route("/", name="link_index", methods={"GET"})
     */
    public function index(LinkRepository $linkRepository): Response
    {
        $user = $this->getUser();

        $username = $user->getUsername();
        $links = $user->getLinks();

        return $this->render(
            'link/index.html.twig',
            [
                'username' => $username,
                'links' => $links,
            ]
        );
    }

    /**
     * @Route("/new", name="link_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $link = new Link();
        $link->setPosition(0);
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
     * @Route("/{id}/edit", name="link_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Link $link): Response
    {
        if (!$this->checkUser($link)) {
            return new Response('Forbidden', Response::HTTP_FORBIDDEN);
        }

        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('link_index');
        }

        return $this->render(
            'link/edit.html.twig',
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

        if ($this->isCsrfTokenValid('delete' . $link->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($link);
            $entityManager->flush();
        }

        return $this->redirectToRoute('link_index');
    }

    private function checkUser(Link $link): bool
    {
        return $link->getUser() == $this->getUser();
    }
}

<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/playlist")
 */
class PlaylistController extends AbstractController
{
    /**
     * @Route("/", name="playlist_index", methods={"GET"})
     */
    public function index(PlaylistRepository $playlistRepository): Response
    {
        return $this->render('playlist/index.html.twig', [
            'playlists' => $playlistRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="playlist_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('playlist_index');
        }

        return $this->render('playlist/new.html.twig', [
            'playlist' => $playlist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="playlist_show", methods={"GET"})
     */
    public function show(Playlist $playlist): Response
    {
        return $this->render('playlist/show.html.twig', [
            'playlist' => $playlist,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="playlist_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Playlist $playlist): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('playlist_index');
        }

        return $this->render('playlist/edit.html.twig', [
            'playlist' => $playlist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="playlist_delete", methods={"POST"})
     */
    public function delete(Request $request, Playlist $playlist): Response
    {
        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($playlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('playlist_index');
    }
}

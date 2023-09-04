<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/admin_artiste', name: 'app_admin_artiste')]
class AdminArtisteController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(ArtisteRepository $artisteRepository): Response
    {
        $artistes = $artisteRepository->findAll();
        return $this->render('admin/admin_artiste/index.html.twig', [
            'controller_name' => 'AdminArtisteController',
            'artistes' => $artistes
        ]);
    }

    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifier/{id}', name: '_modifier')]
    public function editerArtiste(Request $request,
                               EntityManagerInterface $entityManager,
                               ArtisteRepository $artisteRepository,
                               int $id = null): Response {

        if($id == null) {
            $artiste = new Artiste();
        } else {
            $artiste = $artisteRepository->find($id);
        }

        $form = $this->createForm(ArtisteType::class, $artiste);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($artiste);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                "L'artiste a été " . ($id == null ? 'ajouter' : 'modifier')
            );

            return $this->redirectToRoute('app_admin_artiste_lister');
        }

        return $this->render('admin/admin_artiste/editerArtiste.html.twig',
            [
                'controller_name' => 'AdminArtisteController',
                'form' => $form,
            ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimerArtiste(EntityManagerInterface $entityManager,
                                  ArtisteRepository $artisteRepository,
                                  int $id): Response {
        $artiste = $artisteRepository->find($id);
        $entityManager->remove($artiste);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            "L'artiste a été supprimé");
        return $this->redirectToRoute('app_admin_artiste_lister');
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/admin_festival', name: 'app_admin_festival')]
class AdminFestivalController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(FestivalRepository $festivalRepository): Response
    {
        $festivals = $festivalRepository->findAll();
        return $this->render('admin/admin_festival/admin_festival.html.twig', [
            'controller_name' => 'AdminArtisteController',
            'festivals' => $festivals
        ]);
    }

    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifier/{id}', name: '_modifier')]
    public function editerFestival(Request $request,
                               EntityManagerInterface $entityManager,
                               FestivalRepository $festivalRepository,
                               int $id = null): Response {

        if($id == null) {
            $festival = new Festival();
        } else {
            $festival = $festivalRepository->find($id);
        }

        $form = $this->createForm(FestivalType::class, $festival);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($festival);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Le festival a été ' . ($id == null ? 'ajouter' : 'modifier')
            );

            return $this->redirectToRoute('app_admin_festival_lister');
        }

        return $this->render('admin/admin_festival/editerFestival.html.twig',
            [
                'controller_name' => 'AdminFestivalController',
                'form' => $form,
            ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimerFestival(EntityManagerInterface $entityManager,
                                  FestivalRepository $festivalRepository,
                                  int $id): Response {
        $festivals = $festivalRepository->find($id);
        $entityManager->remove($festivals);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Le festival a été supprimé');
        return $this->redirectToRoute('app_admin_festival_lister');
    }
}

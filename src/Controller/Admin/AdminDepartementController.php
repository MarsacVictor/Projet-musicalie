<?php

namespace App\Controller\Admin;

use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/admin_departement', name: 'app_admin_departement')]
class AdminDepartementController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(DepartementRepository $departementRepository): Response
    {
        $departements = $departementRepository->findAll();
        return $this->render('admin/admin_departement/index.html.twig', [
            'controller_name' => 'AdminDepartementController',
            'departements' => $departements
        ]);
    }

    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifier/{id}', name: '_modifier')]
    public function editerDepartement(Request $request,
                                  EntityManagerInterface $entityManager,
                                  DepartementRepository $departementRepository,
                                  int $id = null): Response {

        if($id == null) {
            $departement = new Departement();
        } else {
            $departement = $departementRepository->find($id);
        }

        $form = $this->createForm(DepartementType::class, $departement);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($departement);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                "Le departement a été " . ($id == null ? 'ajouter' : 'modifier')
            );

            return $this->redirectToRoute('app_admin_departement_lister');
        }

        return $this->render('admin/admin_departement/editerDepartement.html.twig',
            [
                'controller_name' => 'AdminDepartementController',
                'form' => $form,
            ]);
    }
    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimerDepartement(EntityManagerInterface $entityManager,
                                  DepartementRepository $departementRepository,
                                  int $id): Response {
        $departement = $departementRepository->find($id);
        $entityManager->remove($departement);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Le departement a été supprimé');
        return $this->redirectToRoute('app_admin_departement_lister');
    }
}

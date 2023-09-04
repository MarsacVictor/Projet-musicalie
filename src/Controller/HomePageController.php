<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home_page', name: 'app_home')]
class HomePageController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(FestivalRepository $festivalRepository): Response
    {
        $festivals = $festivalRepository->findAll();
        return $this->render('home_page/index.html.twig', [
            'festivals' => $festivals
        ]);
    }
    #[Route('/departement/{region}', name: '_voir_departement')]
    public function voirDepartement(DepartementRepository $departementRepository, FestivalRepository $festivalRepository, String $region = null): Response
    {
        $festivals = [];
        $departements = $departementRepository->findBy(array('region' => $region));
        foreach ($departements as $departement) {
            array_push($festivals, $festivalRepository->findBy(array('departement' => $departement)));
        }
        return $this->render('departement/voir_departement.html.twig', [
            'departements' => $departements,
            'festivals' => $festivals,
            'region' => $region
        ]);
    }
    #[Route('/festival/{id}', name: '_voir_festival')]
    public function voirFestival(FestivalRepository $festivalRepository, int $id = null): Response
    {
        $festival = $festivalRepository->find($id);
        return $this->render('festival/voir_festival.html.twig', [
            'festival' => $festival
        ]);
    }
}

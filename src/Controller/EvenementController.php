<?php

namespace App\Controller;

use App\Store;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



class EvenementController extends AbstractController
{
    #[Route('/evenements', name: 'app_evenement_index')]
    public function index(Store $store): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $store->getTableau(),
            'moins_cher'=> $store->getEvenementsMoischeres(),
        ]);
    }

    #[Route('/evenements/{id}', name: 'app_evenement_show', requirements: ['id' => '\d+'])]
    public function show(int $id, Store $store): Response
    {
        $evenement = $store->getEvenementParId($id);

        if ($evenement === null) {
            throw $this->createNotFoundException('Cet événement n\'existe pas.');
        }

        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }


    #[Route('/evenements/categorie/{categorie}', name: 'app_evenement_categorie')]
    public function categorie(string $categorie, Store $store): Response
    {
        if (!in_array($categorie, $store->getCategories())) {
            $this->addFlash('warning', 'La catégorie « ' . $categorie . ' » n\'existe pas.');

            return $this->redirectToRoute('app_evenement_index');
        }

        return $this->render('evenement/categorie.html.twig', [
            'categorie' => $categorie,
            'evenements' => $store->getParCategorie($categorie),

        ]);
    }

    #[Route('/evenements/par-mois/{annee}/{mois}', name: 'app_evenement_par_mois', requirements: ['annee' => '\d{4}', 'mois' => '\d{1,2}'])]
    public function parMois(int $annee, int $mois, Store $store): Response
    {
        if ($annee < 2024 || $annee > 2030) {
            $this->addFlash('danger', 'Année invalide : elle doit être entre 2024 et 2030.');
            return $this->redirectToRoute('app_evenement_index');
        }
        if ($mois < 1 || $mois > 12) {
            $this->addFlash('danger', 'Mois invalide : il doit être entre 1 et 12.');
            return $this->redirectToRoute('app_evenement_index');
        }

        $result = [];
        $evenements = $store->getTableau();

        foreach ($evenements as $evenement) {
            $date = new DateTime($evenement['date_debut']);

            if ((int) $date->format('Y') === $annee && (int) $date->format('m') === $mois) {
                $result[] = $evenement;
            }
        }

        return $this->render('evenement/index.html.twig', [
            'evenements' => $result,
            'annee' => $annee,
            'mois' => $mois,
        ]);



    }
    #[Route('/evenements/disponibles/{nombre}', name: 'app_evenement_disponibles', requirements: ['nombre' => '\d+'])]
    public function disponibles(int $nombre, Store $store,Request $request): Response
    {

        if ($nombre < 1 || $nombre > 50) {
            $this->addFlash('danger', 'Le nombre de places doit être compris entre 1 et 50.');
            return $this->redirectToRoute('app_evenement_index');
        }



        $evenements = $store->getTableau();
        $resultat=[];
        $categorie=$request->query->get('categorie');
        if ($categorie!==null&&!in_array($categorie, $store->getCategories())) {
            $this->addFlash('danger',"la categorie n est pas dans le tableau.$categorie");
            return $this->redirectToRoute('app_evenement_index');
        }

        $resultat = [];
        foreach ($store->getTableau() as $evenement) {
            if (
                $evenement['places_disponibles'] >= $nombre
                && $evenement['statut'] !== 'annule'
                && ($categorie === null || $evenement['categorie'] === $categorie)
            ) {
                $resultat[] = $evenement;
            }
        }


        // 3. Partie 3 : le render viendra ici
        return $this->render('evenement/disponibles.html.twig :', [
            'evenements' => $resultat,
            'nombre' => $nombre,
            'categorie' => $categorie,

        ]);
    }
    #[Route('/evenements/prix/{max}', name: 'app_evenement_prix', requirements: ['max' => '\d+'])]
    public function pMAx(int $prixMax ,int $prix, Store $store): Response{
        $evenements = $store->getTableau();
        $resultat=[];
        foreach ($store->getTableau() as $evenement) {
            $evenement['prix']=$prix;
            if ($prixMax <0  || $prixMax>50 && $prixMax<$evenement['prix']) {
                return false;
            }
            $resultat[] = $evenement;
        }
        return $this->render('evenement/prix.html.twig', [
            'evenements' => $resultat,
            'prixMax' => $prixMax,
        ]);

    }









}

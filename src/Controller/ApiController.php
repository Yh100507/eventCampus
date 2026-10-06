<?php

namespace App\Controller;

use App\Store;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/evenements')]
class ApiController extends AbstractController
{
    #[Route('', name: 'api_evenements', methods: ['GET'])]
    public function liste(Request $request, Store $store): JsonResponse
    {
        $categorie = $request->query->get('categorie');
        $acces = $request->query->get('acces');
        $dateDebut = $request->query->get('date_Debut');
        $dateFin = $request->query->get('date_Fin');
        $statut = $request->query->get('statut');
        $prixMax = $request->query->get('prix_max');
        $placesMin = $request->query->get('places_min');
        $organisateur=$request->query->get('organisateurs');
        $resulat=[];
        $evenement=$store->getTableau();
       foreach ($evenement as $evenement) {
        if($statut!==null && !in_array( $statut, ['ouvert', 'complet', 'annule'])){
            return new JsonResponse([
                'success' => false,
                'error' => 'Aucun événement avec l\'organisateur' . $organisateur . '.',
            ], 400);
            }
        }// ← NOUVEAU

        // ← NOUVEAU : si places_min n'est pas un nombre → erreur 400
        if ($placesMin !== null && !is_numeric($placesMin)) {
            return new JsonResponse([
                'success' => false,
                'error' => 'places_min doit être un nombre.',
            ], 400);
        }

        $dateMin = $dateDebut ? new DateTime($dateDebut) : null;
        $dateMax = $dateFin ? new DateTime($dateFin) : null;

        $evenements = $store->getTableau();

        $resultat = array_filter($evenements, function (array $e) use ($categorie, $acces, $dateMin, $dateMax, $statut, $prixMax, $placesMin,$organisateur) {   // ← $placesMin ajouté
            if ($categorie !== null && $e['categorie'] !== $categorie) {
                return false;
            }
            if ($statut !== null && $e['statut'] !== $statut) {
                return false;
            }
            if ($prixMax !== null && (float)$prixMax < $e['prix']) {
                return false;
            }
            // ← NOUVEAU : pas assez de places disponibles → on rejette
            if ($placesMin !== null && $e['places_disponibles'] < (int)$placesMin) {
                return false;
            }
            if ($acces === 'gratuit' && $e['prix'] > 0) {
                return false;
            }
            if ($acces === 'payant' && $e['prix'] <= 0) {
                return false;
            }

            $dateEvenement = new DateTime($e['date_debut']);
            if ($dateMin !== null && $dateEvenement < $dateMin) {
                return false;
            }
            if ($dateMax !== null && $dateEvenement > $dateMax) {
                return false;
            }
            return true;
        });

        $resultat = array_values($resultat);

        return new JsonResponse([
            'success' => true,
            'count' => count($resultat),
            'data' => $resultat,
        ]);
    }

    #[Route('/affichage', name: 'api_evenements_affichage', methods: ['GET'])]
    public function affichage(Store $store): JsonResponse
    {
        $evenements = $store->getTableau();

        $resultat = array_map(function (array $e) {
            $pourcentage = $e['places_totales'] > 0
                ? round(($e['places_totales'] - $e['places_disponibles']) / $e['places_totales'] * 100)
                : 0;

            if ($e['places_disponibles'] === 0) {
                $message = 'Complet';
            } elseif ($pourcentage >= 90) {
                $message = 'Presque complet';
            } else {
                $message = "Encore {$e['places_disponibles']} places";
            }

            $e['pourcentage_remplissage'] = $pourcentage;
            $e['message'] = $message;
            unset($e['places_disponibles']);

            return $e;
        }, $evenements);

        return new JsonResponse(array_values($resultat));
    }


    // GET /api/evenements/{id}
    #[Route('/{id}', name: 'api_evenement', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, Store $store): JsonResponse
    {
        $evenement = $store->getEvenementParId($id);

        if ($evenement === null) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Aucun événement avec l\'identifiant ' . $id . '.',
            ], 404);
        }

        return new JsonResponse([
            'success' => true,
            'data' => $evenement,
        ]);
    }

    #[Route('/recherche',name: 'api_evenements_recherche', methods: ['GET'])]
public function recherche(Request $request, Store $store): JsonResponse{
    $organisateur=$request->query->get('organisateur');
        $statut=$request->query->get('statut');
        if($statut!==null && !in_array($statut, ['ouvert', 'complet', 'annule'])){
            return new JsonResponse([
                'success' => false,
                'error'=>'aucun evenement qui est contient les statut '

            ],400 );
        }
        $resultat = [];
        foreach ($store->getTableau() as $evenement) {
            if (
                ($organisateur === null ||$evenement['organisateur'] === $organisateur)
                && ($statut === null || $evenement['statut'] === $statut)
            ) {
                $resultat[] = $evenement;
            }
        }
        return new JsonResponse([
            'success' => true,
            'data' => $resultat,
            'count' => count($resultat),
        ]);

    }

}

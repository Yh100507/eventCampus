<?php

namespace App;

class Store
{
    public function getEvenement(): array
    {
        return array_slice($this->getTableau(), -3, 3, true);
    }

    public function getTableau(): array
    {
        return [
            1 => [
                'id' => 1,
                'titre' => 'Soirée Étudiante Halloween',
                'description' => 'Grande soirée costumée pour célébrer Halloween au campus !',
                'date_debut' => '2026-10-31 20:00:00',
                'date_fin' => '2026-11-01 02:00:00',
                'lieu' => 'Amphithéâtre Central',
                'categorie' => 'festif',
                'organisateur' => 'BDE Campus',
                'prix' => 8.0,
                'places_disponibles' => 150,
                'places_totales' => 200,
                'image' => 'halloween.jpg',
                'statut' => 'ouvert',
            ],
            2 => [
                'id' => 2,
                'titre' => 'Tournoi Inter-Filières de Football',
                'description' => 'Seize équipes, une seule coupe. Matchs de poules le matin, finales l\'après-midi.',
                'date_debut' => '2026-10-10 09:00:00',
                'date_fin' => '2026-10-10 18:00:00',
                'lieu' => 'Stade universitaire',
                'categorie' => 'sportif',
                'organisateur' => 'Bureau des Sports',
                'prix' => 5.0,
                'places_disponibles' => 32,
                'places_totales' => 64,
                'image' => 'football.jpg',
                'statut' => 'ouvert',
            ],
            3 => [
                'id' => 3,
                'titre' => 'Festival du Court-Métrage Étudiant',
                'description' => 'Douze courts-métrages réalisés par des étudiants, projetés sur grand écran.',
                'date_debut' => '2026-10-17 18:30:00',
                'date_fin' => '2026-10-17 22:30:00',
                'lieu' => 'Auditorium Marie Curie',
                'categorie' => 'culturel',
                'organisateur' => 'Ciné-Club Campus',
                'prix' => 0.0,
                'places_disponibles' => 80,
                'places_totales' => 120,
                'image' => 'court-metrage.jpg',
                'statut' => 'ouvert',
            ],
            4 => [
                'id' => 4,
                'titre' => 'Forum des Associations',
                'description' => 'Rencontrez plus de quarante associations du campus et inscrivez-vous sur place.',
                'date_debut' => '2026-10-05 10:00:00',
                'date_fin' => '2026-10-05 17:00:00',
                'lieu' => 'Hall principal',
                'categorie' => 'associatif',
                'organisateur' => 'Maison des Associations',
                'prix' => 0.0,
                'places_disponibles' => 300,
                'places_totales' => 500,
                'image' => 'forum.jpg',
                'statut' => 'ouvert',
            ],
            5 => [
                'id' => 5,
                'titre' => 'Gala de Fin d\'Année',
                'description' => 'Dîner assis, orchestre live et piste de danse. Tenue de soirée exigée.',
                'date_debut' => '2026-12-12 19:30:00',
                'date_fin' => '2026-12-13 03:00:00',
                'lieu' => 'Salle des Fêtes municipale',
                'categorie' => 'festif',
                'organisateur' => 'BDE Campus',
                'prix' => 35.0,
                'places_disponibles' => 0,
                'places_totales' => 250,
                'image' => 'gala.jpg',
                'statut' => 'complet',
            ],
            6 => [
                'id' => 6,
                'titre' => 'Tournoi de Basket 3x3',
                'description' => 'Des équipes de trois, des matchs de dix minutes, ouvert à tous les niveaux.',
                'date_debut' => '2026-10-14 14:00:00',
                'date_fin' => '2026-10-14 19:00:00',
                'lieu' => 'Playground du Campus',
                'categorie' => 'sportif',
                'organisateur' => 'Bureau des Sports',
                'prix' => 3.0,
                'places_disponibles' => 6,
                'places_totales' => 48,
                'image' => 'basket.jpg',
                'statut' => 'ouvert',
            ],
            7 => [
                'id' => 7,
                'titre' => 'Concert des Talents du Campus',
                'description' => 'Les groupes du campus sur scène. Concert annulé, nouvelle date à venir.',
                'date_debut' => '2026-11-07 20:00:00',
                'date_fin' => '2026-11-07 23:30:00',
                'lieu' => 'Salle Polyvalente',
                'categorie' => 'culturel',
                'organisateur' => 'Association Musicale',
                'prix' => 10.0,
                'places_disponibles' => 60,
                'places_totales' => 100,
                'image' => 'concert.jpg',
                'statut' => 'annule',
            ],
            8 => [
                'id' => 8,
                'titre' => 'Journée Solidaire et Collecte de Dons',
                'description' => 'Collecte de vêtements, de livres et de denrées au profit d\'associations locales.',
                'date_debut' => '2026-11-14 09:30:00',
                'date_fin' => '2026-11-14 16:30:00',
                'lieu' => 'Parvis de la Bibliothèque',
                'categorie' => 'associatif',
                'organisateur' => 'Association Solidarité Campus',
                'prix' => 0.0,
                'places_disponibles' => 40,
                'places_totales' => 80,
                'image' => 'solidaire.jpg',
                'statut' => 'ouvert',
            ],
        ];
    }

    public function getEvenementParId(int $id): ?array
    {
        return $this->getTableau()[$id] ?? null;
    }

    public function getCategories(): array
    {
        return ['culturel', 'sportif', 'associatif', 'festif'];
    }

    public function getParCategorie(string $categorie): array
    {
        return array_filter($this->getTableau(), fn($evenement) => $evenement['categorie'] === $categorie);
    }

    public function getStatistiques(): array
    {
        $evenements = $this->getTableau();
        $total = count($evenements);

        $parCategorie = [];
        foreach ($this->getCategories() as $categorie) {
            $nombre = count($this->getParCategorie($categorie));
            $parCategorie[$categorie] = [
                'nombre' => $nombre,
                'pourcentage' => $total > 0 ? round($nombre / $total * 100) : 0,
            ];
        }
        $payant = [];
        foreach ($evenements as $evenement) {
            if ($evenement['prix'] > 0) {
                $payant[] = $evenement;
            }

        }
        $sommeNombre = array_sum(array_column($payant, 'prix'));
        $countTotale = count($payant);
        $prixMoyent = $countTotale > 0 ? $sommeNombre / $countTotale : 0;

        return [
            'total' => $total,
            'places_disponibles' => array_sum(array_column($evenements, 'places_disponibles')),
            'places_totales' => array_sum(array_column($evenements, 'places_totales')),
            'par_categorie' => $parCategorie,
            'prix_moyent' => $prixMoyent,

        ];
    }

    public function getEvenementsParMois(): array
    {
        $aujourdhui = new \DateTime();
        $mois = $aujourdhui->format('n');
        $annee = $aujourdhui->format('Y');

        $resultat = [];

        foreach ($this->getTableau() as $evenement) {
            $dateVerifie = new \DateTime($evenement['date_debut']);
            $dateVerifieMois = $dateVerifie->format('n');
            $dateVerifieAnnee = $dateVerifie->format('Y');

            if ($dateVerifieAnnee == $annee && $dateVerifieMois == $mois) {
                $resultat[] = $evenement;
            }
        }

        return $resultat;
    }

    public function getEvenementsMoischeres(): array
    {
        $evenements = $this->getTableau();
        $result = [];
        foreach ($evenements as $evenement) {
            $moinChere = 0;
            if ($evenement['prix'] < $moinChere) {
                return false;
            }
            return $result[] = $evenement;

        }
        return $result;
    }

    public function getCompteEvenement(): array
    {
        $nombre = 0;
        $evenements = $this->getTableau();
        foreach ($evenements as $evenement) {
            if ($evenement['places_disponible'] == 0 || $evenement['statut'] != 'complet') {
                $nombre++;
            }

        }
        return $nombre;
    }

    public function getCompteEvenementsLongs(): int
    {
        $nombre = 0;

        foreach ($this->getTableau() as $evenement) {
            $debut = new \DateTime($evenement['date_debut']);
            $fin = new \DateTime($evenement['date_fin']);

            $heures = ($fin->getTimestamp() - $debut->getTimestamp()) / 3600;

            if ($heures > 5) {
                $nombre++;
            }
        }

        return $nombre;
    }
    // pour calculer la diffrence entre une dateDe fin et debut sur heure $heures = ($fin->getTimestamp() - $debut->getTimestamp()) / 3600;
}

// pour trouver la possition d un element dans un tableau classique
//$evenements = array_values($this->getTableau());   // on s'assure d'avoir des index 0, 1, 2...
//$position = null;
//
//foreach ($evenements as $index => $evenement) {
//    if ($evenement['id'] === $id) {
//        $position = $index;
//    }


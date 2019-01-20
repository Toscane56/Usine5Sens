<?php

// inscription => /user/register OK
// connexion => /user/login OK
// profil => /user/profile OK
// maj profil => /user/update OK

// lister expositions => /expositions/index
// lister ateliers (reliés à des sens)=> /workshops/index
// réserver atelier => /workshops/book

// lister réservations => /reservations/index
// supprimer réservation => /reservations/delete

function renderData($data)
{
    return json_encode($data, JSON_PRETTY_PRINT);
}

function renderSuccess($data)
{
    return json_encode(array_merge([
        'status' => 'success',
        'message' => '...'
    ], $data), JSON_PRETTY_PRINT);
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>API</title>
</head>
<body>
    <h1>API</h1>
    <pre>POST <em>/chemin/vers/le/dossier/public</em>/index.php?entity=<strong>{entity}</strong>&action=<strong>{action}</strong></pre>
    <p>* Les types de requête marquées d'une astérisque doivent être envoyées avec un token ("X-AUTH-TOKEN" dans les headers)</p>
    <h2>Utilisateurs</h2>
    <table border="1">
        <tr>
            <th>Type</th>
            <th>Entité</th>
            <th>Action</th>
            <th>Données à envoyer</th>
            <th>Réponse</th>
        </tr>
        <tr>
            <th>Créer un compte</th>
            <td>user</td>
            <td>register</td>
            <td><pre><?= renderData(['email' => 'jean@dupont.fr', 'password' => 'azerty', 'firstname' => 'Jean', 'lastname' => 'Dupont']) ?></pre></td>
            <td><pre><?= renderSuccess(['user' => ['token' => '*********', 'id' => '**', 'email' => 'jean@dupont.fr', 'firstname' => 'Jean', 'lastname' => 'Dupont']]) ?></pre></td>
        </tr>
        <tr>
            <th>Récupérer un token</th>
            <td>user</td>
            <td>login</td>
            <td><pre><?= renderData(['email' => 'jean@dupont.fr', 'password' => 'azerty']) ?></pre></td>
            <td><pre><?= renderSuccess(['user' => ['token' => '*********', 'id' => '**', 'email' => 'jean@dupont.fr', 'firstname' => 'Jean', 'lastname' => 'Dupont']]) ?></pre></td>
        </tr>
        <tr>
            <th>Récupérer le profil *</th>
            <td>user</td>
            <td>profile</td>
            <td></td>
            <td><pre><?= renderSuccess(['user' => ['id' => '**', 'email' => 'jean@dupont.fr', 'firstname' => 'Jean', 'lastname' => 'Dupont']]) ?></pre></td>
        </tr>
        <tr>
            <th>Mettre à jour un profil *</th>
            <td>user</td>
            <td>update</td>
            <td><pre><?= renderData(['email' => 'jean@dupont.fr', 'password' => 'azerty', 'firstname' => 'Jean', 'lastname' => 'Dupont']) ?></pre></td>
            <td><pre><?= renderSuccess(['user' => ['id' => '**', 'email' => 'jean@dupont.fr', 'firstname' => 'Jean', 'lastname' => 'Dupont']]) ?></pre></td>
        </tr>
    </table>
    <h2>Expositions</h2>
    <table border="1">
        <tr>
            <th>Type</th>
            <th>Entité</th>
            <th>Action</th>
            <th>Données à envoyer</th>
            <th>Réponse</th>
        </tr>
        <tr>
            <th>Récupérer l'exposition du jour</th>
            <td>exposition</td>
            <td>index</td>
            <td></td>
            <td><pre><?= renderSuccess(['exposition' => ['id' => '**', 'name' => "L'eau", 'img' => "water.png", 'description' => 'Nee sur le site de l ancienne usine...', 'starting_at' => '2019-01-03', 'ending_at' => '2019-03-03']]) ?></pre></td>
        </tr>
    </table>
    <h2>Workshops</h2>
    <table border="1">
        <tr>
            <th>Type</th>
            <th>Entité</th>
            <th>Action</th>
            <th>Données à envoyer</th>
            <th>Réponse</th>
        </tr>
        <tr>
            <th>Récupérer les workshops du jour</th>
            <td>workshop</td>
            <td>index</td>
            <td></td>
            <td><pre><?= renderSuccess(['workshops' => [
                        ['id' => '**', 'name' => "Photographie et nature", 'img' => "atelier_photo.jpg", 'description' => 'Apprenez a faire de jolies photographies...', 'starting_at' => '2019-01-01', 'ending_at' => '2019-06-19', 'duration' => 2, 'scheduled_at' => '16:00:00', 'senses' => [
                            ['id' => '**', 'name' => 'vue'],
                            ['id' => '**', 'name' => 'toucher']
                        ]]
                    ]]) ?></pre></td>
        </tr>
        <tr>
            <th>Récupérer les réservations *</th>
            <td>workshop</td>
            <td>reservations</td>
            <td></td>
            <td><pre><?= renderSuccess(['workshops' => [
                        ['id' => '**', 'date' => '2019-01-19', 'name' => "Photographie et nature", 'img' => "atelier_photo.jpg", 'description' => 'Apprenez a faire de jolies photographies...', 'starting_at' => '2019-01-01', 'ending_at' => '2019-06-19', 'duration' => 2, 'scheduled_at' => '16:00:00', 'senses' => [
                            ['id' => '**', 'name' => 'vue'],
                            ['id' => '**', 'name' => 'toucher']
                        ]],
                        ['id' => '**', 'date' => '2019-01-19', 'name' => "Atelier cuisine", 'img' => "atelier_cuisine.jpg", 'description' => 'Au menu de nos cours, decouvrez des saveurs...', 'starting_at' => '2019-01-01', 'ending_at' => '2019-08-15', 'duration' => 3, 'scheduled_at' => '11:00:00', 'senses' => [
                            ['id' => '**', 'name' => 'toucher'],
                            ['id' => '**', 'name' => 'goût'],
                            ['id' => '**', 'name' => 'odorat']
                        ]]
                    ]]) ?></pre></td>
        </tr>
        <tr>
            <th>Réserver un workshop aujourd'hui *</th>
            <td>workshop</td>
            <td>book</td>
            <td><?= renderData(['workshop_id' => '**']) ?></td>
            <td><pre><?= renderSuccess(['workshop' => '...', 'reservation' => [
                'date' => '2019-01-20',
                        'id' => '**',
                        'user_id' => '**',
                        'workshop_id' => '**'
                    ]]) ?></pre></td>
        </tr>
        <tr>
            <th>Supprimer une réservation *</th>
            <td>workshop</td>
            <td>remove</td>
            <td><?= renderData(['workshop_id' => '**']) ?></td>
            <td><pre><?= renderSuccess([]) ?></pre></td>
        </tr>
    </table>
    <style>
        th, td { padding: 10px; }
    </style>
</body>
</html>

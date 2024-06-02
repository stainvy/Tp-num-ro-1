<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Liste des villes</title>
        <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
    <?php
    session_start();
    if (isset($_SESSION['MSG_OK'])) {
        // Affichage du message de succès
        echo '<div class="alert alert-success" role="alert">' . $_SESSION['MSG_OK'] . '</div>';
        // Suppression du message de la session pour éviter de l'afficher à nouveau lors du rechargement de la page
        unset($_SESSION['MSG_OK']);
    }
    ?>
    <?php
    include('../include/menu.php');
    ?>
        <div class="container">
            <h1>Les villes</h1>
    <?php
    include("../include/connexion.php");

    
    // Requête SQL pour récupérer les informations des villes
    $requete = 'SELECT nom
                , codepostal
                , pays
                , code
                FROM ville';
    ?>
    <!-- Création d'un tableau pour afficher les informations des villes -->
    <table class="table table-striped display table-hover" style="width:100%" id="villes">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Code postal</th>
            <th>Pays</th>
        </tr>
    </thead>
    <tbody>
    <?php
    try {
        // Exécution de la requête et affichage des résultats dans le tableau
        foreach($bdd->query($requete) as $ligne) {
            echo '<tr class="clickable-row" data-href="ville.php?id=' . $ligne['code'] . '">';
            echo '<td>' . $ligne['nom'] . '</td>';
            echo '<td>' . $ligne['codepostal'] . '</td>';
            echo '<td>' . $ligne['pays'] . '</td>';
            echo "</tr>\n";
        }
    } catch (PDOException $e) {
        // Affichage d'un message d'erreur en cas d'échec de l'exécution de la requête
        echo 'Erreur !: ' . $e->getMessage() . '<br>';
        die();
    }
    ?>
        </tbody>
    </table>
    <div class="container">
    <!-- Formulaire pour ajouter une nouvelle ville -->
    <form method="get" action="ville.php">
        <div class="form-group mb-3 text-start">
            <input type="hidden" name="new" value="true">
            <input type="submit" class="btn btn-primary" name="Nouveau" value="Nouveau">
        </div>
    </div>
    </form>
        <script src="../node_modules/jquery/dist/jquery.min.js"></script>
        <script src="../node_modules/datatables.net/js/dataTables.min.js"></script>
        <script src="../node_modules/dataTables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script>
        $(document).ready(function () {
            $('#villes').DataTable({
                language: {
                    url: '../include/traduction.json',
                },
            });
            // Redirection vers la page de la ville lors du clic sur une ligne du tableau
            $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });  
        });
        
        </script>
        
       
    </body>
</html>
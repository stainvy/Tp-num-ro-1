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
include('../include/menu.php');
?>
        <div class="container">
            <h1>Les villes</h1>
<?php
include("../include/connexion.php");

/**
 * Page qui affiche la liste de toutes les villes
 */

$requete = 'SELECT nom
            , codepostal
            , pays
            , code
            FROM ville';
?>
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
    foreach($bdd->query($requete) as $ligne) {
        echo '<tr class="clickable-row" data-href="ville.php?id=' . $ligne['code'] . '">';
        echo '<td>' . $ligne['nom'] . '</td>';
        echo '<td>' . $ligne['codepostal'] . '</td>';
        echo '<td>' . $ligne['pays'] . '</td>';
        echo "</tr>\n";
    }
} catch (PDOException $e) {
    echo 'Erreur !: ' . $e->getMessage() . '<br>';
    die();
}
?>
        </tbody>
    </table>
    </div>
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
            $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });  
        });
        
        </script>
        
       
    </body>
</html>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Liste des fournisseurs</title>
        <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>Les fournisseurs</h1>
<?php
include("../include/connexion.php");

/**
 * Page qui affiche la liste de toutes les villes
 */

$requete = 'select f.nom, c.libelle, f.contact, v.codepostal, v.nom as ville
from fournisseur f, civilite c, ville v 
where v.code = f.ville and f.civilite = c.code';
?>
    <table class="table table-striped display" style="width:100%" id="fournisseurs">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Civilité</th>
                <th>Contact</th>
                <th>Code postal</th>
                <th>Ville</th>
            </tr>
        </thead>
        <tbody>
<?php
try {
    foreach($bdd->query($requete) as $ligne) {
        echo '<tr>';
        echo '<td>' . $ligne['nom'] . '</td>';
        echo '<td>' . $ligne['libelle'] . '</td>';
        echo '<td>' . $ligne['contact'] . '</td>';
        echo '<td>' . $ligne['codepostal'] . '</td>';
        echo '<td>' . $ligne['ville'] . '</td>';
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
            $('#fournisseurs').DataTable({
                language: {
                    url: '../include/traduction.json',
                },
            });
        });
        </script>
       
    </body>
</html>
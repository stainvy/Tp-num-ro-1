<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Liste des fournisseurs</title>
        <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
    <?php
    session_start();
    // Si un message de succès est présent dans la session, on l'affiche et on le supprime de la session
    if (isset($_SESSION['MSG_OK'])) {
        echo '<div class="alert alert-success" role="alert">' . $_SESSION['MSG_OK'] . '</div>';
        unset($_SESSION['MSG_OK']); // pour ne pas afficher le message à nouveau lors du rechargement de la page
    }
    ?>
    <?php
    include('../include/menu.php');
    ?>
        <div class="container">
            <h1>Les fournisseurs</h1>
    <?php
    include("../include/connexion.php");


    // Requête SQL pour récupérer les informations des fournisseurs
    $requete = 'select f.nom, c.libelle, f.contact, v.codepostal, v.nom as ville, f.code
    from fournisseur f, civilite c, ville v 
    where v.code = f.ville and f.civilite = c.code';
    ?>
    <!-- Tableau pour afficher les informations des fournisseurs -->
    <table class="table table-striped display table-hover" style="width:100%" id="fournisseurs">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Civilité</th>
            <th>Contact</th>
            <th>Code postal</th>
            <th>Ville</th>
            <th>Code</th>
        </tr>
    </thead>
    <tbody>
    <?php
    try {
        // Exécution de la requête et affichage des résultats dans le tableau
        foreach($bdd->query($requete) as $ligne) {
            echo '<tr class="clickable-row" data-href="fournisseur.php?id=' . $ligne['code'] . '">';
            echo '<td>' . $ligne['nom'] . '</td>';
            echo '<td>' . $ligne['libelle'] . '</td>';
            echo '<td>' . $ligne['contact'] . '</td>';
            echo '<td>' . $ligne['codepostal'] . '</td>';
            echo '<td>' . $ligne['ville'] . '</td>';
            echo '<td>' . $ligne['code'] . '</td>';
            echo "</tr>\n";
        }
    } catch (PDOException $e) {
        // En cas d'erreur, on affiche un message et on arrête l'exécution du script
        echo 'Erreur !: ' . $e->getMessage() . '<br>';
        die();
    }
    ?>
    </tbody>
</table>
<div class="container">
    <!-- Formulaire pour créer un nouveau fournisseur -->
    <form method="get" action="fournisseur.php">
        <div class="form-group mb-3 text-start">
            <input type="hidden" name="new" value="true">
            <input type="submit" class="btn btn-primary" name="Nouveau" value="Nouveau">
        </div>
    </form>
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

    // Lorsqu'on clique sur une ligne, on est redirigé vers la page du fournisseur correspondant
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
</body>
</html>
<?php
session_start();

require_once('../include/connexion.php');
require_once('../include/fonction.php');

// Si le paramètre 'new' est présent dans l'URL et qu'il est égal à 'true', alors on initialise l'ID à 0
// Sinon, on récupère l'ID du fournisseur depuis l'URL, ou on le définit à 0 si aucun ID n'est spécifié
if(isset($_GET['new']) && $_GET['new'] == 'true') {
    $id = 0;
} else {
    $id = (isset($_GET['id']))?$_GET['id']:0;
}

// Si l'ID est égal à 0 et que le paramètre 'new' n'est pas présent dans l'URL, alors on redirige l'utilisateur vers la liste des fournisseurs
if($id == 0 && !isset($_GET['new'])) {
    header("Location:$url/listefournisseur.php");
    die();
}

// Si la méthode de la requête HTTP est POST, alors on traite les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Creer'])) {
        // Initialisation du message d'erreur
        $_SESSION['MSG_KO'] = '';

        // Contrôles sur les données du formulaire
        if (strlen($_POST['nom']) < 3) {
            $_SESSION['MSG_KO'] .= "Le nom doit comporter au moins 3 caractères<br>";
        }
        if (empty($_POST['adresse1'])) {
            $_SESSION['MSG_KO'] .= "L'adresse est obligatoire<br>";
        }

        // Contrôle de l'unicité du nom du fournisseur
        $requete = $bdd->prepare('SELECT COUNT(*) as cpt FROM fournisseur WHERE nom = ?');
        $requete->execute(array($_POST['nom']));
        $compteur = $requete->fetch();
        if($compteur['cpt'] > 0) {
            $_SESSION['MSG_KO'] .= "Le nom (".$_POST['nom'].") est déjà pris<br />";
        }

        // Si aucune erreur, exécution de la requête d'insertion
        if (empty($_SESSION['MSG_KO'])) {
            try {
                $requete = $bdd->prepare('INSERT INTO fournisseur (nom, adresse1, adresse2, ville, contact, civilite) VALUES (:nom, :adresse1, :adresse2, :ville, :contact, :civilite)');
                $requete->execute(array(
                    'nom' => $_POST['nom'],
                    'adresse1' => $_POST['adresse1'],
                    'adresse2' => $_POST['adresse2'],
                    'ville' => $_POST['ville'],
                    'contact' => $_POST['contact'],
                    'civilite' => $_POST['civilite']
                ));
                $_SESSION['MSG_OK'] = "Création bien enregistrée";
            } catch (PDOException $e) {
                print "Erreur !: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    } elseif (isset($_POST['Modifier'])) {
        // Initialisation du message d'erreur
        $_SESSION['MSG_KO'] = '';

        // Contrôles sur les données du formulaire
        if (strlen($_POST['nom']) < 3) {
            $_SESSION['MSG_KO'] .= "Le nom doit comporter au moins 3 caractères<br>";
        }
        if (empty($_POST['adresse1'])) {
            $_SESSION['MSG_KO'] .= "L'adresse est obligatoire<br>";
        }

        // Contrôle de l'unicité du nom du fournisseur
        $requete = $bdd->prepare('SELECT COUNT(*) as cpt FROM fournisseur WHERE nom = ? AND code != ?');
        $requete->execute(array($_POST['nom'], $id));
        $compteur = $requete->fetch();
        if($compteur['cpt'] > 0) {
            $_SESSION['MSG_KO'] .= "Le nom (".$_POST['nom'].") est déjà pris<br />";
        }

        // Si aucune erreur, exécution de la requête de mise à jour
        if (empty($_SESSION['MSG_KO'])) {
            try {
                $requete = $bdd->prepare('update fournisseur
                set nom = :nom,
                adresse1 = :adresse1,
                adresse2 = :adresse2,
                ville = :ville,
                contact = :contact,
                civilite = :civilite
                where code = :code');
                $requete->execute(array(
                    'nom' => $_POST['nom'],
                    'adresse1' => $_POST['adresse1'],
                    'adresse2' => $_POST['adresse2'],
                    'ville' => $_POST['ville'],
                    'contact' => $_POST['contact'],
                    'civilite' => $_POST['civilite'],
                    'code' => $id
                ));
                $_SESSION['MSG_OK'] = "Modification bien enregistrée";
            } catch (PDOException $e) {
                print "Erreur !: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    
    } elseif (isset($_POST['Annuler'])) {
        // Si le bouton 'Annuler' a été cliqué, alors on redirige l'utilisateur vers la liste des fournisseurs
        header("Location: /public/Fournisseurs.php");
        exit();
    } elseif (isset($_POST['Supprimer'])) {
        // Si le bouton 'Supprimer' a été cliqué, alors on exécute la requête de suppression
        try {
            $requete = $bdd->prepare('DELETE FROM fournisseur WHERE code = ?');
            $requete->execute(array($id));
            $_SESSION['MSG_OK'] = "Suppression bien effectuée";
            header("Location: /public/Fournisseurs.php");
            exit();
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}

// Si l'ID n'est pas égal à 0, alors on récupère les informations du fournisseur depuis la base de données
if($id != 0) {
    try {
        $requete = $bdd->prepare('select nom, adresse1, adresse2, ville, contact, civilite from fournisseur where code = ?');
        $requete->execute(array($id));
        $fournisseur = $requete->fetch();
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
} else {
    // Sinon, on initialise un tableau vide pour le fournisseur
    $fournisseur = array('nom' => '', 'adresse1' => '', 'adresse2' => '', 'ville' => '', 'contact' => '', 'civilite' => '');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<?php
include('../include/menu.php');

// Affichage des messages d'erreur ou de succès
afficheMessages();

?>
<div class="container">
<h1>
        <?php 
            // Si l'ID du fournisseur est présent dans l'URL, alors on affiche le nom du fournisseur
            // Sinon, on affiche 'Nouveau fournisseur'
            if (isset($_GET['id'])) {
                echo $fournisseur['nom'];
            } else {
                echo 'Nouveau fournisseur';
            }
        ?>
    </h1>
    <form method="post">
    <div class="text-end">
    <?php if($id != 0): ?>
    <!-- Si l'ID n'est pas égal à 0, alors on affiche les boutons 'Modifier' et 'Supprimer' -->
    <input type="submit" class="btn btn-primary" name="Modifier" value="Modifier">
    <input type="submit" class="btn btn-danger confirm" name="Supprimer" value="Supprimer">
<?php else: ?>
    <!-- Sinon, on affiche le bouton 'Creer' -->
    <input type="submit" class="btn btn-primary" name="Creer" value="Créer">
<?php endif; ?>
<input type="submit" class="btn btn-secondary" name="Annuler" value="Annuler">
</div>
</form>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Script jQuery pour afficher une confirmation de suppression lorsque l'utilisateur clique sur le bouton 'Supprimer'
$(function() {
    $('.confirm').click(function() {
        return window.confirm("Êtes-vous sur ?");
    });
});
</script>
</html>
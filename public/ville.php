<?php
session_start();

require_once('../include/connexion.php');
require_once('../include/fonction.php');

// Si le paramètre 'new' est présent dans l'URL et qu'il est égal à 'true', on initialise l'id à 0
// Sinon, on récupère l'id depuis l'URL, ou on le définit à 0 si non présent
if(isset($_GET['new']) && $_GET['new'] == 'true') {
    $id = 0;
} else {
    $id = (isset($_GET['id']))?$_GET['id']:0;
}

// Si l'id est égal à 0 et que le paramètre 'new' n'est pas présent dans l'URL, on redirige vers la page 'listeville.php'
if($id == 0 && !isset($_GET['new'])) {
    header("Location:$url/listeville.php");
    die();
}

// Si la méthode de la requête est POST, on traite les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialisation du message d'erreur
    $_SESSION['MSG_KO'] = '';

    // Contrôles sur les données du formulaire
    if (strlen($_POST['nom']) < 2) {
        $_SESSION['MSG_KO'] .= "Le nom doit comporter au moins 2 caractères<br>";
    }
    if (!is_numeric($_POST['codepostal']) || strlen($_POST['codepostal']) != 5) {
        $_SESSION['MSG_KO'] .= "Le code postal doit être numérique et comporter exactement 5 caractères<br>";
    }
    if (!in_array($_POST['pays'], ['France', 'Andorre', 'Monaco'])) {
        $_SESSION['MSG_KO'] .= "Le pays doit être dans la liste suivante : France, Andorre ou Monaco <br>";
    }
    // Contrôle de l'unicité du nom de la ville
    $requete = $bdd->prepare('SELECT COUNT(*) as cpt FROM ville WHERE nom = ? AND code != ?');
    $requete->execute(array($_POST['nom'], $id)); 
    $compteur = $requete->fetch();
    if($compteur['cpt'] > 0) {
        $_SESSION['MSG_KO'] .= "Le nom (".$_POST['nom'].") est déjà pris<br />";
    }

    // Si le bouton 'Créer' a été cliqué et qu'il n'y a pas d'erreur, on insère la nouvelle ville dans la base de données
    if (isset($_POST['Creer']) && empty($_SESSION['MSG_KO'])) {
        try {
            $requete = $bdd->prepare('INSERT INTO ville (nom, codepostal, pays, code) VALUES (:nom, :codepostal, :pays, :code)');
            $requete->execute(array(
                'nom' => $_POST['nom'],
                'codepostal' => $_POST['codepostal'],
                'pays' => $_POST['pays'],
                'code' => $id 
            ));

            $_SESSION['MSG_OK'] = "Création bien enregistrée";
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    } 
    // Si le bouton 'Modifier' a été cliqué et qu'il n'y a pas d'erreur, on met à jour la ville dans la base de données
    elseif (isset($_POST['Modifier']) && empty($_SESSION['MSG_KO'])) {
        try {
            $requete = $bdd->prepare('UPDATE ville SET nom = :nom, codepostal = :codepostal, pays = :pays WHERE code = :code');
            $requete->execute(array(
                'nom' => $_POST['nom'],
                'codepostal' => $_POST['codepostal'],
                'pays' => $_POST['pays'],
                'code' => $id 
            ));

            $_SESSION['MSG_OK'] = "Modification bien enregistrée";
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    } 
    // Si le bouton 'Annuler' a été cliqué, on redirige vers la page 'Villes.php'
    elseif (isset($_POST['Annuler'])) {
        header("Location: /public/Villes.php");
        exit();
    } 
    // Si le bouton 'Supprimer' a été cliqué, on supprime la ville de la base de données et on redirige vers la page 'Villes.php'
    elseif (isset($_POST['Supprimer'])) {
        try {
            $requete = $bdd->prepare('DELETE FROM ville WHERE code = ?');
            $requete->execute(array($id));
            $_SESSION['MSG_OK'] = "Suppression bien effectuée";
            header("Location: /public/Villes.php");
            exit();
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}

// On récupère les informations de la ville depuis la base de données
try {
    $requete = $bdd->prepare('select nom, codepostal, pays from ville where code = ?');
    $requete->execute(array($id));
    $ville = $requete->fetch();
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
<?php
if (!isset($ville)) {
    $ville = array();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ville <?php echo (is_array($ville) && isset($ville['nom'])) ? $ville['nom'] : 'Nouvelle ville'; ?></title>
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
<h1><?php echo (is_array($ville) && isset($ville['nom'])) ? $ville['nom'] : 'Nouvelle ville'; ?></h1>
<form method="post">
    <!-- Formulaire pour la création/modification d'une ville -->
    <div class="form-group row mb-3">
        <label for="nom" class="col-sm-2 col-form-label">Nom</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="nom" value="<?php echo isset($_POST['nom']) ? $_POST['nom'] : ((is_array($ville) && isset($ville['nom'])) ? $ville['nom'] : ''); ?>">
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="codepostal" class="col-sm-2 col-form-label">Code Postal</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="codepostal" value="<?php echo isset($_POST['codepostal']) ? $_POST['codepostal'] : ((is_array($ville) && isset($ville['codepostal'])) ? $ville['codepostal'] : ''); ?>">
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="pays" class="col-sm-2 col-form-label">Pays</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="pays" value="<?php echo isset($_POST['pays']) ? $_POST['pays'] : ((is_array($ville) && isset($ville['pays'])) ? $ville['pays'] : ''); ?>">
        </div>
    </div>
    <div class="text-end">
    <!-- Boutons du formulaire -->
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
// Script pour confirmer la suppression d'une ville
$(document).ready(function() {
    $('.confirm').click(function() {
        return window.confirm("Êtes-vous sur ?");
    });
});
</script>
</body>
</html>
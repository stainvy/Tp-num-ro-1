<?php
session_start();
require_once('../include/connexion.php');
require_once('../include/fonction.php');

$id = (isset($_GET['id']))?$_GET['id']:0;
if($id == 0) {
    header("Location:$url/listefournisseur.php");
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Modifier'])) {
        // Initialisation du message d'erreur
        $_SESSION['MSG_KO'] = '';

        // Contrôles
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

        // Si aucune erreur, exécution de la requête
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
        header("Location: /public/Fournisseurs.php");
        exit();
    } elseif (isset($_POST['Supprimer'])) {
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


try {
    $requete = $bdd->prepare('select nom, adresse1, adresse2, ville, contact, civilite from fournisseur where code = ?');
    $requete->execute(array($id));
    $fournisseur = $requete->fetch();
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fournisseur <?php echo $fournisseur['nom']; ?></title>
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<?php
include('../include/menu.php');
afficheMessages();

?>
<div class="container">
<h1>fournisseur <?php echo isset($fournisseur['nom']) ? $fournisseur['nom'] : ''; ?></h1>
<form method="post">
    <div class="form-group row mb-3">
        <label for="nom" class="col-sm-2 col-form-label">Nom</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="nom" value="<?php echo isset($_POST['nom']) ? $_POST['nom'] : (isset($fournisseur['nom']) ? $fournisseur['nom'] : ''); ?>">
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="adresse1" class="col-sm-2 col-form-label">Adresse 1</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="adresse1" value="<?php echo isset($_POST['adresse1']) ? $_POST['adresse1'] : (isset($fournisseur['adresse1']) ? $fournisseur['adresse1'] : ''); ?>">
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="adresse2" class="col-sm-2 col-form-label">Adresse 2</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="adresse2" value="<?php echo isset($_POST['adresse2']) ? $_POST['adresse2'] : (isset($fournisseur['adresse2']) ? $fournisseur['adresse2'] : ''); ?>">
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="ville" class="col-sm-2 col-form-label">Ville</label>
        <div class="col-sm-10">
            <?php 
                $selectedVille = isset($_POST['ville']) ? $_POST['ville'] : (isset($fournisseur['ville']) ? $fournisseur['ville'] : '');
                echo str_replace('<select', '<select class="form-select"', selectVille('ville', $selectedVille)); 
            ?>
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="contact" class="col-sm-2 col-form-label">Contact</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="contact" value="<?php echo isset($_POST['contact']) ? $_POST['contact'] : (isset($fournisseur['contact']) ? $fournisseur['contact'] : ''); ?>">
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="civilite" class="col-sm-2 col-form-label">Civilité</label>
        <div class="col-sm-10">
            <?php 
                $selectedCivilite = isset($_POST['civilite']) ? $_POST['civilite'] : (isset($fournisseur['civilite']) ? $fournisseur['civilite'] : '');
                echo str_replace('<select', '<select class="form-select"', selectCivilite('civilite', $selectedCivilite)); 
            ?>
        </div>
    </div>
    <input type="submit" class="btn btn-default" name="Annuler" value="Annuler">
    <input type="submit" class="btn btn-primary" name="Modifier" value="Modifier">
    <input type="submit" class="btn btn-danger confirm" name="Supprimer" value="Supprimer">
</form>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('.confirm').click(function() {
        return window.confirm("Êtes-vous sur ?");
    });
});
</script>
</html>
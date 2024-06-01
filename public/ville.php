<?php
session_start();
require_once('../include/connexion.php');
require_once('../include/fonction.php');

$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
if($id == 0) {
    header("Location:$url/listeville.php");
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Modifier'])) {
        // Initialisation du message d'erreur
        $_SESSION['MSG_KO'] = '';

      // Contrôles
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
$requete->execute(array($_POST['nom'], $id)); // Utilisez $id au lieu de $_POST['code']
$compteur = $requete->fetch();
if($compteur['cpt'] > 0) {
    $_SESSION['MSG_KO'] .= "Le nom (".$_POST['nom'].") est déjà pris<br />";
}

// Si aucune erreur, exécution de la requête
if (empty($_SESSION['MSG_KO'])) {
    try {
        $requete = $bdd->prepare('update ville
        set nom = :nom,
        codepostal = :codepostal,
        pays = :pays
        where code = :code');
        $requete->execute(array(
            'nom' => $_POST['nom'],
            'codepostal' => $_POST['codepostal'],
            'pays' => $_POST['pays'],
            'code' => $id // Utilisez $id au lieu de $code
        ));

        $_SESSION['MSG_OK'] = "Modification bien enregistrée";
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}
    
    } elseif (isset($_POST['Annuler'])) {
        header("Location: /public/Villes.php");
        exit();
    }

    if (isset($_POST['Supprimer'])) {
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

try {
    $requete = $bdd->prepare('select nom, codepostal, pays from ville where code = ?');
    $requete->execute(array($id));
    $ville = $requete->fetch();
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
    <title>Ville <?php echo $ville['nom']; ?></title>
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<?php
include('../include/menu.php');
afficheMessages();

?>
<div class="container">
<h1>Ville <?php echo isset($ville['nom']) ? $ville['nom'] : ''; ?></h1>
<form method="post">
    <div class="form-group row mb-3">
        <label for="nom" class="col-sm-2 col-form-label">Nom</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="nom" value="<?php echo isset($_POST['nom']) ? $_POST['nom'] : (isset($ville['nom']) ? $ville['nom'] : ''); ?>">
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="codepostal" class="col-sm-2 col-form-label">Code Postal</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="codepostal" value="<?php echo isset($_POST['codepostal']) ? $_POST['codepostal'] : (isset($ville['codepostal']) ? $ville['codepostal'] : ''); ?>">
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="pays" class="col-sm-2 col-form-label">Pays</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="pays" value="<?php echo isset($_POST['pays']) ? $_POST['pays'] : (isset($ville['pays']) ? $ville['pays'] : ''); ?>">
        </div>
    </div>
    <input type="submit" class="btn btn-default" name="Annuler" value="Annuler">
    <input type="submit" class="btn btn-primary" name="Modifier" value="Modifier">
    <input type="submit" class="btn btn-danger confirm" name="Supprimer" value="Supprimer">
</form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    $('.confirm').click(function() {
        return window.confirm("Êtes-vous sur ?");
    });
});
</script>
</body>
</html>
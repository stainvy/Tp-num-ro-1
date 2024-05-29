<?php
require_once('../include/connexion.php');
require_once('../include/fonction.php');$id = (isset($_GET['id']))?$_GET['id']:0;
if($id == 0) {
 header("Location:$url/listefournisseur.php");
 die();
}
try {
 $requete = $bdd->prepare('select nom, adresse1, adresse2, ville, contact,
civilite from fournisseur where code = ?');
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
?>
 <div class="container">
 <h1>fournisseur <?php echo $fournisseur['nom']; ?></h1>
 <!-- Affichage des informations du fournisseur -->
 <form method="post">
    <div class="form-group row mb-3">
        <label for="nom" class="col-sm-2 col-form-label">Nom</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="nom" value="<?php echo $fournisseur['nom']; ?>" readonly>
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="adresse1" class="col-sm-2 col-form-label">Adresse 1</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="adresse1" value="<?php echo $fournisseur['adresse1']; ?>" readonly>
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="adresse2" class="col-sm-2 col-form-label">Adresse 2</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="adresse2" value="<?php echo $fournisseur['adresse2']; ?>" readonly>
        </div>
    </div>
    <div class="form-group row mb-3">
    <label class="col-form-label col-sm-2" for="ville">Ville</label>
    <div class="col-sm-10">
        <?php echo selectVille('ville', $fournisseur['ville']); ?>
    </div>
</div>
    <div class="form-group row mb-3">
        <label for="contact" class="col-sm-2 col-form-label">Contact</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="contact" value="<?php echo $fournisseur['contact']; ?>" readonly>
        </div>
        </div>
    <div class="form-group row mb-3">
        <label for="civilite" class="col-sm-2 col-form-label">Civilité</label>
        <div class="col-sm-10">
        <?php echo selectCivilite('civilite', $fournisseur['civilite']); ?>
            </div>
    </div>
    <div class="form-group row float-right">
 <input type="submit" class="btn btn-default" name="Annuler"
value="Annuler">
 <input type="submit" class="btn btn-primary" name="Modifier"
value="Modifier">
 </div>
</form>
 </body>
</html>
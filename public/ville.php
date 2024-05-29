<?php
require_once('../include/connexion.php');
require_once('../include/fonction.php');

$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
if($id == 0) {
    header("Location:$url/listeville.php");
    die();
}

try {
    $requete = $bdd->prepare('SELECT * FROM ville WHERE code = ?');
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
?>
<div class="container">
    <h1>Ville <?php echo $ville['nom']; ?></h1>
    <!-- Affichage des informations de la ville -->
    <form>
        <div class="form-group row mb-3">
            <label for="nom" class="col-sm-2 col-form-label">Nom</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nom" value="<?php echo $ville['nom']; ?>" readonly>
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="codepostal" class="col-sm-2 col-form-label">Code Postal</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="codepostal" value="<?php echo $ville['codepostal']; ?>" readonly>
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="pays" class="col-sm-2 col-form-label">Pays</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pays" value="<?php echo $ville['pays']; ?>" readonly>
            </div>
        </div>
    </form>
</div>
</body>
</html>
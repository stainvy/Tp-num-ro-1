<?php
// Cette fonction génère un menu déroulant HTML pour sélectionner une ville à partir d'une base de données.
function selectVille($name, $selectedCode = null) {
    // Connexion à la base de données
    $db = new PDO('mysql:host=localhost;dbname=tpnumro1', 'stainvy', 'stainvy');

    // Exécution de la requête SQL pour récupérer toutes les villes
    $result = $db->query('SELECT * FROM ville');

    // Initialisation du code HTML du menu déroulant
    $html = '<select class="form-select" id="' . $name . '" name="' . $name . '">';

    // Parcours des résultats de la requête SQL
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Si le code de la ville correspond au code sélectionné, on ajoute l'attribut "selected" à l'option
        $selected = $row['code'] == $selectedCode ? ' selected="selected"' : '';
        // Ajout de l'option au menu déroulant
        $html .= '<option value="' . $row['code'] . '"' . $selected . '>' . $row['nom'] . '</option>';
    }

    $html .= '</select>';

    return $html;
}

// Cette fonction est similaire à la fonction selectVille, mais elle génère un menu déroulant pour sélectionner une civilité.
function selectCivilite($name, $selectedCode = null) {
    $db = new PDO('mysql:host=localhost;dbname=tpnumro1', 'stainvy', 'stainvy');

    $result = $db->query('SELECT * FROM civilite');

    $html = '<select class="form-select" id="' . $name . '" name="' . $name . '">';

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $selected = $row['code'] == $selectedCode ? ' selected="selected"' : '';
        $html .= '<option value="' . $row['code'] . '"' . $selected . '>' . $row['libelle'] . '</option>';
    }

    $html .= '</select>';

    return $html;
}

// Cette fonction vérifie si le menu actuel correspond au menu passé en paramètre.
// Si c'est le cas, elle renvoie la chaîne de caractères 'active', sinon elle renvoie une chaîne de caractères vide.
function menuActif($menu) {
    $ecran = basename($_SERVER['SCRIPT_FILENAME'], ".php");
    if ($ecran == $menu) {
        return 'active';
    } else {
        return '';
    }
}

// Cette fonction affiche les messages de succès et d'erreur stockés dans la session.
function afficheMessages() {
    $retour = '';

    // Gestion des messages de succès
    if(!empty($_SESSION['MSG_OK'])) {
        $retour .= '<div class="alert alert-success">' . $_SESSION['MSG_OK'] . '</div>'."\n";
        unset($_SESSION['MSG_OK']);
    }

    // Gestion des messages d'erreur
    if(!empty($_SESSION['MSG_KO'])) {
        $retour .= '<div class="alert alert-danger">' . $_SESSION['MSG_KO'] . '</div>'."\n";
        unset($_SESSION['MSG_KO']);
    }

    echo $retour;
}

// Cette fonction génère un formulaire de connexion HTML.
function formulaireLogin() {
    // Le formulaire de connexion est affiché mais n'est pas fonctionnel
    return '
    <form class="form-inline" method="post">
        <div class="form-group form-inline">
            <input type="text" class="form-control" id="login" name="login" placeholder="Identifiant">
            <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
            <button type="submit" class="btn btn-sm btn-primary" name="Connexion">Connexion</button>
        </div>
    </form>';
}
?>
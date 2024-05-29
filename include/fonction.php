<?php
function selectVille($name, $selectedCode = null) {
    $db = new PDO('mysql:host=localhost;dbname=tpnumro1', 'stainvy', 'stainvy');

    $result = $db->query('SELECT * FROM ville');

    $html = '<select class="form-select" id="' . $name . '" name="' . $name . '">';

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $selected = $row['code'] == $selectedCode ? ' selected="selected"' : '';
        $html .= '<option value="' . $row['code'] . '"' . $selected . '>' . $row['nom'] . '</option>';
    }

    $html .= '</select>';

    return $html;
}

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
function menuActif($menu) {
    $ecran = basename($_SERVER['SCRIPT_FILENAME'], ".php");
    if ($ecran == $menu) {
        return 'active';
    } else {
        return '';
    }
}
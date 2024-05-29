<?php
    include_once 'fonction.php';
?>
<nav class="menu">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link <?php echo menuActif('index'); ?>" href="/index.php">
                <img src="chemin_vers_votre_logo.png" alt="Logo" /> 
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo menuActif('Fournisseurs'); ?>" href="/public/Fournisseurs.php">Fournisseurs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo menuActif('Villes'); ?>" href="/public/Villes.php">Villes</a>
        </li>
    </ul>
</nav>
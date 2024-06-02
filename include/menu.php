<?php
    include_once 'fonction.php';
?>
<nav class="menu">
    <ul class="nav">
        <!-- Les liens du menu. La fonction menuActif() est utilisée pour ajouter la classe 'active' au lien de la page actuelle -->
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
    <!-- Affichage du formulaire de connexion -->
    <?php echo formulaireLogin(); ?>
</nav>
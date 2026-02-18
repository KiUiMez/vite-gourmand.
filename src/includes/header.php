<?php
require_once __DIR__ . '/../config/session.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Vite & Gourmand') ?> — Traiteur à Bordeaux</title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription ?? 'Vite & Gourmand, traiteur bordelais depuis 25 ans.') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/public/css/style.css">

    <?php if (isset($pageCss)): ?>
        <link rel="stylesheet" href="/public/css/<?= htmlspecialchars($pageCss) ?>">
    <?php endif; ?>
</head>
<body>

<nav id="navbar" role="navigation" aria-label="Navigation principale">
    <a href="/public/index.php" class="nav-logo" aria-label="Vite et Gourmand - Accueil">
        Vite <span>& Gourmand</span>
    </a>

    <ul class="nav-links">
        <li><a href="/public/index.php">Accueil</a></li>
        <li><a href="/public/menus.php">Nos Menus</a></li>
        <li><a href="/public/contact.php">Contact</a></li>

        <?php if (estConnecte()): ?>
            <li>
                <a href="/public/<?= getRoleConnecte() === 'administrateur' ? 'admin' : (getRoleConnecte() === 'employe' ? 'employe' : 'compte') ?>/dashboard.php">
                    👤 <?= htmlspecialchars(getPrenomConnecte()) ?>
                </a>
            </li>
            <li><a href="/public/deconnexion.php" class="nav-btn">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="/public/connexion.php" class="nav-btn">Connexion</a></li>
        <?php endif; ?>
    </ul>

    <button class="nav-toggle" aria-label="Ouvrir le menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

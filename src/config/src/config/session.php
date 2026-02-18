<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estConnecte(): bool {
    return isset($_SESSION['utilisateur_id']);
}

function getRoleConnecte(): ?string {
    return $_SESSION['role'] ?? null;
}

function aLeDroit(string $role): bool {
    return estConnecte() && getRoleConnecte() === $role;
}

function estAdminOuEmploye(): bool {
    return aLeDroit('administrateur') || aLeDroit('employe');
}

function requireConnexion(): void {
    if (!estConnecte()) {
        header('Location: /public/connexion.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

function requireRole(string $role): void {
    requireConnexion();
    if (!aLeDroit($role)) {
        header('Location: /public/index.php?erreur=acces_refuse');
        exit;
    }
}

function connecterUtilisateur(array $utilisateur): void {
    session_regenerate_id(true);
    $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
    $_SESSION['email'] = $utilisateur['email'];
    $_SESSION['nom'] = $utilisateur['nom'];
    $_SESSION['prenom'] = $utilisateur['prenom'];
    $_SESSION['role'] = $utilisateur['libelle'];
}

function deconnecterUtilisateur(): void {
    session_unset();
    session_destroy();
}

function getPrenomConnecte(): string {
    return $_SESSION['prenom'] ?? 'Visiteur';
}

function getIdConnecte(): ?int {
    return $_SESSION['utilisateur_id'] ?? null;
}

<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT * FROM horaire ORDER BY horaire_id");
    $horaires = $stmt->fetchAll();
} catch (Exception $e) {
    $horaires = [
        ['jour' => 'Lundi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '18:00'],
        ['jour' => 'Mardi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '18:00'],
        ['jour' => 'Mercredi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '18:00'],
        ['jour' => 'Jeudi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '18:00'],
        ['jour' => 'Vendredi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '19:00'],
        ['jour' => 'Samedi', 'heure_ouverture' => '10:00', 'heure_fermeture' => '17:00'],
        ['jour' => 'Dimanche', 'heure_ouverture' => null, 'heure_fermeture' => null],
    ];
}
?>

<footer role="contentinfo">
    <div class="footer-top">
        <div class="footer-brand">
            <h3>Vite <span>& Gourmand</span></h3>
            <p>Traiteur artisanal à Bordeaux depuis 1999. Julie et José vous accompagnent pour tous vos événements avec passion et savoir-faire.</p>
        </div>

        <nav class="footer-col" aria-label="Navigation secondaire">
            <h4>Navigation</h4>
            <ul>
                <li><a href="/public/index.php">Accueil</a></li>
                <li><a href="/public/menus.php">Nos Menus</a></li>
                <li><a href="/public/contact.php">Contact</a></li>
                <?php if (!estConnecte()): ?>
                    <li><a href="/public/connexion.php">Connexion</a></li>
                    <li><a href="/public/inscription.php">Créer un compte</a></li>
                <?php else: ?>
                    <li><a href="/public/compte/dashboard.php">Mon espace</a></li>
                    <li><a href="/public/deconnexion.php">Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="footer-col">
            <h4>Horaires</h4>
            <div class="horaires-list" aria-label="Horaires d'ouverture">
                <?php foreach ($horaires as $horaire): ?>
                    <div class="horaire-item">
                        <span class="jour"><?= htmlspecialchars($horaire['jour']) ?></span>
                        <?php if ($horaire['heure_ouverture']): ?>
                            <span><?= htmlspecialchars($horaire['heure_ouverture']) ?> – <?= htmlspecialchars($horaire['heure_fermeture']) ?></span>
                        <?php else: ?>
                            <span class="ferme">Fermé</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="footer-col">
            <h4>Contact</h4>
            <ul>
                <li><a href="mailto:contact@vitegourmand.fr">contact@vitegourmand.fr</a></li>
                <li><a href="tel:+33500000000">05 00 00 00 00</a></li>
                <li><a href="/public/contact.php">10 Rue du Palais, Bordeaux</a></li>
            </ul>
        </div>
    </div>

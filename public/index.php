<?php
// ============================================================
//  VITE & GOURMAND — Page d'accueil
//  Fichier : public/index.php
// ============================================================

$pageTitle = "Accueil";
$pageDescription = "Vite & Gourmand, traiteur bordelais depuis 25 ans. Menus raffinés pour tous vos événements.";

require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/includes/header.php';

// Récupération des avis validés depuis la base de données
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("
        SELECT a.*, u.prenom, u.nom, DATE_FORMAT(a.created_at, '%M %Y') as date_fr
        FROM avis a
        INNER JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
        WHERE a.statut = 'valide'
        ORDER BY a.created_at DESC
        LIMIT 6
    ");
    $stmt->execute();
    $avis = $stmt->fetchAll();
    
    // Calcul de la note moyenne
    $stmtMoyenne = $pdo->query("SELECT AVG(note) as moyenne, COUNT(*) as total FROM avis WHERE statut = 'valide'");
    $stats = $stmtMoyenne->fetch();
    $noteMoyenne = round($stats['moyenne'], 1);
    $totalAvis = $stats['total'];
    
} catch (Exception $e) {
    // Avis par défaut si erreur BDD
    $avis = [];
    $noteMoyenne = 4.9;
    $totalAvis = 127;
}

// Fonction pour afficher les étoiles
function afficherEtoiles($note) {
    $etoiles = '';
    for ($i = 1; $i <= 5; $i++) {
        $etoiles .= $i <= $note ? '★' : '☆';
    }
    return $etoiles;
}
?>

<!-- ==================== HERO ==================== -->
<main>
    <section class="hero" aria-labelledby="hero-title">
        <div class="hero-grid" aria-hidden="true"></div>
        <div class="hero-circle" aria-hidden="true"></div>

        <div class="hero-content">
            <div class="hero-tag">Traiteur à Bordeaux depuis 1999</div>

            <h1 id="hero-title">
                L'art de<br>
                <em>sublimer</em><br>
                vos événements
            </h1>

            <p>
                Julie et José vous proposent une cuisine raffinée et généreuse,
                élaborée avec des produits locaux soigneusement sélectionnés,
                pour faire de chaque moment un souvenir inoubliable.
            </p>

            <div class="hero-btns">
                <a href="menus.php" class="btn-primary">Découvrir nos menus</a>
                <a href="contact.php" class="btn-ghost">Nous contacter</a>
            </div>
        </div>

        <div class="hero-stats" aria-label="Chiffres clés">
            <div class="stat-item">
                <div class="stat-number">25</div>
                <div class="stat-label">Ans d'expérience</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Événements réalisés</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $noteMoyenne ?></div>
                <div class="stat-label">Note moyenne</div>
            </div>
        </div>

        <div class="scroll-indicator" aria-hidden="true">
            <div class="scroll-line"></div>
            <span>Défiler</span>
        </div>
    </section>

    <!-- ==================== PRÉSENTATION ==================== -->
    <section class="section-presentation reveal" aria-labelledby="presentation-title">
        <div class="presentation-texte">
            <div class="section-tag">
                <span>Notre histoire</span>
            </div>

            <h2 id="presentation-title">
                Une passion,<br>
                <em>deux artisans</em>
            </h2>

            <p>
                Fondée en 1999 par Julie et José, Vite & Gourmand est née d'une
                conviction simple : chaque repas mérite d'être une expérience.
                Depuis 25 ans, nous mettons notre passion au service de vos moments
                de vie, qu'il s'agisse d'un repas de Noël intime ou d'un événement
                d'entreprise d'envergure.
            </p>

            <p>
                Ancrés à Bordeaux, nous travaillons exclusivement avec des
                producteurs locaux et de saison, garantissant une qualité irréprochable
                à chaque prestation. Notre approche artisanale et notre sens du détail
                font de chaque menu une création unique.
            </p>

            <a href="menus.php" class="btn-primary" style="margin-top: 1rem; display: inline-block;">
                Voir nos menus
            </a>
        </div>

        <div class="presentation-image">
            <div class="img-placeholder">
                <span class="img-icon" aria-hidden="true">🍽️</span>
            </div>
            <div class="img-frame" aria-hidden="true"></div>
            <div class="team-badge">
                <div class="team-badge-title">Julie & José</div>
                <div class="team-badge-sub">Fondateurs — Bordeaux</div>
            </div>
        </div>
    </section>

    <!-- ==================== VALEURS ==================== -->
    <section class="section-valeurs reveal" aria-labelledby="valeurs-title">
        <div class="valeurs-header">
            <div class="section-tag" style="justify-content: center;">
                <span>Notre engagement</span>
            </div>
            <h2 id="valeurs-title">Ce qui nous <em>distingue</em></h2>
        </div>

        <div class="valeurs-grid">
            <div class="valeur-card">
                <div class="valeur-number" aria-hidden="true">01</div>
                <span class="valeur-icon" aria-hidden="true">🌿</span>
                <div class="valeur-title">Produits locaux</div>
                <p class="valeur-text">
                    Nous collaborons avec des producteurs bordelais et girondins
                    pour garantir fraîcheur et traçabilité à chaque prestation.
                    Nos circuits courts sont notre fierté.
                </p>
            </div>

            <div class="valeur-card">
                <div class="valeur-number" aria-hidden="true">02</div>
                <span class="valeur-icon" aria-hidden="true">👨‍🍳</span>
                <div class="valeur-title">Savoir-faire artisanal</div>
                <p class="valeur-text">
                    25 ans de métier se ressentent dans chaque assiette.
                    José et Julie préparent chaque menu avec la même attention
                    et la même exigence depuis le premier jour.
                </p>
            </div>

            <div class="valeur-card">
                <div class="valeur-number" aria-hidden="true">03</div>
                <span class="valeur-icon" aria-hidden="true">✨</span>
                <div class="valeur-title">Sur-mesure</div>
                <p class="valeur-text">
                    Chaque événement est unique. Nous adaptons nos prestations
                    à vos contraintes, vos goûts et votre budget pour une
                    expérience parfaitement personnalisée.
                </p>
            </div>
        </div>
    </section>

    <!-- ==================== ÉQUIPE ==================== -->
    <section class="section-equipe reveal" aria-labelledby="equipe-title">
        <div class="equipe-header">
            <div class="section-tag" style="justify-content: center;">
                <span>L'équipe</span>
            </div>
            <h2 id="equipe-title">Les visages de <em>Vite & Gourmand</em></h2>
        </div>

        <div class="equipe-grid">
            <article class="equipe-card">
                <div class="equipe-photo" aria-label="Photo de Julie">
                    <span aria-hidden="true">👩‍🍳</span>
                </div>
                <h3 class="equipe-nom">Julie Martin</h3>
                <div class="equipe-role">Co-fondatrice · Cuisine & Création</div>
                <p class="equipe-bio">
                    Formée à l'École Hôtelière de Bordeaux, Julie est l'âme créative
                    de Vite & Gourmand. Sa passion pour les saveurs régionales et son
                    inventivité font de chaque menu une expérience culinaire mémorable.
                </p>
            </article>

            <article class="equipe-card">
                <div class="equipe-photo" aria-label="Photo de José">
                    <span aria-hidden="true">👨‍🍳</span>
                </div>
                <h3 class="equipe-nom">José Dupont</h3>
                <div class="equipe-role">Co-fondateur · Logistique & Relations clients</div>
                <p class="equipe-bio">
                    José est le garant de la qualité opérationnelle de chaque prestation.
                    Son sens de l'organisation et son relationnel exceptionnel assurent
                    que chaque événement se déroule parfaitement.
                </p>
            </article>
        </div>
    </section>

    <!-- ==================== AVIS CLIENTS ==================== -->
    <section class="section-avis reveal" aria-labelledby="avis-title">
        <div class="avis-header">
            <div class="section-tag" style="justify-content: center;">
                <span>Témoignages</span>
            </div>
            <h2 id="avis-title">Ce que disent <em>nos clients</em></h2>

            <div class="avis-moyenne" aria-label="Note moyenne : <?= $noteMoyenne ?> sur 5, basée sur <?= $totalAvis ?> avis">
                <span class="avis-note-global"><?= $noteMoyenne ?></span>
                <div>
                    <div class="avis-etoiles-global" aria-hidden="true">
                        <?= afficherEtoiles(5) ?>
                    </div>
                    <div class="avis-count">Basé sur <?= $totalAvis ?> avis vérifiés</div>
                </div>
            </div>
        </div>

        <div class="avis-carousel" role="region" aria-label="Carrousel d'avis clients">
            <div class="avis-track" id="avisTrack">

                <?php if (count($avis) > 0): ?>
                    <?php foreach ($avis as $unAvis): ?>
                        <article class="avis-card">
                            <div class="avis-etoiles" aria-label="<?= $unAvis['note'] ?> étoiles sur 5">
                                <?= afficherEtoiles($unAvis['note']) ?>
                            </div>
                            <blockquote class="avis-texte">
                                <?= htmlspecialchars($unAvis['commentaire']) ?>
                            </blockquote>
                            <div class="avis-auteur">
                                <div class="avis-avatar" aria-hidden="true">
                                    <?= strtoupper(substr($unAvis['prenom'], 0, 1)) ?>
                                </div>
                                <div class="avis-auteur-info">
                                    <div class="avis-nom">
                                        <?= htmlspecialchars($unAvis['prenom']) ?> 
                                        <?= htmlspecialchars(substr($unAvis['nom'], 0, 1)) ?>.
                                    </div>
                                    <div class="avis-date"><?= ucfirst($unAvis['date_fr']) ?></div>
                                </div>
                                <span class="avis-badge">Vérifié</span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Avis par défaut si la BDD est vide -->
                    <article class="avis-card">
                        <div class="avis-etoiles" aria-label="5 étoiles sur 5">★★★★★</div>
                        <blockquote class="avis-texte">
                            "Un repas de Noël absolument exceptionnel. Chaque plat était
                            une découverte, la présentation soignée et la livraison ponctuelle.
                            Toute ma famille a été conquise !"
                        </blockquote>
                        <div class="avis-auteur">
                            <div class="avis-avatar" aria-hidden="true">S</div>
                            <div class="avis-auteur-info">
                                <div class="avis-nom">Sophie L.</div>
                                <div class="avis-date">Décembre 2024</div>
                            </div>
                            <span class="avis-badge">Vérifié</span>
                        </div>
                    </article>

                    <article class="avis-card">
                        <div class="avis-etoiles" aria-label="5 étoiles sur 5">★★★★★</div>
                        <blockquote class="avis-texte">
                            "Vite & Gourmand a sublimé notre mariage avec un buffet
                            gastronomique mémorable. Julie et José sont des professionnels
                            à l'écoute, rigoureux et talentueux."
                        </blockquote>
                        <div class="avis-auteur">
                            <div class="avis-avatar" aria-hidden="true">M</div>
                            <div class="avis-auteur-info">
                                <div class="avis-nom">Marc & Claire D.</div>
                                <div class="avis-date">Juin 2024</div>
                            </div>
                            <span class="avis-badge">Vérifié</span>
                        </div>
                    </article>

                    <article class="avis-card">
                        <div class="avis-etoiles" aria-label="5 étoiles sur 5">★★★★★</div>
                        <blockquote class="avis-texte">
                            "Commande passée pour un dîner d'entreprise de 30 personnes.
                            Tout était parfait, du foie gras maison au dessert. Je recommande
                            les yeux fermés !"
                        </blockquote>
                        <div class="avis-auteur">
                            <div class="avis-avatar" aria-hidden="true">T</div>
                            <div class="avis-auteur-info">
                                <div class="avis-nom">Thomas R.</div>
                                <div class="avis-date">Novembre 2024</div>
                            </div>
                            <span class="avis-badge">Vérifié</span>
                        </div>
                    </article>
                <?php endif; ?>

            </div>
        </div>

        <div class="avis-controls" role="tablist" aria-label="Navigation du carrousel">
            <button class="avis-dot active" role="tab" aria-selected="true" aria-label="Avis 1" data-index="0"></button>
            <button class="avis-dot" role="tab" aria-selected="false" aria-label="Avis 2" data-index="1"></button>
        </div>
    </section>
</main>

<script>
// Carousel avis
const track = document.getElementById('avisTrack');
const dots = document.querySelectorAll('.avis-dot');
let current = 0;
let autoPlay;

function goTo(index) {
    current = index;
    const visibles = window.innerWidth < 768 ? 1 : window.innerWidth < 1024 ? 2 : 3;
    const cardW = track.querySelector('.avis-card').offsetWidth + 32;
    track.style.transform = `translateX(-${index * cardW}px)`;
    dots.forEach((d, i) => {
        d.classList.toggle('active', i === index);
        d.setAttribute('aria-selected', i === index);
    });
}

dots.forEach(dot => {
    dot.addEventListener('click', () => {
        clearInterval(autoPlay);
        goTo(parseInt(dot.dataset.index));
        startAuto();
    });
});

function startAuto() {
    autoPlay = setInterval(() => {
        const max = dots.length - 1;
        goTo(current >= max ? 0 : current + 1);
    }, 4000);
}

startAuto();
</script>

<?php require_once __DIR__ . '/../src/includes/footer.php'; ?>

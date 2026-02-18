<?php
define('DB_HOST', 'sql308.infinityfree.com');
define('DB_PORT', '3306');
define('DB_NAME', 'if0_41181243_vitegourmand');
define('DB_USER', 'if0_41181243');
define('DB_PASSWORD', '(Your vPanel Password)');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log("Erreur BDD : " . $e->getMessage());
            die(json_encode(['erreur' => 'Connexion impossible']));
        }
    }
    return $pdo;
}

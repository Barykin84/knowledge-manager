<?php
// On vérifie si la fonction n'existe pas déjà avant de la créer
if (!function_exists('load_env')) {
    function load_env() {
        $path = __DIR__ . '/../.env';
        if (!file_exists($path)) {
            return [];
        }
        return parse_ini_file($path);
    }
}

// On charge tout d'un coup
$config = load_env();


$host    = $config['DB_HOST'];
$db      = $config['DB_NAME'];
$user    = $config['DB_USER'];
$pass    = $config['DB_PASS'];
$charset = $config['DB_CHARSET'];


try {
    $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset={$config['DB_CHARSET']}";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("Erreur de base de données.");
}

// 2. Variables globales pour tes pages (comme TinyMCE)
$tinyKey = $config['TINYMCE_KEY'] ?? ''; 

?>
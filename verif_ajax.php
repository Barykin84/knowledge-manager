<?php
require_once 'db.php';

$type = $_GET['type'] ?? '';
$valeur = $_GET['valeur'] ?? '';

if ($type === 'login') {
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ?");
    $stmt->execute([$valeur]);
    echo $stmt->rowCount() > 0 ? "Ce login est déjà utilisé." : "";
} elseif ($type === 'email') {
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$valeur]);
    echo $stmt->rowCount() > 0 ? "Cet email est déjà utilisé." : "";
}
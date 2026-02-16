<?php
include 'db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM sous_titres WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: index.php');
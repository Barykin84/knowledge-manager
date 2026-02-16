<?php
// Sécurité minimale
if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Aucun fichier valide reçu.']);
    exit;
}

$targetDir = __DIR__ . '/uploads/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true); // Crée le dossier si besoin
}

$filename = basename($_FILES['file']['name']);
$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (!in_array($extension, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Format de fichier non autorisé.']);
    exit;
}

// Nom unique pour éviter les conflits
$newName = uniqid('img_', true) . '.' . $extension;
$destination = $targetDir . $newName;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors du téléchargement.']);
    exit;
}

// Réponse au format attendu par TinyMCE
echo json_encode(['location' => 'uploads/' . $newName]);
<?php
require_once 'db.php';
$message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE token_activation = ?");
    $stmt->execute([$token]);
    $utilisateur = $stmt->fetch();

    if ($utilisateur) {
        // Activation du compte
        $stmt = $pdo->prepare("UPDATE utilisateurs SET est_active = 1, token_activation = NULL WHERE id = ?");
        $stmt->execute([$utilisateur['id']]);

        $message = "Votre compte a bien été activé. Vous pouvez maintenant vous connecter.";
    } else {
        $message = "Lien invalide ou déjà utilisé.";
    }
} else {
    $message = "Aucun token fourni.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Activation du compte</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 30px; }
        .container { background: white; padding: 20px; max-width: 500px; margin: auto; border-radius: 8px; text-align: center; }
        a { color: #007BFF; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Activation du compte</h2>
    <p><?= htmlspecialchars($message) ?></p>

    <?php if (strpos($message, 'activé')): ?>
        <a href="connexion.php">Se connecter</a>
    <?php endif; ?>
</div>

</body>
</html>
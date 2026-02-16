<?php
require_once 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $code = random_int(100000, 999999);
        $stmt = $pdo->prepare("UPDATE utilisateurs SET reset_code = ?, reset_code_expires = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?");
        $stmt->execute([$code, $email]);

        // Envoyer l'email (à adapter selon votre configuration serveur)
        $sujet = "Code de réinitialisation de votre mot de passe";
        $message_email = "Votre code de réinitialisation est :\n\n $code\n\nCe code est valide pendant 15 minutes.";
        $headers = "From: no-reply@bacadem.org";

        mail($email, $sujet, $message_email, $headers);

        header("Location: verifier_code.php?email=" . urlencode($email));
        exit;
    } else {
        $message = "Adresse e-mail non trouvée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" type="text/css" href="style_connexion.css" media="screen"/ >
</head>
<body>



<form method="post">
    <h2 class=form-title>Mot de passe oublié</h2>
    <div class="msg"><?= htmlspecialchars($message) ?></div>
    <input type="email" name="email" placeholder="Votre adresse e-mail" required>
    <input type="submit" value="Envoyer un code">
    <p class="back-to-login">
    <a href="connexion.php">← Retour à la connexion</a>
</p>
</form>



</body>
</html>
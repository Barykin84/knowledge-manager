<?php
session_start();
require_once 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        if ($user['est_active']) {
            $_SESSION['utilisateur_id'] = $user['id'];
            $_SESSION['utilisateur_login'] = $user['login'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Votre compte n'est pas encore activé.";
        }
    } else {
        $message = "Identifiants invalides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="style_connexion.css" media="screen"/ >
    <!-- PWA Manifest -->
<link rel="manifest" href="/manifest.json">

<meta name="theme-color" content="#0055aa">

<!-- Icône PWA -->
<link rel="icon" sizes="192x192" href="/icons/icon-192.png">

<!-- Pour iOS (facultatif mais recommandé) -->
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

</head>
<body>



<form method="post">
    <h2 class=form-title>Connexion</h2>
    <div class="msg"><?= htmlspecialchars($message) ?></div>
    <input type="text" name="login" placeholder="Login" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <input type="submit" value="Se connecter">
     
   <p class="back-to-login"> <a href="inscription.php">Créer un compte</a> | <a href="motdepasse_oublie.php">Mot de passe oublié</a></p>

</form>
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
      .then(reg => console.log('✅ SW v2 actif'))
      .catch(err => console.error('❌ Erreur SW :', err));
  }
</script>
</body>
</html>
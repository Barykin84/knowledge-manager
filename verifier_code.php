<?php
require_once 'db.php';
$message = "";
$email = isset($_GET['email']) ? $_GET['email'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $code = $_POST['code'];

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND reset_code = ? AND reset_code_expires > NOW()");
    $stmt->execute([$email, $code]);
    $user = $stmt->fetch();

    if ($user) {
        header("Location: modifier_motdepasse.php?email=" . urlencode($email));
        exit;
    } else {
        $message = "Code invalide ou expiré.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérifier le code</title>
     <link rel="stylesheet" type="text/css" href="style_connexion.css" media="screen"/ >
</head>
<body>



<form method="post">
    <h2 style="text-align:center;">Vérification du code</h2>
    <div class="msg"><?= htmlspecialchars($message) ?></div>
    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
    <input type="text" name="code" placeholder="Code à 6 chiffres" required>
    <input type="submit" value="Vérifier le code">
</form>

</body>
</html>
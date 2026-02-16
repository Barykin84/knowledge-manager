<?php
require_once 'db.php';
$message = "";
$email = isset($_GET['email']) ? $_GET['email'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];
    $confirmation = $_POST['confirmation'];

    if ($motdepasse !== $confirmation) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{9,}$/', $motdepasse)) {
        $message = "Mot de passe non conforme (9 caractères, A-Z, 0-9, spécial).";
    } else {
        $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ?, reset_code = NULL, reset_code_expires = NULL WHERE email = ?");
        $stmt->execute([$hash, $email]);
        header("Location: connexion.php?reset=success");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le mot de passe</title>
    <link rel="stylesheet" type="text/css" href="style_connexion.css" media="screen"/ >
</head>
<body>

<form method="post">
    <h2 class=form-title>Nouveau mot de passe</h2>
    <div class="msg"><?= htmlspecialchars($message) ?></div>
    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

    <input type="password" name="motdepasse" placeholder="Nouveau mot de passe" id="motdepasse" required>
    <div class="toggle" onclick="togglePassword('motdepasse')">👁️</div>
    <div id="strength"></div>
    <div class="error" id="mdp-error"></div>

    <input type="password" name="confirmation" placeholder="Confirmer le mot de passe" id="confirmation" required>
    <div class="toggle" onclick="togglePassword('confirmation')">👁️</div>
    <div class="error" id="mdp-confirm-error"></div>

    <input type="submit" value="Changer le mot de passe">
    <p class="back-to-login">
    <a href="connexion.php">← Retour à la connexion</a>
</p>
</form>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function updateStrengthBar(password) {
    const bar = document.getElementById('strength');
    let strength = 0;

    if (password.length >= 9) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    bar.className = '';
    if (strength === 0) bar.style.width = "0%";
    else if (strength === 1) bar.classList.add("strength-weak");
    else if (strength <= 3) bar.classList.add("strength-medium");
    else bar.classList.add("strength-strong");
}

document.addEventListener("DOMContentLoaded", function () {
    const mdp = document.getElementById("motdepasse");
    const mdpConfirm = document.getElementById("confirmation");


    mdp.addEventListener("input", function () {
        updateStrengthBar(mdp.value);
        const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{9,}$/;
        document.getElementById("mdp-error").innerText = regex.test(mdp.value) ? "" : "Mot de passe trop faible.";
    });

    mdpConfirm.addEventListener("input", function () {
        const message = mdp.value !== mdpConfirm.value ? "Les mots de passe ne correspondent pas." : "";
        document.getElementById("mdp-confirm-error").innerText = message;
    });
});
</script>

</body>
</html>
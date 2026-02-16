<?php
require_once 'db.php'; // Connexion à la BDD
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['motdepasse'];
  
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16));

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, login, email, mot_de_passe, token_activation) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $login, $email, $hash, $token]);

    // Envoi mail
    $lien = "https://pense.bacadem.org/activation.php?token=$token";
    $sujet = "Activation de votre compte";
    $contenu = "Bonjour $prenom,\n\nCliquez sur ce lien pour activer votre compte : $lien";
    mail($email, $sujet, $contenu, "From: no-reply@bacadem.org");

    $message = "Inscription réussie ! Vérifiez votre email pour activer votre compte.";
/* }
    }*/
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="style_connexion.css" media="screen"/ >
</head>
<body>

<form method="post" id="form-inscription">
    <h2 class=form-title>Créer un compte</h2>
    <div class="msg"><?= htmlspecialchars($message) ?></div>
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>

    <input type="text" name="login" placeholder="Nom d'utilisateur" id="login" required>
    <div class="error" id="login-error"></div>

    <input type="email" name="email" placeholder="Adresse e-mail" id="email" required>
    <div class="error" id="email-error"></div>

    <input type="password" id="password" name="motdepasse" placeholder="Mot de passe (9 caractères alphanumériques)" oninput="updateStrengthBar(this.value)" required>
    <div class="toggle" onclick="togglePassword('password')">👁️</div>
    <div id="strength"><span></span></div>
    <p id="strength-label" style="margin-top: 5px; font-weight: bold;"></p>
    

    <input type="password" name="motdepasse_confirm" placeholder="Confirmation du mot de passe" id="motdepasse_confirm" required>
    <div class="toggle" onclick="togglePassword('motdepasse_confirm')">👁️</div>
    <div class="error" id="mdp-confirm-error"></div>

    <input type="submit" value="S'inscrire">
    <p class="back-to-login"><a href="connexion.php">Déjà inscrit ? Connexion</a></p>
</form>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function updateStrengthBar(password) {
  const span = document.querySelector('#strength span');
  const label = document.getElementById('strength-label');
  let strength = 0;

  if (password.length >= 9) strength++;
  if (/[A-Z]/.test(password)) strength++;
  if (/[0-9]/.test(password)) strength++;
  if (/[^A-Za-z0-9]/.test(password)) strength++;

  if (password.length === 0) {
    span.style.width = "0%";
    span.style.backgroundColor = "#ccc";
    label.textContent = "";
  } else if (strength === 1) {
    span.style.width = "25%";
    span.style.backgroundColor = "red";
    label.textContent = "Faible";
    label.style.color = "red";
  } else if (strength <= 3) {
    span.style.width = "50%";
    span.style.backgroundColor = "orange";
    label.textContent = "Moyen";
    label.style.color = "orange";
  } else {
    span.style.width = "100%";
    span.style.backgroundColor = "green";
    label.textContent = "Fort";
    label.style.color = "green";
  }
}


document.addEventListener("DOMContentLoaded", function () {
    const login = document.getElementById("login");
    const email = document.getElementById("email");
    const mdp = document.getElementById("password");
    const mdpConfirm = document.getElementById("motdepasse_confirm");

    login.addEventListener("blur", function () {
        fetch("verif_ajax.php?type=login&valeur=" + encodeURIComponent(login.value))
            .then(res => res.text())
            .then(data => document.getElementById("login-error").innerText = data);
    });

    email.addEventListener("blur", function () {
        fetch("verif_ajax.php?type=email&valeur=" + encodeURIComponent(email.value))
            .then(res => res.text())
            .then(data => document.getElementById("email-error").innerText = data);
    });
    email.addEventListener("input", function () {
    const emailError = document.getElementById("email-error");
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(email.value)) {
        emailError.innerText = "Adresse e-mail invalide.";
    } else {
        emailError.innerText = "";
    }
    });

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

document.getElementById('form-inscription').addEventListener('submit', function (e) {
  const pwd = document.getElementById('password').value;
  const confirm = document.getElementById('motdepasse_confirm').value;

  if (pwd !== confirm) {
    e.preventDefault();
    document.getElementById('mdp-confirm-error').textContent = 'Les mots de passe ne correspondent pas.';
    document.getElementById('mdp-confirm-error').style.color = 'red';
  }
});

</script>

</body>
</html>
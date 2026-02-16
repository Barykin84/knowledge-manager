<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bacadem</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#0055aa">
  <link rel="apple-touch-icon" href="/icons/icon-192.png">
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f2f2f2;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: sans-serif;
    }

    .go-button {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background-color: #0055aa;
      color: white;
      font-size: 2rem;
      font-weight: bold;
      border: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      transition: background 0.2s;
    }

    .go-button:hover {
      background-color: #003f80;
    }
  </style>
</head>
<body>

  <a href="/connexion.php">
    <button class="go-button">GO</button>
  </a>

  <script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
      .then(reg => console.log('✅ SW v2 actif'))
      .catch(err => console.error('❌ Erreur SW :', err));
  }
</script>

</body>
</html>

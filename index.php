<?php 
include 'session.php';
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  
  <title>Encyclopédie</title>

  <link rel="stylesheet" href="style.css">
  <!-- PWA Manifest -->
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0055aa">

<!-- Icône PWA -->
<link rel="icon" sizes="192x192" href="/icons/icon-192.png">

<!-- Pour iOS (facultatif mais recommandé) -->
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <style>
    body { display: flex; font-family: Arial, sans-serif; }
    .left { width: 30%; padding: 10px; background: #f0f0f0; }
    .right { width: 70%; padding: 10px; }
    ul { list-style: none; padding-left: 15px; }
    li { margin-bottom: 5px; }
  </style>
  
</head>
<body>
  <?php
  $data = null;
  if (isset($_GET['sous_id'])) {
        $stmt = $pdo->prepare("SELECT a.titre, s.sous_titre, s.contenu, s.id, a.id AS aid FROM sous_titres s JOIN articles a ON s.article_id = a.id WHERE s.id = ?");
        $stmt->execute([$_GET['sous_id']]);
        $data = $stmt->fetch();
  }
    ?>

 <div class="left">
    <?php
        // Toujours afficher "Ajouter" et "Déconnexion"
        echo " <a href='?page=1'><img src='images/ajouter.png' width=30 title='Ajouter'></a>";
        
        // N'afficher "Modifier" et "Supprimer" QUE si un article est sélectionné ($data n'est pas null)
        if ($data) {
            echo "<a href='?id={$data['id']}&page=2'><img src='images/modifier.png' width=27 title='Modifier'></a>"; 
            echo "<a href='supprimer_sous_article.php?id={$data['id']}' onclick='return confirm(\"Supprimer cet article ?\")'><img src='images/supprimer.png' width=30 title='Supprimer Article'></a>";
        }
        
        echo "<a href='logout.php'><img src='images/deconnexion.png' width=30 title='Deconnexion'></a>";
    ?>
    
    <input type="text" id="search" placeholder="Rechercher..." onkeyup="rechercher()">
    <div class="alphabet">
      <?php foreach (range('A', 'Z') as $l): ?>
        <a href="#" class="letter-filter"><?= $l ?></a>
      <?php endforeach; ?>
    </div>
    <div id="resultats">
      <div id="results"><?php include 'liste.php'; ?></div>
    </div>
  </div>

  <div class="right">
    <?php
    // Choix de la page à afficher
   switch($_GET['page'] ?? 0) {
    case 1:
        include('ajouter_article.php');
        break;
    case 2:
        include('modifier_article.php');
        break;
}
    // Affichage aléatoire ou article ciblé
    
if (!empty($data) && empty($_GET['page'])){
        echo "<h2>{$data['titre']}</h2><h3>{$data['sous_titre']}</h3><div>{$data['contenu']}</div>";
        echo "<hr>";
        echo " <a href='?page=1'><img src=images/ajouter.png width=30 title=Ajouter></a> ";
        echo "<a href='?id={$data['id']}&page=2'><img src=images/modifier.png width=27 title=Modifier></a> "; 
        echo "<a href='supprimer_sous_article.php?id={$data['id']}' onclick='return confirm(\"Supprimer cette pagearticle ?\")'><img src=images/supprimer.png width=30 title=Supprimer Article></a> ";
        echo "<a href='supprimer_article.php?id={$data['aid']}' onclick='return confirm(\"Supprimer article et les sous articles? ?\")'><img src=images/supprimerall.png width=30 title=SupprimerTous></a> ";
        echo"<hr>";
      }
  elseif (empty($_GET['page'])){
     echo "<div style='
    display: flex; 
    justify-content: center; 
    align-items: center; 
    height: 100vh; 
    text-align: center;'>
    <h1>Bienvenue sur votre espace de gestion de contenu</h1>
    </div>";
}
    ?>
  </div>
  <script src="script.js"></script>
  <script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
      .then(reg => console.log('✅ SW v2 actif'))
      .catch(err => console.error('❌ Erreur SW :', err));
  }
</script>

</body>
</html>
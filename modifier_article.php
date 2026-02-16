<?php
include 'session.php';
include 'db.php';

$id = $_GET['id'] ?? null;
$user_id = $_SESSION['utilisateur_id'];

if (!$id) { die("<center>Aucun article spécifié.</center>"); }

// 🔐 Sélectionner uniquement si l’article appartient à l’utilisateur connecté
$stmt = $pdo->prepare("SELECT s.*, a.titre, a.id_utilisateur, a.id AS article_id 
                       FROM sous_titres s 
                       JOIN articles a ON s.article_id = a.id 
                       WHERE s.id = ? AND a.id_utilisateur = ?");
$stmt->execute([$id, $user_id]);
$data = $stmt->fetch();

if (!$data) { die("⚠️ Sous-article introuvable ou accès non autorisé."); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $sous_titre = $_POST['sous_titre'];
    $contenu = $_POST['contenu'];

    // Mise à jour : seulement si l'article appartient à l'utilisateur
    $pdo->prepare("UPDATE articles SET titre = ? WHERE id = ? AND id_utilisateur = ?")
        ->execute([$titre, $data['article_id'], $user_id]);

    $pdo->prepare("UPDATE sous_titres SET sous_titre = ?, contenu = ? WHERE id = ?")
        ->execute([$sous_titre, $contenu, $id]);

    echo "<script>window.location.href = 'index.php?sous_id=$id';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Modifier un article</title>
    <!-- TinyMCE CDN (version gratuite, sans API Key) -->
  <script src="https://cdn.tiny.cloud/1/<?php echo $tinyKey; ?>/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
    selector: 'textarea', // Tous les <textarea> auront l'éditeur
    plugins: 'link image media table code lists',
    toolbar: 'file | undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code',
    height: 400,

    /* Permet de charger les fichiers locaux (images) */
    automatic_uploads: true,
    images_upload_url: 'upload_image.php',
    file_picker_types: 'image',

    file_picker_callback: function (cb, value, meta) {
      var input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', (meta.filetype === 'image') ? 'image/*' : '*');

      input.onchange = function () {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function () {
          cb(reader.result, { title: file.name });
        };
        reader.readAsDataURL(file);
      };

      input.click();
      }
});
</script>
</head>
<?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'){ ?>
<body>
  <h2>Modifier un article</h2>
  <form method="post">
    <label>Titre général :</label><br>
    <input type="text" name="titre" value="<?= htmlspecialchars($data['titre']) ?>" required><br><br>

    <label>Sous-titre :</label><br>
    <input type="text" name="sous_titre" value="<?= htmlspecialchars($data['sous_titre']) ?>" required><br><br>

    <label>Contenu :</label><br>
    <textarea name="contenu" rows="10" cols="60"><?= htmlspecialchars($data['contenu']) ?></textarea><br><br>

    <button type="submit">Enregistrer</button>
  </form>
</body>
<?php } ?>
</html>
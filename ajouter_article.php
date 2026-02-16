<?php
include 'session.php';
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'] === 'autre' ? $_POST['autre_titre'] : $_POST['titre'];
    $sous_titre = $_POST['sous_titre'];
    $contenu = $_POST['contenu'];
    $utilisateur_id = $_SESSION['utilisateur_id'];

    $stmt = $pdo->prepare("SELECT id, titre FROM articles WHERE titre = ? AND id_utilisateur = ?");
    $stmt->execute([$titre, $utilisateur_id]);
    $article = $stmt->fetch();

    if (!$article) {
        $stmt = $pdo->prepare("INSERT INTO articles (titre, id_utilisateur) VALUES (?, ?)");
        $stmt->execute([$titre, $utilisateur_id]);
        $article_id = $pdo->lastInsertId();
    } else {
        $article_id = $article['id'];
    }

    $stmt = $pdo->prepare("INSERT INTO sous_titres (article_id, sous_titre, contenu, id_utilisateur) VALUES (?, ?, ?, ?)");
    $stmt->execute([$article_id, $sous_titre, $contenu, $utilisateur_id]);
    $article_last = $pdo->lastInsertId();

    echo "<script>window.location.href = 'index.php?sous_id=$article_last';</script>";
    exit;
}

$stmt = $pdo->prepare("SELECT titre FROM articles WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$titres_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titres = [];
foreach ($titres_raw as $row) {
    $titre = $row['titre'];
    if (!in_array($titre, $titres)) {
        $titres[] = $titre;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un article</title>

  <!-- Select2 CSS + jQuery + JS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- TinyMCE -->
   
  <script src="https://cdn.tiny.cloud/1/<?php echo $tinyKey; ?>/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'link image media table code lists',
      toolbar: 'file | undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code',
      height: 400,
      automatic_uploads: true,
      images_upload_url: 'upload_image.php',
      file_picker_types: 'image',
      file_picker_callback: function (cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', meta.filetype === 'image' ? 'image/*' : '*');
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
<body>
  <h2>Ajouter un article</h2>
  <form method="post">
    <label for="titre_select">Titre général :</label><br>
    <select name="titre" id="titre_select" class="custom-select" required onchange="toggleAutreTitre()" style="width: 100%;">
      <?php foreach ($titres as $titre): ?>
        <option value="<?= htmlspecialchars($titre) ?>"><?= htmlspecialchars($titre) ?></option>
      <?php endforeach; ?>
      <option value="autre">Nouveau titre</option>
    </select><br><br>

    <div id="autre_titre_container" style="display: none;">
      <label for="autre_titre">Nouveau titre :</label><br>
      <input type="text" name="autre_titre" id="autre_titre">
    </div><br>

    <label>Sous-titre :</label><br>
    <input type="text" name="sous_titre" required><br><br>

    <label>Contenu :</label><br>
    <textarea name="contenu" rows="10" cols="60"></textarea><br><br>

    <button type="submit">Ajouter</button>
  </form>

  <script>
    function toggleAutreTitre() {
      const select = document.getElementById('titre_select');
      const container = document.getElementById('autre_titre_container');
      const autreInput = document.getElementById('autre_titre');

      if (select.value === 'autre') {
        container.style.display = 'block';
        autreInput.setAttribute('required', 'required');
      } else {
        container.style.display = 'none';
        autreInput.removeAttribute('required');
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      $('#titre_select').select2(); // 🧠 Active Select2
      toggleAutreTitre();
    });
  </script>
</body>
</html>

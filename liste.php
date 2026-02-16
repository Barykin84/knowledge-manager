<?php
include 'session.php';
require_once 'db.php';

$q = $_GET['q'] ?? '';
$letter = $_GET['letter'] ?? '';
$id_utilisateur = $_SESSION['utilisateur_id'];
$params = [];
$sql = "SELECT a.id AS article_id, a.titre, st.id, st.sous_titre 
        FROM articles a 
        JOIN sous_titres st ON a.id = st.article_id 
        WHERE a.id_utilisateur = ?";

$params[] = $id_utilisateur;

if ($q) {
    $sql .= " AND (a.titre LIKE ? OR st.sous_titre LIKE ? OR st.contenu LIKE ?)";
    $params[] = "%$q%";
    $params[] = "%$q%";
    $params[] = "%$q%";
} elseif ($letter) {
    $sql .= " AND st.sous_titre LIKE ?";
    $params[] = "$letter%";
}

$sql .= " ORDER BY a.titre, st.sous_titre";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$current = null;
$idx = 0; // pour des IDs uniques (ARIA)
foreach ($stmt as $row) {
    if ($current !== $row['titre']) {
        // fermer le panneau précédent si ouvert
        if ($current !== null) echo "</ul></div>";

        $current = $row['titre'];
        $idx++;

        // conteneur d’un item d’accordéon
        echo '<div class="acc-item">';

        // Titre cliquable (bouton)
        echo '<button class="acc-title" '
            .'type="button" '
            .'aria-expanded="false" '
            .'aria-controls="panel-'.$idx.'">'
            . htmlspecialchars($current)
            . '</button>';

        // Panneau (la liste des sous-titres)
        echo '<ul id="panel-'.$idx.'" class="acc-panel">';
    }

    echo "<li><a href='?sous_id=".(int)$row['id']."'>"
        . htmlspecialchars($row['sous_titre'])
        . "</a></li>";
}
// fermer le dernier panneau si nécessaire
if ($current) echo "</ul></div>";

?>
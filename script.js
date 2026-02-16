document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("search");
  const results = document.getElementById("results");
  const articleView = document.getElementById("articleView");
  const formContainer = document.getElementById("formulaireAjout");
 

  function loadArticle(id) {
    fetch("affiche.php?id=" + id).then(r => r.text()).then(html => {
      articleView.innerHTML = html;
      formContainer.innerHTML = "";
    });
  }

  function loadListe(q = "", letter = "") {
    fetch(`liste.php?q=${q}&letter=${letter}`)
      .then(r => r.text()).then(html => results.innerHTML = html);
  }

  searchInput.addEventListener("input", () => loadListe(searchInput.value));
  document.querySelectorAll(".letter-filter").forEach(l => {
    l.addEventListener("click", e => {
      e.preventDefault();
      loadListe("", l.textContent);
    });
  });

  document.addEventListener("click", e => {
    if (e.target.matches(".subtitle-link")) {
      e.preventDefault();
      loadArticle(e.target.dataset.id);
    } else if (e.target.matches("#btnAdd")) {
      fetch("form_add.php").then(r => r.text()).then(html => {
        formContainer.innerHTML = html;
        articleView.innerHTML = "";
      });
    }
  });

  if (randomId) loadArticle(randomId);
});



document.addEventListener('DOMContentLoaded', function () {
  const container = document.querySelector('.left'); // ta colonne gauche

  container.addEventListener('click', function (e) {
    const btn = e.target.closest('.acc-title');
    if (!btn) return;

    // Trouver le panneau ciblé par ce bouton
    const panelId = btn.getAttribute('aria-controls');
    const panel = document.getElementById(panelId);

    const isOpen = btn.getAttribute('aria-expanded') === 'true';

    // Fermer tous les autres panneaux
    document.querySelectorAll('.acc-title[aria-expanded="true"]').forEach(function (openBtn) {
      if (openBtn !== btn) {
        openBtn.setAttribute('aria-expanded', 'false');
        const other = document.getElementById(openBtn.getAttribute('aria-controls'));
        if (other) other.style.display = 'none';
      }
    });

    // Basculer l’état du panneau cliqué
    btn.setAttribute('aria-expanded', String(!isOpen));
    panel.style.display = isOpen ? 'none' : 'block';
  });
});


// Fonction pour changer d'onglet de stats
export function switchStats(toTab) {
  // Récupération des éléments
  const btnPrepa = document.getElementById("btn-prepa");
  const btnAttente = document.getElementById("btn-attente");
  const contentPrepa = document.getElementById("content-prepa");
  const contentAttente = document.getElementById("content-attente");

  // Classes pour l'état actif et inactif des boutons
  const activeClasses = ["text-black", "border-black"];
  const inactiveClasses = ["text-black/40", "border-transparent"];

  // Mises à jour des données
  if (toTab === "attente") {
    contentAttente.classList.remove("hidden");
    contentPrepa.classList.add("hidden");

    btnAttente.classList.add(...activeClasses);
    btnAttente.classList.remove(...inactiveClasses);

    btnPrepa.classList.add(...inactiveClasses);
    btnPrepa.classList.remove(...activeClasses);
  } else {
    contentPrepa.classList.remove("hidden");
    contentAttente.classList.add("hidden");

    btnPrepa.classList.add(...activeClasses);
    btnPrepa.classList.remove(...inactiveClasses);

    btnAttente.classList.add(...inactiveClasses);
    btnAttente.classList.remove(...activeClasses);
  }
}

// Logique d'ouverture du menu déroulant des commandes archivées
export function toggleArchivedOrders() {
  const list = document.getElementById("archiveList");
  const icon = document.getElementById("archiveIcon");

  // Gestion de la visibilité de la liste
  if (list && icon) {
    list.classList.toggle("hidden");
    icon.classList.toggle("rotate-180");
  }
}

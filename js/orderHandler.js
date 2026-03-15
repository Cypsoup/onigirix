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
  const list = document.getElementById("archived-orders-list");
  const icon = document.getElementById("archived-orders-icon");

  // Gestion de la visibilité de la liste
  if (list && icon) {
    list.classList.toggle("hidden");
    icon.classList.toggle("rotate-180");
  }
}

export function toggleAddOrderPanel(todo) {
  const panel = document.getElementById("add-order-panel");
  const overlay = document.getElementById("add-order-panel-overlay");

  if (todo === "open") {
    panel.classList.remove("translate-x-full");
    overlay.classList.remove("hidden");
    setTimeout(() => overlay.classList.add("opacity-100"), 10);
  } else {
    panel.classList.add("translate-x-full");
    overlay.classList.remove("opacity-100");
    setTimeout(() => overlay.classList.add("hidden"), 10);
  }
}



export function updateOrderStatus(orderId, currentStatus) {
  let nextStatus = "";
  if (currentStatus === "attente") nextStatus = "prepa";
  else if (currentStatus === "prepa") nextStatus = "pret";
  else if (currentStatus === "pret") nextStatus = "archive";

  const formData = new FormData();
  formData.append("orderId", orderId);
  formData.append("newStatus", nextStatus);

  fetch("update_status.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.reload();
      } else {
        alert("Erreur lors de la mise à jour");
      }
    });
}
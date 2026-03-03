export function showToast(message, type = "success") {
  const toast = document.createElement("div");

  // Couleur
  const bgColor = type === "error" ? "bg-red-600" : "bg-[#22C55E]";

  // Style du cadre
  toast.className = `
        fixed top-5 left-1/2 -translate-x-1/2 
        ${bgColor} text-white px-8 py-4 
        border-4 border-black font-black uppercase tracking-widest text-sm
        shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]
        z-[9999] transition-all duration-300 transform -translate-y-24 opacity-0
        flex items-center gap-3
    `;

  // Icône à ajouter
  const iconName = type === "error" ? "alert-circle" : "check-circle";
  toast.innerHTML = `
        <i data-lucide="${iconName}" class="w-5 h-5"></i>
        <span>${message}</span>
    `;

  document.body.appendChild(toast);

  if (window.lucide) lucide.createIcons();

  // Animation d'entrée
  requestAnimationFrame(() => {
    toast.classList.remove("-translate-y-24", "opacity-0");
  });

  // Animation de sortie après 3.5 secondes
  setTimeout(() => {
    toast.classList.add("-translate-y-24", "opacity-0");
    setTimeout(() => toast.remove(), 300);
  }, 2500);
}

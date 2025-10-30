const app = document.getElementById('app');
let currentSearchTerm = '';

app.addEventListener('click', (e) => {
    const businessCard = e.target.closest('.business-card');
    if (businessCard) return renderBusinessPage(businessCard.dataset.id);

    const backButton = e.target.closest('#back-button');
    if (backButton) return renderHome(currentSearchTerm);
});

// Inicializa la página
renderHome();

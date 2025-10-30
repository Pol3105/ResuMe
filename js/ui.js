// Función para renderizar estrellas
function getStarRating(rating) {
    let stars = '';
    for (let i = 0; i < 5; i++) {
        stars += `<span class="stars">${i < rating ? '★' : '☆'}</span>`;
    }
    return stars;
}

// Render de página principal
function renderHome(searchTerm = '') {
    currentSearchTerm = searchTerm.toLowerCase().trim();

    const filteredBusinesses = currentSearchTerm
        ? mockData.businesses.filter(b => 
            b.name.toLowerCase().includes(currentSearchTerm) || 
            b.category.toLowerCase().includes(searchTerm)
          )
        : mockData.businesses;

    let businessListHTML = '';
    if (filteredBusinesses.length > 0) {
        businessListHTML = filteredBusinesses.map(b => `
            <div class="business-card bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer fade-in" data-id="${b.id}">
                <h2 class="text-xl font-semibold text-indigo-600">${b.name}</h2>
                <p class="text-gray-600">${b.category}</p>
            </div>
        `).join('');
    } else {
        businessListHTML = `<p class="text-gray-500 text-center col-span-1 md:col-span-2">No se encontraron negocios.</p>`;
    }

    app.innerHTML = `
        <header class="text-center mb-8 fade-in">
            <h1 class="text-4xl font-bold text-gray-800">Reseñ<span class="text-indigo-600">.IA</span></h1>
            <p class="text-lg text-gray-600">Tu asistente de reseñas inteligente</p>
        </header>

        <div class="mb-8">
            <input type="search" id="search-input" value="${searchTerm}" placeholder="Busca un negocio..." class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">${businessListHTML}</div>
    `;

    document.getElementById('search-input').addEventListener('input', e => renderHome(e.target.value));
}

// Render de página de negocio
function renderBusinessPage(businessId) {
    const business = mockData.businesses.find(b => b.id === businessId);
    const reviews = mockData.reviews.filter(r => r.businessId === businessId);
    const summary = mockData.aiSummaries[businessId] || 'No hay resumen disponible.';

    const totalRating = reviews.reduce((acc, r) => acc + r.rating, 0);
    const averageRating = reviews.length ? (totalRating / reviews.length).toFixed(1) : 'N/A';

    const reviewsHTML = reviews.map(r => `
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-semibold">${r.user}</h4>
                <div class="text-sm">${getStarRating(r.rating)}</div>
            </div>
            <p class="text-gray-700">${r.comment}</p>
        </div>
    `).join('');

    app.innerHTML = `
        <div class="fade-in">
            <button id="back-button" class="mb-4 text-indigo-600 hover:text-indigo-800 font-medium">&larr; Volver a la búsqueda</button>
            <h1 class="text-3xl font-bold mb-2">${business.name}</h1>
            <p class="text-xl text-gray-600 mb-6">${business.category}</p>

            <div class="bg-white p-6 rounded-lg shadow-lg mb-8 border border-indigo-100">
                <h2 class="text-2xl font-semibold mb-3">Resumen de IA</h2>
                <div class="flex items-center mb-4 text-lg">
                    <span class="font-bold mr-2">Rating Promedio:</span>
                    <span class="stars text-xl mr-2">${getStarRating(Math.round(averageRating))}</span>
                    <span class="font-bold text-gray-800">${averageRating} / 5.0</span>
                </div>
                <p class="text-gray-700 leading-relaxed">${summary}</p>
            </div>

            <div>
                <h2 class="text-2xl font-semibold mb-4">Todas las Reseñas (${reviews.length})</h2>
                <div class="space-y-4">${reviewsHTML}</div>
            </div>
        </div>
    `;
}

<?php
    require 'includes/app.php'; // ya carga la DB y clases

    // Obtener término de búsqueda
    $searchTerm = $_GET['search'] ?? '';

    // Traer todos los negocios
    $negocios = Business::all();

    // Filtrar si hay búsqueda
    if($searchTerm){
        $negocios = array_filter($negocios, function($b) use ($searchTerm){
            return stripos($b->name, $searchTerm) !== false || stripos($b->category, $searchTerm) !== false;
        });
    }

    incluirTemplate('header');
?>

    <body>

        <!-- Contenedor principal -->
        <div class="main-app-container">
            
            <header class="app-header">
                <!-- Título estilizado con clase semántica -->
                <h1 class="main-title">Resu<span class="main-title-accent">Me</span></h1>
                <p class="subtitle">Your Smart Review Assistant</p>
            </header>

            <!-- Barra de búsqueda estilizada -->
            <form method="get" class="search-form">
                <input 
                    type="search" 
                    name="search" 
                    value="<?php echo htmlspecialchars($searchTerm); ?>"
                    placeholder="Search for a business by name or category..." 
                    class="search-input"
                >
            </form>

            <!-- Resultados (Grid de Tailwind) -->
            <div class="results-grid">
                
                <?php if(count($negocios) > 0): ?>
                    <?php foreach($negocios as $b): ?>
                        <!-- Tarjeta de Negocio (Business Card) -->
                        <a href="business.php?id=<?php echo $b->id; ?>" 
                        class="business-card fade-in">
                            
                            <h2 class="card-title">
                                <?php echo htmlspecialchars($b->name); ?>
                            </h2>
                            
                            <p class="card-category">
                                <?php echo htmlspecialchars($b->category); ?>
                            </p>
                            
                            <div class="card-rating">
                                <!-- showRating() usa la clase .card-rating internamente -->
                                <?php echo $b->showRating(); ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Mensaje de no resultados -->
                    <p class="no-results">
                        No results found for "<?php echo htmlspecialchars($searchTerm); ?>".
                    </p>
                <?php endif; ?>

            </div> <!-- Fin del grid -->
        </div> <!-- Fin del contenedor principal -->
    </body>


    </html>

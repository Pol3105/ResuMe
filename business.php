<?php
    require 'includes/app.php';

    $id = $_GET['id'] ?? 0;
    $negocio = Business::find($id);

    if(!$negocio){
        echo "Negocio no encontrado";
        exit;
    }

    $reviews = $negocio->withReviews();

    // Primero administro y pongo todas las reseñas en un solo string
    
    $allComments = '';
    foreach($reviews as $r){
        $allComments .= $r->comment . ". ";
    }


    $resumenAI = resumirResenas($allComments);


    incluirTemplate('header');

?>

<body class="bg-gray-100 text-gray-900 min-h-screen">

    <div class="detail-container fade-in">

        <!-- Botón Volver -->
        <a href="index.php" class="back-button">&larr; Back</a>

        <h1 class="business-title"><?php echo htmlspecialchars($negocio->name); ?></h1>
        <p class="business-category"><?php echo htmlspecialchars($negocio->category); ?></p>

        <!-- Resumen IA simulado -->
        <div class="summary-card">
            <h2 class="card-title">AI Summary</h2>
            <div class="rating-info">
                <span class="rating-label">Average Rating:</span>
                <span class="card-rating"><?php echo $negocio->showRating(); ?></span>
                <span class="rating-value">&nbsp;<?php echo $negocio->ratingPromedio(); ?> / 5.0</span>
            </div>
            <p class="summary-text">
                <?php echo htmlspecialchars($resumenAI); ?>
            </p>
        </div>

        <!-- Reseñas individuales -->
        <div class="reviews-section">
            <h2 class="section-title">All Reviews (<?php echo count($reviews); ?>)</h2>
            <div class="reviews-list">
                <?php foreach($reviews as $r): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <h4 class="review-user"><?php echo htmlspecialchars($r->user_name); ?></h4>
                            <div class="card-rating"><?php echo $r->showRating(); ?></div>
                        </div>
                        <p class="review-text"><?php echo htmlspecialchars($r->comment); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</body>
</html>

<?php

require_once 'Reviews.php'; // Para poder usar la clase Reviews
require_once __DIR__ . '/../includes/config/huggingface.php';


class Business {

    // Base de datos
    protected static $db;
    protected static $columnas_DB = ['id','name','category','summary','actual_reviews','total_reviews'];

    // Propiedades del objeto
    public $id;
    public $name;
    public $category;
    public $summary;
    public $actual_reviews;
    public $total_reviews;

    // Constructor
    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->category = $args['category'] ?? '';
        $this->summary = $args['summary'] ?? '';
        $this->actual_reviews = $args['actual_reviews'] ?? 0;
        $this->total_reviews = $args['total_reviews'] ?? 0;
    }

    // Conectar base de datos
    public static function setDB($database){
        self::$db = $database;
    }

    // --- FUNCIONES DE BASE DE DATOS ---

    // Traer todos los negocios
    public static function all(){
        $query = "SELECT * FROM businesses ORDER BY name ASC";
        $resultado = self::$db->query($query);
        $negocios = [];
        while($row = $resultado->fetch_assoc()){
            $negocios[] = new self($row);
        }
        return $negocios;
    }

    // Traer un negocio por id
    public static function find($id){
        $id = (int)$id;
        $query = "SELECT * FROM businesses WHERE id = $id LIMIT 1";
        $resultado = self::$db->query($query);
        $row = $resultado->fetch_assoc();
        return $row ? new self($row) : null;
    }

    // Guardar un negocio nuevo
    public function save(){
        $name = self::$db->real_escape_string($this->name);
        $category = self::$db->real_escape_string($this->category);

        $query = "INSERT INTO businesses (name, category) VALUES ('$name', '$category')";
        $resultado = self::$db->query($query);
        if($resultado){
            $this->id = self::$db->insert_id;
        }
        return $resultado;
    }

    // Actualizar negocio existente
    public function update(){
        if(!$this->id) return false;

        $name = self::$db->real_escape_string($this->name);
        $category = self::$db->real_escape_string($this->category);

        $query = "UPDATE businesses SET name='$name', category='$category' WHERE id = {$this->id}";
        return self::$db->query($query);
    }

    // Borrar negocio
    public function delete(){
        if(!$this->id) return false;
        $query = "DELETE FROM businesses WHERE id = {$this->id}";
        return self::$db->query($query);
    }

    // Obtener reseñas asociadas a este negocio
    public function withReviews(){
        if(!$this->id) return [];
        return Reviews::whereBusiness($this->id);
    }

    // Calcular rating promedio del negocio
    public function ratingPromedio(){
        $resenas = $this->withReviews();
        if(count($resenas) === 0) return 'N/A';
        $total = 0;
        foreach($resenas as $r){
            $total += $r->rating;
        }
        return round($total / count($resenas), 1);
    }

    // Mostrar estrellas basado en rating promedio
    public function showRating(){
        $rating = $this->ratingPromedio();
        if($rating === 'N/A') return 'N/A';

        $stars = '';
        $rating = round($rating);
        for($i = 0; $i < 5; $i++){
            $stars .= $i < $rating ? '★' : '☆';
        }
        return $stars;
    }


    function resumirResenas($reviewsText) {
        // Limpiamos nombres y estrellas para que la IA solo vea los comentarios
        $cleanReviews = preg_replace('/^[A-Za-z\s]+\.?\s*(★|☆)+/m', '', $reviewsText); 
        $cleanReviews = trim($cleanReviews);

        $prompt = "Summarize the following customer reviews in 2-3 sentences. "
        . "Focus on positives, common complaints, and overall sentiment. "
        . "Only return the summary, do not repeat instructions or labels:\n\n"
        . $cleanReviews;


        $data = json_encode([
            "inputs" => $prompt
        ]);

        $url = "https://router.huggingface.co/hf-inference/models/philschmid/bart-large-cnn-samsum";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . HF_TOKEN,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        if(curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return "CURL Error: " . $error_msg;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        // Hugging Face devuelve el resumen en ['summary_text'] para este modelo
        $summary = $result[0]['summary_text'] ?? "No se pudo generar resumen.";


        $summary = preg_replace(
            '/^Summarize the customer reviews.*?\.\s*/i',
            '',
            $summary
        );




        return $summary;
    }

    private function updateSummary(){
        if(!$this->id) return false;

        $reviews = $this->withReviews();
        // Primero administro y pongo todas las reseñas en un solo string
        
        $allComments = '';
        foreach($reviews as $r){
            $allComments .= $r->comment . ". ";
        }

        $resumenAI = resumirResenas($allComments);

        // Escapar para seguridad
        $resumenAI = self::$db->real_escape_string($resumenAI);
        

        $query = "UPDATE businesses SET summary='$resumenAI' WHERE id = {$this->id}";
        $query2 = "UPDATE businesses SET actual_reviews='$this->total_reviews' WHERE id = {$this->id}";
        
        $resultado = self::$db->query($query);
        $resultado2 = self::$db->query($query2);
        
    }


    public function actualizarResumenAI(){
        
        if( $this->actual_reviews != $this->total_reviews || empty($this->summary)){
            $this->updateSummary();
            return true;
        }
        else {
            return false;
        }
    }
    
}
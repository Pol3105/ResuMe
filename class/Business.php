<?php

require_once 'Reviews.php'; // Para poder usar la clase Reviews

class Business {

    // Base de datos
    protected static $db;
    protected static $columnas_DB = ['id','name','category'];

    // Propiedades del objeto
    public $id;
    public $name;
    public $category;

    // Constructor
    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->category = $args['category'] ?? '';
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

}

?>

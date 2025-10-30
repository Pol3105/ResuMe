<?php

class Reviews {

    // Base de datos
    protected static $db;
    protected static $columnas_DB = ['id','business_id','user_name','rating','comment','created_at'];

    // Propiedades del objeto
    public $id;
    public $business_id;
    public $user_name;
    public $rating;
    public $comment;
    public $created_at;

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->business_id = $args['business_id'] ?? '';
        $this->user_name = $args['user_name'] ?? '';
        $this->rating = $args['rating'] ?? 0;
        $this->comment = $args['comment'] ?? '';
        $this->created_at = $args['created_at'] ?? date('Y-m-d H:i:s');
    }

    // Conectar la base de datos
    public static function setDB($database){
        self::$db = $database;
    }

    // Mostrar estrellas
    public function showRating(){
        $stars = '';
        for($i = 0; $i < 5; $i++){
            $stars .= $i < $this->rating ? '★' : '☆';
        }
        return $stars;
    }

    // --- FUNCIONES DE BASE DE DATOS ---

    // Traer todas las reseñas
    public static function all(){
        $query = "SELECT * FROM reviews ORDER BY created_at DESC";
        $resultado = self::$db->query($query);
        $reviews = [];
        while($row = $resultado->fetch_assoc()){
            $reviews[] = new self($row);
        }
        return $reviews;
    }

    // Traer una reseña por id
    public static function find($id){
        $id = (int)$id;
        $query = "SELECT * FROM reviews WHERE id = $id LIMIT 1";
        $resultado = self::$db->query($query);
        $row = $resultado->fetch_assoc();
        return $row ? new self($row) : null;
    }

    // Traer reseñas por business_id
    public static function whereBusiness($business_id){
        $business_id = (int)$business_id;
        $query = "SELECT * FROM reviews WHERE business_id = $business_id ORDER BY created_at DESC";
        $resultado = self::$db->query($query);
        $reviews = [];
        while($row = $resultado->fetch_assoc()){
            $reviews[] = new self($row);
        }
        return $reviews;
    }

    // Guardar nueva reseña
    public function save(){
        $business_id = self::$db->real_escape_string($this->business_id);
        $user_name = self::$db->real_escape_string($this->user_name);
        $rating = (int)$this->rating;
        $comment = self::$db->real_escape_string($this->comment);
        $created_at = $this->created_at;

        $query = "INSERT INTO reviews (business_id, user_name, rating, comment, created_at) 
                  VALUES ('$business_id','$user_name',$rating,'$comment','$created_at')";

        $resultado = self::$db->query($query);
        if($resultado){
            $this->id = self::$db->insert_id;
        }
        return $resultado;
    }

    // Actualizar reseña existente
    public function update(){
        if(!$this->id) return false;

        $business_id = self::$db->real_escape_string($this->business_id);
        $user_name = self::$db->real_escape_string($this->user_name);
        $rating = (int)$this->rating;
        $comment = self::$db->real_escape_string($this->comment);

        $query = "UPDATE reviews SET 
                    business_id='$business_id',
                    user_name='$user_name',
                    rating=$rating,
                    comment='$comment'
                  WHERE id = {$this->id}";

        return self::$db->query($query);
    }

    // Borrar reseña
    public function delete(){
        if(!$this->id) return false;
        $query = "DELETE FROM reviews WHERE id = {$this->id}";
        return self::$db->query($query);
    }
}

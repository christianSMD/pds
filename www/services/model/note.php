<?php 
  class Note {

    private $conn;
    private $table = 'zt_notes';

    public $tid;
    public $text;
    public $created;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get requests
    public function read() {
      $query = 'SELECT *  FROM ' . $this->table;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }


    public function read_single() {
        
        $query = 'SELECT *  FROM ' . $this->table . ' WHERE tid = :tid';
        $stmt = $this->conn->prepare($query);
        $this->tid = htmlspecialchars(strip_tags($this->tid));
        $stmt->bindParam(':tid', $this->tid);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . ' SET tid = :tid, text = :text, created = :created';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->tid = htmlspecialchars(strip_tags($this->tid));
          $this->text = htmlspecialchars(strip_tags($this->text));
          $this->created = $this->created;

          // Bind data
          $stmt->bindParam(':tid', $this->tid);
          $stmt->bindParam(':text', $this->text);
          $stmt->bindParam(':created', $this->created);

          
           try {

             $stmt->execute();
             return true;
             
           } catch(PDOException $e) {

             //echo $e->getMessage();
             return false;
          }
          
      
    }

    public function update() {
        return true;
    }
    
  }
<?php

class Model {
    protected $db;
    protected $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    protected function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}

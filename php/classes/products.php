<?php

include_once 'dbtable.php';

class products extends dbTable {

    protected $fields = array("id", "code", "name", "price", "kolli");
    protected $tablename = "core_products";

    public function getId() {
        return $this->properties["id"];
    }

    public function getCode() {
        return $this->properties["code"];
    }

    public function getName() {
        return $this->properties["name"];
    }

    public function getPrice() {
        return $this->properties["price"];
    }

    public function getKolli() {
        return $this->properties["kolli"];
    }

    public function getCategoryId() {
        return $row->id;
    }

    public function getCategoryName() {
        return $this->properties["name"];
    }

    function getProducts($parameter = NULL) {
        $sql = "SELECT id FROM $this->tablename";
        if ($parameter) {
            $sql .= " $parameter";
        }
        $result = $this->conn->query($sql);
        
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $products = array();
        
        while ($row = $result->fetch_object()) {
            $products[$row->id] = new products($this->conn, $row->id);
        }
        return $products;
    }

    public function getProductFromId($id) {
        $sql = "SELECT id FROM $this->tablename WHERE id='$id'";
        $result = $this->conn->query($sql);
        if(!empty($result)){
            $row = $result->fetch_object();
            $product = new products($this->conn, $row->id);
            return $product;
        } else {
            return "Error!";
        }
    }

    public function getProductFromCode($code) {
        $sql = "SELECT id FROM $this->tablename WHERE code='$code'";
        $result = $this->conn->query($sql);
        if(!empty($result)){
            $row = $result->fetch_object();
            $product = new products($this->conn, $row->id);
            return $product;
        } else {
            return "Error!";
        }
    }
}

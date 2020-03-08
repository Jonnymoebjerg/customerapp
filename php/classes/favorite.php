<?php

include_once 'dbtable.php';

class favorite extends dbTable {

    protected $fields = array("id", "customer_id", "product_id");
    protected $tablename = "app_fav";

    public function getId() {
        return $this->properties["id"];
    }

    public function getCustomerId() {
        return $this->properties["customer_id"];
    }

    public function setCustomerId($customer_id) {
        $this->properties["customer_id"] = $customer_id;
    }

    public function getProductId() {
        return $this->properties["product_id"];
    }

    public function setProductId($product_id) {
        $this->properties["product_id"] = $product_id;
    }

    public function getFavorites($parameter = NULL) {
        $sql = "SELECT id FROM $this->tablename";
        if ($parameter) {
            $sql .= " $parameter";
        }

        $result = $this->conn->query($sql);
        
        $favorites = array();
        while ($row = $result->fetch_object()) {
            $favorites[$row->id] = new favorite($this->conn, $row->id);
        }

        return $favorites;
    }
        
    public function getFavorite($customer_id,$product_id){
        $sql = "SELECT id FROM $this->tablename WHERE customer_id = $customer_id AND product_id = $product_id";
        $result = $this->conn->query($sql);
        if(!empty($result)){
            $row = $result->fetch_object();
            $favorite = new favorite($this->conn, $row->id);
            return "Success!";
        } else {
            return "Error!";
        }
    }

    public function setFavorite($customer_id,$product_id) {
        $this->setCustomerId($customer_id);
        $this->setProductId($product_id);
        $this->save();
        return "Succes";
    }

    public function unsetFavorite($customer_id,$product_id) {
        $sql = "DELETE FROM $this->tablename WHERE customer_id = $customer_id AND product_id = $product_id";
        $this->conn->query($sql);
        return "Deleted";
    }
}

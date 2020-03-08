<?php

include_once 'dbtable.php';

class productSpecialPrices extends dbTable {

    protected $fields = array("id", "product_id", "customer_group_id", "price");
    protected $tablename = "core_products_specialprices";

    public function getId() {
        return $this->properties["id"];
    }

    public function getProductId() {
        return $this->properties["product_id"];
    }

    public function getCustomerGroupId() {
        return $this->properties["customer_group_id"];
    }

    public function getPrice() {
        return $this->properties["price"];
    }

    function getProductSpecialPrices($parameter = NULL) {
        $sql = "SELECT id FROM $this->tablename";
        if ($parameter) {
            $sql .= " $parameter";
        }
        $result = $this->conn->query($sql);
        
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $productSpecialPrices = array();
        
        while ($row = $result->fetch_object()) {
            $productSpecialPrices[$row->id] = new productSpecialPrices($this->conn, $row->id);
        }
        return $productSpecialPrices;
    }

    public function getProductSpecialPrice($id) {
        $sql = "SELECT id FROM $this->tablename WHERE id='$id'";
        $result = $this->conn->query($sql);
        if(!empty($result)){
            $row = $result->fetch_object();
            $$productSpecialPrice = new productSpecialPrices($this->conn, $row->id);
            return $productSpecialPrice;
        } else {
            return "Error!";
        }
    }

    public function getProductSpecialPriceFromGroup($group) {
        $sql = "SELECT id FROM $this->tablename WHERE customer_group_id='$group'";
        $result = $this->conn->query($sql);
        if(!empty($result)){
            $row = $result->fetch_object();
            $$productSpecialPrice = new productSpecialPrices($this->conn, $row->id);
            return $productSpecialPrice;
        } else {
            return "Error!";
        }
    }
}

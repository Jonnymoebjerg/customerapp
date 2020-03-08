<?php

include_once 'dbtable.php';

class productCategories extends dbTable {

    protected $fields = array("id", "name");
    protected $tablename = "core_product_catagories";

    public function getId() {
        return $this->properties["id"];
    }

    public function getName() {
        return $this->properties["name"];
    }

    function getProductCategories($parameter = NULL) {
        $sql = "SELECT id FROM $this->tablename";
        if ($parameter) {
            $sql .= " $parameter";
        }
        $result = $this->conn->query($sql);

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        $productCategory = array();
        while ($row = $result->fetch_object()) {
            $productCategory[$row->id] = new productCategories($this->conn, $row->id);
        }
        return $productCategory;
    }

    public function getProductCategoryFromId($id) {
        $sql = "SELECT id FROM $this->tablename WHERE id='$id'";
        $result = $this->conn->query($sql);
        if (!empty($result)) {
            $row = $result->fetch_object();
            $productCategory = new productCategories($this->conn, $row->id);
            return $productCategory;
        } else {
            return "Error!";
        }
    }

    public function getProductCount($id) {
        $sql = "SELECT id FROM $this->tablename WHERE id='$id'";
        $result = $this->conn->query($sql);
        if (!empty($result)) {
            $row = $result->fetch_object();
            $productCategory = new productCategories($this->conn, $row->id);
            return $productCategory;
        } else {
            return "Error!";
        }
    }

    public function getCount($id = NULL, $parameter = NULL) {
        if ($id === NULL) {
            $sql = "SELECT * FROM core_products";
        } else {
            $sql = "SELECT * FROM core_products WHERE category = $id";
        }
        if ($parameter) {
            $sql .= " $parameter";
        }
        $result = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($result);
        return $num_rows;
    }

}

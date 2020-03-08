<?php

include_once 'dbtable.php';

class orders extends dbTable {

    protected $fields = array("id", "customer_num", "items", "datetime", "price");
    protected $tablename = "app_orders";

    public function getId() {
        return $this->properties["id"];
    }

    public function getCustomerNumber() {
        return $this->properties["customer_num"];
    }

    public function setCustomerNumber($customer_num) {
        $this->properties["customer_num"] = $customer_num;
    }

    public function getItems() {
        return $this->properties["items"];
    }

    public function setItems($items) {
        $this->properties["items"] = $items;
    }

    public function getDatetime() {
        return $this->properties["datetime"];
    }

    public function setDatetime($datetime) {
        $this->properties["datetime"] = $datetime;
    }

    public function getPrice() {
        return $this->properties["price"];
    }

    public function setPrice($price) {
        $this->properties["price"] = $price;
    }

    public function getOrders($parameter = NULL) {
        $sql = "SELECT id FROM $this->tablename";
        if ($parameter) {
            $sql .= " $parameter";
        }

        $result = $this->conn->query($sql);
        
        $orders = array();
        while ($row = $result->fetch_object()) {
            $orders[$row->id] = new orders($this->conn, $row->id);
        }

        return $orders;
    }
        
    public function getOrderFromId($id){
        $sql = "SELECT id FROM $this->tablename WHERE id='$id'";
        $result = $this->conn->query($sql);
        if(!empty($result)){
            $row = $result->fetch_object();
            $order = new orders($this->conn, $row->id);
            return $order;
        } else {
            return "Error!";
        }
    }

    public function setOrder($customer_number, $items, $datetime, $price) {
        $this->setCustomerNumber($customer_number);
        $this->setItems($items);
        $this->setDatetime($datetime);
        $this->setPrice($price);
        $this->save();
        return "Succes";
    }
}

<?php

include_once 'dbtable.php';

class customers extends dbTable {

    protected $fields = array("id", "customer_num", "customer_group", "name", "email","phone","address","city","region","country");
    protected $tablename = "core_customers";

    public function getId() {
        return $this->properties["id"];
    }

    public function getUsername() {
        return $this->properties["username"];
    }

    public function setUsername($username) {
        $this->properties["username"] = $username;
    }

    public function getPassword() {
        return $this->properties["password"];
    }

    public function setPassword($password) {
        $this->properties["password"] = $password;
    }

    public function getCustomerNumber() {
        return $this->properties["customer_num"];
    }

    public function setCustomerNumber($customer_num) {
        $this->properties["customer_num"] = $customer_num;
    }

    public function getEmail() {
        return $this->properties["email"];
    }

    public function setEmail($email) {
        $this->properties["email"] = $email;
    }

    public function getCustomers($parameter = NULL) {
        //parameters
        $sql = "SELECT id FROM $this->tablename";
        if ($parameter) {
            $sql .= " $parameter";
        }
        $result = $this->conn->query($sql);

        $users = array();
        while ($row = $result->fetch_object()) {
            $users[$row->id] = new customers($this->conn, $row->id);
        }
        return $users;
    }

    public function getCustomerFromId($id) {
        $sql = "SELECT id FROM $this->tablename WHERE id='$id'";
        $result = $this->conn->query($sql);
        if(!empty($result)){
            $row = $result->fetch_object();
            $customer = new customers($this->conn, $row->id);
            return $customer;
        } else {
            return "Error!";
        }
    }
    
    public function forgotPwd($email) {
        $sql = "SELECT id, customer_num FROM $this->tablename WHERE email='$email'";
        $result = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            $row = $result->fetch_object();
            return $row->customer_num;
        } else {
            return false;
        }
    }
}

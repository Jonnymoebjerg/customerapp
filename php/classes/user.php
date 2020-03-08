<?php

include_once 'dbtable.php';

class user extends dbTable {

    protected $fields = array("id", "username", "password", "customer_num", "passwordunprotected");
    protected $tablename = "app_users";

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

    public function getPwdUnProtected() {
        return $this->properties["passwordunprotected"];
    }

    public function getUsers($parameter = NULL) {
        //parameters
        $sql = "SELECT id FROM $this->tablename";
        if ($parameter) {
            $sql .= " $parameter";
        }
        $result = $this->conn->query($sql);

        $users = array();
        while ($row = $result->fetch_object()) {
            $users[$row->id] = new user($this->conn, $row->id);
        }
        return $users;
    }
        
    public function getUserFromCustomernum($customer_num) {
        $sql = "SELECT id FROM $this->tablename WHERE customer_num='$customer_num'";
        $result = $this->conn->query($sql);
        if(!empty($result)){
            $row = $result->fetch_object();
            $user = new user($this->conn, $row->id);
            return $user;
        } else {
            return "Error!";
        }
    }

    function login($username, $password) {
        $sql = "SELECT username, password FROM $this->tablename WHERE username='$username' AND password='$password'";
        $query = mysqli_query($this->conn, $sql);
        $num_rows = mysqli_num_rows($query);
        if ($num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}

<?php

class dbTable {

    protected $fields = array();
    protected $properties = array();
    protected $conn;
    protected $tablename;

    public function __construct($conn, $id = false) {
        $this->conn = $conn;
        $this->properties = array_fill_keys($this->fields, null);

        if ($id) {
            $this->findById($id);
        }
    }

    protected function findById($id) {
        $fields = implode(",", $this->fields);
        $sql = "SELECT $fields FROM $this->tablename WHERE id=$id";

        $result = $this->conn->query($sql);
        $row = $result->fetch_object();
        foreach ($this->fields as $field) {
            $this->properties[$field] = $row->$field;
        }
    }

    public function findByUsername($username) {
        $fields = implode(",", $this->fields);
        $sql = "SELECT $fields FROM $this->tablename WHERE username=$username";

        $result = $this->conn->query($sql);
        $row = $result->fetch_object();
        foreach ($this->fields as $field) {
            $this->properties[$field] = $row->$field;
        }
    }

    public function findAll() {
        $fields = implode(",", $this->fields);
        $sql = "SELECT $fields FROM $this->tablename WHERE id=$id";

        $result = $this->conn->query($sql);
        $row = $result->fetch_object();
        foreach ($this->fields as $field) {
            $this->properties[$field] = $row->$field;
        }
    }

    public function delete() {
        $sql = "DELETE FROM $this->tablename WHERE id=" . $this->properties["id"];

        return $this->conn->query($sql);
    }

    protected function cleanValues() {
        foreach ($this->properties as $key => $value) {
            $this->properties[$key] = $this->conn->real_escape_string($value);
        }
    }

    public function save() {
        $this->cleanValues();
        if ($this->properties["id"] > 0) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    protected function update() {
        foreach ($this->properties as $key => $value) {
            $string .= $key . "='" . $value . "',";
        }
        $string = substr($string, 0, -1);
        $sql = "UPDATE $this->tablename SET $string WHERE id=" . $this->properties["id"];

        return $this->conn->query($sql);
    }

    protected function insert() {
        $fields = implode(",", $this->fields);
        $properties = implode("','", $this->properties);
        $sql = "INSERT INTO $this->tablename ($fields) VALUES ('$properties')";
        $this->conn->query($sql);
        return $this->properties["id"] = $this->conn->insert_id;
    }
}

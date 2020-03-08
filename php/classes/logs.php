<?php

include_once 'dbtable.php';

class logs extends dbTable {

    protected $fields = array("id", "application_id", "typeid", "timestamp", "content", "data_content");
    protected $tablename = "core_logs";

    public function setApplicationId($applicationId) {
        $this->properties["application_id"] = $applicationId;
    }

    public function setType($type) {
        $this->properties["typeid"] = $type;
    }

    public function setTimeStamp($timestamp) {
        $this->properties["timestamp"] = $timestamp;
    }

    public function setContent($content) {
        $this->properties["content"] = $content;
    }

    public function setData($data) {
        $this->properties["data_content"] = $data;
    }

    public function setLog($applicationId, $type, $content, $data) {
        $this->setApplicationId($applicationId);
        $this->setType($type);
        date_default_timezone_set('Europe/Copenhagen');
        $this->setTimeStamp(date('Y-m-d H:i:s'));
        $this->setContent($content);
        $this->setData($data);
        $this->save();
        return "Succes";
    }
}

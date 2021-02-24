<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class FailedLoginsM extends CI_Model {
    private $db_table = "divs_failedlogins";

    public function getAllLogs($start = 0, $limit = "all") {
        if($limit == "all") {
            return $this->db->order_by('id', 'DESC')->get($this->db_table)->result_array();
        } else {
            return $this->db->limit($limit, $start)->order_by('id', 'DESC')->get($this->db_table)->result_array();
        }
    }
    public function getBy($column, $data, $oneInArray = false) {
        $result = $this->db->where($column, $data)->get($this->db_table)->result_array();
        if($result == null) {
            return null;
        } else if(count($result) > 1) {
            return $result;
        } else {
            if($oneInArray) return $result;
            return $result[0];
        }
    }
    public function add($data) {
        return $this->db->insert($this->db_table, $data);
    }
    public function addMultiple($data) {
        return $this->db->insert_batch($this->db_table, $data);
    }
    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->db_table);
    }
}
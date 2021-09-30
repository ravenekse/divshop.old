<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed!');

class ServersM extends CI_Model
{
    private $db_table = 'divs_servers';

    public function getAll()
    {
        return $this->db->order_by('id')->get($this->db_table)->result_array();
    }

    public function getFive($start = 0, $limit = '5')
    {
        return $this->db->limit($limit, $start)->order_by('id')->get($this->db_table)->result_array();
    }

    public function getBy($column, $data)
    {
        $result = $this->db->where($column, $data)->get($this->db_table)->result_array();
        if ($result == null) {
            return null;
        } elseif (count($result) > 1) {
            return $result;
        } else {
            return $result[0];
        }
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->db_table, $data);
    }

    public function add($data)
    {
        return $this->db->insert($this->db_table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->db_table);
    }
}

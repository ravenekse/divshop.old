<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed!');

class AdminsM extends CI_Model
{
    private $db_table = 'divs_admins';

    public function getAll($start = 0, $limit = 'all')
    {
        if ($limit == 'all') {
            return $this->db->order_by('id')->get($this->db_table)->result_array();
        } else {
            return $this->db->limit($limit, $start)->order_by('id')->get($this->db_table)->result_array();
        }
    }

    public function getBy($column, $data)
    {
        return $this->db->where($column, $data)->get($this->db_table)->row_array();
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

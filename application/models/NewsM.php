<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed!');

class NewsM extends CI_Model
{
    private $db_table = 'divs_news';

    public function getAll()
    {
        return $this->db->order_by('id')->get($this->db_table)->result_array();
    }

    public function getBy($column, $data, $oneInArray = false)
    {
        $result = $this->db->where($column, $data)->get($this->db_table)->result_array();
        if ($result == null) {
            return null;
        } elseif (count($result) > 1) {
            return $result;
        } else {
            if ($oneInArray) {
                return $result;
            }

            return $result[0];
        }
    }

    public function add($data)
    {
        return $this->db->insert($this->db_table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->db_table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->db_table);
    }
}

<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed!');

class BansM extends CI_Model
{
    private $db_table = 'punishmenthistory';

    public function getAllBans($start = 0, $limit = 'all')
    {
        if ($limit == 'all') {
            return $this->db->order_by('id', 'DESC')->get($this->db_table)->result_array();
        } else {
            return $this->db->limit($limit, $start)->order_by('id', 'DESC')->get($this->db_table)->result_array();
        }
    }

    public function getPlayerBy($uuid)
    {
        $result = $this->db->where($uuid)->order_by('id', 'DESC')->get($this->db_table)->result_array();
        if ($result == null) {
            return null;
        } elseif (count($result) > 1) {
            return $result;
        }
    }
}

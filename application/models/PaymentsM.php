<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed!');

class PaymentsM extends CI_Model
{
    private $db_table = 'divs_payments';

    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->db_table)->row_array();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->db_table, $data);
    }
}

<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

class SettingsM extends CI_Model
{
    private $db_table = 'divs_settings';

    public function getSettings()
    {
        return $this->db->where('id', 1)->get($this->db_table)->row_array();
    }

    public function update($data)
    {
        return $this->db->where('id', 1)->update($this->db_table, $data);
    }
}

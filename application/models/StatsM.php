<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed!');

class StatsM extends CI_Model {
    private $db_table = "divs_players";

    public function getAllPlayers($start = 0, $limit = "all") {
        if($limit == "all") {
            return $this->db->order_by('points', 'DESC')->get($this->db_table)->result_array();
        } else {
            return $this->db->limit($limit, $start)->order_by('points', 'DESC')->get($this->db_table)->result_array();
        }
    }
    public function getPlayerBy($uuid) {
        $result = $this->db->where($uuid)->order_by('points', 'DESC')->get($this->db_table)->result_array();
        if($result == null) {
            return null;
        } elseif(count($result) > 1) {
            return $result;
        }
    }
}
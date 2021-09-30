<?php

class Database
{
    public function create_database($data)
    {
        $mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], '');
        if (mysqli_connect_errno()) {
            return false;
        }
        $mysqli->query('CREATE DATABASE IF NOT EXISTS '.$data['database']);
        $mysqli->close();

        return true;
    }

    public function create_tables($data)
    {
        $mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);
        if (mysqli_connect_errno()) {
            return false;
        }
        $query = file_get_contents('assets/databases/divshop_database.sql');
        $mysqli->multi_query($query);
        $mysqli->close();

        return true;
    }

    public function create_admin($data)
    {
        $mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);
        if (mysqli_connect_errno()) {
            return false;
        }
        $password = $data['admin_pass'];
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $mysqli->query("INSERT INTO `divs_admins` (`id`, `name`, `email`, `password`, `image`, `browser`, `lastIP`, `lastLogin`) VALUES (1, '".$data['admin_login']."', '".$data['admin_email']."', '".$password_hashed."', NULL, NULL, NULL, NULL);");
        $mysqli->close();

        return true;
    }
}

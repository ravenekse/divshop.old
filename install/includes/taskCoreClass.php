<?php

class Core
{
    public function checkEmpty($data)
    {
        if (!empty($data['hostname']) && !empty($data['username']) && !empty($data['database']) && !empty($data['url']) && !empty($data['template'])) {
            return true;
        } else {
            return false;
        }
    }

    public function show_message($type, $message)
    {
        return $message;
    }

    public function getAllData($data)
    {
        return $data;
    }

    public function write_db_config($data)
    {
        $template_path = 'includes/database.php';
        $output_path = '../application/config/database.php';
        $database_file = file_get_contents($template_path);

        $new = str_replace('%HOSTNAME%', $data['hostname'], $database_file);
        $new = str_replace('%USERNAME%', $data['username'], $new);
        $new = str_replace('%PASSWORD%', $data['password'], $new);
        $new = str_replace('%DATABASE%', $data['database'], $new);

        $handle = fopen($output_path, 'w+');
        @chmod($output_path, 0777);

        if (is_writable(dirname($output_path))) {
            if (fwrite($handle, $new)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function write_config($data)
    {
        $template_path = 'includes/config.php';
        $output_path = '../application/config/config.php';
        $config_file = file_get_contents($template_path);

        $new = str_replace('%BASE_URL%', $data['url'], $config_file);

        $handle = fopen($output_path, 'w+');
        @chmod($output_path, 0777);

        if (is_writable(dirname($output_path))) {
            if (fwrite($handle, $new)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkFile()
    {
        $output_path = '../application/config/database.php';

        if (file_exists($output_path)) {
            return true;
        } else {
            return false;
        }
    }
}

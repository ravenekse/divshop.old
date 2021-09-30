<?php

    class MicroSMSTransfers
    {
        private $url = 'https://microsms.pl/api/bankTransfer/';
        private $ips = 'https://microsms.pl/psc/ips/';
        private $fields = [];

        public function add($field, $value)
        {
            $this->fields[$field] = $value;
        }

        public function submit()
        {
            echo '<html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <title>Przekierowanie do platnosci ...</title>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <script>
                            function submitform() {
                                document.getElementById(\'send\').submit();
                            }
                        </script>
                    </head>
                            <body onload="submitform();">
                    <h3>Przekierowanie do platnosci ...</h3>
                    <form id=\'send\' action="'.$this->url.'" method="get"  >
                    ';

            foreach ($this->fields as $name => $value) {
                echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
            }

            echo '<input type="hidden" name="charset" value="utf-8" />
                        <input type="submit" value="Kliknij tutaj, jezeli nie zostaniesz przeniesiony w ciągu 10 sekund" />
                    </form>
                    </body>
                    </html>';
            exit();
        }

        public function validate_ipn()
        {
            if (!in_array($_SERVER['REMOTE_ADDR'], explode(',', file_get_contents($this->ips))) == true) {
                exit('Access denid.');
            }
        }

        public function validate_user($config, $post)
        {
            if ($config['transfers']['userid'] != $post['userid']) {
                exit('Bad user');
            }
        }
    }

<?php
header('Content-Type: application/json');

$errors = 0;
$msg = "";
function addlog($m){
    global $msg;
    $msg .= "<p>" . date('H:i:s') . ": " . $m . "</p>";
}

error_reporting(0);
$db_config_path = '../application/config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
    sleep(5);
    
	require_once('includes/taskCoreClass.php');
	require_once('includes/databaseLibrary.php');

	$core = new Core();
	$database = new Database();

	if($core->checkEmpty($_POST) == true) {
        if($database->create_database($_POST) == false) {
            $errors++;
            addlog("Nie można połączyć z bazą danych. Upewnij się, że Twój host, nazwa użytkownika, hasło i nazwa bazy danych są poprawne.");
        } 
        else if ($database->create_tables($_POST) == false) {
            $errors++;
            addlog("Nie można połączyć z bazą danych. Upewnij się, że Twój host, nazwa użytkownika, hasło i nazwa bazy danych są poprawne.");
        } 
        else if ($database->create_admin($_POST) == false) {
            $errors++;
            addlog("Nie można połączyć z bazą danych. Upewnij się, że Twój host, nazwa użytkownika, hasło i nazwa bazy danych są poprawne.");
        } 
        else if ($core->checkFile() == false) {
            $errors++;
            addlog("Plik application/config/database.php jest pusty");
        }
        else if ($core->write_db_config($_POST) == false) {
            $errors++;
            addlog("Nie można zapisać pliku konfiguracyjnego bazy danych, proszę ustawić chmod pliku <b>application/config/database.php</b> na <b>777</b>");
        }
        else if ($core->write_config($_POST) == false) {
            $errors++;
            addlog("Nie można zapisać pliku <b>config.php</b>, chmod plik application/config/config.php na 777");
        }
	} else {
        $errors++;
        addlog('Host, nazwa użytkownika, hasło, nazwa bazy danych i adres URL są wymagane.');
	}
	
    if ($errors == 0) {
        sleep(1);
        @chmod('../install', 0600);
    }
    
    $status = ( $errors == 0 ) ? true : false;
    echo json_encode(array('status' => $status, 'msg' => $msg));
    exit;
}
?>
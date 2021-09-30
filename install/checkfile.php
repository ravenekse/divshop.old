<?php

header('Content-Type: application/json');

$msg = '';
$errors = 0;

function addlog($m)
{
    global $msg;
    $msg .= '<p>'.date('H:i:s').': '.$m.'</p>';
}

$phpversion = phpversion();
if ($phpversion >= 5.6) {
    addlog('Wersja PHP: '.$phpversion.': OK');
} else {
    $errors++;
    addlog('Zbyt niska wersja PHP: '.$phpversion.' (Minimalna wymagana wersja: 5.6)');
}

if (extension_loaded('mysqli')) {
    addlog('Moduł MYSQLi: OK');
} else {
    $errors++;
    addlog('Brak modułu MYSQLi');
}

if (extension_loaded('mbstring')) {
    addlog('Moduł mbstring: OK');
} else {
    $errors++;
    addlog('Brak modułu mbstring');
}

if (extension_loaded('zip')) {
    addlog('Moduł PHP Zip: OK');
} else {
    $errors++;
    addlog('Brak modułu PHP Zip');
}

if (function_exists('curl_version')) {
    addlog('Moduł PHP Curl: OK');
} else {
    $errors++;
    addlog('Brak modułu PHP Curl');
}

$_SESSION['divshop_session_work'] = 1;
if (!empty($_SESSION['divshop_session_work'])) {
    addlog('Moduł sesji: OK');
    unset($_SESSION['divshop_session_work']);
} else {
    $errors++;
    addlog('Moduł sesji musi być włączony');
}

sleep(6);

if (is_dir('../assets/uploads') && is_writable('../assets/uploads') == true) {
    addlog('Folder assets/uploads: OK');
} elseif (!is_dir('../assets/uploads')) {
    $errors++;
    addlog('Folder <b>assets/uploads</b>: Brak folderu');
} elseif (is_writable('../assets/uploads') == false) {
    $errors++;
    addlog('Folder <b>assets/uploads</b>: Proszę ustawić chmody na <b>777</b>');
}

if (is_dir('../assets/uploads/admins') && is_writable('../assets/uploads/admins') == true) {
    addlog('Folder assets/uploads/admins: OK');
} elseif (!is_dir('../assets/uploads/admins')) {
    $errors++;
    addlog('Folder <b>assets/uploads/admins</b>: Brak folderu');
} elseif (is_writable('../assets/uploads/admins') == false) {
    $errors++;
    addlog('Folder <b>assets/uploads/admins</b>: Proszę ustawić chmody na <b>777</b>');
}

if (is_dir('../assets/uploads/servers') && is_writable('../assets/uploads/servers') == true) {
    addlog('Folder assets/uploads/servers: OK');
} elseif (!is_dir('../assets/uploads/servers')) {
    $errors++;
    addlog('Folder <b>assets/uploads/servers</b>: Brak folderu');
} elseif (is_writable('../assets/uploads/servers') == false) {
    $errors++;
    addlog('Folder <b>assets/uploads/servers</b>: Proszę ustawić chmody na <b>777</b>');
}

if (is_dir('../assets/uploads/services') && is_writable('../assets/uploads/services') == true) {
    addlog('Folder assets/uploads/services: OK');
} elseif (!is_dir('../assets/uploads/services')) {
    $errors++;
    addlog('Folder <b>assets/uploads/services</b>: Brak folderu');
} elseif (is_writable('../assets/uploads/services') == false) {
    $errors++;
    addlog('Folder <b>assets/uploads/services</b>: Proszę ustawić chmody na <b>777</b>');
}

if (substr(decoct(fileperms('../application/config/config.php')), -4) == 777) {
    addlog('Plik application/config/config.php: OK');
} elseif (substr(decoct(fileperms('../application/config/config.php')), -4) != 777) {
    $errors++;
    addlog('Plik <b>application/config/config.php</b>: Proszę ustawić chmody na <b>777</b>');
}

if (substr(decoct(fileperms('../application/config/database.php')), -4) == 777) {
    addlog('Plik application/config/database.php: OK');
} elseif (substr(decoct(fileperms('../application/config/database.php')), -4) != 777) {
    $errors++;
    addlog('Plik <b>application/config/database.php</b>: Proszę ustawić chmody na <b>777</b>');
}

$status = ($errors == 0) ? true : false;
echo json_encode(['status' => $status, 'msg' => $msg]);
exit;

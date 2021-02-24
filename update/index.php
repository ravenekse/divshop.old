<?php 
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/
define('BASEPATH', 'system');
define('ENVIRONMENT', 'production');
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('../application/libraries/divshop-api/divsAPI.php');
require_once('../application/config/database.php');
$database = array(
    'host'    =>   $db['default']['hostname'],
    'user'    =>   $db['default']['username'],
    'pass'    =>   $db['default']['password'],
    'name'    =>   $db['default']['database']
);
$api = new DIVShopAPI();
$connection = $api->check_connection();
$updateData = $api->check_update();
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>DIVShop.pro - Aktualizator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.8.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap"> 
    <style>
      a,a:hover,a:focus,a:active{color:#2c3e50;font-weight:700}body,html{background:#f1f1f1}.changelog{font-family:'Barlow',sans-serif;background:#f5f5f5;padding:20px 30px;width:100%;margin-bottom:30px;font-size:13px;font-weight:700}.changelog .changelog-pl{padding-left:30px;margin-top:10px;font-weight:700}.text-center{text-align:center}.mr-auto{margin-right:auto!important}.ml-auto{margin-left:auto!important}.justify-content-center{justify-content:center!important}.d-flex{display:flex!important}.button.is-custom{background:#2c3e50;color:#fff;font-family:'Barlow',sans-serif;font-weight:700;border-color:#2c3e50}
      .error-connection-btns{display:flex;justify-content:center;}
      .error-connection-btns .btn-error{padding:5px 10px;background:0 0;border:3px solid #2c3e50;margin: 15px 15px 0 15px; border-radius:5px;font-family: 'Barlow', sans-serif;font-weight:600;transition:all .2s}.btn-error:active,.btn-error:focus,.btn-error:hover{background:#2c3e50;color:#fff}
      @media only screen and (max-width: 390px) {
          .error-connection-btns {
              display: grid;
          }
      }
    </style>
</head>
<body>
    <div class="container" style="padding-top:20px;">
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-four-fifths">
                    <h1 class="title text-center" style="padding-top:20px;margin-bottom:40px;">Aktualizator DIVShop.pro</h1>
                    <div class="box">
                        <?php if($connection['status'] == true): ?>
                            <?php if(!isset($_COOKIE['isLogged'])): ?>
                                <h2 class="text-center">Nie masz uprawnień do aktualizatora! Aby go użyć zaloguj się do panelu administratora</h2>
                            <?php else: ?>
                                <?php if($updateData['status'] == true): ?>
                                    <div class="message is-success">
                                        <div class="message-body">Proszę wykonać backup plików sklepu i bazy danych przed aktualizacją</div>
                                    </div>
                                    <p class="subtitle is-5 text-center" style="margin-bottom:25px;"><?php echo $updateData['message']; ?></p>
                                    <div class="content">
                                        <?php if($updateData['status']): ?>
                                            <div class="changelog">
                                                CHANGELOG:
                                                <div class="changelog-pl">
                                                    <?php echo $updateData['changelog']; ?>
                                                </div>
                                            </div>
                                        <?php
                                            $update_id = null;
                                            $has_sql = null;
                                            $version = null;
                                            
                                            if(! empty($_POST['update_id'])):
                                                $update_id = strip_tags(trim($_POST['update_id']));
                                                $has_sql = strip_tags(trim($_POST['has_sql']));
                                                $version = strip_tags(trim($_POST['version']));
                                                echo '<progress id="prog" value="0" max="100" class="progress is-success" style="margin-bottom: 10px;"></progress>';
                                                $api->download_update(
                                                    $_POST['update_id'],
                                                    $_POST['has_sql'],
                                                    $_POST['version'],
                                                    null,
                                                    null,
                                                    array(
                                                        'db_host' => $database['host'],
                                                        'db_user' => $database['user'],
                                                        'db_pass' => $database['pass'],
                                                        'db_name' => $database['name']
                                                    )
                                                );
                                            else: ?>
                                                <form action="" method="POST">
                                                    <input type="hidden" name="update_id" class="form-control" value="<?php echo $updateData['update_id']; ?>">
                                                    <input type="hidden" name="has_sql" class="form-control" value="<?php echo $updateData['has_sql']; ?>">
                                                    <input type="hidden" name="version" class="form-control" value="<?php echo $updateData['version']; ?>">
                                                    <div class="d-flex justify-content-center">
                                                        <button type="submit" class="button is-custom is-rounded">Aktualizuj</button>
                                                    </div>
                                                    <div class="d-flex justify-content-center" style="margin-top:10px;">
                                                        Aktualna wersja: <?php echo $api->get_current_version(); ?>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <h2 class="text-center">Aktualnie nie nie ma żadnych dostępnych aktualizacji</h2>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <h2 class="text-center">Wystąpił błąd podczasł łączenia z API. Spróbuj odświeżyć stronę lub spróbuj użyć aktualizatora za kilka minut.</h2>
                            <p class="text-center">Problem się powtarza?</p>
                            <div class="error-connection-btns">
                                <a target="_blank" href="https://status.divshop.pro/" class="btn-error">Statusy usług</a>
                                <a target="_blank" href="https://divshop.pro/check-url/" class="btn-error">Sprawdź URL</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content text-center">
        <p>Copyright &copy; <?php echo date('Y'); ?> by <a href="https://divshop.pro/" target="_blank" rel="noopener noreferrer">DIVShop.pro</a></p>
    </div>
</body>
</html>
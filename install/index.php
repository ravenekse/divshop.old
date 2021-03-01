<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>DIVShop.pro - Instalator</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,300;0,700;1,300;1,700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
    <link rel="stylesheet" href="assets/css/divshop.install.css">
    <link rel="stylesheet" href="assets/css/icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php
        $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $base_url .= "://" . $_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
        $base_url = str_replace('install/', '', $base_url);
    ?>
    <div class="install-wrapper">
        <div class="install-content">
            <div class="install-header">
                <h1>DIVShop.pro - <small>Instalator</small></h1>
            </div>
            <div id="success-wrap" style="display:none;">
                <div class="step-content">
                    <div class="step-final">
                    
                        <div class="success-icon">
                            <i class="gg-check"></i>
                        </div>
                        <h2>Gratulacje! Pomyślnie zainstalowano DIVShop.pro!</h2>

                        <p>Możesz teraz przejść na stronę główną lub do ACP. Po skończonej instalacji należy usunąć katalog <b>install/</b> z głównego katalogu i ze względu na bezpieczeństwo przestawić chmody plików <b>config.php</b> i <b>database.php</b> na <b>644</b></p><br>

                    </div>
                    <div class="step-footer">
                        <a class="btn mr docs-link" href="https://divshop.pro/docs/"><i class="fa fa-book"></i>&nbsp;Dokumentacja</a>
                        <a class="btn ml" href="<?php echo $base_url; ?>"><i class="fa fa-desktop"></i>&nbsp;Strona główna</a>
                        <a class="btn ml" style="margin-left:0;" href="<?php echo $base_url . "admin"; ?>"><i class="fa fa-key"></i>&nbsp;Panel ACP</a>
                    </div>
                </div>
            </div>

            <div id="main-wrap">
                <form action="#" method="POST" class="steps">
                    <div class="step-list">
                        <ul>
                            <li><button class="btn" data-step="1"><span>1</span> Powitanie</button></li>
                            <li><button class="btn" data-step="2"><span>2</span> Sprawdzenie plików</button></li>
                            <li><button class="btn" data-step="3"><span>3</span> Łączenie z bazą danych</button></li>
                            <li><button class="btn" data-step="4"><span>4</span> Konto administratora</button></li>
                            <li><button class="btn" data-step="5"><span>5</span> Instalacja</button></li>
                        </ul>
                    </div>
                    <div class="step-content">
                        <div class="step-item" data-step="1">
                            <h2>Witamy w instalatorze sklepu DIVShop.pro</h2>

                            <p>Za chwilę przejdziesz krok po kroku przez proces instalacji swojego sklepu.</p>
                            <p>Aby kontynuować wciśnij przycisk "Dalej"</p>
                            <br>
                            <p><b>UWAGA!</b> Jeżeli posiadasz certyfikat SSL i chciałbyś zainstalować sklep z certyfikatem SSL, </p>
                            <p>zwróć uwagę czy w adresie URL znajduje się <b>https://</b> (symboliczna kłódka)</p>
                        </div>
                        
                        <div class="step-item" data-step="2">
                            <h2>Sprawdzenie plików i modułów <span class="loading"></span></h2>

                            <div class="msg-box info show">
                                <p>Trwa sprawdzanie uprawnień katalogów, plików oraz poprawność załadowanych modułów</p>
                            </div>

                            <div class="msg-box success">
                                <p>Proces sprawdzania plików przebiegł pomyślnie, można kontynuować</p>
                            </div>

                            <div class="msg-box error">
                                <p>Niestety wykryto problem z plikami</p>
                            </div>
                        </div>

                        <div class="step-item" data-step="3">
                            <h2>Łączenie z bazą danych</h2>

                            <p>W poniższe pola wpisz dane do połączenia z bazą danych</p>

                            <div class="form-group">
                                
                                <div class="form-row">
                                    <label for="dbhost">Host bazy danych:</label>
                                    <input type="text" class="form-control" name="dbhost" id="dbhost" value="localhost">
                                    <p class="form-help">Host do bazy danych</p>
                                </div>
                                <div class="form-row">
                                    <label for="dbuser">Użytkownik bazy danych:</label>
                                    <input type="text" class="form-control" name="dbuser" id="dbuser">
                                    <p class="form-help">Nazwa użytkownika bazy danych</p>
                                </div>
        
                                <div class="form-row">
                                    <label for="dbpass">Hasło bazy danych:</label>
                                    <input type="password" class="form-control" name="dbpass" id="dbpass">
                                    <p class="form-help">Hasło do bazy danych</p>
                                </div>
                                
                                <div class="form-row">
                                    <label for="dbname">Nazwa bazy danych:</label>
                                    <input type="text" class="form-control" name="dbname" id="dbname">
                                    <p class="form-help">Nazwa bazy danych, którą chcesz użyć do DIVShop.pro</p>
                                </div>
                            </div>

                            <div class="msg-box error">
                                <p>Sprawdź poprawność wpisanych pól</p>
                            </div>
                        </div>

                        <div class="step-item" data-step="4">
                            <h2>Tworzenie konta administratora</h2>

                            <p>W poniższe pola wpisz dane do konta administratora</p>

                            <div class="form-group">
                                <div class="form-row">
                                    <label for="adname">Nazwa administratora:</label>
                                    <input type="text" class="form-control" name="adname" id="adname">
                                    <p class="form-help">Nazwa administratora, ze wględów bezpieczeństwa zaleca się użycie innego niż <i>admin</i></p>
                                </div>
        
                                <div class="form-row">
                                    <label for="adpasswd">Hasło administratora:</label>
                                    <input type="password" class="form-control" name="adpasswd" id="adpasswd">
                                    <p class="form-help">Hasło do konta administratora</p>
                                    <div class="form-meter">Siła hasła: </div>
                                </div>
        
                                <div class="form-row">
                                    <label for="adpasswd2">Powtórz hasło administratora:</label>
                                    <input type="password" class="form-control" name="adpasswd2" id="adpasswd2">
                                    <p class="form-help">Oba hasła muszą się zgadzać</p>
                                </div>
        
                                <div class="form-row">
                                    <label for="ademail">Adres email administraora:</label>
                                    <input type="email" class="form-control" name="ademail" id="ademail">
                                    <p class="form-help">Będzie on wykorzystywany do celów administracyjnych</p>
                                </div>
                            </div>

                            <div class="msg-box error">
                                <p>Sprawdź poprawność wpisanych pól</p>
                            </div>
                        </div>

                        <div class="step-item" data-step="5">
                            <h2>Wygląda na to, że jesteś gotów do instalacji</h2>

                            <p>Kliknij "Instaluj" aby rozpocząć instalację</p>

                            <div id="form-progress">

                            </div>

                            <div class="msg-box error">
                                <p>Sprawdź poprawność wypełnionych pól</p>
                            </div>
                        </div>

                        <div class="step-footer">
                            <button class="btn mr" type="button" data-step="back"><i class="gg-chevron-left"></i>&nbsp;Wróć</button>
                            <button class="btn ml" style="padding-bottom:16px;padding-top:17px;" type="button" data-step="try_again">Sprawdź ponownie <i class="gg-repeat"></i></button>
                            <button class="btn ml" style="margin-left:0;" type="button" data-step="next">Dalej&nbsp;<i class="gg-chevron-right"></i></button>
                            <button class="btn ml" type="button" data-step="install">Instaluj&nbsp;<i class="gg-chevron-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="install-footer">
            <p>Copyright &copy; <?php echo date('Y'); ?> by <a href="https://divshop.pro/" target="_blank" rel="noopener noreferrer">DIVShop.pro</a></p>
        </div>
    </div>

    <script src='assets/js/jquery.min.js'></script>
    <script>
        <?php echo "let base_url = '$base_url'"; ?>
    </script>
    <script src="assets/js/utils.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
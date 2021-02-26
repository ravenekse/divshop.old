<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?php 
        if(file_exists(APPPATH . 'views/panel/components/Sidebar.php') && file_exists(APPPATH . 'views/panel/components/Navbar.php')):
          $this->load->view('panel/components/Navbar');
          $this->load->view('panel/components/Sidebar');
        elseif(! file_exists(APPPATH . 'views/panel/components/Navbar.php')):
          die('File <b>views/panel/components/Navbar.php</b> missing!');
        elseif(!file_exists(APPPATH . 'views/panel/components/Sidebar.php')):
          die('File <b>views/panel/components/Sidebar.php</b> missing!');
        endif; 
      ?>
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Ustawienia</h1>
          </div>
          <?php if(file_exists(APPPATH . 'views/panel/components/TopAlerts.php')):
            $this->load->view('panel/components/TopAlerts');
          else:
            die('File <b>views/panel/components/TopAlerts.php</b> missing!');
          endif ?>
          <div class="row">
            <div class="col-md-12 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-header">
                        <nav class="w-100">
                            <div class="nav nav-tabs d-flex justify-content-center" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="divsShopMainSettings-tab" data-toggle="tab" href="#divsShopMainSettings" role="tab" aria-controls="divsShopMainSettings" aria-selected="true">Główne ustawienia strony</a>
                                <a class="nav-item nav-link" id="divsShopVouchersSettings-tab" data-toggle="tab" href="#divsShopVouchersSettings" role="tab" aria-controls="divsShopVouchersSettings">Ustawienia voucherów</a>
                                <a class="nav-item nav-link" id="divsShopLayoutSettings-tab" data-toggle="tab" href="#divsShopLayoutSettings" role="tab" aria-controls="divsShopLayoutSettings">Ustawienia wyglądu</a>
                                <a class="nav-item nav-link" id="divsShopWebhookSettings-tab" data-toggle="tab" href="#divsShopWebhookSettings" role="tab" aria-controls="divsShopWebhookSettings">Ustawienia Webhooka</a>
                                <a class="nav-item nav-link" id="divsShopAntybotSettings-tab" data-toggle="tab" href="#divsShopAntybotSettings" role="tab" aria-controls="divsShopAntybotSettings">Ustawienia Antybota</a>
                            </div>
                        </nav>
                    </div>
                    <div class="card-body">
                        <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/settings/save')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="divsShopMainSettings" role="tabpanel" aria-labelledby="divsShopMainSettings-tab">
                                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                        <div class="col-xl-12 col-xxl-10">
                                            <div class="row justify-content-center">
                                                <div class="col-xl-8">
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Nazwa strony <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="settingsPageTitle" value="<?php echo $settings['pageTitle']; ?>">
                                                            <small class="text-muted">Używany tekst w tagu <b>&lt;title&gt;&lt;/title&gt;</b>. Pojawia się w nazwie karty przeglądarki</small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Opis strony</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <textarea class="form-control panel-notes" name="settingsPageDescription" rows="3" style="height: 98px;" placeholder="Aktualnie strona nie ma opisu"><?php echo $settings['pageDescription']; ?></textarea>
                                                            <small class="text-muted">Wyświetlany tekst przez wyszukiwarki internetowe</small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Tagi strony</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="settingsPageTags" value="<?php echo $settings['pageTags']; ?>">
                                                            <small class="text-muted">Pozycjonują stronę w wyszukiwarce internetowej. Oddzielane przecinkami ze spacją</small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Meta charset <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="settingsPageCharset" value="<?php echo $settings['pageCharset']; ?>">
                                                            <small class="text-muted">Określa kodowanie znaków</small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Preloader</label>
                                                        <div class="col-3">
                                                            <label class="custom-switch mt-2">
                                                                <label for="settingsPagePreloader" class="mr-2 mt-1">Off</label>
                                                                <input type="checkbox" name="settingsPagePreloader" class="custom-switch-input" <?php echo($settings['pagePreloader'] == 0) ? '' : 'checked' ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                                <label for="settingsPagePreloader" class="ml-2 mt-1">On</label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Strona włączona</label>
                                                        <div class="col-3">
                                                            <label class="custom-switch mt-2">
                                                                <label for="settingsPageEnabled" class="mr-2 mt-1">Off</label>
                                                                <input type="checkbox" name="settingsPageEnabled" class="custom-switch-input" <?php echo($settings['pageActive'] == 0) ? '' : 'checked' ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                                <label for="settingsPageEnabled" class="ml-2 mt-1">On</label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Tryb demo</label>
                                                        <div class="col-3">
                                                            <label class="custom-switch mt-2" data-toggle="tooltip" title="Ze względu na bezpieczeństwo, ta opcja jest możliwa do zmiany jedynie przez bazę danych">
                                                                <label for="settingsDemoMode" class="mr-2 mt-1">Off</label>
                                                                <input type="checkbox" name="settingsDemoMode" class="custom-switch-input" <?php echo($settings['demoMode'] == 0) ? '' : 'checked' ?> disabled>
                                                                <span class="custom-switch-indicator"></span>
                                                                <label for="settingsDemoMode" class="ml-2 mt-1">On</label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Powód wyłączonej strony <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <textarea class="form-control panel-notes" name="settingsPageBreakDescription" rows="3" style="height: 98px;"><?php echo $settings['pageBreakDescription']; ?></textarea>
                                                            <small class="text-muted">Wyświetlany tekst podczas gdy strona jest wyłączona</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="divsShopVouchersSettings" role="tabpanel" aria-labelledby="divsShopVouchersSettings-tab">
                                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                        <div class="col-xl-12 col-xxl-10">
                                            <div class="row justify-content-center">
                                                <div class="col-xl-8">
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Przedrostek voucherów <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="settingsVoucherPrfx" value="<?php echo $settings['voucherPrfx']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Długość kodu <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="number" class="form-control" name="settingsVoucherLength" value="<?php echo $settings['voucherLength']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="divsShopLayoutSettings" role="tabpanel" aria-labelledby="divsShopLayoutSettings-tab">
                                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                        <div class="col-xl-12 col-xxl-10">
                                            <div class="row justify-content-center">
                                                <div class="col-xl-8">
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Ikona ulubionych (favicon)</label>
                                                        <div class="col-lg-9 col-xl-8 col-md-12">
                                                            <div class="avatar-upload">
                                                                <div class="avatar-edit">
                                                                    <input type="file" id="imageUpload" name="settingsPageFavicon" accept=".png, .jpg, .jpeg, .gif">
                                                                    <label for="imageUpload"></label>
                                                                </div>
                                                                <div class="avatar-preview">
                                                                    <div id="imagePreview" style="background-image: url(<?php echo($settings['pageFavicon'] == null) ? $this->config->base_url('assets/images/icon-undefined.png') : $settings['pageFavicon']; ?>);">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <small id="removeImage" style="cursor:pointer;display:none;"><b>Usuń ikonę</b></small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Logo</label>
                                                        <div class="col-lg-9 col-xl-8 col-md-12">
                                                            <div class="custom-file">
                                                                <label class="custom-file-label" for="customFile">Wybierz plik</label>
                                                                <input type="file" class="custom-file-input" id="customFile" name="settingsPageLogo" accept=".png, .jpg, .jpeg, .gif">
                                                            </div>
                                                            <small id="removeLogo" style="cursor:pointer;display:none;"><b>Usuń logo</b></small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Obrazek w headerze</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="url" class="form-control" name="settingsPageBackground" value="<?php echo $settings['pageBackground']; ?>">
                                                            <small>Link do obrazka, który pojawi się w headerze sklepu</small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Własny CSS</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="url" class="form-control" name="settingsPageCustomCSS" value="<?php echo $settings['pageCustomCSS']; ?>">
                                                            <small>Link do pliku CSS</small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Kolorystyka sklepu</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <select class="form-control selectric" name="settingsPageTheme">
                                                                <option value="defaultlight" <?php echo($settings['pageTheme'] == 'defaultlight') ? 'selected' : ''; ?>>Jasna kolorystyka</option>
                                                                <option value="defaultdark" <?php echo($settings['pageTheme'] == 'defaultdark') ? 'selected' : ''; ?>>Ciemna kolorystyka</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Pozycja sidebara</label>
                                                        <div class="col-3">
                                                            <label class="custom-switch mt-2">
                                                                <label for="settingsSidebarPosition" class="mr-2 mt-1">Lewa</label>
                                                                <input type="checkbox" name="settingsSidebarPosition" class="custom-switch-input" <?php echo($settings['pageSidebarPosition'] == 0) ? '' : 'checked' ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                                <label for="settingsSidebarPosition" class="ml-2 mt-1">Prawa</label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="divsShopWebhookSettings" role="tabpanel" aria-labelledby="divsShopWebhookSettings-tab">
                                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                        <div class="col-xl-12 col-xxl-10">
                                            <div class="row justify-content-center">
                                                <div class="col-xl-8">
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Adres URL Webhooka</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="url" class="form-control" name="settingsWebhookUrl" value="<?php echo $settings['shopDiscordWebhookUrl']; ?>">
                                                            <small><a href="https://divshop.pro/docs/#konfiguracja-discordowego-webhooka" target="_blank">Konfiguracja Webhooka w DIVShop.pro</a></small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Nazwa Bota wysyłającego wiadomości <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="settingsWebhookBotName" value="<?php echo $settings['shopDiscordWebhookBotName']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Tytuł wiadomości <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="settingsWebhookEmbedTitle" value="<?php echo $settings['shopDiscordWebhookEmbedTitle']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Treść wiadomości <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <textarea class="form-control panel-notes" name="settingsWebhookEmbedContent" rows="3" style="height: 98px;"><?php echo $settings['shopDiscordWebhookDesc']; ?></textarea>
                                                            <small class="text-muted">
                                                                Zmienne:
                                                                    <div class="ml-3">
                                                                        <b>{BUYER}</b> - Nazwa kupującego
                                                                        <br>
                                                                        <b>{SERVICE}</b> - Zakupiona usługa
                                                                    </div>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Kolor osadzenia <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="color" class="form-control" name="settingsWebhookEmbedColor" value="#<?php echo $settings['shopDiscordWebhookHex']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Webhook włączony</label>
                                                        <div class="col-3">
                                                            <label class="custom-switch mt-2">
                                                                <label for="settingsWebhookEnabled" class="mr-2 mt-1">Off</label>
                                                                <input type="checkbox" name="settingsWebhookEnabled" class="custom-switch-input" <?php echo($settings['shopDiscordWebhookEnabled'] == 0) ? '' : 'checked' ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                                <label for="settingsWebhookEnabled" class="ml-2 mt-1">On</label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="divsShopAntybotSettings" role="tabpanel" aria-labelledby="divsShopAntybotSettings-tab">
                                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                        <div class="col-xl-12 col-xxl-10">
                                            <div class="row justify-content-center">
                                                <div class="col-xl-8">
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Klucz witryny reCaptcha</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="settingsRecaptchaSiteKey" value="<?php echo $settings['recaptchaSiteKey']; ?>">
                                                            <small>Klucz witryny i sekretny klucz reCaptcha można wygenerować <a href="https://www.google.com/recaptcha/admin/create" target="_blank">tutaj</a></small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Sekretny klucz reCaptcha</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <div class="input-group">
                                                                <input type="password" class="form-control" id="divsRecaptchaSecret" name="settingsRecaptchaSecretKey" value="<?php echo $settings['recaptchaSecretKey']; ?>">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text show-password-btn" onclick="showRecaptchaSecret();" data-toggle="tooltip" title="Kliknij, aby pokazać lub ukryć sekretny klucz reCaptcha">
                                                                        <i class="bi bi-eye" id="showSecret"></i>
                                                                        <i class="bi bi-eye-slash d-none" id="hideSecret"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary btn-xs mt-4" onclick="saveSettings(this);"><i class="bi bi-check"></i> Zapisz ustawienia</button>
                                </div>
                            </div>
                        <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                    </div>
                </div>
            </div>
          </div>
        </section>
      </div>
      <?php $this->load->view('panel/components/Footer'); ?>
    </div>
  </div>

  <?php $this->load->view('components/Scripts'); ?>
</body>
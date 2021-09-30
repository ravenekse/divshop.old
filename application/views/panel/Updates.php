
<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?php
        if (file_exists(APPPATH.'views/panel/components/Sidebar.php') && file_exists(APPPATH.'views/panel/components/Navbar.php')) {
            $this->load->view('panel/components/Navbar');
            $this->load->view('panel/components/Sidebar');
        } elseif (!file_exists(APPPATH.'views/panel/components/Navbar.php')) {
            exit('File <b>views/panel/components/Navbar.php</b> missing!');
        } elseif (!file_exists(APPPATH.'views/panel/components/Sidebar.php')) {
            exit('File <b>views/panel/components/Sidebar.php</b> missing!');
        }
      ?>
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Aktualizacje</h1>
          </div>
          <?php if (file_exists(APPPATH.'views/panel/components/TopAlerts.php')) {
          $this->load->view('panel/components/TopAlerts');
      } else {
          exit('File <b>views/panel/components/TopAlerts.php</b> missing!');
      } ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-body" id="update-app">
                        <?php if ($divsAPI['divsConnection']['status'] == true) { ?>
                            <?php if ($divsAPI['divsUpdate']['status'] == true) { ?>
                                <div class="alert alert-warning alert-has-icon text-center alert-custom">
                                    <div class="alert-icon ml-3" style="position:relative;top:-2px;display:flex;align-items:center;">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="text-center mr-auto ml-auto">
                                        Proszę wykonać kopię zapasową plików oraz bazy danych przed aktualizacją
                                    </div>
                                </div>
                                <p class="text-center mt-4"><?php echo $divsAPI['divsUpdate']['message']; ?></p>
                                <div class="changelog">
                                    CHANGELOG:
                                    <div class="ml-4 changelog-pl">
                                        <?php echo $divsAPI['divsUpdate']['changelog']; ?>
                                    </div>
                                </div>
                                <?php if ($updateInfo['show_loader']) { ?>
                                    <div class="col-md-6 mr-auto ml-auto mt-3 mb-3">
                                    <progress id="prog" value="0" max="100" class="progress" role="progressbar" style="margin-bottom: 10px; width: 100%"></progress>
                                    </div>
                                    <?php $updateInfo['api']->download_update($updateInfo['update_id'], $updateInfo['has_sql'], $updateInfo['version'], null, null, ['db_host' => $this->db->hostname, 'db_user' => $this->db->username, 'db_pass' => $this->db->password, 'db_name' => $this->db->database]); ?>
                                <?php } else { ?>
                                    <?php echo form_open(base_url('panel/updates')); ?>
                                        <input type="hidden" name="update_id" class="form-control" value="<?php echo $divsAPI['divsUpdate']['update_id']; ?>">
                                        <input type="hidden" name="has_sql" class="form-control" value="<?php echo $divsAPI['divsUpdate']['has_sql']; ?>">
                                        <input type="hidden" name="version" class="form-control" value="<?php echo $divsAPI['divsUpdate']['version']; ?>">
                                        <div class="d-flex justify-content-center mt-4">
                                            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Aktualizuj</button>
                                        </div>
                                        <div class="d-flex justify-content-center" style="margin-top:10px;">
                                          Aktualna wersja: <?php echo $updateInfo['api']->get_current_version(); ?>
                                        </div>
                                    <?php echo form_close(); ?>
                                <?php } ?>
                            <?php } else { ?>
                                <h4 class="divshop-no-data">Aktualnie nie ma żadnych dostępnych aktualizacji</h4>
                                <div class="d-flex justify-content-center mt-4">
                                    <button class="btn btn-warning" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Sprawdź dostępność aktualizacji</button>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Wystąpił błąd podczas łączenia z API. Spróbuj odświeżyć stronę lub spróbuj użyć aktualizatora za kilka minut.</h4>
                            <p class="text-center text-muted">Problem się powtarza?</p>
                            <div class="d-flex justify-content-center">
                                <a target="_blank" href="https://status.divshop.pro/" class="btn btn-dark mr-3">Statusy usług</a>
                                <a target="_blank" href="https://divshop.pro/check-url/" class="btn btn-dark ml-3">Sprawdź URL</a>
                            </div>
                        <?php } ?>
                        <p class="text-center mt-5">Copyright &copy; <?php echo date('Y'); ?> by <a href="https://divshop.pro/" target="_blank" rel="noopener noreferrer">DIVShop.pro</a></p>
                    </div>
                </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

  <?php $this->load->view('components/Scripts'); ?>
</body>
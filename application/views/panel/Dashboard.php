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
            <h1>Dashboard</h1>
          </div>
          <?php if (file_exists(APPPATH.'views/panel/components/TopAlerts.php')) {
          $this->load->view('panel/components/TopAlerts');
      } else {
          exit('File <b>views/panel/components/TopAlerts.php</b> missing!');
      } ?>
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon">
                  <i class="fas fa-shopping-basket icon-big icon-warning"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Ilość usług</h4>
                  </div>
                  <div class="card-body">
                    <?php echo $statsData['services'] ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon">
                  <i class="fas fa-server icon-big icon-success"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Ilość serwerów</h4>
                  </div>
                  <div class="card-body">
                    <?php echo $statsData['servers']; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon">
                  <i class="fas fa-tags icon-big icon-danger"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Ilość zakupów</h4>
                  </div>
                  <div class="card-body">
                    <?php echo $statsData['purchases']; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon">
                  <i class="fas fa-money-bill-wave icon-big icon-aqua"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Zyski</h4>
                  </div>
                  <div class="card-body">
                    <?php echo number_format($statsData['profit'], 2, ',', ' '); ?>
                    <small class="font-weight-bold">zł</small>
                  </div>
                </div>
              </div>
            </div>                  
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
              <div class="card">
                <div class="card-header">
                  <h4><i class="bi bi-card-text"></i> Notatki</h4>
                </div>
                <?php echo($settings['demoMode'] == 0) ? form_open(base_url('panel/dashboard/update/notes')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                  <div class="card-body">
                    <div class="form-group">
                      <textarea class="form-control panel-notes" id="notesContent" name="notesContent" rows="3" style="height: 98px;" placeholder="Aktualnie nie ma notatek :)"><?php echo $settings['panelNotes']; ?></textarea>
                      <button type="submit" id="btnPanelNotesSave" class="btn btn-primary mt-3">Zapisz notatki</button>
                    </div>
                    <p class="text-muted"><b>UWAGA!</b> Notatki są widoczne dla wszystkich administratorów</p>
                  </div>
                <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
              </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
              <div class="card">
                <div class="card-header">
                  <h4><i class="bi bi-cash-stack"></i> Statystyki transakcji</h4>
                </div>
                <div class="card-body">
                  <canvas id="divshopPurchasesStatistics" height="132"></canvas>
                  <div class="statistic-details">
                    <div class="statistic-details-item">
                      <?php if ($percentTransactions == 0) { ?>
                        <span class="text-muted"><span class="text-muted"><i class="fas fa-caret-up"></i><i class="fas fa-caret-down"></i></span> 0%</span>
                        <div class="detail-name">Tyle samo transakcji co wczoraj</div>
                      <?php } elseif ($percentTransactions < 0) { ?>
                        <span class="text-muted"><span class="text-danger"><i class="fas fa-caret-down"></i></span> <?php echo str_replace('-', '', $percentTransactions); ?>%</span>
                        <div class="detail-name">Dzisiejsze transakcje (mniej niż wczoraj)</div>
                      <?php } elseif ($percentTransactions > 0) { ?>
                        <span class="text-muted"><span class="text-success"><i class="fas fa-caret-up"></i></span> <?php echo str_replace('-', '', $percentTransactions); ?>%</span>
                        <div class="detail-name">Dzisiejsze transakcje (więcej niż wczoraj)</div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-8">
              <div class="card">
                  <div class="card-header">
                      <h4><i class="bi bi-hdd-rack"></i> Statusy serwerów</h4>
                  </div>
                  <div class="card-body">
                      <?php if (!$servers) { ?>
                        <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych serwerów do wyświetlenia!</h4>
                      <?php } else { ?>
                        <div class="table-responsive">
                          <table class="table text-center table-md d-md-table mb-0">
                            <thead>
                              <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Nazwa</th>
                                <th class="text-center">Adres IP i port</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Wersja</th>
                                <th class="text-center">Ilość graczy</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($servers as $server) { ?>
                                <tr>
                                  <td>#<?php echo $server['id']; ?></td>
                                  <td><?php echo $server['name']; ?></td>
                                  <td><?php echo $server['ip'].':'.$server['port']; ?></td>
                                  <?php if (isset($server['status'])) { ?>
                                    <td><span class="badge badge-success badge-server"><i class="bi bi-check2"></i> Online</span></td>
                                    <td><span class="btn btn-primary btn-sm" data-toggle="tooltip" title="<?php if ($server['serverVersion'] == null) {
          echo $server['status']['version'];
      } else {
          echo $server['serverVersion'].' (Własne)';
      } ?>"><i class="fas fa-eye"></i></span></td>
                                    <td class="pt-3" style="position:relative;top:-1px;"><?php echo $server['status']['onlinePlayers'].'/'.$server['status']['maxPlayers']; ?></td>
                                  <?php } else { ?>
                                    <td><span class="badge badge-danger badge-server"><i class="bi bi-x"></i> Offline</span></td>
                                    <td data-toggle="tooltip" title="Not available">n/a</td>
                                    <td data-toggle="tooltip" title="Not available">n/a</td>
                                  <?php } ?>
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      <?php } ?>
                  </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h4><i class="bi bi-basket-fill" style="top:-3px;"></i> Ostatnie 5 zakupów</h4>
                </div>
                <div class="card-body">
                  <?php if (!$lastPurchases) { ?>
                    <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych zakupów do wyświetlenia!</h4>
                  <?php } else { ?>
                    <div class="table-responsive">
                      <table class="table text-center table-md d-md-table mb-0">
                        <thead>
                          <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Avatar</th>
                            <th class="text-center">Serwer</th>
                            <th class="text-center">Usługa</th>
                            <th class="text-center">Metoda</th>
                            <th class="text-center">Zysk</th>
                            <th class="text-center">Data</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($lastPurchases as $lastPurchase) { ?>
                            <tr>
                              <td class="pt-3">#<?php echo $lastPurchase['id']; ?></td>
                              <td>
                                <div class="avatar avatar-custom-size avatar-not-circle" data-toggle="tooltip" title="<?php echo xss_clean($lastPurchase['buyerName']); ?>">
                                  <img src="<?php echo 'https://mc-heads.net/avatar/'.xss_clean($lastPurchase['buyerName']).'/50'; ?>" alt="<?php echo xss_clean($lastPurchase['buyerName']); ?>">
                                </div>
                              </td>
                              <td class="pt-3"><?php echo $lastPurchase['server']; ?></td>
                              <td class="pt-3"><?php echo $lastPurchase['service']; ?></td>
                              <td class="pt-3">
                                <?php switch ($lastPurchase['method']) {
                                  case 'SMS Premium':
                                    echo '<span class="badge badge-warning badge-sm">SMS</span>';
                                    break;
                                  case 'PayPal':
                                    echo '<span class="badge badge-info badge-sm">PayPal</span>';
                                    break;
                                  case 'Przelew':
                                    echo '<span class="badge badge-danger badge-sm">Przelew</span>';
                                    break;
                                  case 'Voucher':
                                    echo '<span class="badge badge-dark badge-sm">Voucher</span>';
                                    break;
                                } ?>
                              </td>
                              <td class="pt-3"><?php echo($lastPurchase['method'] == 'Voucher') ? '0,00' : number_format($lastPurchase['profit'], 2, ',', ' '); ?> zł</td>
                              <td class="pt-3"><?php echo formatDate($lastPurchase['date']); ?></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-sm-8 col-md-6 col-lg-4 mr-auto ml-auto">
              <div class="card">
                <div class="card-header">
                  <h4><i class="bi bi-person-x"></i> Nieudane próby logowania</h4>
                </div>
                <div class="card-body">
                  <div class="failedlogins-card-scroll">
                    <?php if (!$failedLogins) { ?>
                      <h4 class="divshop-no-data"><i class="bi bi-emoji-smile"></i> Aktualnie nie ma nieudanych prób logowania!</h4>
                    <?php } else { ?>
                      <?php foreach ($failedLogins as $failedLogin) { ?>
                        <div class="failedlogin-item mt-2">
                          <div class="failedlogin-avatar avatar avatar-custom-size">
                            <img src="<?php echo $this->config->base_url('assets/images/default_avatar.png'); ?>" alt="Failed Login">
                          </div>
                          <div class="failedlogin-info">
                            <div class="failedlogin-username"><?php echo xss_clean($failedLogin['user']); ?></div>
                            <div class="failedlogin-date"><?php echo formatDate($failedLogin['time']); ?></div>
                          </div>
                        </div>
                      <?php } ?>
                    <?php } ?>
                  </div>
                  <?php if ($failedLogins) { ?>
                    <small class="d-flex justify-content-center mt-3">
                      <a href="<?php echo $this->config->base_url('panel/failedlogins'); ?>">Wszystkie nieudane próby logowania</a>
                    </small>
                  <?php } ?>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h4><i class="bi bi-gear"></i> Informacje o systemie i skrypcie</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table text-center table-md d-md-table mb-0" style="min-width:250px;">
                      <tr>
                        <td class="text-left">Wersja sklepu</td>
                        <td class="text-center"><span class="badge badge-sm <?php echo($divsAPI['divsUpdate']['status']) ? 'badge-danger' : 'badge-success'; ?>" data-toggle="tooltip" title="<?php echo($divsAPI['divsUpdate']['status']) ? 'Ta wersja sklepu nie jest aktualna!' : 'Ta wersja sklepu jest aktualna!'; ?>"><?php echo $divsAPI['divsVersion']; ?></span></td>
                      </tr>
                      <tr>
                        <td class="text-left">Wersja PHP</td>
                        <td class="text-center"><span class="badge badge-sm <?php echo(phpversion() < 7.0) ? 'badge-danger' : 'badge-success'; ?>" data-toggle="tooltip" title="<?php echo(phpversion() < 7.0) ? 'Przestarzała wersja PHP. Zalecana wersja: 7.0 lub wyższa' : 'Poprawna wersja PHP'; ?>"><?php echo phpversion(); ?></span></td>
                      </tr>
                      <tr>
                        <td class="text-left">System</td>
                        <td class="text-center"><span class="badge badge-info badge-sm"><?php echo PHP_OS; ?></span></td>
                      </tr>
                      <tr>
                        <td class="text-left">Ścieżka</td>
                        <td class="text-center">
                          <span data-toggle="tooltip" title="Kliknij, aby pokazać lub ukryć ścieżkę">
                            <button type="button" class="btn btn-primary btn-sm" data-container="body" data-toggle="popover" data-placement="left" data-content="<?php echo getcwd(); ?>">
                              <i class="fas fa-eye"></i>
                            </button>
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td class="text-left">Pomoc techniczna</td>
                        <td class="text-center">
                          <a class="icon-support" href="https://facebook.com/divshoppro" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" title="Kliknij, aby przejść na profil DIVShop.pro na Facebooku">
                            <i class="bi bi-facebook"></i>
                          </a>
                          <a class="icon-support" href="https://messenger.com/divshoppro" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" title="Kliknij, aby skontaktować się z zespołem DIVShop.pro przez Messengera">
                            <i class="bi bi-chat-dots"></i>
                          </a>
                          <a class="icon-support" href="mailto:contact@divshop.pro" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" title="Kliknij, aby skontaktować się z zespołem DIVShop.pro przez E-mail">
                            <i class="bi bi-envelope"></i>
                          </a>
                        </td>
                      </tr>
                    </table>
                  </div>
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
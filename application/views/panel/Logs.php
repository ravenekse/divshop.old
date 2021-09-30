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
            <h1>Logi</h1>
          </div>
          <?php if (file_exists(APPPATH.'views/panel/components/TopAlerts.php')) {
          $this->load->view('panel/components/TopAlerts');
      } else {
          exit('File <b>views/panel/components/TopAlerts.php</b> missing!');
      } ?>
          <div class="row">
            <div class="col-md-12 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-header justify-content-center d-md-block d-lg-flex text-center">
                        <h4><i class="bi bi-list-task"></i> Logi</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!$logs) { ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych logów do wyświetlenia!</h4>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table text-center table-md d-md-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Administrator</th>
                                            <th class="text-center">Sekcja</th>
                                            <th class="text-center">Szczegóły</th>
                                            <th class="text-center">Adres IP</th>
                                            <th class="text-center">Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($logs as $log) { ?>
                                            <tr>
                                                <td class="pt-3"><?php echo xss_clean($log['user']); ?></td>
                                                <td class="pt-3"><?php echo getLogBadge($log['section']); ?></td>
                                                <td class="pt-3">
                                                    <?php if ($log['section'] == 'Logowanie') {
          if ($settings['demoMode'] == 1 && strpos($log['details'], xss_clean($log['logIP']))) {
              echo str_replace(xss_clean($log['logIP']), 'W demo ukryte', $log['details']);
          } else {
              echo $log['details'];
          }
      } else {
          echo $log['details'];
      } ?>
                                                </td>
                                                <td class="pt-3">
                                                    <?php if ($settings['demoMode'] == 1 && $log['logIP'] != null) {
          echo 'W demo ukryte';
      } elseif ($log['logIP'] != null) {
          echo xss_clean($log['logIP']);
      } elseif ($log['logIP'] == null) {
          echo 'Brak';
      } ?>
                                                </td>
                                                <td class="pt-3"><?php echo formatDate($log['time']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo $pagination; ?>
                        <?php } ?>
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
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
            <h1>Nieudane próby logowania</h1>
          </div>
          <?php if(file_exists(APPPATH . 'views/panel/components/TopAlerts.php')):
            $this->load->view('panel/components/TopAlerts');
          else:
            die('File <b>views/panel/components/TopAlerts.php</b> missing!');
          endif ?>
          <div class="row">
            <div class="col-md-12 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-header justify-content-center d-md-block d-lg-flex text-center">
                        <h4><i class="bi bi-person-x"></i> Nieudane próby logowania</h4>
                    </div>
                    <div class="card-body">
                        <?php if(!$failedlogins): ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych nieudanych prób logowania do wyświetlenia!</h4>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-center table-md d-md-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nazwa użyta podczas logowania</th>
                                            <th class="text-center">Typ</th>
                                            <th class="text-center">Szczegóły</th>
                                            <th class="text-center">Adres IP</th>
                                            <th class="text-center">Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($failedlogins as $failedlogin): ?>
                                            <tr>
                                                <td class="pt-3"><?php echo xss_clean($failedlogin['user']); ?></td>
                                                <td class="pt-3"><?php echo $failedlogin['section']; ?></td>
                                                <td class="pt-3"><?php echo $failedlogin['details']; ?></td>
                                                <td class="pt-3">
                                                    <?php if($settings['demoMode'] == 1 && $failedlogin['ipAddress'] != null):
                                                        echo "W demo ukryte";
                                                    elseif($failedlogin['ipAddress'] != null):
                                                        echo xss_clean($failedlogin['ipAddress']);
                                                    elseif($failedlogin['ipAddress'] == null):
                                                        echo "Brak";
                                                    endif; ?>
                                                </td>
                                                <td class="pt-3"><?php echo formatDate($failedlogin['time']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo $pagination; ?>
                        <?php endif; ?>
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
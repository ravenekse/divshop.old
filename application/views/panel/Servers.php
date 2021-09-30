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
            <h1>Serwery</h1>
          </div>
          <?php if (file_exists(APPPATH.'views/panel/components/TopAlerts.php')) {
          $this->load->view('panel/components/TopAlerts');
      } else {
          exit('File <b>views/panel/components/TopAlerts.php</b> missing!');
      } ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-header justify-content-center d-md-block d-lg-flex text-center">
                        <h4><i class="bi bi-hdd-rack"></i> Lista serwerów</h4>
                        <div class="ml-md-auto">
                            <a href="<?php echo $this->config->base_url('panel/add/server'); ?>" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="bi bi-plus-circle"></i> Dodaj serwer
                            </a>
                        </div>
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
                                            <th class="text-center">Obrazek</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Wersja</th>
                                            <th class="text-center">Ilość graczy</th>
                                            <th class="text-center">Ustawienia połączenia</th>
                                            <th class="text-center">Port ukryty?</th>
                                            <th class="text-center">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($servers as $server) { ?>
                                            <tr>
                                                <td class="pt-3">#<?php echo $server['id']; ?></td>
                                                <td class="pt-3"><?php echo $server['name']; ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#divsServerImageID<?php echo $server['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                                <?php if (isset($server['status'])) { ?>
                                                    <td class="d-block" style="margin-top:4px;"><span class="badge badge-success badge-server"><i class="bi bi-check2"></i> Online</span></td>
                                                    <td><span class="btn btn-primary btn-sm" data-toggle="tooltip" title="<?php if ($server['serverVersion'] == null) {
          echo $server['status']['version'];
      } else {
          echo $server['serverVersion'].' (Własne)';
      } ?>"><i class="fas fa-eye"></i></span></td>
                                                    <td class="pt-3" style="position:relative;top:-1px;"><?php echo $server['status']['onlinePlayers'].'/'.$server['status']['maxPlayers']; ?></td>
                                                <?php } else { ?>
                                                    <td class="d-block" style="margin-top:4px;"><span class="badge badge-danger badge-server"><i class="bi bi-x"></i> Offline</span></td>
                                                    <td class="pt-3" data-toggle="tooltip" title="Not available">n/a</td>
                                                    <td class="pt-3" data-toggle="tooltip" title="Not available">n/a</td>
                                                <?php } ?>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#divsServerConnectionSettingsID<?php echo $server['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                                <td class="pt-3"><?php echo($server['showPort'] == 0) ? 'Tak' : 'Nie'; ?></td>
                                                <td class="d-flex">
                                                    <?php echo form_open(base_url('panel/edit/server')); ?>
                                                        <input type="hidden" name="serverId" value="<?php echo $server['id'] ?>">
                                                        <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edytuj serwer">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    <?php echo form_close(); ?>
                                                    <?php echo form_open(base_url('panel/remove/server')); ?>
                                                        <input type="hidden" name="serverId" value="<?php echo $server['id'] ?>">
                                                        <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="tooltip" title="Usuń serwer" onclick="areYouSure(this);">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    <?php echo form_close(); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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
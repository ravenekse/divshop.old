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
            <h1>Usługi</h1>
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
                        <h4><i class="bi bi-basket"></i> Lista usług</h4>
                        <div class="ml-md-auto">
                            <?php if (!$servers) { ?>
                                <a href="#" class="btn btn-success btn-sm btn-icon icon-left" disabled data-toggle="tooltip" title="Aktualnie dodawanie usług jest zablokowane, ponieważ nie ma żadnego serwera!">
                                    <i class="bi bi-plus-circle"></i> Dodaj usługę
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo $this->config->base_url('panel/add/service'); ?>" class="btn btn-success btn-sm btn-icon icon-left">
                                    <i class="bi bi-plus-circle"></i> Dodaj usługę
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!$services) { ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych usług do wyświetlenia!</h4>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table text-center table-md d-md-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Nazwa usługi</th>
                                            <th class="text-center">Serwer</th>
                                            <th class="text-center">Opis usługi, obrazek i komendy</th>
                                            <th class="text-center">Ustawienia płatności</th>
                                            <th class="text-center">Usługa widoczna?</th>
                                            <th class="text-center">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($services as $service) { ?>
                                            <tr>
                                                <td class="pt-3">#<?php echo $service['id']; ?></td>
                                                <td class="pt-3"><?php echo $service['name']; ?></td>
                                                <td class="pt-3"><?php echo $service['server'].' (ID: #'.$service['serverid'].')'; ?></td>
                                                <td class="pt-3">
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#divsServiceImageDescriptionCommandsID<?php echo $service['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                                <td class="pt-3">
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#divsServicePaymentsSettingsID<?php echo $service['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                                <td class="pt-3"><?php echo ($service['serviceActive'] == 0) ? 'Nie' : 'Tak'; ?></td>
                                                <td class="d-flex">
                                                    <?php echo form_open(base_url('panel/edit/service')); ?>
                                                        <input type="hidden" name="serviceId" value="<?php echo $service['id'] ?>">
                                                        <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edytuj usługę">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    <?php echo form_close(); ?>
                                                    <?php echo form_open(base_url('panel/remove/service')); ?>
                                                        <input type="hidden" name="serviceId" value="<?php echo $service['id'] ?>">
                                                        <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="tooltip" title="Usuń usługę" onclick="areYouSure(this);">
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
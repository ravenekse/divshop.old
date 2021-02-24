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
            <h1>Vouchery</h1>
          </div>
          <?php if(file_exists(APPPATH . 'views/panel/components/TopAlerts.php')):
            $this->load->view('panel/components/TopAlerts');
          else:
            die('File <b>views/panel/components/TopAlerts.php</b> missing!');
          endif ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-header justify-content-center d-md-block d-lg-flex text-center">
                        <h4><i class="bi bi-tags"></i> Lista voucherów</h4>
                        <div class="ml-md-auto">
                            <?php if(!$services): ?>
                                <a href="#" class="btn btn-success btn-sm btn-icon icon-left" disabled data-toggle="tooltip" title="Aktualnie dodawanie voucherów jest zablokowane, ponieważ nie ma żadnej usługi!">
                                    <i class="bi bi-plus-circle"></i> Dodaj voucher
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $this->config->base_url('panel/add/voucher'); ?>" class="btn btn-success btn-sm btn-icon icon-left">
                                    <i class="bi bi-plus-circle"></i> Dodaj voucher
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(!$vouchers): ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych voucherów do wyświetlenia!</h4>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-center table-md d-md-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Serwer</th>
                                            <th class="text-center">Usługa</th>
                                            <th class="text-center">Voucher</th>
                                            <th class="text-center">Data utworzenia</th>
                                            <th class="text-center">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($vouchers as $voucher): ?>
                                            <tr>
                                                <td class="pt-3">#<?php echo $voucher['id']; ?></td>
                                                <td class="pt-3"><?php echo $voucher['server']; ?></td>
                                                <td class="pt-3"><?php echo $voucher['service']; ?></td>
                                                <td class="pt-3"><?php echo $voucher['code']; ?></td>
                                                <td class="pt-3"><?php echo formatDate($voucher['generated']); ?></td>
                                                <td class="d-flex">
                                                    <?php echo form_open(base_url('panel/remove/voucher')); ?>
                                                        <input type="hidden" name="voucherId" value="<?php echo $voucher['id'] ?>">
                                                        <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="tooltip" title="Usuń voucher" onclick="areYouSure(this);">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    <?php echo form_close(); ?>
                                                </td>
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
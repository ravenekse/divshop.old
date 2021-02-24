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
            <h1>Historia zakupów</h1>
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
                        <h4><i class="bi bi-clock-history"></i> Historia zakupów</h4>
                    </div>
                    <div class="card-body">
                        <?php if(!$lastPurchases): ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych zakupów do wyświetlenia!</h4>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-center table-md d-md-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Avatar</th>
                                            <th class="text-center">Usługa</th>
                                            <th class="text-center">Serwer</th>
                                            <th class="text-center">Metoda</th>
                                            <th class="text-center">Szczegóły</th>
                                            <th class="text-center">ID Płatności</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Zysk</th>
                                            <th class="text-center">Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($lastPurchases as $lastPurchase): ?>
                                            <tr>
                                                <td class="pt-3">#<?php echo $lastPurchase['id']; ?></td>
                                                <td>
                                                    <div class="avatar avatar-custom-size avatar-not-circle" data-toggle="tooltip" title="<?php echo $lastPurchase['buyerName']; ?>">
                                                    <img src="<?php echo 'https://mc-heads.net/avatar/' . $lastPurchase['buyerName'] . '/50'; ?>" alt="<?php echo $lastPurchase['buyerName']; ?>">
                                                    </div>
                                                </td>
                                                <td class="pt-3"><?php echo $lastPurchase['service']; ?></td>
                                                <td class="pt-3"><?php echo $lastPurchase['server']; ?></td>
                                                <td class="pt-3">
                                                  <?php switch($lastPurchase['method']):
                                                      case "SMS Premium":
                                                        echo '<span class="badge badge-warning badge-sm">SMS Premium</span>';
                                                        break;
                                                      case "PayPal":
                                                        echo '<span class="badge badge-info badge-sm">PayPal</span>';
                                                        break;
                                                      case "Przelew":
                                                        echo '<span class="badge badge-danger badge-sm">Przelew</span>';
                                                        break;
                                                      case "Voucher":
                                                        echo '<span class="badge badge-dark badge-sm">Voucher</span>';
                                                        break;
                                                  endswitch; ?>
                                                </td>
                                                <td class="pt-3"><?php echo($lastPurchase['details'] != null) ? $lastPurchase['details'] : '---'; ?></td>
                                                <td class="pt-4"><?php echo($lastPurchase['payId'] != null) ? $lastPurchase['payId'] : '---'; ?></td>
                                                <td class="pt-3">
                                                    <?php if($lastPurchase['status'] == "failed"): ?>
                                                        <i class="bi bi-hand-thumbs-down text-danger" style="font-size:19px;" data-toggle="tooltip" title="Zakup nie powódł się"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-hand-thumbs-up text-success" style="font-size:19px;" data-toggle="tooltip" title="Zakup powódł się"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="pt-3"><?php echo($lastPurchase['method'] == "Voucher") ? '0,00' : number_format($lastPurchase['profit'], 2, ',', ' '); ?> zł</td>
                                                <td class="pt-3"><?php echo($lastPurchase['date'] != null) ? formatDate($lastPurchase['date']) : 'Nigdy'; ?></td>
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
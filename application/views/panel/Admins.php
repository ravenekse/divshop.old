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
            <h1>Użytkownicy ACP</h1>
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
                        <h4><i class="bi bi-people"></i> Lista użytkowników ACP</h4>
                        <div class="ml-md-auto">
                            <a href="<?php echo $this->config->base_url('panel/add/admin'); ?>" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="bi bi-plus-circle"></i> Dodaj administratora
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(!$admins): ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych administratorów do wyświetlenia!</h4>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-center table-md d-md-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Avatar</th>
                                            <th class="text-center">Nazwa</th>
                                            <th class="text-center">Adres e-mail</th>
                                            <th class="text-center">Przeglądarka</th>
                                            <th class="text-center">Ostatni adres IP</th>
                                            <th class="text-center">Ostatnie logowanie</th>
                                            <th class="text-center">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($admins as $admin): ?>
                                            <tr>
                                                <td class="pt-3">#<?php echo $admin['id']; ?></td>
                                                <td>
                                                    <div class="avatar avatar-custom-size">
                                                        <img src="<?php echo($admin['image'] != null) ? $admin['image'] : $this->config->base_url('assets/images/default_avatar.png'); ?>" alt="<?php echo $admin['name']; ?>">
                                                    </div>
                                                </td>
                                                <td class="pt-3"><?php echo $admin['name']; ?></td>
                                                <td class="pt-3"><?php echo($admin['email'] != null) ? $admin['email'] : 'Brak adresu e-mail'; ?></td>
                                                <td>
                                                    <span data-toggle="tooltip" title="Kliknij, aby pokazać lub ukryć przeglądarkę">
                                                        <button type="button" class="btn btn-primary btn-sm" data-container="body" data-toggle="popover" data-placement="left" data-content="<?php echo($admin['browser'] != null) ? $admin['browser'] : 'Nie dostępne'; ?>">
                                                        <i class="fas fa-eye"></i>
                                                        </button>
                                                    </span>
                                                </td>
                                                <td class="pt-3">
                                                    <?php if($settings['demoMode'] == 1 && $admin['lastIP'] != null): ?>
                                                        W demo ukryte
                                                    <?php elseif($settings['demoMode'] != 1 && $admin['lastIP']): ?>
                                                        <?php echo $admin['lastIP']; ?>
                                                    <?php elseif($admin['lastIP'] == null): ?>
                                                        Niedostępne
                                                    <?php endif; ?>
                                                </td>
                                                <td class="pt-3"><?php echo($admin['lastLogin'] != null) ? formatDate($admin['lastLogin']) : 'Nigdy'; ?></td>
                                                <td class="d-flex">
                                                    <?php if($admin['name'] == $this->session->userdata('name')): ?>
                                                        <a href="<?php echo $this->config->base_url('panel/account'); ?>" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edytuj administratora">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="tooltip" title="Usuń administratora" onclick="notToday();">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <?php echo form_open(base_url('panel/edit/admin')); ?>
                                                            <input type="hidden" name="adminId" value="<?php echo $admin['id']; ?>">
                                                            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edytuj administratora">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                        <?php echo form_close(); ?>
                                                        <?php if($admin['id'] != 1): ?>
                                                            <?php echo form_open(base_url('panel/remove/admin')); ?>
                                                                <input type="hidden" name="adminId" value="<?php echo $admin['id']; ?>">
                                                                <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="tooltip" title="Usuń administratora" onclick="areYouSure(this);">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            <?php echo form_close(); ?>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="tooltip" title="Usuń administratora" onclick="youCanNot();">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
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
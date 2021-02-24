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
            <h1>Moduły</h1>
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
                        <h4><i class="bi bi-stack"></i> Lista modułów</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if(!$modules): ?>
                                <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych modułów do wyświetlenia!</h4>
                            <?php else: ?>
                                <?php foreach($modules as $module): ?>
                                    <div class="col-md-4 col-sm-6 pb-1">
                                        <div class="card card-module">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-5">
                                                        <i class="<?php echo getModuleIcon($module['moduleName']); ?>" style="font-size:24px"></i>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h4 class="font-weight-bold text-muted"><?php echo getModuleName($module['moduleName']); ?></h4>
                                                        <p class="font-weight-bold">
                                                            <?php echo getModuleDescription($module['moduleName']); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge badge-success badge-sm" data-toggle="tooltip" title="Moduł systemowy">System</span>
                                                    <div class="ml-auto">
                                                        <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/modules/changeModuleStatus')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                                            <input type="hidden" name="moduleId" value="<?php echo $module['id']; ?>">
                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                <label class="<?php echo($module['moduleEnabled'] == 0) ? '' : 'active'; ?> btn btn-sm btn btn-background-transparent <?php echo($module['moduleEnabled'] == 0) ? 'text-success' : 'text-danger'; ?> text-uppercase">
                                                                    <input type="checkbox" name="moduleStatus" id="moduleStatus" onchange="changeModuleStatus(this)" <?php echo($module['moduleEnabled'] == 0) ? '' : 'checked'; ?>>
                                                                    <?php echo($module['moduleEnabled'] == 0) ? 'Włącz moduł' : 'Wyłącz moduł'; ?>
                                                                </label>
                                                            </div>
                                                        <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
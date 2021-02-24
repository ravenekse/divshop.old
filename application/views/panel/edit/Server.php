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
            <h1>Edycja serwera <?php echo $server['name'] . " (ID: #" . $server['id'] . ")"; ?></h1>
          </div>
          <?php if(file_exists(APPPATH . 'views/panel/components/TopAlerts.php')):
            $this->load->view('panel/components/TopAlerts');
          else:
            die('File <b>views/panel/components/TopAlerts.php</b> missing!');
          endif ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                            <div class="col-xl-12 col-xxl-10">
                                <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/edit/server/save')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                    <input type="hidden" name="serverId" value="<?php echo $server['id']; ?>">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Obrazek serwera</label>
                                              <div class="col-lg-9 col-xl-8 col-md-12">
                                                  <div class="custom-file">
                                                    <label class="custom-file-label" for="customFile">Wybierz plik</label>
                                                    <input type="file" class="custom-file-input" id="customFile" name="serverImage" accept=".png, .jpg, .jpeg, .gif">
                                                  </div>
                                                  <small id="removeImage" style="cursor:pointer;display:none;"><b>Usuń zdjęcie</b></small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Nazwa serwera <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="serverName" value="<?php echo $server['name']; ?>">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Adres IP i port <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-6">
                                                <input type="text" class="form-control" name="serverIpAddress" value="<?php echo $server['ip']; ?>">
                                              </div>
                                              <div class="col-3">
                                                <input type="number" class="form-control" name="serverPort" value="<?php echo $server['port']; ?>" max="65535">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Hasło i port RCON <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-6">
                                                <div class="input-group mb-3">
                                                    <input type="password" id="divsServerRconPass" name="serverRconPass" class="form-control" value="<?php echo $server['rconPass']; ?>">
                                                    <div class="input-group-append">
                                                      <span class="input-group-text show-password-btn" onclick="showRconPassword();" data-toggle="tooltip" title="Kliknij, aby pokazać lub ukryć hasło RCON">
                                                          <i class="bi bi-eye" id="showPassword"></i>
                                                          <i class="bi bi-eye-slash d-none" id="hidePassword"></i>
                                                      </span>
                                                    </div>
                                                </div>
                                              </div>
                                              <div class="col-3">
                                                <input type="number" class="form-control" name="serverRconPort" max="65535" value="<?php echo $server['rconPort']; ?>">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Własna wersja serwera | Wyświetlanie portu serwera</label>
                                              <div class="col-3">
                                                <label class="custom-switch mt-2">
                                                  <label for="btnOtherVersion" class="mr-2 mt-1">Off</label>
                                                  <input type="checkbox" id="otherServerVersion" name="otherServerVersion" onclick="useOtherVersion();" class="custom-switch-input" <?php echo($server['serverVersion'] == null) ? '' : 'checked' ?>>
                                                  <span class="custom-switch-indicator"></span>
                                                  <label for="btnOtherVersion" class="ml-2 mt-1">On</label>
                                                </label>
                                                <label for="btnOtherVersion">Czy własna wersja serwera?</label>
                                              </div>
                                              <div class="col-3">
                                                <label class="custom-switch mt-2">
                                                  <label for="serverShowPort" class="mr-2 mt-1">Off</label>
                                                  <input type="checkbox" id="serverShowPort" name="serverShowPort" class="custom-switch-input"<?php echo($server['showPort'] == 0) ? '' : 'checked' ?>>
                                                  <span class="custom-switch-indicator"></span>
                                                  <label for="serverShowPort" class="ml-2 mt-1">On</label>
                                                </label>
                                                <label for="serverShowPort">Czy wyświetlać port serwera?</label>
                                              </div>
                                            </div>
                                            <div class="form-group row" id="otherVersionField" <?php echo($server['serverVersion'] == null) ? 'style="display: none;"' : '' ?>>
                                              <label class="col-xl-3 col-lg-3 col-form-label text-left">Własna wersja serwera <span data-toggle="tooltip" data-theme="dark" title="Pole wymagane" class="text-danger">*</span></label>
                                              <div class="col-xl-5 col-lg-6 col-9">
                                                  <input class="form-control" name="serverVersion" id="serverVersion" type="text" value="<?php echo($server['serverVersion']); ?>">
                                              </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" id="btnEditSubmit" class="btn btn-primary btn-xs mt-4"><i class="bi bi-check"></i> Potwierdź i zapisz</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                            </div>
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
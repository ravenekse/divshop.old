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
            <h1>Edycja strony <?php echo $page['title'].' (ID: #'.$page['id'].')'; ?></h1>
          </div>
          <?php if (file_exists(APPPATH.'views/panel/components/TopAlerts.php')) {
          $this->load->view('panel/components/TopAlerts');
      } else {
          exit('File <b>views/panel/components/TopAlerts.php</b> missing!');
      } ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                            <div class="col-xl-12 col-xxl-10">
                                <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/edit/page/save')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                    <input type="hidden" name="pageId" value="<?php echo $page['id']; ?>">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-12">
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Nazwa strony <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="pageTitle" value="<?php echo $page['title']; ?>">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Odnośnik</label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="url" class="form-control" name="pageLink" value="<?php echo $page['link']; ?>">
                                                <small>Umieść tu link, na który ma przekierowywać przycisk w nawigacji. Jeśli to pole zostanie puste wyświetli się zawartość pola "Zawartość strony"</small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Ikona</label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="pageIcon" value="<?php echo $page['icon']; ?>">
                                                <small>
                                                    Tutaj możesz wpisać klasę ikony FontAwesome lub Bootstrap Icons. Będzie się ona wyświetlać obok nazwy strony w nawigacji. Przykłady:
                                                    <br>
                                                    <b>fas fa-address-book</b> - FontAwesome 5
                                                    <br>
                                                    <b>bi bi-bookmarks</b> - Bootstrap Icons
                                                    <br>
                                                    Pełne listy ikon można znaleźć tutaj: <a href="https://fontawesome.com/icons?d=gallery" target="_blank">Ikony FontAwesome 5</a> lub <a href="https://icons.getbootstrap.com/" target="_blank">Ikony Bootstrap Icons</a>
                                                </small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Zawartość strony</label>
                                              <div class="col-lg-12 col-xl-12">
                                                <textarea name="pageContent" class="summernote"><?php echo $page['content']; ?></textarea>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Czy strona włączona</label>
                                                <div class="col-3">
                                                    <label class="custom-switch mt-2">
                                                        <label for="pageActive" class="mr-2 mt-1">Off</label>
                                                        <input type="checkbox" name="pageActive" class="custom-switch-input" <?php echo($page['pageActive'] == 0) ? '' : 'checked'; ?>>
                                                        <span class="custom-switch-indicator"></span>
                                                        <label for="pageActive" class="ml-2 mt-1">On</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" id="btnEditSubmit" class="btn btn-primary btn-xs mt-4"><i class="bi bi-check"></i> Potwierdź i dodaj</button>
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
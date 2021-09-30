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
            <h1>Własne strony</h1>
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
                        <h4><i class="bi bi-file-earmark-code"></i> Lista stron</h4>
                        <div class="ml-md-auto">
                            <a href="<?php echo $this->config->base_url('panel/add/page'); ?>" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="bi bi-plus-circle"></i> Dodaj stronę
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!$pages) { ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych stron do wyświetlenia!</h4>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table text-center table-md d-md-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Tytuł</th>
                                            <th class="text-center">Ikona</th>
                                            <th class="text-center">Link</th>
                                            <th class="text-center">Treść strony</th>
                                            <th class="text-center">Strona włączona?</th>
                                            <th class="text-center">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pages as $page) { ?>
                                            <tr>
                                                <td class="pt-3">#<?php echo $page['id']; ?></td>
                                                <td class="pt-3"><?php echo $page['title']; ?></td>
                                                <td class="pt-2" style="font-size:22px;"><?php echo($page['icon'] != null) ? '<i class="'.$page['icon'].'"></i>' : 'Brak ikony'; ?></td>
                                                <td class="pt-3">
                                                  <?php if ($page['link'] != null) { ?>
                                                    <a href="<?php echo $page['link']; ?>" target="_blank"><?php echo $page['title']; ?></a>
                                                  <?php } else { ?>
                                                    <a href="<?php echo $this->config->base_url('page/'.getPageUrl($page['title'])); ?>" target="_blank"><?php echo $page['title']; ?></a>
                                                  <?php } ?>
                                                </td>
                                                <td class="pt-3">
                                                  <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#divsPageContentID<?php echo $page['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                  </button>
                                                </td>
                                                <td class="pt-3"><?php echo($page['pageActive'] == 0) ? 'Nie' : 'Tak'; ?></td>
                                                <td class="d-flex pt-3">
                                                  <?php echo form_open(base_url('panel/edit/page')); ?>
                                                    <input type="hidden" name="pageId" value="<?php echo $page['id'] ?>">
                                                    <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edytuj stronę">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                  <?php echo form_close(); ?>
                                                  <?php echo form_open(base_url('panel/remove/page')); ?>
                                                    <input type="hidden" name="pageId" value="<?php echo $page['id'] ?>">
                                                    <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="tooltip" title="Usuń stronę" onclick="areYouSure(this);">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                  <?php echo form_close(); ?>
                                                </td>
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
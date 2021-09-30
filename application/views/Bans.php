<?php $this->load->view('components/Navbar'); ?>
	<div class="page-header header-filter header-small divshop-bg-header" data-parallax="true">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <div class="brand text-center divshop-header-shop-title">
                        <h1><?php echo $pageTitle; ?></h1>
                        <h3 class="title text-center"><?php echo $pageSubtitle; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
      
    <div class="main main-raised" style="padding-bottom: 120px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12 pt-4">
                    <div class="row">
                        <?php if (!$banList) { ?>
                            <div class="mr-auto ml-auto">
                                <h6 class="text-center"><i class="fas fa-exclamation-triangle"></i> Aktualnie nie ma żadnych graczy do wyświetlenia</h6>
                            </div>
                        <?php } else { ?>
                            <table class="table table-responsive d-sm-table mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">Gracz</th>
                                        <th class="text-center">Banujący</th>
                                        <th class="text-center">Data bana</th>
                                        <th class="text-center">Wygasa</th>
                                        <th class="text-center">Powód</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($banList as $ban) {  ?>
                                        <tr class="divshop-table-element">
                                            <td class="text-center">
                                                <div class="d-block d-md-flex justify-content-center align-items-center">
                                                    <img src="<?php echo 'https://mc-heads.net/avatar/'.xss_clean($ban['name']).'/36'; ?>" alt="<?php echo xss_clean($ban['name']); ?>" class="img-fluid rounded">
                                                    <span class="ml-md-4"><?php echo xss_clean($ban['name']); ?></span>
                                                </div>
                                            </td>
                                            <td class="d-flex justify-content-center">
                                                <div class="d-block d-md-flex align-items-center">
                                                    <img src="<?php echo 'https://mc-heads.net/avatar/'.xss_clean($ban['operator']).'/36'; ?>" alt="<?php echo xss_clean($ban['operator']); ?>" class="img-fluid rounded">
                                                    <span class="ml-md-4"><?php echo $ban['operator']; ?></span>
                                                </div>
                                            </td>
                                            <td class="pt-3"><?php echo getOnlyDate($ban['start'] / 1000); ?> <span class="badge badge-ban-hour"><?php echo getHourWithMinute($ban['start'] / 1000); ?></span></td>
                                            <td class="pt-3"><?php echo getOnlyDate($ban['end'] / 1000); ?> <span class="badge badge-ban-hour"><?php echo getHourWithMinute($ban['end'] / 1000); ?></span></td>
                                            <td class="pt-3"><?php echo $ban['reason']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="mr-auto ml-auto">
                                <?php echo $pagination; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>
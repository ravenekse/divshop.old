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
                        <?php if(!$playerStats): ?>
                            <div class="mr-auto ml-auto">
                                <h6 class="text-center"><i class="fas fa-exclamation-triangle"></i> Aktualnie nie ma żadnych graczy do wyświetlenia</h6>
                            </div>
                        <?php else: ?>
                            <table class="table table-responsive d-sm-table mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="padding-left:50px;">Pozycja/Gracz</th>
                                        <th class="text-center">Ilość punktów</th>
                                        <th class="text-center">Zabójstw</th>
                                        <th class="text-center">Śmierci</th>
                                        <th class="text-center">Gildia</th>
                                        <th class="text-center">Ban gildii?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($playerStats as $playerStat):  ?>
                                        <tr class="divshop-table-element">
                                            <td>
                                                <div class="d-block d-md-flex float-left justify-content-center align-items-center">
                                                    <span class="mr-3"><?php echo $playerPos++; ?></span>
                                                    <img src="<?php echo 'https://mc-heads.net/avatar/' . xss_clean($playerStat['name']) . '/36'; ?>" alt="<?php echo xss_clean($playerStat['name']); ?>" class="img-fluid rounded player-image">
                                                    <span class="ml-4"><?php echo xss_clean($playerStat['name']); ?></span>
                                                </div>
                                            </td>
                                            <td class="pt-3"><?php echo $playerStat['points']; ?></td>
                                            <td class="pt-3"><?php echo $playerStat['kills']; ?></td>
                                            <td class="pt-3"><?php echo $playerStat['deaths']; ?></td>
                                            <?php if($playerStat['guild'] != null): ?>
                                                <td class="pt-3"><?php echo xss_clean($playerStat['guild']); ?></td>
                                            <?php else: ?>
                                                <td class="pt-3">Brak gildii</td>
                                            <?php endif; ?>
                                            <?php if($playerStat['ban'] == 0): ?>
                                                <td class="pt-3">Nie</td>
                                            <?php else: ?>
                                                <td class="pt-3">Tak</td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="mr-auto ml-auto">
                                <?php echo $pagination; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>
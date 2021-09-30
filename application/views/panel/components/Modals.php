<?php if ($this->uri->segment('2') == 'servers') { ?>
    <?php if ($servers) { ?>
        <?php foreach ($servers as $server) { ?>
            <div class="modal fade" tabindex="-1" role="dialog" id="divsServerImageID<?php echo $server['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-whitesmoke">
                            <h5 class="modal-title">Obrazek serwera <?php echo $server['name'].' (ID: #'.$server['id'].')'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="bi bi-x"></i></span>
                            </button>
                        </div>
                        <div class="modal-body mr-auto ml-auto text-center">
                            <?php if ($server['image'] == null) { ?>
                                <h6>Aktualnie serwer <?php echo $server['name'].' (ID: #'.$server['id'].')'; ?> nie posiada obrazka. Możesz to zmienić edytując ten serwer</h6>
                            <?php } else { ?>
                                <img class="img-fluid rounded" src="<?php echo $server['image']; ?>" alt="Obrazek serwera <?php echo $server['name'] ?>">
                            <?php } ?>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-background-transparent text-danger text-uppercase" data-dismiss="modal">Zamknij</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="divsServerConnectionSettingsID<?php echo $server['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-whitesmoke">
                            <h5 class="modal-title">Ustawienia połączenia serwera <?php echo $server['name'].' (ID: #'.$server['id'].')'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="bi bi-x"></i></span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <nav>
                                <div class="nav nav-tabs d-flex justify-content-center" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="divsServerIPAddressPortID<?php echo $server['id']; ?>-tab" data-toggle="tab" href="#divsServerIPAddressPortID<?php echo $server['id']; ?>" role="tab" aria-controls="divsServerIPAddressPortID<?php echo $server['id']; ?>" aria-selected="true">Adres IP i port</a>
                                    <a class="nav-item nav-link" id="divsServerRconPassPortID<?php echo $server['id']; ?>-tab" data-toggle="tab" href="#divsServerRconPassPortID<?php echo $server['id']; ?>" role="tab" aria-controls="divsServerRconPassPortID<?php echo $server['id']; ?>" aria-selected="false">Hasło i port RCON</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="divsServerIPAddressPortID<?php echo $server['id']; ?>" role="tabpanel" aria-labelledby="divsServerIPAddressPortID<?php echo $server['id']; ?>-tab">
                                    <div class="table-reponsive">
                                        <table class="table text-center table-md d-md-table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Adres IP serwera</td>
                                                    <td><?php echo $server['ip'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Port serwera</td>
                                                    <td><?php echo $server['port'] ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="divsServerRconPassPortID<?php echo $server['id']; ?>" role="tabpanel" aria-labelledby="divsServerRconPassPortID<?php echo $server['id']; ?>-tab">
                                    <div class="table-reponsive">
                                        <table class="table text-center table-md d-md-table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">Port RCON</td>
                                                    <td class="text-center"><?php echo $server['rconPort'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center pt-3">Hasło RCON</td>
                                                    <td class="text-center">
                                                        <div class="form-group">
                                                            <div class="input-group mb-3">
                                                                <input type="password" id="divsServerRconPass<?php echo $server['id']; ?>" class="form-control" value="<?php echo $server['rconPass'] ?>" disabled="">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text show-password-btn" onclick="showRconPassword<?php echo $server['id']; ?>();" data-toggle="tooltip" title="Kliknij, aby pokazać lub ukryć hasło RCON">
                                                                        <i class="bi bi-eye" id="showPassword<?php echo $server['id']; ?>"></i>
                                                                        <i class="bi bi-eye-slash d-none" id="hidePassword<?php echo $server['id']; ?>"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-background-transparent text-danger text-uppercase" data-dismiss="modal">Zamknij</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>

<?php if ($this->uri->segment('2') == 'services') { ?>
    <?php if ($services) { ?>
        <?php foreach ($services as $service) { ?>
            <div class="modal fade" tabindex="-1" role="dialog" id="divsServiceImageDescriptionCommandsID<?php echo $service['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-whitesmoke">
                            <h5 class="modal-title">Opis usługi, obrazek i komendy <?php echo $service['name'].' (ID: #'.$service['id'].')'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="bi bi-x"></i></span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <nav>
                                <div class="nav nav-tabs d-flex justify-content-center" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="divsServiceDescriptionID<?php echo $service['id']; ?>-tab" data-toggle="tab" href="#divsServiceDescriptionID<?php echo $service['id']; ?>" role="tab" aria-controls="divsServiceDescriptionID<?php echo $service['id']; ?>" aria-selected="true">Opis usługi</a>
                                    <a class="nav-item nav-link" id="divsServiceImageID<?php echo $service['id']; ?>-tab" data-toggle="tab" href="#divsServiceImageID<?php echo $service['id']; ?>" role="tab" aria-controls="divsServiceImageID<?php echo $service['id']; ?>" aria-selected="false">Obrazek usługi</a>
                                    <a class="nav-item nav-link" id="divsServiceCommandsID<?php echo $service['id']; ?>-tab" data-toggle="tab" href="#divsServiceCommandsID<?php echo $service['id']; ?>" role="tab" aria-controls="divsServiceCommandsID<?php echo $service['id']; ?>" aria-selected="false">Komendy</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="divsServiceDescriptionID<?php echo $service['id']; ?>" role="tabpanel" aria-labelledby="divsServiceDescriptionID<?php echo $service['id']; ?>-tab">
                                    <div class="d-grid justify-content-center mt-3">
                                        <?php if ($service['description'] == null) { ?>
                                            <h6>Aktualnie usługa <?php echo $service['name'].' (ID: #'.$service['id'].')'; ?> nie posiada opisu. Możesz to zmienić edytując tą usługę</h6>
                                        <?php } else { ?>
                                            <?php echo $service['description']; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="divsServiceImageID<?php echo $service['id']; ?>" role="tabpanel" aria-labelledby="divsServiceImageID<?php echo $service['id']; ?>-tab">
                                    <div class="d-grid justify-content-center mt-3">
                                        <?php if ($service['image'] == null) { ?>
                                            <h6>Aktualnie usługa <?php echo $service['name'].' (ID: #'.$service['id'].')'; ?> nie posiada obrazka. Możesz to zmienić edytując tą usługę</h6>
                                        <?php } else { ?>
                                            <img src="<?php echo $service['image']; ?>" alt="<?php echo $service['image']; ?>" class="img-fluid rounded">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="divsServiceCommandsID<?php echo $service['id']; ?>" role="tabpanel" aria-labelledby="divsServiceCommandsID<?php echo $service['id']; ?>-tab">
                                    <div class="d-grid justify-content-center mt-3">
                                        <?php if ($service['commands'] == null) { ?>
                                            <h6>Aktualnie usługa <?php echo $service['name'].' (ID: #'.$service['id'].')'; ?> nie posiada komend. Możesz to zmienić edytując tą usługę</h6>
                                        <?php } else { ?>
                                            <pre>
                                                <table class="table">
                                                    <tbody>
                                                        <?php foreach ($service['commands'] as $command) { ?>
                                                            <tr class="service-command">
                                                                <td><?php echo $command; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </pre>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-background-transparent text-danger text-uppercase" data-dismiss="modal">Zamknij</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="divsServicePaymentsSettingsID<?php echo $service['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-whitesmoke">
                            <h5 class="modal-title">Ustawienia płatności usługi <?php echo $service['name'].' (ID: #'.$service['id'].')'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="bi bi-x"></i></span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <nav>
                                <div class="nav nav-tabs d-flex justify-content-center" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="divsServicePaymentSettingsSMS<?php echo $service['id']; ?>-tab" data-toggle="tab" href="#divsServicePaymentSettingsSMS<?php echo $service['id']; ?>" role="tab" aria-controls="divsServicePaymentSettingsSMS<?php echo $service['id']; ?>" aria-selected="true">SMS Premium</a>
                                    <a class="nav-item nav-link" id="divsServicePaymentSettingsPayPal<?php echo $service['id']; ?>-tab" data-toggle="tab" href="#divsServicePaymentSettingsPayPal<?php echo $service['id']; ?>" role="tab" aria-controls="divsServicePaymentSettingsPayPal<?php echo $service['id']; ?>" aria-selected="false">PayPal</a>
                                    <a class="nav-item nav-link" id="divsServicePaymentSettingsTransfer<?php echo $service['id']; ?>-tab" data-toggle="tab" href="#divsServicePaymentSettingsTransfer<?php echo $service['id']; ?>" role="tab" aria-controls="divsServicePaymentSettingsTransfer<?php echo $service['id']; ?>" aria-selected="false">Przelewy</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="divsServicePaymentSettingsSMS<?php echo $service['id']; ?>" role="tabpanel" aria-labelledby="divsServicePaymentSettingsSMS<?php echo $service['id']; ?>-tab">
                                    <div class="table-reponsive">
                                        <table class="table text-center table-md d-md-table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Operator SMS</td>
                                                    <td>MicroSMS.pl</td>
                                                </tr>
                                                <tr>
                                                    <td>Kanał SMS</td>
                                                    <td><?php echo $service['smsConfig']['smsChannel']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>ID Kanału SMS</td>
                                                    <td><?php echo $service['smsConfig']['smsChannelId']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Numer SMS</td>
                                                    <td><?php echo $service['smsConfig']['smsNumber']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Koszt SMS</td>
                                                    <td><?php echo getNettoPrice($service['smsConfig']['smsNumber'], 1); ?> zł (<?php echo getBruttoPrice($service['smsConfig']['smsNumber'], 1); ?> zł z VAT)</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php if ($service['paypalCost'] != null) { ?>
                                    <div class="tab-pane fade" id="divsServicePaymentSettingsPayPal<?php echo $service['id']; ?>" role="tabpanel" aria-labelledby="divsServicePaymentSettingsPayPal<?php echo $service['id']; ?>-tab">
                                        <div class="table-reponsive">
                                            <table class="table text-center table-md d-md-table mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>Koszt</td>
                                                        <td><?php echo number_format(round($service['paypalCost'], 2), 2, ',', ''); ?> zł</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($service['transferCost'] != null) { ?>
                                    <div class="tab-pane fade" id="divsServicePaymentSettingsTransfer<?php echo $service['id']; ?>" role="tabpanel" aria-labelledby="divsServicePaymentSettingsTransfer<?php echo $service['id']; ?>-tab">
                                        <div class="table-reponsive">
                                            <table class="table text-center table-md d-md-table mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>Koszt</td>
                                                        <td><?php echo number_format(round($service['transferCost'], 2), 2, ',', ''); ?> zł</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-background-transparent text-danger text-uppercase" data-dismiss="modal">Zamknij</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>

<?php if ($this->uri->segment('2') == 'news') { ?>
    <?php if ($news) { ?>
        <?php foreach ($news as $news) { ?>
            <div class="modal fade" tabindex="-1" role="dialog" id="divsNewsImageID<?php echo $news['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-whitesmoke">
                            <h5 class="modal-title">Obrazek newsa <?php echo $news['title'].' (ID: #'.$news['id'].')'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="bi bi-x"></i></span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="d-grid justify-content-center mt-3">
                                <?php if ($news['image'] == null) { ?>
                                    <h6>Aktualnie news <?php echo $news['name'].' (ID: #'.$news['id'].')'; ?> nie posiada obrazka. Możesz to zmienić edytując tego newsa</h6>
                                <?php } else { ?>
                                    <img src="<?php echo $news['image']; ?>" alt="<?php echo $news['image']; ?>" class="img-fluid rounded">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-background-transparent text-danger text-uppercase" data-dismiss="modal">Zamknij</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="divsNewsContentID<?php echo $news['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-whitesmoke">
                            <h5 class="modal-title">Treść newsa <?php echo $news['title'].' (ID: #'.$news['id'].')'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="bi bi-x"></i></span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="d-grid justify-content-center mt-3">
                                <?php echo $news['content']; ?>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-background-transparent text-danger text-uppercase" data-dismiss="modal">Zamknij</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>

<?php if ($this->uri->segment('2') == 'pages') { ?>
    <?php if ($pages) { ?>
        <?php foreach ($pages as $page) { ?>
            <div class="modal fade" tabindex="-1" role="dialog" id="divsPageContentID<?php echo $page['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-whitesmoke">
                            <h5 class="modal-title">Treść strony <?php echo $page['title'].' (ID: #'.$page['id'].')'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="bi bi-x"></i></span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="d-grid justify-content-center mt-3">
                                <?php echo $page['content']; ?>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-background-transparent text-danger text-uppercase" data-dismiss="modal">Zamknij</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>

<?php if (isset($_SESSION['divsUpdateAvailable'])) { ?>
        <div class="modal fade" tabindex="-1" role="dialog" id="divsUpdateAvailable" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-whitesmoke">
                        <h5 class="modal-title"><i class="fas fa-exclamation"></i> Powiadomienie o aktualizacji</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="bi bi-x"></i></span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="d-grid justify-content-center mt-3">
                            <?php echo $_SESSION['divsUpdateAvailable']; ?>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-background-transparent text-danger text-uppercase mr-auto" data-dismiss="modal">Zamknij</button>
                        <a href="<?php echo $this->config->base_url('panel/updates'); ?>" class="btn btn-background-transparent text-info text-uppercase">Aktualizuj</button>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>
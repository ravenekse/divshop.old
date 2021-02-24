<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Remove extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }

    /**   Start Remove Admin   */
    public function adminRemove() {

        $this->form_validation->set_rules('adminId', 'adminId', 'required|trim');

        if($this->form_validation->run() === TRUE) {
            $adminId = $this->input->post('adminId');

            $this->load->model('AdminsM');

            if($adminId != '1') {
                if (!$admin = $this->AdminsM->getBy('id', $adminId)) {
                    $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
                    redirect(base_url('panel/admins'));
                }
            } else {
                $_SESSION['messageDangerSmall'] = "Próbujesz usunąć samego(-ą) siebie. Zaraz, tak nie można.";
                redirect(base_url('panel/admins'));
            }

            if(!$this->AdminsM->delete($adminId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect(base_url('panel/admins'));
            }

            $this->db->query("ALTER TABLE divs_admins AUTO_INCREMENT = 1");

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Usuwanie | Administratorzy";
            $data['details'] = "Użytkownik usunął <strong>administratora</strong> o nazwie <strong>" . $admin['name'] . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie usunięto administratora o nazwie &lt;strong&gt;" . $admin['name'] ."&lt;/strong&gt;!";
            redirect(base_url('panel/admins'));
        } else {
            $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
            redirect(base_url('panel/admins'));
        }
    }
    /**   End Remove Admin   */

    /**   Start Remove Server   */
    public function serverRemove() {

        $this->form_validation->set_rules('serverId', 'serverId', 'required|trim');

        if($this->form_validation->run() === TRUE) {
            $serverId = $this->input->post('serverId');

            $this->load->model('ServersM');
            $this->load->model('ServicesM');
            $this->load->model('PurchasesM');
            $this->load->model('VouchersM');

            if(!$server = $this->ServersM->getBy('id', $serverId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
                redirect(base_url('panel/servers'));
            }

            if(!$this->ServersM->delete($serverId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect(base_url('panel/servers'));
            }

            $serverServices = $this->ServicesM->getBy('server', $serverId, true);
            $serverPurchases = $this->PurchasesM->getBy('server', $serverId, true);

            if($serverServices != null) {
                $serverVouchers = null;
                $servicesIds = array();
                $vouchersIds = array();

                foreach($serverServices as $sService) {
                    array_push($servicesIds, $sService['id']);
                    if($serverVouchers == null) {
                        $serverVouchers = $this->VouchersM->getBy('service', $sService['id'], true);
                    } else {
                        array_merge($serverVouchers, $this->VouchersM->getBy('service'), $sService['id'], true);
                    }
                }
                if($serverVouchers != null) {
                    foreach($serverVouchers as $sVoucher) {
                        array_push($vouchersIds, $sVoucher['id']);
                    }
                }
                if(!empty($vouchersIds)) {
                    if(!$this->VouchersM->deleteMultiple($vouchersIds)) {
                        $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych";
                        redirect($this->config->base_url('panel/servers'));
                    }
                }
                if(!$this->ServicesM->deleteMultiple($servicesIds)) {
                    $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                    redirect($this->config->base_url('panel/servers'));
                }
            }
            if($serverPurchases) {
                $purchasesIds = array();

                foreach($serverPurchases as $sPurchase) {
                    array_push($purchasesIds, $sPurchase['id']);
                }
                if(!$this->PurchasesM->deleteMultiple($purchasesIds)) {
                    $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                    redirect($this->config->base_url('panel/servers'));
                }
            }

            $this->db->query("ALTER TABLE divs_servers AUTO_INCREMENT = 1");

            $logs = array();

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Usuwanie | Serwery";
            $data['details'] = "Użytkownik usunął <strong>serwer</strong> o nazwie <strong>" . $server['name'] . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            array_push($logs, $data);

            if(!empty($serverServices)) {
                foreach($serverServices as $sService) {
                    $data['user'] = $_SESSION['name'];
                    $data['section'] = "Usuwanie | Usługi";
                    $data['details'] = "Użytkownik usunął <strong>usługę</strong> o nazwie <strong>" . $sService['name'] . "</strong>";
                    $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
                    if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
                    $data['time'] = time();
                    array_push($logs, $data);
                }
            }
            if(!empty($serverVouchers)) {
                foreach($serverVouchers as $sVoucher) {
                    $data['user'] = $_SESSION['name'];
                    $data['section'] = "Usuwanie | Vouchery";
                    $data['details'] = "Użytkownik usunął <strong>voucher</strong> o nazwie <strong>" . $sVoucher['name'] . "</strong>";
                    $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
                    if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
                    $data['time'] = time();
                    array_push($logs, $data);
                }
            }

            $this->LogsM->addMultiple($logs);

            $_SESSION['messageSuccessBig'] = "Pomyślnie usunięto serwer o nazwie &lt;strong&gt;" . $server['name'] ."&lt;/strong&gt;!";
            redirect(base_url('panel/servers'));
        } else {
            $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
            redirect(base_url('panel/servers'));
        }
    }
    /**   End Remove Server   */

    /**   Start Remove Service   */
    public function serviceRemove() {

        $this->form_validation->set_rules('serviceId', 'serviceId', 'required|trim');

        if($this->form_validation->run() === TRUE) {
            $serviceId = $this->input->post('serviceId');

            $this->load->model('ServicesM');

            if(!$service = $this->ServicesM->getBy('id', $serviceId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
                redirect(base_url('panel/services'));
            }

            if(!$this->ServicesM->delete($serviceId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect(base_url('panel/services'));
            }

            $this->load->model('VouchersM');

            $serviceVouchers = $this->VouchersM->getBy('service', $serviceId, true);

            if($serviceVouchers) {
                $vouchersIds = array();
                foreach($serviceVouchers as $sVoucher) {
                    array_push($vouchersIds, $sVoucher['id']);
                }
                if(!$this->VouchersM->deleteMultiple($vouchersIds)) {
                    $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                    redirect(base_url('panel/services'));
                }
            }

            $this->load->model('PurchasesM');

            $servicePurchases = $this->PurchasesM->getBy('service', $serviceId, true);

            if($servicePurchases) {
                $purchasesIds = array();
                foreach($servicePurchases as $sPurchase) {
                    array_push($purchasesIds, $sPurchase['id']);
                }
                if(!$this->PurchasesM->deleteMultiple($purchasesIds)) {
                    $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                    redirect(base_url('panel/services'));
                }
            }

            $this->db->query("ALTER TABLE divs_services AUTO_INCREMENT = 1");

            $logs = array();

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Usuwanie | Usługi";
            $data['details'] = "Użytkownik usunął <strong>usługę</strong> o nazwie <strong>" . $service['name'] . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            array_push($logs, $data);

            if(!empty($sVoucher) ) {
                foreach($sVoucher as $sVoucher) {
                    $data['user'] = $_SESSION['name'];
                    $data['section'] = "Usuwanie | Vouchery";
                    $data['details'] = "Użytkownik usunął <strong>voucher</strong> o ID <strong>#" . $sVoucher['id'] . "</strong>.";
                    $data['date'] = time();
                    array_push($logs, $data);
                }
            }

            $this->LogsM->addMultiple($logs);

            $_SESSION['messageSuccessBig'] = "Pomyślnie usunięto usługę o nazwie &lt;strong&gt;" . $service['name'] ."&lt;/strong&gt;!";
            redirect(base_url('panel/services'));
        } else {
            $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
            redirect(base_url('panel/services'));
        }
    }
    /**   End Remove Service   */

    /**   Start Remove News   */
    public function newsRemove() {

        $this->form_validation->set_rules('newsId', 'newsId', 'required|trim');

        if($this->form_validation->run() === TRUE) {
            $newsId = $this->input->post('newsId');

            $this->load->model('NewsM');

            if(!$news = $this->NewsM->getBy('id', $newsId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
                redirect(base_url('panel/news'));
            }

            if(!$this->NewsM->delete($newsId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect(base_url('panel/news'));
            }

            $this->db->query("ALTER TABLE divs_news AUTO_INCREMENT = 1");

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Usuwanie | Newsy";
            $data['details'] = "Użytkownik usunął <strong>newsa</strong> o nazwie <strong>" . $news['title'] . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie usunięto newsa o nazwie &lt;strong&gt;" . $news['title'] ."&lt;/strong&gt;!";
            redirect(base_url('panel/news'));
        } else {
            $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
            redirect(base_url('panel/news'));
        }
    }
    /**   End Remove News   */

    /**   Start Remove Page   */
    public function pageRemove() {

        $this->form_validation->set_rules('pageId', 'pageId', 'required|trim');

        if($this->form_validation->run() === TRUE) {
            $pageId = $this->input->post('pageId');

            $this->load->model('PagesM');

            if(!$page = $this->PagesM->getBy('id', $pageId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
                redirect(base_url('panel/pages'));
            }

            if(!$this->PagesM->delete($pageId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect(base_url('panel/pages'));
            }

            $this->db->query("ALTER TABLE divs_pages AUTO_INCREMENT = 1");

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Usuwanie | Strony";
            $data['details'] = "Użytkownik usunął <strong>stronę</strong> o nazwie <strong>" . $page['title'] . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie usunięto stronę o nazwie &lt;strong&gt;" . $page['title'] ."&lt;/strong&gt;!";
            redirect(base_url('panel/pages'));
        } else {
            $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
            redirect(base_url('panel/pages'));
        }
    }
    /**   End Remove News   */

    /**   Start Remove Voucher   */
    public function voucherRemove() {

        $this->form_validation->set_rules('voucherId', 'voucherId', 'required|trim');

        if($this->form_validation->run() === TRUE) {
            $voucherId = $this->input->post('voucherId');

            $this->load->model('VouchersM');

            if(!$voucher = $this->VouchersM->getBy('id', $voucherId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
                redirect(base_url('panel/vouchers'));
            }

            if(!$this->VouchersM->delete($voucherId)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect(base_url('panel/vouchers'));
            }

            $this->db->query("ALTER TABLE divs_vouchers AUTO_INCREMENT = 1");

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Usuwanie | Vouchery";
            $data['details'] = "Użytkownik usunął <strong>voucher</strong> o ID <strong>#" . $voucher['id'] . " (Kod vouchera: " . $voucher['code'] . ")</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie usunięto voucher o ID &lt;strong&gt;#" . $voucher['id'] ." (Kod vouchera: " . $voucher['code'] . ")&lt;/strong&gt;!";
            redirect(base_url('panel/vouchers'));
        } else {
            $_SESSION['messageDangerSmall'] = "Wystąpił błąd, spróbuj jeszcze raz!";
            redirect(base_url('panel/vouchers'));
        }
    }
    /**   End Remove Voucher   */

}
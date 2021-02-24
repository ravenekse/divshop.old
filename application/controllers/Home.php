<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class Home extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {

			/**  Head section  */
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			/**  Body section  */
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

			$this->load->model('ModulesM');
			$this->load->model('NewsM');
			$this->load->model('ServersM');
			$servers = $this->ServersM->getAll();
			$news = $this->NewsM->getAll();
			$modules = $this->ModulesM->getAll();

			if($modules[0]['moduleName'] == "news" && $modules[0]['moduleEnabled'] == 1 && count($news) >= 1) {
			
				require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPing.php');
				require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPingException.php');
				require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
				$this->load->model('ServersM');
				$this->load->model('PurchasesM');
				$this->load->model('ServicesM');
				$this->load->model('ModulesM');
				$this->load->model('NewsM');
				$this->load->model('PagesM');
				$api = new DIVShopAPI();
				$bodyD['divsAPI'] = array(
					'divsVersion'  =>  $api->get_current_version()
				);

				/**  Head section  */
				$headerD['settings'] = $this->SettingsM->getSettings();
				$headerD['pageTitle'] = $headerD['settings']['pageTitle'];
				$headerD['pageSubtitle'] = "Strona główna";
				$this->load->view('components/Header', $headerD);

				/**  Body section  */
				$servers = $this->ServersM->getAll();
				$services = $this->ServicesM->getAll();
				$lastBuyers = $this->PurchasesM->getAll(0, 54);

				$bodyD['modules'] = $this->ModulesM->getAll();
				$bodyD['pages'] = $this->PagesM->getAll();
				$bodyD['news'] = $this->NewsM->getAll();
				$bodyD['pages'] = $this->PagesM->getAll();
				$bodyD['servers'] = array();
				$lastBuyersServer = array();
				$bodyD['lastBuyers'] = array();
				
				foreach($servers as $server) {
					try {
						$query = new MinecraftPing($server['ip'], $server['port']);
						$result = $query->Query();
						if($server['serverVersion'] == null) {
							$version = $result['version']['name'];
							$version = str_split($version);
							$server['status']['version'] = "";
							foreach($version as $char) { if(is_numeric($char) || $char == ".") { $server['status']['version'] .= $char; } }
						}
						$server['status']['onlinePlayers'] = $result['players']['online'];
						$server['status']['maxPlayers'] = $result['players']['max'];
						if(($server['status']['onlinePlayers'] == 0) || ($server['status']['maxPlayers'] == 0)) {
							$server['status']['percent'] = 0;
						} else {
							$server['status']['percent'] = round($server['status']['onlinePlayers'] / $server['status']['maxPlayers'] * 100, 0);
						}
						if($server['status']['maxPlayers'] == 0) {
							$server['status']['percent'] = 0;
						}
					} catch (MinecraftPingException $e) {
						log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] MinecraftPingException: " . $e->getMessage());
					} finally {
						if(isset($query)) $query->Close();
					}
					array_push($bodyD['servers'], $server);
					
					foreach($lastBuyers as $lastBuyer) {
						if($lastBuyer['server'] == $server['id']) {
							$lastBuyer['server'] = $server['name'];
							array_push($lastBuyersServer, $lastBuyer);
						}
					}
				}
				foreach($services as $service) {
					foreach($lastBuyersServer as $lastBuyer) {
						if($lastBuyer['service'] == $service['id']) {
							$lastBuyer['service'] = $service['name'];
							array_push($bodyD['lastBuyers'], $lastBuyer);
						}
					}
				}
				$this->load->view('NewsHome', $bodyD);

			} else if($modules[0]['moduleName'] == "news" && $modules[0]['moduleEnabled'] == 0 || count($news) == 0) {
				if(count($servers) == 1) {
					
					require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPing.php');
					require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPingException.php');
					require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
					$this->load->model('ServersM');
					$this->load->model('PurchasesM');
					$this->load->model('ServicesM');
					$this->load->model('ModulesM');
					$this->load->model('PaymentsM');
					$this->load->model('PagesM');
					$this->load->model('NewsM');
					$api = new DIVShopAPI();
					$bodyD['divsAPI'] = array(
						'divsVersion'  =>  $api->get_current_version()
					);
					$bodyD['server'] = $servers[0];
					$bodyD['modules'] = $this->ModulesM->getAll();
					$bodyD['pages'] = $this->PagesM->getAll();
					$bodyD['news'] = $this->NewsM->getAll();

					/**  Head section  */
					$headerD['settings'] = $this->SettingsM->getSettings();
					$headerD['pageTitle'] = $headerD['settings']['pageTitle'];
					$headerD['pageSubtitle'] = "Sklep serwera " . $bodyD['server']['name'];
					$this->load->view('components/Header', $headerD);

					/**  Body section  */
					$bodyD['services'] = $this->ServicesM->getBy('server', $bodyD['server']['id'], true);
					$bodyD['lastBuyers'] = $this->PurchasesM->getBy('server', $bodyD['server']['id'], true);

					if($bodyD['services']) {
						for($i = 0; $i < count($bodyD['services']); $i++) {
							if($bodyD['services'][$i]['smsConfig'] != null) {
								$bodyD['services'][$i]['smsConfig'] = json_decode($bodyD['services'][$i]['smsConfig'], true);
							}
							if($bodyD['lastBuyers'] != null) {
								for($x = 0; $x < count($bodyD['lastBuyers']); $x++) {
									if($bodyD['lastBuyers'][$x]['service'] == $bodyD['services'][$i]['id']) {
										$bodyD['lastBuyers'][$x]['service'] = $bodyD['services'][$i]['name'];
										$bodyD['lastBuyers'][$x]['server'] = $bodyD['server']['name'];
									}
								}
							}
						}
					}

					$paypal = $this->PaymentsM->get(2);
					$transfer = $this->PaymentsM->get(3);
					$payments = array(
						'paypal'    =>  json_decode($paypal['config'], true),
						'transfer'  =>  json_decode($transfer['config'], true)
					);
					$bodyD['payments'] = array(
						'paypal'    =>  $payments['paypal'],
						'transfer'  =>  $payments['transfer']['transfer']
					);

					try {
						$query = new MinecraftPing($bodyD['server']['ip'], $bodyD['server']['port']);
						$result = $query->Query();
						if($bodyD['server']['serverVersion'] == null) {
							$version = $result['version']['name'];
							$version = str_split($version);
							$bodyD['server']['status']['version'] = "";
							foreach($version as $char) { if(is_numeric($char) || $char == ".") { $bodyD['server']['status']['version'] .= $char; } }
						}
						$bodyD['server']['status']['onlinePlayers'] = $result['players']['online'];
						$bodyD['server']['status']['maxPlayers'] = $result['players']['max'];
						if(($bodyD['server']['status']['onlinePlayers'] == 0) || ($bodyD['server']['status']['maxPlayers'] == 0)) {
							$bodyD['server']['status']['percent'] = 0;
						} else {
							$bodyD['server']['status']['percent'] = round($bodyD['server']['status']['onlinePlayers'] / $bodyD['server']['status']['maxPlayers'] * 100, 0);
						}
						if($bodyD['server']['status']['maxPlayers'] == 0) {
							$bodyD['server']['status']['percent'] = 0;
						}
					} catch (MinecraftPingException $e) {
						log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] MinecraftPingException: " . $e->getMessage());
					} finally {
						if(isset($query)) $query->Close();
					}

					$this->load->view('Shop', $bodyD);

				} else {
					
					require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPing.php');
					require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPingException.php');
					require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
					$this->load->model('ServersM');
					$this->load->model('PurchasesM');
					$this->load->model('ServicesM');
					$this->load->model('ModulesM');
					$this->load->model('PaymentsM');
					$this->load->model('NewsM');
					$this->load->model('PagesM');
					$api = new DIVShopAPI();
					$bodyD['divsAPI'] = array(
						'divsVersion'  =>  $api->get_current_version()
					);
					$bodyD['modules'] = $this->ModulesM->getAll();

					/**  Head section  */
					$headerD['settings'] = $this->SettingsM->getSettings();
					$headerD['pageTitle'] = $headerD['settings']['pageTitle'];
					$headerD['pageSubtitle'] = "Wybór serwera";
					$this->load->view('components/Header', $headerD);

					/**  Body section  */
					$servers = $this->ServersM->getAll();
					$bodyD['news'] = $this->NewsM->getAll();
					$bodyD['pages'] = $this->PagesM->getAll();
					$bodyD['servers'] = array();

					foreach($servers as $server) {
						try {
							$query = new MinecraftPing($server['ip'], $server['port']);
							$result = $query->Query();
							if($server['serverVersion'] == null) {
								$version = $result['version']['name'];
								$version = str_split($version);
								$server['status']['version'] = "";
								foreach($version as $char) { if(is_numeric($char) || $char == ".") { $server['status']['version'] .= $char; } }
							}
							$server['status']['onlinePlayers'] = $result['players']['online'];
							$server['status']['maxPlayers'] = $result['players']['max'];
							if(($server['status']['onlinePlayers'] == 0) || ($server['status']['maxPlayers'] == 0)) {
								$server['status']['percent'] = 0;
							} else {
								$server['status']['percent'] = round($server['status']['onlinePlayers'] / $server['status']['maxPlayers'] * 100, 0);
							}
							if($server['status']['maxPlayers'] == 0) {
								$server['status']['percent'] = 0;
							}
						} catch (MinecraftPingException $e) {
							log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] MinecraftPingException: " . $e->getMessage());
						} finally {
							if(isset($query)) $query->Close();
						}
						array_push($bodyD['servers'], $server);
					}
					
					$this->load->view('Servers', $bodyD);

				}
			}
		}
	}
}

<?php
if(count(get_included_files()) == 1) exit("No direct script access allowed");
define("DIVS_API_DEBUG", false);
define("DIVS_SHOW_UPDATE_PROGRESS", true);

define("DIVS_TEXT_CONNECTION_FAILED", 'Serwer jest w tej chwili niedostępny, spróbuj ponownie później.');
define("DIVS_TEXT_INVALID_RESPONSE", 'Serwer zwrócił nieprawidłową odpowiedź, skontaktuj się z pomocą techniczną.');
define("DIVS_TEXT_VERIFIED_RESPONSE", 'Zweryfikowano! Dziękujemy za zakup.');
define("DIVS_TEXT_PREPARING_MAIN_DOWNLOAD", 'Przygotowuję do pobrania aktualizacji...');
define("DIVS_TEXT_MAIN_UPDATE_SIZE", 'Rozmiar aktualizacji:');
define("DIVS_TEXT_DONT_REFRESH", '(Proszę nie odświeżać strony)');
define("DIVS_TEXT_DOWNLOADING_MAIN", 'Pobieranie aktualizacji...');
define("DIVS_TEXT_UPDATE_PERIOD_EXPIRED", 'Twój okres aktualizacji dobiegł końca lub Twoja licencja jest nieważna, skontaktuj się z pomocą techniczną.');
define("DIVS_TEXT_UPDATE_PATH_ERROR", 'Folder nie ma uprawnień do zapisu lub nie można ustalić ścieżki pliku aktualizacji, skontaktuj się z pomocą techniczną.');
define("DIVS_TEXT_MAIN_UPDATE_DONE", 'Pliki aktualizacji pobrane i rozpakowane.');
define("DIVS_TEXT_UPDATE_EXTRACTION_ERROR", 'Aktualizacja rozpakowywania plików ZIP nie powiodła się.');
define("DIVS_TEXT_PREPARING_SQL_DOWNLOAD", 'Przygotowuję do pobrania aktualizacji SQL...');
define("DIVS_TEXT_SQL_UPDATE_SIZE", 'Rozmiar aktualizacji SQL:');
define("DIVS_TEXT_DOWNLOADING_SQL", 'Pobieranie aktualizacji SQL...');
define("DIVS_TEXT_SQL_UPDATE_DONE", 'Pobrano aktualizację SQL.');
define("DIVS_TEXT_UPDATE_WITH_SQL_IMPORT_FAILED", 'Aplikacja została pomyślnie zaktualizowana, ale automatyczne importowanie SQL nie powiodło się. Proszę ręcznie zaimportować pobrany plik SQL do bazy danych.');
define("DIVS_TEXT_UPDATE_WITH_SQL_IMPORT_DONE", 'Aplikacja została pomyślnie zaktualizowana, a plik SQL został automatycznie zaimportowany.');
define("DIVS_TEXT_UPDATE_WITH_SQL_DONE", 'Aplikacja została pomyślnie zaktualizowana, ręcznie zaimportuj pobrany plik SQL do bazy danych.');
define("DIVS_TEXT_UPDATE_WITHOUT_SQL_DONE", 'Aplikacja została pomyślnie zaktualizowana, nie było aktualizacji SQL.');

if(!DIVS_API_DEBUG){
	@ini_set('display_errors', 0);
}

if((@ini_get('max_execution_time')!=='0')&&(@ini_get('max_execution_time'))<600){
	@ini_set('max_execution_time', 600);
}
@ini_set('memory_limit', '256M');

class DIVShopAPI {

	private $product_id;
	private $api_url;
	private $api_key;
	private $api_language;
	private $current_version;
	private $current_path;
	private $root_path;
	private $license_file;

	public function __construct(){ 
		$this->product_id = 'F78FF149';
		$this->api_url = 'https://api.divshop.pro/';
		$this->api_key = '8A01F27E4C22FE47C673';
		$this->api_language = 'polish';
		$this->current_version = '1.1.0 Stable';
		$this->current_path = getcwd();
		$this->root_path = realpath($this->current_path.'/..');
		$this->license_file = $this->current_path.'/.lic';
	}

	public function get_current_version(){
		return $this->current_version;
	}

	private function call_api($method, $url, $data){
		$curl = curl_init();
		switch($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                         
				break;
		  	default:
		  		if($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}
		$this_server_name = getenv('SERVER_NAME')?:
			$_SERVER['SERVER_NAME']?:
			getenv('HTTP_HOST')?:
			$_SERVER['HTTP_HOST'];
		$this_http_or_https = ((
			(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
			(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
				$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		)?'https://':'http://');
		$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
		$this_ip = getenv('SERVER_ADDR')?:
			$_SERVER['SERVER_ADDR']?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		curl_setopt($curl, CURLOPT_HTTPHEADER, 
			array('Content-Type: application/json', 
				'LB-API-KEY: '.$this->api_key, 
				'LB-URL: '.$this_url, 
				'LB-IP: '.$this_ip, 
				'LB-LANG: '.$this->api_language)
		);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 2);
		$result = curl_exec($curl);
		if(!$result&&!DIVS_API_DEBUG){
			$rs = array(
				'status' => FALSE, 
				'message' => DIVS_TEXT_CONNECTION_FAILED
			);
			return json_encode($rs);
		}
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($http_status != 200){
			if(DIVS_API_DEBUG){
				$temp_decode = json_decode($result, true);
				$rs = array(
					'status' => FALSE, 
					'message' => ((!empty($temp_decode['error']))?
						$temp_decode['error']:
						$temp_decode['message'])
				);
				return json_encode($rs);
			}else{
				$rs = array(
					'status' => FALSE, 
					'message' => DIVS_TEXT_INVALID_RESPONSE
				);
				return json_encode($rs);
			}
		}
		curl_close($curl);
		return $result;
	}

	public function check_connection(){
		$data_array =  array();
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/check_connection_ext', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function get_latest_version(){
		$data_array =  array(
			"product_id"  => $this->product_id
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/latest_version', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function check_update(){
		$data_array =  array(
			"product_id"  => $this->product_id,
			"current_version" => $this->current_version
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/check_update', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function download_update($update_id, $type, $version, $license = false, $client = false, $db_for_import = false){ 
		if(!empty($license)&&!empty($client)){
			$data_array =  array(
				"license_file" => null,
				"license_code" => $license,
				"client_name" => $client
			);
		}else{
			if(is_file($this->license_file)){
				$data_array =  array(
					"license_file" => file_get_contents($this->license_file),
					"license_code" => null,
					"client_name" => null
				);
			}else{
				$data_array =  array();
			}
		}
		ob_end_flush(); 
		ob_implicit_flush(true);  
		$version = str_replace(".", "_", $version);
		ob_start();
		$source_size = $this->api_url."api/get_update_size/main/".$update_id; 
		echo DIVS_TEXT_PREPARING_MAIN_DOWNLOAD."<br>";
		if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 1;</script>';}
		ob_flush();
		echo DIVS_TEXT_MAIN_UPDATE_SIZE." ".$this->get_remote_filesize($source_size)." ".DIVS_TEXT_DONT_REFRESH."<br>";
		if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 5;</script>';}
		ob_flush();
		$temp_progress = '';
		$ch = curl_init();
		$source = $this->api_url."api/download_update/main/".$update_id; 
		curl_setopt($ch, CURLOPT_URL, $source);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_array);
		$this_server_name = getenv('SERVER_NAME')?:
			$_SERVER['SERVER_NAME']?:
			getenv('HTTP_HOST')?:
			$_SERVER['HTTP_HOST'];
		$this_http_or_https = ((
			(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
			(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
				$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		)?'https://':'http://');
		$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
		$this_ip = getenv('SERVER_ADDR')?:
			$_SERVER['SERVER_ADDR']?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'LB-API-KEY: '.$this->api_key, 
			'LB-URL: '.$this_url, 
			'LB-IP: '.$this_ip, 
			'LB-LANG: '.$this->api_language)
		);
		if(DIVS_SHOW_UPDATE_PROGRESS){curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this, 'progress'));}
		if(DIVS_SHOW_UPDATE_PROGRESS){curl_setopt($ch, CURLOPT_NOPROGRESS, false);}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
		echo DIVS_TEXT_DOWNLOADING_MAIN."<br>";
		if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 10;</script>';}
		ob_flush();
		$data = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($http_status != 200){
			if($http_status == 401){
				curl_close($ch);
				exit("<br>".DIVS_TEXT_UPDATE_PERIOD_EXPIRED);
			}else{
				curl_close($ch);
				exit("<br>".DIVS_TEXT_INVALID_RESPONSE);
			}
		}
		curl_close($ch);
		$destination = $this->root_path."/update_main_".$version.".zip"; 
		$file = fopen($destination, "w+");
		if(!$file){
			exit("<br>".DIVS_TEXT_UPDATE_PATH_ERROR);
		}
		fputs($file, $data);
		fclose($file);
		if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 65;</script>';}
		ob_flush();
		$zip = new ZipArchive;
		$res = $zip->open($destination);
		if($res === TRUE){
			$zip->extractTo($this->root_path."/"); 
			$zip->close();
			unlink($destination);
			echo DIVS_TEXT_MAIN_UPDATE_DONE."<br><br>";
			if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 75;</script>';}
			ob_flush();
		}else{
			echo DIVS_TEXT_UPDATE_EXTRACTION_ERROR."<br><br>";
			ob_flush();
		}
		if($type == true){
			$source_size = $this->api_url."api/get_update_size/sql/".$update_id; 
			echo DIVS_TEXT_PREPARING_SQL_DOWNLOAD."<br>";
			ob_flush();
			echo DIVS_TEXT_SQL_UPDATE_SIZE." ".$this->get_remote_filesize($source_size)." ".DIVS_TEXT_DONT_REFRESH."<br>";
			if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 85;</script>';}
			ob_flush();
			$temp_progress = '';
			$ch = curl_init();
			$source = $this->api_url."api/download_update/sql/".$update_id;
			curl_setopt($ch, CURLOPT_URL, $source);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_array);
			$this_server_name = getenv('SERVER_NAME')?:
				$_SERVER['SERVER_NAME']?:
				getenv('HTTP_HOST')?:
				$_SERVER['HTTP_HOST'];
			$this_http_or_https = ((
				(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
				(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
					$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
			)?'https://':'http://');
			$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
			$this_ip = getenv('SERVER_ADDR')?:
				$_SERVER['SERVER_ADDR']?:
				$this->get_ip_from_third_party()?:
				gethostbyname(gethostname());
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'LB-API-KEY: '.$this->api_key, 
				'LB-URL: '.$this_url, 
				'LB-IP: '.$this_ip, 
				'LB-LANG: '.$this->api_language)
			); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			echo DIVS_TEXT_DOWNLOADING_SQL."<br>";
			if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 90;</script>';}
			ob_flush();
			$data = curl_exec($ch);
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($http_status!=200){
				curl_close($ch);
				exit(DIVS_TEXT_INVALID_RESPONSE);
			}
			curl_close($ch);
			$destination = $this->root_path."/update_sql_".$version.".sql"; 
			$file = fopen($destination, "w+");
			if(!$file){
				exit(DIVS_TEXT_UPDATE_PATH_ERROR);
			}
			fputs($file, $data);
			fclose($file);
			echo DIVS_TEXT_SQL_UPDATE_DONE."<br><br>";
			if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 95;</script>';}
			ob_flush();
			if(is_array($db_for_import)){
				if(!empty($db_for_import["db_host"])&&!empty($db_for_import["db_user"])&&!empty($db_for_import["db_name"])){
					$db_host = strip_tags(trim($db_for_import["db_host"]));
            		$db_user = strip_tags(trim($db_for_import["db_user"]));
            		$db_pass = strip_tags(trim($db_for_import["db_pass"]));
            		$db_name = strip_tags(trim($db_for_import["db_name"]));
					$con = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
					if(mysqli_connect_errno()){
						echo DIVS_TEXT_UPDATE_WITH_SQL_IMPORT_FAILED;
					}else{
						$templine = '';
						$lines = file($destination);
						foreach($lines as $line){
							if(substr($line, 0, 2) == '--' || $line == '')
								continue;
							$templine .= $line;
							$query = false;
							if(substr(trim($line), -1, 1) == ';'){
								$query = mysqli_query($con, $templine);
								$templine = '';
							}
						}
						@chmod($destination,0777);
						if(is_writeable($destination)){
							unlink($destination);
						}
						echo DIVS_TEXT_UPDATE_WITH_SQL_IMPORT_DONE;
					}
				}else{
					echo DIVS_TEXT_UPDATE_WITH_SQL_IMPORT_FAILED;
				}
			}else{
				echo DIVS_TEXT_UPDATE_WITH_SQL_DONE;
			}
			if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 100;</script>';}
			ob_flush();
		}else{
			if(DIVS_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById(\'prog\').value = 100;</script>';}
			echo DIVS_TEXT_UPDATE_WITHOUT_SQL_DONE;
			ob_flush();
		}
		ob_end_flush(); 
	}

	private function progress($resource, $download_size, $downloaded, $upload_size, $uploaded){
		static $prev = 0;
		if($download_size == 0){
			$progress = 0;
		}else{
			$progress = round( $downloaded * 100 / $download_size );
		}
		if(($progress!=$prev) && ($progress == 25)){
			$prev = $progress;
			echo '<script>document.getElementById(\'prog\').value = 22.5;</script>';
			ob_flush();
		}
		if(($progress!=$prev) && ($progress == 50)){
			$prev=$progress;
			echo '<script>document.getElementById(\'prog\').value = 35;</script>';
			ob_flush();
		}
		if(($progress!=$prev) && ($progress == 75)){
			$prev=$progress;
			echo '<script>document.getElementById(\'prog\').value = 47.5;</script>';
			ob_flush();
		}
		if(($progress!=$prev) && ($progress == 100)){
			$prev=$progress;
			echo '<script>document.getElementById(\'prog\').value = 60;</script>';
			ob_flush();
		}
	}

	private function get_ip_from_third_party(){
		$curl = curl_init ();
		curl_setopt($curl, CURLOPT_URL, "http://ipecho.net/plain");
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	private function get_remote_filesize($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_NOBODY, TRUE);
		$this_server_name = getenv('SERVER_NAME')?:
			$_SERVER['SERVER_NAME']?:
			getenv('HTTP_HOST')?:
			$_SERVER['HTTP_HOST'];
		$this_http_or_https = ((
			(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
			(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
				$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		)?'https://':'http://');
		$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
		$this_ip = getenv('SERVER_ADDR')?:
			$_SERVER['SERVER_ADDR']?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'LB-API-KEY: '.$this->api_key, 
			'LB-URL: '.$this_url, 
			'LB-IP: '.$this_ip, 
			'LB-LANG: '.$this->api_language)
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30); 
		$result = curl_exec($curl);
		$filesize = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		if($filesize){
			switch($filesize){
				case $filesize < 1024:
					$size = $filesize .' B'; break;
				case $filesize < 1048576:
					$size = round($filesize / 1024, 2) .' KB'; break;
				case $filesize < 1073741824:
					$size = round($filesize / 1048576, 2) . ' MB'; break;
				case $filesize < 1099511627776:
					$size = round($filesize / 1073741824, 2) . ' GB'; break;
			}
			return $size; 
		}
	}
}

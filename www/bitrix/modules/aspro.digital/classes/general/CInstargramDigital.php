<?
if(!defined('DIGITAL_MODULE_ID'))
	define('DIGITAL_MODULE_ID', 'aspro.digital');

class CInstargramDigital{
	const MODULE_ID = DIGITAL_MODULE_ID;
	const URL_INSTAGRAM_API = 'https://api.instagram.com/v1/';

	private $access_token = 0;
	public $token_params = 0;
	public $error = "";
	public $App = "";

	public function __construct($token){
		global $APPLICATION;
		$this->token_params = $token;
		$this->App=$APPLICATION;
	}

	public function checkApiToken(){
		if(!strlen($this->token_params)){
			$this->error="No API token instagram";
		}
		$this->access_token='/?access_token='.$this->token_params;
	}

	public function getFormatResult($method){
		if(function_exists('curl_init'))
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, self::URL_INSTAGRAM_API.$method.$this->access_token);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = curl_exec($curl);
			$data =  $out ? $out : curl_error($curl);			
		}
		else
		{
			$data = file_get_contents(self::URL_INSTAGRAM_API.$method.$this->access_token);			
		}
		
		$data = json_decode($data, true);
		$data = $this->App->ConvertCharsetArray($data, 'UTF-8', LANG_CHARSET);

		return $data;
	}

	public function getInstagramPosts(){
		$this->checkApiToken();

		if($this->error){
			return array("ERROR" => "Y", "MESSAGE" => $this->error);
		}else{
			$data=$this->getFormatResult('users/self/media/recent');
		}

		return $data;
	}
	
	public function getInstagramUser(){
		$this->checkApiToken();

		if($this->error){
			return $this->error;
		}else{
			$data=$this->getFormatResult('users/self');
		}

		return $data;
	}

	public function getInstagramTag($tag) {
		$this->checkApiToken();

		if($this->error){
			return $this->error;
		}else{
			$data=$this->getFormatResult('tag/'.$tag.'/media/recent');
		}

		return $data;
	}
}?>
<?

class Visitor{

	private $referer;
	private $ip;
	private $userAgent;
	private $logFile;

	function __construct(){
		date_default_timezone_set('Europe/Istanbul');
		$this->referer = empty($_SERVER['HTTP_REFERER']) ? 'no referer' : $_SERVER['HTTP_REFERER'] ;
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];
		$this->logFile = 'data/who-visited-me.txt';
	}

	function getDataFromIp(){
		$data = file_get_contents('http://ip-api.com/json/' . $this->ip);
		if(!empty($data)){
			$ipData = json_decode($data);
			if($ipData->status === 'success'){
				return $ipData->country . '/' . $ipData->regionName . '/' . $ipData->city;
			}
		}
	}

	function getWriteData(){
		return date('d.m.Y H:i:s') . ' ' . $this->referer . ' ' . $this->ip . ' ' . $this->userAgent . ' ' . $this->getDataFromIp();

	}

	function getLogFileContent(){
		return '<pre>' . file_get_contents($this->logFile) . '</pre>';
	}

	function writeLogToFile(){
		$fileData = $this->getWriteData() . PHP_EOL . file_get_contents($this->logFile);
		file_put_contents($this->logFile, $fileData);
	}

}

?>

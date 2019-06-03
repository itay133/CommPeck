<?php
/**
 *  property declaration
 */
include_once("readfile.php");

class call
{
   public $CustomerID;
   public $CallDate;
   public $Seconds;
   public $PhoneNumber;
   public $CustomerIP;

   function __construct($_CustomerID,$_CallDate ,$_Seconds ,$_PhoneNumber ,$_CustomerIP )
   {
     $this->CustomerID   =  $_CustomerID;
     $this->CallDate     =  $_CallDate;
     $this->Seconds      =  $_Seconds;
     $this->PhoneNumber  =  $_PhoneNumber;
     $this->CustomerIP   =  $_CustomerIP;
   }

}

/**
 *
 */
class callData
{

  var $calls = array();
  var $customerID = array();

	function __construct($file)
	{

		$csvFile = read_csv($file);
		for ($i=0; $i < count($csvFile); $i++) {
			$c = new call($csvFile[$i][0],$csvFile[$i][1],$csvFile[$i][2],$csvFile[$i][3],$csvFile[$i][4]);
			$this->calls[]=$c;
			if(!in_array($c->CustomerID, $this->customerID)){
				$this->customerID[] = $c->CustomerID;
			}

		}
        //for debug
        return ;
	}

	public function getAllCustomerID(){
		return $this->customerID;
	}

	function getDataForCID($id)
	{
		$cidCalls = array();

		foreach($this->calls as $c) {
			if($c->CustomerID == $id){
				$cidCalls[]=$c;
			}
		}
		return $cidCalls;
	}

	public function getNumCallsForCustomer($id){
		$cidCalls=$this->getDataForCID($id);
		return count($cidCalls);
	}
	public function getCallsDurationForCustomer($id){
		$cidCalls=$this->getDataForCID($id);
		$n = 0;
		foreach($cidCalls as $c){
			$n += $c->Seconds;
		}
		return $n ;
	}

	function getSamecallsFormCID($id){
		$sameContinent = array();
		$cidCalls=$this->getDataForCID($id);
		foreach($cidCalls as $c){
			$ipContinent = $this->getIpContinent($c->CustomerIP);
			$phoneContinent = $this->getPhoneContinent($c->PhoneNumber);;
			if($ipContinent == $phoneContinent){
				$sameContinent[] = $c;
			}
		}
		return $sameContinent;
	}


	function startsWith ($string, $startString)
	{
		$len = strlen($startString);
		return (substr($string, 0, $len) === $startString);
	}

	function getPhoneContinent($phoneNuber){
		$countryInfo = read_csv('countryinfo.csv');
		for($i=0 ; $i<count($countryInfo) ; $i++){
			if($this->startsWith($phoneNuber , $countryInfo[$i][12])){
				return $countryInfo[$i][8];
			}

		}
		return null;
	}

	function getIpContinent($ip){
		//initialize curl
		$accKey = "858462c71e435c6032e233f0c7dafae6";
		$ch  = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$accKey.'');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//store the data
		$json = curl_exec($ch);
		curl_close($ch);

		//decode json response
		$api_result = json_decode($json, true);

		//output the data
		return $api_result['continent_code'];

	}

	public function getNumCallsSameContinent($id){
		$callData = $this->getSamecallsFormCID($id);
		return count($callData);
	}

	public function getDurationCallsSameContinent($id){
		$callData = $this->getSamecallsFormCID($id);
		$n = 0;
		foreach($callData as $c){

			$n += $c->Seconds;
		}
		return $n;
	}


}

?>

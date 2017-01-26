<?php
namespace Payclub;

/**
PayclubSend :: Dinersclub
Clase destinada al control del plugin del 
boton de pagos para el envio y generacion
del formulario de invocacion del boton de
pagos.
 */
class PayclubSend extends \Payclub\PlugInClientSend {
	var $merchantId = null;
	var $adquirerId = null;
	var $urlVPOS = null;
	var $xmlVPOS = null;
	
	
	function __construct( $mid, $localid ){
    $config = new Config('prod');
		#llaves
		$this->setIV( $config->get('VECTORINI') );
		$this->setSignPrivateKey( $config->get('CCPRIVSIGN') );
		$this->setCipherPublicKey( $config->get('PCPUBCIPHER') );
		#param form fields
		$this->setAdquirerId( $config->get('ADQUIRERID') );
		$this->setMerchantId( $mid );
		#param plugin
		$this->setLocalID( $localid );
		$this->setCurrencyID( $config->get('CURRENCYID') );
		$this->setSourceDescription( $config->get('URLTEC') );
		#param payclub urls
		$this->setUrlVPOS( $config->get('URLVPOS') );
		$this->setXmlVPOS( $config->get('XMLVPOS') );

	}

	function setParamametros($param){
		foreach ($param as $k => $v) {
			switch ($k){
				case 'trxid': $this->setTransacctionID($v); break;
				case 'subt': $this->setTransacctionValue($v); break;

				case 'tax1': $this->setTaxValue1($v); break;
				case 'tax2': $this->setTaxValue2($v); break;
				case 'tip': $this->setTipValue($v); break;
				case 'ref1': $this->setReferencia1($v); break;
				case 'ref2': $this->setReferencia2($v); break;
				case 'ref3': $this->setReferencia3($v); break;
				case 'ref4': $this->setReferencia4($v); break;
				case 'ref5': $this->setReferencia5($v); break;
			}
		}
		return $this;
	}
	
	function getFormFields(){
		//Generate SING
		$XMLGKey = $this->CreateXMLGENERATEKEY();
		$this->XMLProcess();
		
		//Fields
        $formFields['XMLREQUEST']     = $this->getXMLREQUEST();
        $formFields['TXTREQUEST']     = $this->getTXTREQUEST();
		$formFields['TRANSACCIONID']	= $this->getTransacctionID();
        $formFields['XMLDIGITALSIGN'] = $this->getXMLDIGITALSIGN();
        $formFields['XMLACQUIRERID']  = $this->getAdquirerId();
        $formFields['XMLMERCHANTID']  = $this->getMerchantId();
        $formFields['XMLGENERATEKEY'] = $XMLGKey;
		
		return $formFields;
	}
	// setters & getters	
	function setMerchantId($value) { $this->merchantId = $value; }
	function getMerchantId() { return $this->merchantId; }
	
	function setAdquirerId($value) { $this->adquirerId = $value; }
	function getAdquirerId() { return $this->adquirerId; }

	function setUrlVPOS($value) { $this->urlVPOS = $value; }
	function getUrlVPOS() { return $this->urlVPOS; }

	function setXmlVPOS($value) { $this->xmlVPOS = $value; }
	function getXmlVPOS() { return $this->xmlVPOS; }

}


/**
PayclubPosproc :: Dinersclub
Clase destinada al control del plugin del 
boton de pagos para la recepcion y decodificacion
de los datos recibidos en la pagina de porproceso
 */
class PayclubPosproc extends \Payclub\TripleDESEncryption {
	var $iniVector=null;
	var $simetricKey=null;
	var $merchantId=null;
	
	function __construct(){
		#llaves
		$this->setIV( Config::VECTORINI );
		$this->setSimetricKey( Config::SIMETRICKEY );
		#datos
		$this->setMerchantId( Config::MERCHANTID );
	}
	
	function getXMLReq($postdata){
		$_post = urldecode($postdata);
		$output=null;
		$querystring = $this->decrypt($_post, $this->getSimetricKey(), $this->getIV());
		parse_str($querystring, $output);
		return $output;
	}
	
	function setIV($iv){ $this->iniVector = $iv; }
	function getIV(){ return $this->iniVector; }
	
	function setSimetricKey($sk){ $this->simetricKey = $sk; }
	function getSimetricKey(){ return $this->simetricKey; }
	
	function setMerchantId($value) { $this->merchantId = $value; }
	function getMerchantId() { return $this->merchantId; }
}


/**
PayclubConfig :: Dinersclub
Clase para almacenamiento de datos de configuracion
del boton de pagos.
 */
class Config {
  var $jsoncfg;
  var $environment;
  
	function __construct($env){
		$this->jsoncfg = @file_get_contents("config/app_commerce.json");
    if($this->jsoncfg == FALSE) {
      #throw new Exception($message, $severity, $severity, $file, $line);
      die();
    }
    $this->environment = $env;
	}
  
  public function get($value)
  {
    return $this->jsoncfg->environment;
  }
  
}


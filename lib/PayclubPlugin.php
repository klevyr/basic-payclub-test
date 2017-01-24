<?php
require_once ('RSAEncryption.php');
require_once ('TripleDESEncryption.php');

require_once ('PlugInClientSend.php');
/**
PayclubSend :: Dinersclub
Clase destinada al control del plugin del 
boton de pagos para el envio y generacion
del formulario de invocacion del boton de
pagos.
 */
class PayclubSend extends PlugInClientSend {
	var $merchantId = null;
	var $adquirerId = null;
	var $urlVPOS = null;
	var $xmlVPOS = null;
	
	
	function __construct( $mid, $localid ){
		#llaves
		$this->setIV( Config::VECTORINI );
		$this->setSignPrivateKey( Config::CCPRIVSIGN );
		$this->setCipherPublicKey( Config::PCPUBCIPHER );
		#param form fields
		$this->setMerchantId( $mid );
		$this->setAdquirerId( Config::ADQUIRERID );
		#param plugin
		$this->setLocalID( $localid );
		$this->setCurrencyID( Config::CURRENCYID );
		$this->setSourceDescription( Config::URLTEC );
		#param payclub urls
		$this->setUrlVPOS( Config::URLVPOS );
		$this->setXmlVPOS( Config::XMLVPOS );

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
class PayclubPosproc extends TripleDESEncryption {
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
const 
	/** Parametro Payclub RUC Comercio*/
	MERCHANTID 		= '',
	/** Parametro Payclub RUC COmercio / Url Tecnica*/
	ADQUIRERID 		= '1790015424001',
	/** Codigo de localidad */
	LOCALID 		= 'GN01',
	/** URL de invocacion pasarela de pago payclub */
	URLVPOS 		= 'https://www.optar.com.ec/webmpi/vpos',
	/** URL de consulta XML de payclub */
	XMLVPOS 		= 'https://www.optar.com.ec/webmpi/qvpos',
	/** URL Tecnica */
	URLTEC 			= 'https://www.payclub.com.ec/payclubexpress/www/webapp/preFactura/procesaPago',
	/** Cod. de moneda 840=Dollar */
	CURRENCYID 		= '840',
	/** Parametro Ventos de Inicializacion */
	VECTORINI 		= 'gctflzTop4o=',
	/** Llave simetrica */
	SIMETRICKEY 	= '37UghvQ997pYovc076hGsKTqyImriuwx',
	/** Payclub: Cifrado Publico */
	PCPUBCIPHER 	= '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDEL3OXZFKtoLHe+b/32B3RJJ1M
xBRbgP86F2QgOBfJGlR/eMFH/3cqgNgG9jhveNKPEGBZuMEkZmxncE7qytogv9TB
uGQlT6jr+77EBZawWxF8ds3UhqrxYPyD1eGzP9QXljSB47OHobt6ef+ZxKE9R6DU
cfOtnLx9pJHw0/Hb3wIDAQAB
-----END PUBLIC KEY-----',
	/** Payclub: Firma Publica */
	PCPUBSIGN 		= '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCfATtVcQ4bKQ8+bUlDxVY2hidS
BXAyvPtxfjfBztHhPVfbSQkktwQwNaJuLP2CC5ghgQvy156uyKDtgBL7Kq0g7M8J
TpfPB9G/RDmf2mAbz/ZVu+rNS4UJpbp4kX3hF6B7PZjwnvZ5QJjAFRhCRsbfNftK
ivDTQLYYQPfEze6tqQIDAQAB
-----END PUBLIC KEY-----',
	/** Cuponcity: Cifrado Privado */
	CCPRIVCIPHER 	= '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC8JltHpi0c5ZjuyEhJ1hruFrS2ko/XAiqKXnFpT1hE6HcGAIXL
Z7W1iSppquAs/N3l++ZIA3/yAgaH3imkUttarDwMlgMpclIlmkr4gruRwKNNHwl+
7d7OuKfP8C5UszBzPpoBBy4D7Jo+itAvNQ/H4It3bKV1eTx2bilQD2twgwIDAQAB
AoGBAIpqGVrTeHq6udBojS2skjE3iQiLN8BwOoWCxyI3GfPPpMhImCU0jawYVZhY
+gR+nmvz7cxqrrSGIvHPUYku32lJCGebRflZPVUpEOWCF++T+wSedZl8Sk6H8jDL
otAyUhlbdU74Y3kwajKXdeqxzZYJrAKV5cSl6gsbS2knsBmJAkEA4/knBgCdE38e
bdbIMNNjUEZHKm2oIHs3+io3h7Ke41GKpspqwk76Ka37RHcFHX6FjVBWrVaanNP7
F3SgzfvhbQJBANNH3lvgI0BxidsJ8UbvQtWNx5sK2G7YJl9ALxRBpLgY6gokhRKB
dgHLObFGFek+VPi/NQU/F7ZvLWOzjRz+U68CQFMusS9+f9ICWy8G5Mr0BtPeoM75
bhRUAYvVZaes5E7bjPTo/OVjfeJBamKD7yjg5og2nTnoVMgOjRr04kvmsU0CQQCz
oDh12BtgYKcZaFyPOD+UHBQFxrS3mGEXPhRInn6SXewsb1wLnbFcWRFVnAZo5KuN
R9KmATwByIfIahChA8DVAkBHdt+3cB5FZzqZgT3PTuJmf+k8IEnhdoTiuopi44xE
R++EE6gYoOC24D4P7x88aboY6a0i/PI/7swnmGIXsRr/
-----END RSA PRIVATE KEY-----',
	/** Cuponcity: Cifrado Publico */
	CCPUBCIPHER 	= '@deprecated',
	/** Cuponcity: Firma Privado */
	CCPRIVSIGN 		= '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDEflPcChsqGVUIpSWr7taufD5ejNPQxTbi6k/Z+zuVmUUqvCcD
GOIpailo2DY5SOKRpagOA0RtMpl0veBtRR6Q+kKordF+a9E/MhGSB1id5RyoZls2
5ueRBsiErmxkuY/eVm7tqOVAu4wEhVUBZL1Tt+qrSPQQJJkJ0TFDeR5NiwIDAQAB
AoGAS1FlEaYqDXQSXTL4grQVRGLJgXKXyqGdzVMlcpfTmh9bHOtsRMqn/ln8L30Z
I9IlTtDh3yUveG/51H7n5NqmwR2EBRw9vdafANTkJiig0vX2CrnSaufwKy1StUiq
M6Ailau8tUX312iSdh+rhYYwSUMl82i3YuC11pb03FkvvFECQQDtcWtgk1h8QOmU
0egxl/LHNB0Vuu8Tvovg1z+j3DbLwYWr/THbPnONyqEQJ9y6OB1tVpEnTBAfDp1k
KmuBcG5TAkEA09meuNErFBGEXCadPdciA35+Ngwe/1NsHrfrqkwP3O2aZw9Fa+lf
kuOXbcgR8uLj75/jExsmD/0ytT5UKSoM6QJAE021PRS9jNx6IO8vwVhpFGjYNHwO
E7zaAl19fwTYL5h8FY9wDjL1kgF3NqkG+Ny3B7yR8G/un5fAk/Qe6VcKoQJBAKQp
HjVY4GjMqiQ+R9qDune6rVJ0UhDINCAZpSRxjFlGRT9XNhRI4yaP9ee8ASyVZMtq
uisz/mqlaYXIpq6/RwECQCcndHILfTYiW2KJ4UtaitrJehwo+zeIFLdBZ3toJrqZ
ckQOjMlpEcRG19KH3nP2W5pioxd3whguZnwTgawagAw=
-----END RSA PRIVATE KEY-----',
	/** Cuponcity: Firma Publica */
	CCPUBSIGN 		= '@deprecated';

}


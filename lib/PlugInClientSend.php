<?php
namespace Payclub;

define('SIZEREFERENCE', 30);

class PlugInClientSend
{
	var $LocalID;
	var $TransacctionID;
	var $CurrencyID;
	var $TransacctionValue;
	var $TaxValue1;
	var $TaxValue2;
	var $TipValue;
	var $SourceDescription;
	var $Referencia1;
	var $Referencia2;
	var $Referencia3;
	var $Referencia4;
	var $Referencia5;

	var $IV;
	var $SignPrivateKey;
  var $CipherPrivateKey;
	var $CipherPublicKey;

	var $xmlGenerateKey;
	var $xmlGenerateKeyEnc;
	var $xmlRequest;
	var $xmlDigitalSign;

	var $txtRequest;

	var $AuthorizationState;
	var $AutorizationCode;
	var $ErrorCode;
	var $ErrorDetails;

	function PlugInClientSend()							{ }

	function setLocalID($localID)						{ $this->LocalID = $localID; }

	function setTransacctionID($transacctionID)			{ $this->TransacctionID = $transacctionID; }

	function setTransacctionValue($transacctionValue)	{ $this->TransacctionValue = $transacctionValue; }

	function setTaxValue1($taxValue1)					{ $this->TaxValue1 = $taxValue1; }

	function setTaxValue2($taxValue2)					{ $this->TaxValue2 = $taxValue2; }

	function setTipValue($tipValue)						{ $this->TipValue = $tipValue; }

	function setCurrencyID($currencyID)					{ $this->CurrencyID = $currencyID; }

	function setSourceDescription($sourceDescription)	{ $this->SourceDescription = $sourceDescription; }

	function setIV($iv)									{ $this->IV = $iv; }

	function setSignPrivateKey($signPrivateKey)			{ $this->SignPrivateKey = $signPrivateKey; }

	function setCipherPrivateKey($cipherPrivateKey)		{ $this->CipherPrivateKey = $cipherPrivateKey; }

	function setCipherPublicKey($cipherPublicKey)		{ $this->CipherPublicKey = $cipherPublicKey; }

	function setCipherPublicKeyFromFile($file)			{ $this->CipherPublicKey = RSAEncryption.readFile($file); }
	
	function setReferencia1($referencia) 
	{
		if(strlen($referencia) > SIZEREFERENCE)
			return "El nꭥro de caracteres de la referncia 1 no puede ser mayor a " . SIZEREFERENCE;
		$this->Referencia1 = $referencia;
	}

	function setReferencia2($referencia)
	{
		if(strlen($referencia) > SIZEREFERENCE)
			return "El nꭥro de caracteres de la referncia 2 no puede ser mayor a " . SIZEREFERENCE;
		$this->Referencia2 = $referencia;
	}

	function setReferencia3($referencia)
	{
		if(strlen($referencia) > SIZEREFERENCE)
			return "El nꭥro de caracteres de la referncia 3 no puede ser mayor a " . SIZEREFERENCE;
		$this->Referencia3 = $referencia;
	}

	function setReferencia4($referencia)
	{
		if(strlen($referencia) > SIZEREFERENCE)
			return "El nꭥro de caracteres de la referncia 4 no puede ser mayor a " . SIZEREFERENCE;
		$this->Referencia4 = $referencia;
	}

	function setReferencia5($referencia)
	{
		if(strlen($referencia) > SIZEREFERENCE)
			return "El nꭥro de caracteres de la referncia 5 no puede ser mayor a " . SIZEREFERENCE;
		$this->Referencia5 = $referencia;
	}


	function validaNulo($sDato)
	{
		if($sDato == null)
			return "";
		return $sDato;
	}

	/*function XMLProcess(HttpServletRequest request) throws Exception
	{
		System.out.println("URL: " + request.getRequestURL().toString());
		XMLProcess(request.getRequestURL().toString());
	}*/

	function XMLProcess()
	{
		$this->xmlRequest = "";

		$cadena = $this->validaNulo($this->LocalID) . ";" .
		$this->validaNulo($this->TransacctionID) . ";" .
		$this->validaNulo($this->CurrencyID) . ";" .
		$this->validaNulo($this->TransacctionValue) . ";" .
		$this->validaNulo($this->TaxValue1) . ";" .
		$this->validaNulo($this->TaxValue2) . ";" .
		$this->validaNulo($this->TipValue) . ";" .
		$this->validaNulo($this->SourceDescription) . ";" .
		$this->validaNulo($this->Referencia1) . ";" .
		$this->validaNulo($this->Referencia2) . ";" .
		$this->validaNulo($this->Referencia3) . ";" .
		$this->validaNulo($this->Referencia4) . ";" .
		$this->validaNulo($this->Referencia5);

		$this->txtRequest = $cadena;

		$this->procesosEncripcion($cadena);
	}



	function procesosEncripcion($cadena)
	{
		$d3 = new TripleDESEncryption();
		$cadena = $d3->encrypt($cadena, $this->xmlGenerateKey, $this->IV);
		$cadena = urlencode($cadena);
		$this->xmlRequest = $cadena;

		// Se firma la cadena encriptada
		$this->xmlDigitalSign = "";

		//openssl_sign($this->xmlRequest, $this->xmlDigitalSign, $this->SignPrivateKey, OPENSSL_ALGO_MD2);
		openssl_sign($this->xmlRequest, $this->xmlDigitalSign, $this->SignPrivateKey);
		$this->xmlDigitalSign = base64_encode($this->xmlDigitalSign);
		$this->xmlDigitalSign = urlencode($this->xmlDigitalSign);
	}

	function CreateXMLGENERATEKEY()
	{
		$this->xmlGenerateKeyEnc = "";

		$d3 = new TripleDESEncryption();

		$this->xmlGenerateKey = $d3->generateKey();
		
		//encripta la llave
		$rsa = new RSAEncryption();
		$rsa->setPublicKey($this->CipherPublicKey);
		$this->xmlGenerateKeyEnc = $rsa->encrypt($this->xmlGenerateKey);
		$this->xmlGenerateKeyEnc = urlencode($this->xmlGenerateKeyEnc);

		return $this->xmlGenerateKeyEnc;
	}

	function getXMLREQUEST()
	{
		return $this->xmlRequest;
	}

	function getXMLDIGITALSIGN()
	{
		return $this->xmlDigitalSign;
	}

	function getTXTREQUEST()
	{
		return $this->txtRequest;
	}

	function getTransacctionID(){ return $this->TransacctionID; }
	
	public function __toString ()	{
		return "Payclub\PlugInClientSend()";
	}

}


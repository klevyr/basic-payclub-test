<?php
namespace Payclub;
use Payclub\RSAEncryption;
use Payclub\TripleDESEncryption;

class PlugInClientRecive
{
	var $LocalID;
	var $MerchantID;
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
	var $SignPublicKey;
	var $CipherPrivateKey;
	var $CipherPublicKey;

	var $xmlGenerateKey;
	var $xmlGenerateKeyEnc;
	var $xmlRequest;
	var $xmlDigitalSign;

	var $AuthorizationState;
	var $AutorizationCode;
	var $ErrorCode;
	var $ErrorDetails;

	var $sizeReference = 30;

	function PlugInClientRecive ()
	{
		$this->sizeReference = 30;
	}

	function setLocalID($localID)
	{
		$this->LocalID = $localID;
	}

	function setMerchantID($merchantID)
	{
		$this->MerchantID = $merchantID;
	}

	function setTransacctionID($transacctionID)
	{
		$this->TransacctionID = $transacctionID;
	}

	function setTransacctionValue($transacctionValue)
	{
		$this->TransacctionValue = $transacctionValue;
	}

	function setTaxValue1($taxValue1)
	{
		$this->TaxValue1 = $taxValue1;
	}

	function setTaxValue2($taxValue2)
	{
		$this->TaxValue2 = $taxValue2;
	}

	function setTipValue($tipValue)
	{
		$this->TipValue = $tipValue;
	}

	function setCurrencyID($currencyID)
	{
		$this->CurrencyID = $currencyID;
	}

	function setSourceDescription($sourceDescription)
	{
		$this->SourceDescription = $sourceDescription;
	}

	function setReferencia1($referencia)
	{
		if(strlen($referencia) > $this->sizeReference)
			return "El nꭥro de caracteres de la referncia 1 no puede ser mayor a " . $this->sizeReference;
		$this->Referencia1 = $referencia;
	}

	function setReferencia2($referencia)
	{
		if(strlen($referencia) > $this->sizeReference)
			return "El nꭥro de caracteres de la referncia 1 no puede ser mayor a " . $this->sizeReference;
		$this->Referencia2 = $referencia;
	}

	function setReferencia3($referencia)
	{
		if(strlen($referencia) > $this->sizeReference)
			return "El nꭥro de caracteres de la referncia 1 no puede ser mayor a " . $this->sizeReference;
		$this->Referencia3 = $referencia;
	}

	function setReferencia4($referencia)
	{
		if(strlen($referencia) > $this->sizeReference)
			return "El nꭥro de caracteres de la referncia 1 no puede ser mayor a " . $this->sizeReference;
		$this->Referencia4 = $referencia;
	}

	function setReferencia5($referencia)
	{
		if(strlen($referencia) > $this->sizeReference)
			return "El nꭥro de caracteres de la referncia 1 no puede ser mayor a " . $this->sizeReference;
		$this->Referencia5 = $referencia;
	}

	function setIV($iv)
	{
		$this->IV = $iv;
	}

	function setIVFromFile($file)
	{
		$rsa = new RSAEncryption();
		$this->IV = $rsa->readFile($file);
	}

	/*function setSignPrivateKey($signPrivateKey)
	{
		$this->SignPrivateKey = $signPrivateKey;
	}*/

	function setSignPublicKey($signPublicKey)
	{
		$this->SignPublicKey = $signPublicKey;
	}

	function setCipherPrivateKey($cipherPrivateKey)
	{
		$this->CipherPrivateKey = $cipherPrivateKey;
	}

	function setCipherPublicKey($cipherPublicKey)
	{
		$this->CipherPublicKey = $cipherPublicKey;
	}

	/*function setCipherPublicKeyFromFile($file)
	{
		$this->CipherPublicKey = RSAEncryption.readFile($file);
	}*/

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

	function XMLProcess($cadena, $firma)
	{
		$this->xmlResponse = "";
		$firmaCorrecta = $this->validateSign($cadena, $firma);
		if ($firmaCorrecta != 1)
			return "Los datos han sido alterados. Error al verificar la firma digital";

		/*try
		{*/
			$cadena = urldecode($cadena);

			$d3 = new TripleDESEncryption();
			$cadena = $d3->decrypt($cadena, $this->xmlGenerateKey, $this->IV);

			$this->xmlResponse = $cadena;
			$sbDatos = explode(";" , $cadena);

			$num = count($sbDatos);
			if ($num > 0)
			{
				$sbDatos[0] = strtoupper($sbDatos[0]);
				$this->AuthorizationState = $sbDatos[0];
				if($sbDatos[0] == "Y")
				{
					if ($num > 1)
					{
						$this->AuthorizationCode = $sbDatos[1];
						if (!is_numeric($sbDatos[1]))
							return "El c㤩go de autorizaci㮠debe ser un valor num鲩co.";
					}
					else
					{
						$this->AuthorizationCode = "";
						return "El c㤩go de autorizaci㮠es obligatorio.";
					}
				}
				else if($sbDatos[0] == "N")
					$this->AuthorizationCode = "";
				else
					return "El estado de autorizaci㮠tiene un valor incorrecto. (" . $sbDatos[0] . ")";
			}
			else
			{
				$this->AuthorizationState = "";
				return "El estado de autorizaci㮠es obligatorio.";
			}

			$i = 2;

			if ($num > $i)
				$this->ErrorCode = $sbDatos[$i];
			else
				$this->ErrorCode = "";

			$i++;
			if ($num > $i)
				$this->ErrorDetails = $sbDatos[$i];
			else
				$this->ErrorDetails = "";

			$i++;
			if ($num > $i)
				$this->LocalID = $sbDatos[$i];
			else
				$this->LocalID = "";

			/*i++;
			if (array_count_values($sbDatos) > i)
				$this->MerchantID = $sbDatos[i];
			else
				$this->MerchantID = "";
			*/
			$i++;
			if ($num > $i)
				$this->TransacctionID = $sbDatos[$i];
			else
				$this->TransacctionID = "";

			$i++;
			if ($num > $i)
				$this->CurrencyID = $sbDatos[$i];
			else
				$this->CurrencyID = "";

			$i++;
			if ($num > $i)
				$this->TransacctionValue = $sbDatos[$i];
			else
				$this->TransacctionValue = "0";

			$i++;
			if ($num > $i)
				$this->TaxValue1 = $sbDatos[$i];
			else
				$this->TaxValue1 = "0";

			$i++;
			if ($num > $i)
				$this->TaxValue2 = $sbDatos[$i];
			else
				$this->TaxValue2 = "0";

			$i++;
			if ($num > $i)
				$this->TipValue = $sbDatos[$i];
			else
				$this->TipValue = "0";

			$i++;
			if ($num > $i)
				$this->SourceDescription = $sbDatos[$i];
			else
				$this->SourceDescription = "";

			$i++;
			if ($num > $i)
				$this->Referencia1 = $sbDatos[$i];
			else
				$this->Referencia1 = "";

			$i++;
			if ($num > $i)
				$this->Referencia2 = $sbDatos[$i];
			else
				$this->Referencia2 = "";

			$i++;
			if ($num > $i)
				$this->Referencia3 = $sbDatos[$i];
			else
				$this->Referencia3 = "";

			$i++;
			if ($num > $i)
				$this->Referencia4 = $sbDatos[$i];
			else
				$this->Referencia4 = "";

			$i++;
			if ($num > $i)
				$this->Referencia5 = $sbDatos[$i];
			else
				$this->Referencia5 = "";
			return $firmaCorrecta;
		/*}
		catch(Exception $e)
		{
			$mensaje = $e->getMessage() != null ? $e->getMessage() : "";
			throw new Exception("Se ha presentado un problema al procesar la informaci㮜n" . $mensaje);
		}*/

	}

	function validateSign($cadena, $firmaDigitalVPOS)
	{
		$firmaDigitalVPOS = base64_decode(urldecode($firmaDigitalVPOS));
		//$ok = openssl_verify($cadena, $firmaDigitalVPOS, $this->getSignPublicKey(), OPENSSL_ALGO_MD2);
		$ok = openssl_verify($cadena, $firmaDigitalVPOS, $this->getSignPublicKey());
		return $ok;
	}

	function CreateXMLGENERATEKEY()
	{
		$this->xmlGenerateKeyEnc = "";
		/*try
		{*/
			$d3 = new TripleDESEncryption();
			$this->xmlGenerateKey = $d3->generateKey();
			//encripta la llave
			$rsa = new RSAEncryption();
			$rsa->setPublicKey($this->CipherPublicKey);
			$this->xmlGenerateKeyEnc = $rsa->encrypt($this->xmlGenerateKey);
			$this->xmlGenerateKeyEnc = urlencode($this->xmlGenerateKeyEnc);
		/*}
		catch (Exception $ex)
		{
			throw $ex;
		}*/

		return $this->xmlGenerateKeyEnc;
	}

	function setXMLGENERATEKEY($xmlGenerateKey)
	{
		/*try
		{*/
			$this->xmlGenerateKeyEnc = $xmlGenerateKey;
			$rsa = new RSAEncryption();
			$rsa->setPrivateKey($this->CipherPrivateKey);
			$xmlGenerateKey = urldecode($xmlGenerateKey);
			if(strrpos($xmlGenerateKey, ">") != -1)
				$xmlGenerateKey = substr($xmlGenerateKey, 0, strlen($xmlGenerateKey) - 1);
			$this->xmlGenerateKey = $rsa->decrypt($xmlGenerateKey);
			if ($this->xmlGenerateKey == "")
				return "Se ha presentado un problema al desencriptar.  Posiblemente la llave utilizada no es correcta";
		/*}
		catch (Exception $ex)
		{
			throw $ex;
		}*/
	}

	function getLocalID()
	{
		return $this->LocalID;
	}

	function getMerchantID()
	{
		return $this->MerchantID;
	}

	function getTransacctionID()
	{
		return $this->TransacctionID;
	}

	function getTransacctionValue()
	{
		return $this->TransacctionValue;
	}

	function getTaxValue1()
	{
		return $this->TaxValue1;
	}

	function getTaxValue2()
	{
		return $this->TaxValue2;
	}

	function getTipValue()
	{
		return $this->TipValue;
	}

	function getCurrencyID()
	{
		return $this->CurrencyID;
	}

	function getSourceDescription()
	{
		return $this->SourceDescription;
	}

	function getReferencia1()
	{
		return $this->Referencia1;
	}

	function getReferencia2()
	{
		return $this->Referencia2;
	}

	function getReferencia3()
	{
		return $this->Referencia3;
	}

	function getReferencia4()
	{
		return $this->Referencia4;
	}

	function getReferencia5()
	{
		return $this->Referencia5;
	}

	function getIV()
	{
		return $this->IV;
	}

	function getSignPrivateKey()
	{
		return $this->SignPrivateKey;
	}


	function getSignPublicKey()
	{
		return $this->SignPublicKey;
	}

	/*String getCipherPrivateKey()
	{
		return CipherPrivateKey;
	}

	String getCipherPublicKey()
	{
		return CipherPublicKey;
	}
*/
	function getXMLREQUEST()
	{
		return $this->xmlRequest;
	}

	function getXMLDIGITALSIGN()
	{
		/*xmlDigitalSingn = "";
		try
		{
			Sign sign = new Sign();
			sign.setPrivateKey(SignPrivateKey);
			String cadena = "Esta es una prueba";
			xmlDigitalSingn = sign.generateSign(xmlRequest );
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}*/
		return $this->xmlDigitalSign;
	}

	function getAuthorizationState()
	{
		return $this->AuthorizationState;
	}

	function getAuthorizationCode()
	{
		return $this->AuthorizationCode;
	}

	function getErrorCode()
	{
		return $this->ErrorCode;
	}

	function getErrorDetails()
	{
		return $this->ErrorDetails;
	}

	function getXMLGENERATEKEY()
	{
		return$this-> xmlGenerateKeyEnc;
	}

	public function __toString ()	{
		return "Payclub\PlugInClientRecive()";
	}
	
}

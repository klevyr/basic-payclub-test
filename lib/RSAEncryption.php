<?php
namespace Payclub;

class RSAEncryption
{
	var $StringPublicKey;
	var $StringPrivateKey;
	var $StringCipherText;
	var $StrngClearText;
	var $intLenKey;

	function readFile($file)
	{
		$texto = "";
		$handle = fopen($file, "r");
		$texto = fread($handle, filesize($file));
		fclose($handle);
		return $texto;
	}

	function setPublicKey($skey)
	{
		$this->StringPublicKey = $skey;
	}

	function setPrivateKey($skey)
	{
		$this->StringPrivateKey = $skey;
	}

	function encrypt($text)
	{
		$encryptedText = "";
		openssl_public_encrypt($text, $encryptedText, $this->StringPublicKey);
		
		$encryptedText = base64_encode($encryptedText);
		return $encryptedText;
	}

	function decrypt($text)
	{
		$decryptedText = "";
		$text = base64_decode($text);
		openssl_private_decrypt($text, $decryptedText, $this->StringPrivateKey);
		return $decryptedText;
	}

	function generateKey($filePubK, $filePriK)
	{
		$res=openssl_pkey_new();
		// Get private key
		openssl_pkey_export($res, $privkey);
		// Get public key
		$pubkey=openssl_pkey_get_details($res);
		$pubkey=$pubkey["key"];

		$this->StringPrivateKey = "file://" . $filePriK;
		$this->StringPublicKey  = "file://" . $filePubK;

		$handle = fopen($filePriK, "w");
		fwrite($handle, $privkey);
		$handle = fopen($filePubK, "w");
		fwrite($handle, $pubkey);
	}
	public function __toString ()	{
		return "Payclub\RSAEncryption()";
	}
}

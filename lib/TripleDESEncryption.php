<?php
namespace Payclub;

class TripleDESEncryption
{
	function TripleDESEncryption (){}

	function encrypt($toEncrypt, $key, $IV)
	{
		#print('KEY'.$key."|IV".$IV);die();
		$data = $toEncrypt;
		$key = base64_decode($key);
		$IV = base64_decode($IV);

		$dlen = strlen($data);
		$pad = 16 - fmod($dlen, 16);
		if ($pad > 0)
		{
			$i = (int)$pad;
			while ($i > 0)
			{
				$data.="\0";
				//$data.="\0";
				$i--;
			}
		}

		$cipher = mcrypt_module_open(MCRYPT_3DES,'',MCRYPT_MODE_CBC,'');

		mcrypt_generic_init($cipher, $key, $IV);
		$data = mcrypt_generic($cipher,$data);
		mcrypt_generic_deinit($cipher);

		$data = base64_encode($data);
		return $data;
	}

	function decrypt($toDecrypt, $key, $IV)
	{
		$data = $toDecrypt;
		$key = base64_decode($key);
		$IV = base64_decode($IV);
		$data = str_replace("\n", "", $data);
		$data = str_replace("\r", "", $data);
		$data = base64_decode($data);

		$cipher = mcrypt_module_open(MCRYPT_3DES,'',MCRYPT_MODE_CBC,'');

		mcrypt_generic_init($cipher, $key, $IV);
		$data = mdecrypt_generic($cipher,$data);
		mcrypt_generic_deinit($cipher);


		$data = str_replace("\0", "", $data);
		return $data;
	}

	function generateKey ()
	{
		return base64_encode($this->generateKeyInterna(24, 24, true, false, true));
	}

	function generateKeyInterna ($minlength, $maxlength, $useupper, $usespecial, $usenumbers)
	{
		$key = "";
		$charset = "abcdefghijklmnopqrstuvwxyz";
		if ($useupper) $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if ($usenumbers) $charset .= "0123456789";
		if ($usespecial) $charset .= "~@#$%^*()_+-={}|]["; // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./";
		if ($minlength > $maxlength)
			$length = mt_rand ($maxlength, $minlength);
		else
			$length = mt_rand ($minlength, $maxlength);
		for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
		return $key;
	}
	
	public function __toString ()	{
		return "Payclub\TripleDESEncryption()";
	}
}

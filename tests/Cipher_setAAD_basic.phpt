--TEST--
Crypto\Cipher::getAAD basic usage.
--SKIPIF--
<?php if (!Crypto\Cipher::hasMode(Crypto\Cipher::MODE_GCM)) die("Skip: GCM mode not defined (update OpenSSL version)"); ?>
--FILE--
<?php
$key = str_repeat('x', 32);
$iv = str_repeat('i', 16);
$data = str_repeat('a', 16);
$aad = str_repeat('b', 16);

// encryption
$cipher = new Crypto\Cipher('aes-256-gcm');
$cipher->setAAD($aad);
$ct = $cipher->encrypt($data, $key, $iv);
$tag = $cipher->getTag(16);
echo bin2hex($ct) . "\n";

// decryption
$cipher = new Crypto\Cipher('aes-256-gcm');
$cipher->setTag($tag);
$cipher->setAAD($aad);
echo $cipher->decrypt($ct, $key, $iv) . "\n";


// flow exception
try {
	$cipher->setAAD($aad);
}
catch (Crypto\AlgorithmException $e) {
	if ($e->getCode() == Crypto\AlgorithmException::CIPHER_AAD_SETTER_FLOW) {
		echo "FLOW\n";
	}
}


?>
--EXPECT--
FLOW
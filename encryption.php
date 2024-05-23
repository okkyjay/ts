<?php

// Encrypt function
function encrypt($data, $key) {
    $method = 'aes-256-cbc';
    $ivLength = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($ivLength);
    $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

// Decrypt function
function decrypt($data, $key) {
    $method = 'aes-256-cbc';
    $data = base64_decode($data);
    $ivLength = openssl_cipher_iv_length($method);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    return openssl_decrypt($encrypted, $method, $key, OPENSSL_RAW_DATA, $iv);
}

function verify_signature($payload, $signature, $public_key) {
    if(!preg_match('/^-----BEGIN/', $public_key)) {
        $public_key = "-----BEGIN PUBLIC KEY-----\n".preg_replace('/.{1,64}/u', "$0\n", $public_key)."-----END PUBLIC KEY-----";
    }

    $public_key_resource = openssl_pkey_get_public($public_key);

    return openssl_verify($payload, base64_decode($signature), $public_key_resource, OPENSSL_ALGO_SHA256);
}

// Example usage
$key = 'secretkey'; // Replace with your secret key
$data = 'Hello, World!';

// Encrypt data
$encryptedData = encrypt($data, $key);
echo "Encrypted data: $encryptedData\n";

// Decrypt data
$decryptedData = decrypt($encryptedData, $key);
echo "Decrypted data: $decryptedData\n";

?>

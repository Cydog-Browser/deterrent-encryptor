<?php
// This function encrypts web page file dynamically
// See display-test-example.php for how to use this function
function encryptWebPage(string $path, string $password, string $type): string {
    if (file_exists($path)) {
        $salt = openssl_random_pseudo_bytes(16); // Generate a random salt
        $key = hash_pbkdf2('sha256', $password, $salt, 10000, 32, true); // Derive key from password and salt
        $iv = openssl_random_pseudo_bytes(12); // Generate a random IV for AES-GCM
        $tag = null; // Will be set by openssl_encrypt for GCM mode
        // Use print_r(unpack('c*', $insert_variable_here)); to see buffer arrays
        if($type === "html"){
            $output = file_get_contents($path);
            $encrypted = openssl_encrypt(
                $output,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                '', // Additional authenticated data, can be empty
                16 // Tag length
            );
            if ($encrypted === false) {
                return "Error. PHP OpenSSL library failed."; // Handle encryption error
            }
            $array = [
                'ciphertext' => base64_encode($encrypted),
                'iv' => base64_encode($iv),
                'salt' => base64_encode($salt),
                'tag' => base64_encode($tag),
            ];
            return json_encode($array);
        } else if ($type === "php"){
            ob_start(); // Start output buffering
            include $path; // Include the script whose output you want to capture
            $output = ob_get_clean();
            $encrypted = openssl_encrypt(
                $output,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                '', // Additional authenticated data, can be empty
                16 // Tag length
            );
            if ($encrypted === false) {
                return "Error. PHP OpenSSL library failed."; // Handle encryption error
            }
            $array = [
                'ciphertext' => base64_encode($encrypted),
                'iv' => base64_encode($iv),
                'salt' => base64_encode($salt),
                'tag' => base64_encode($tag),
            ];
            return json_encode($array);
        } else if ($type === "other"){
            // Other file types might include JSON file types
            $output = file_get_contents($path);
            $encrypted = openssl_encrypt(
                $output,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                '', // Additional authenticated data, can be empty
                16 // Tag length
            );
            if ($encrypted === false) {
                return "Error. PHP OpenSSL library failed."; // Handle encryption error
            }
            $array = [
                'ciphertext' => base64_encode($encrypted),
                'iv' => base64_encode($iv),
                'salt' => base64_encode($salt),
                'tag' => base64_encode($tag),
            ];
            return json_encode($array);
        } else {
            return "Error. Invalid type.";
        }
    } else {
        return "Error. File does not exist.";
    }
}
?>
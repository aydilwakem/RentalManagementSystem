<?php
$ch = curl_init("https://api.paymongo.com/v1/links");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set CA cert manually here
curl_setopt($ch, CURLOPT_CAINFO, "C:/php/extras/ssl/cacert.pem");

$output = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Success! Response: ' . substr($output, 0, 200);
}

curl_close($ch);


/*

------------------- Instructions for setting up CA cert for cURL in PHP -------------------------


1. Download cacert.pem from this site:
https://curl.se/docs/caextract.html

2. Copy and paste file to:
C:\php\extras\ssl

- or kung saang path nakalagay yung PHP niyo

3. Open PHP.ini Configuration file, copy and paste the path of the cacert.pem to "CURL" and "OPENSSL"

[curl]
; A default value for the CURLOPT_CAINFO option. This is required to be an
; absolute path.
curl.cainfo = "C:/php/extras/ssl/cacert.pem"

[openssl]
; The location of a Certificate Authority (CA) file on the local filesystem
; to use when verifying the identity of SSL/TLS peers. Most users should
; not specify a value for this directive as PHP will attempt to use the
; OS-managed cert stores in its absence. If specified, this value may still
; be overridden on a per-stream basis via the "cafile" SSL stream context
; option.
openssl.cafile = "C:/php/extras/ssl/cacert.pem"


*/

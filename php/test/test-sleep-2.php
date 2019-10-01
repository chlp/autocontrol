<?php

set_time_limit(2);

function callCurl($data, $action)
{
    $handle = curl_init();
    $headers = array(
        'Content-type: text/xml; charset="utf-8"',
        'Cache-Control: no-cache',
        'Pragma: no-cache',
        "SOAPAction: $action",
    );
    /** @noinspection CurlSslServerSpoofingInspection */
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($handle, CURLOPT_URL, '/test-sleep.php');
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_USERPWD, 1 . ':' . 2);
    curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($handle, CURLOPT_FAILONERROR, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($handle);
    if (empty($response)) {
        throw new SoapFault('CURL error: ' . curl_error($handle), curl_errno($handle));
    }
    curl_close($handle);
    return $response;
}

var_dump(callCurl(1, 2));
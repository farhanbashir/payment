<?php

function send_notification_android($registatoin_ids, $message) {
$CI =& get_instance();
        // Set POST variables
        $url = $CI->config->item("REMOTE_SOCKET_GOOGLE");

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => array("title"=>$CI->config->item("android_notification_title"),"subtitle"=>'',"message"=>$message),
        );

    //print_r($fields);die;
        $headers = array(
            'Authorization: key=' . $CI->config->item('google_key'),
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        //echo $result;
    }

    function send_notification_iphone($deviceToken, $message, $file_url, $sound='default')
    {
        $CI =& get_instance();
        //$deviceToken = '7229e0f7cc34bd639a31e81802def2c02945b0a89d01ce52c7528f8671ef8f32';

        $file_url = getcwd().$CI->config->item('pem');
        // Put your private key's passphrase here:
        //$passphrase = 'developmentc2gapns';

        $passphrase = $CI->config->item('PASS_PHRASE');

        $remote_url = $CI->config->item('REMOTE_SOCKET_APPLE');

        // Put your alert message here:
        //$message = 'Helo this is first message.';

        ////////////////////////////////////////////////////////////////////////////////

        $ctx = stream_context_create();

        //stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
        stream_context_set_option($ctx, 'ssl', 'local_cert', $file_url);
        //stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client(
                                   $remote_url, $err,
                                   $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        //echo 'Connected to APNS' . PHP_EOL;

        // Create the payload body
        $body['aps'] = array(
                             'alert' => $message,
                             'sound' => 'default',
                             'ji' =>88
                             );




        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

      /*  if (!$result)
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;
        */
        // Close the connection to the server
        fclose($fp);


    }

function sendEmail($data)
{
    $to = $data['to'];
    $from = $data["from"];
    $subject = $data['subject'];
    $message = $data['message'];
    $headers = "From: $from" . "\r\n";

    mail($to, $subject, $message,$headers);
}    
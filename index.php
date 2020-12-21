<?php

require __DIR__ . '/vendor/autoload.php';

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;

$pass_signature = true;

// set LINE channel_access_token and channel_secret
$channel_access_token = "GXBAWWuo6f3nnMXpfd0THXW8UUrjb+pK32Gy9bJscPGkDPXJH5HPkuYTnvs7Kum4qOOX4UX8afT3r2QCCVctIvo1UWXivpPS+oPbziVyyPEDyAn9c/8vvHr9hIjdnygjvnYxHVTkn5zv9kVDblQfhwdB04t89/1O/w1cDnyilFU=";
$channel_secret = "782cac53e7799cd2bb91e7f579e2e51f";

// inisiasi objek bot
$httpClient = new CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

$configs = [
    'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);

$app->get('/', function($req, $res) {
    echo 'Hello World!';
});

// buat route untuk webhook
$app->post('/webhook', function ($req, $res) use ($bot, $httpClient, $pass_signature) 
{
    // get request body and line signature header
    $body = file_get_contents('php://input');
    $signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

    // log body and signature
    file_put_contents('php://stderr', 'Body: '.$body);

    if($pass_signature === false)
    {
        // is LINE_SIGNATURE exists in request header?
        if (empty($signature))
        {
            return $res->withStatus(400, 'Signature not set');
        }

        // is this request comes from LINE?
        if (! SignatureValidator::validatorSignature($body, $channel_secret, $signature))
        {
            return $res->withStatus(400, 'Invalid signature');
        }
    }

});
$app->run();
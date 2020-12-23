<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

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

$app = AppFactory::create();
$app->setBasePath("/public");

$app->get('/', function (Request $request, Response $response, $args)
{
    $response->getBody()->write("Welcome at Dicoding Line ChatBot");
    return $response;
});

// buat route untuk webhook
$app->post('/webhook', function (Request $request, Response $response) use ($channel_secret, $bot, $httpClient, $pass_signature) 
{
    // get request body and line signature header
    $body = $request->getBody();
    $signature = $request->getHeaderLine('HTTP_X_LINE_SIGNATURE');

    // log body and signature
    file_put_contents('php://stderr', 'Body: '.$body);

    if($pass_signature === false)
    {
        // is LINE_SIGNATURE exists in request header?
        if (empty($signature))
        {
            return $response->withStatus(400, 'Signature not set');
        }

        // is this request comes from LINE?
        if (!SignatureValidator::validatorSignature($body, $channel_secret, $signature))
        {
            return $response->withStatus(400, 'Invalid signature');
        }
    }

    $data = json_decode($body, true);
    foreach ($data['events'] as $event)
    {
        $message = strtolower($event['message']['text']);
        if ($message == 'Halo')
        {
            $onboarding = new TextMessageBuilder('Ketik "Cafe" untuk melakukan pencarian Cafe Shop Terfavoritemu di Bandung.');
            $result = $bot->replyMessage($event['replyToken'], $onboarding);
            return $result->getHTTPStatus() . ' ' . $result->getRawBody();
        } 
    
        elseif($message == 'Cafe')
        {
            $carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("Jardin Cafe", "Address: Jl. Cimanuk No.1A, Citarum, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40115","https://i.imgur.com/aqtehou.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/jardinbdg'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("La Costille Cafe", "Address: Jl. Prof. Dr. Sutami No.98, Pasteur, Kec. Sukasari, Kota Bandung, Jawa Barat 40533","https://i.imgur.com/5BBpLyE.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/la_costillabdg'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("Mimiti Coffee & Space", "Address: Jl. Bukit Pakar Timur Jl. Pakar Bar. No.7, Dago, Kec. Cimenyan, Bandung, Jawa Barat 40198","https://i.imgur.com/9Twqs2T.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/mimiticoffee'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("Picknick Cafe", "Address: Jl. Pasir Kaliki No.176, Pasirkaliki, Cicendo, Bandung City, West Java 40173","https://i.imgur.com/kKYhxSf.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/picknick.kaliki'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("Please Please Please Cafe", "Address: Jl. Progo No.37, Citarum, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40115","https://i.imgur.com/YOwVY3o.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/ppplease.eat'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("Sejiwa Coffee", "Address: Jl. Progo No.15, Citarum, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40115","https://i.imgur.com/YxvhEJF.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/sejiwacoffee'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("Sudut Pandang", "Address: Jl. Pagermaneuh, RT.05/RW.07, Pagerwangi, Lembang, Kabupaten Bandung Barat, Jawa Barat 40391","https://i.imgur.com/oW9JyfZ.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/sudutpandang.bdg'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("Sydwic Cafe", "Address: Cilaki St No.63, Citarum, Bandung Wetan, Bandung City, West Java 40115","https://i.imgur.com/Rwolq3m.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/sydwic'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("The Potting Shed", "Address: Jl. Panumbang Jaya No.5, Ciumbuleuit, Kec. Cidadap, Kota Bandung, Jawa Barat 40142","https://i.imgur.com/S2Xr3P1.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/thehousetourhotel'),
                ]),
                new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumTemplateBuilder("Tilu Kitchen & Patisserie", "Address: LLRE Martadinata St No.81, Citarum, Riau,Bandung, Bandung City, West Java 40115","https://i.imgur.com/XbhIgFU.jpeg",[
                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka', 'https://www.instagram.com/tilukitchenandpatisserie'),
                ]),
            ]);

            $templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('Cafe Hits Bandung', $carouselTemplateBuilder);
            $result = $bot->replyMessage($event['replyToken'], $templateMessage);
            return $result->getHTTPStatus() . ' ' . $result->getRawBody();
        
        } 
        
        elseif ($message != '')
        {
            $textMessageBuilder = new TextMessageBuilder('Tidak ada hasil ditemukan!');
            $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
            return $result->getHTTPStatus() . ' ' . $result->getRawBody();
        }
    }
});

$app->run();
<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key;
    private $api_secret_key;

    public function __construct()
    {
        $this->api_key = $_ENV['MJ_APIKEY_PUBLIC'];
        $this->api_secret_key = $_ENV['MJ_APIKEY_PRIVATE'];
    }

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_secret_key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'gabistam@gmail.com',
                        'Name' => 'symfocar'
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                        'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4973173,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                        'name' => $to_name,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $template, $vars = null)
    {
        //récupération des templates html
        $content = file_get_contents(dirname(__DIR__).'/Mail/'.$template);

        //récupération des variables falcultatives
        if($vars){
            foreach ($vars as $key=>$var){
                $content = str_replace('{'.$key.'}', $var, $content);
            }
        }

        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "dingreville-s@live.fr",
                        'Name' => "OnlyInAmerica"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 5859794,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                      'content' => $content
                    ],
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);

    }
}
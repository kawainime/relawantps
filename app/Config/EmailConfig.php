<?php

namespace Config;

class EmailConfig {

    public $provider = 'Standard';
    // public $provider = 'Google';
    // public $provider = 'AmazonSES';

    public $client = ['standard' => [
            'host' => 'smtp.gmail.com'
            , 'username' => 'kosud2017@gmail.com'
            , 'password' => '4lisMerah'
        ]
        , 'google' => ['client_id' => ''
            , 'client_secret' => ''
            , 'refresh_token' => ''
        ]
        , 'ses' => ['username' => ''
            , 'password' => ''
        ]
    ];
    // Disesuaikan dengan konfigurasi username
    public $from = 'kosud2017@gmail.com';
    public $emailSupport = 'kosud2017@gmail.com';

}

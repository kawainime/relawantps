<?php

namespace Config;

class EmailConfig {

    public $provider = 'Standard';
    // public $provider = 'Google';
    // public $provider = 'AmazonSES';

    public $client = ['standard' => [
            'host' => 'mail.relawantps.com'
            , 'username' => 'admin@relawantps.com'
            , 'password' => 'relawanTPS99!'
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
    public $from = 'admin@relawantps.com';
    public $emailSupport = 'admin@relawantps.com';

}

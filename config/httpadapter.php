<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global Configuration
    |--------------------------------------------------------------------------
    |
    | The configuration entered below overrules all the configuration keys
    | set for each connection.
    |
    */
    'global' => [
        //'eventable' => false,
        'config' => [
            // 'protocol_version' => '1.1',
            // 'keep_alive' => true,
            // 'boundary' => '',
            // 'timeout' => 10,
            // 'user_agent' => 'Laravel HTTP Adapter',
            // 'base_uri' => 'https://github.com/hiddeco'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | HTTP Adapter Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Examples of
    | configuring each supported driver is shown below. You can of course have
    | multiple connections per driver.
    |
    */

    'connections' => [

        'main' => [
            'adapter'   => 'file_get_contents',
            //'eventable' => true,
            'config'    => [
                // 'protocol_version' => '1.1',
                // 'keep_alive' => true,
                // 'boundary' => '',
                // 'timeout' => 10,
                // 'user_agent' => 'Laravel HTTP Adapter',
                // 'base_uri' => 'https://github.com/hiddeco'
            ],
        ],

        'alternative' => [
            'adapter'   => 'curl',
        ],
    ],
];

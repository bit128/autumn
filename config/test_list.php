<?php
return [
    'version' => 1,
    'base_url' => 'http://au.cc/',
    'authorize' => [
        'uid' => '',
        'token' => ''
    ],
    'list' => [
        0=>[
            'name' => '测试post请求',
            'path' => 'welcome/testPost',
            'method' => 'post',
            'data' => ['username' => 'hongbo']
        ],
        1=>[
            'name' => '测试字符串',
            'path' => 'welcome/testString',
        ],
        2=>[
            'path' => 'welcome/testJson'
        ],
        3=>[
            'name' => '测试数字',
            'path' => 'welcome/testNumber'
        ]
    ]
];
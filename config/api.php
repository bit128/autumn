<?php
/**
 * Api测试配置
 * ======
 * @author 洪波
 * @version 19.05.21
 */
return [
    'version' => 1,
    //测试地址主域
    'domain' => 'http://au.cc/',
    //额外传输参数，在POST方法中有效
    'extra' => [
        'uid' => '',
        'token' => ''
    ],
    //API测试列表
    'list' => [
        0 => [
            //名称 - 可空
            'name' => '测试post请求',
            //api地址 - 不可空
            'path' => 'site/testPost',
            //POST | GET - 可空，默认GET
            'method' => 'post',
            //请求参数 - 可空
            'data' => ['username' => 'hongbo']
        ],
        1 => [
            //api地址 - 不可空
            'path' => 'site/testBug'
        ]
    ]
];
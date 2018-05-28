<?php
$params = require __DIR__ . '/params-'.YII_ENV.'.php';

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //微讲堂库welive
        'dbWelive' => [
            'class' => 'yii\db\Connection',
            'dsn' => $params['dbWelive']['dsn'],
            'username' => $params['dbWelive']['username'],
            'password' => $params['dbWelive']['password'],
            'charset'  => 'utf8',
            'enableSchemaCache' => true,
        ],
        //微讲堂日志库welive_log
        'dbWeliveLog' => [
            'class' => 'yii\db\Connection',
            'dsn' => $params['dbWeliveLog']['dsn'],
            'username' => $params['dbWeliveLog']['username'],
            'password' => $params['dbWeliveLog']['password'],
            'charset'  => 'utf8',
            'enableSchemaCache' => true,
        ],
        //redis
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => $params['redis']['hostname'],
            'port' => $params['redis']['port'],
            'database' => $params['redis']['database'],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
        'Aliyunoss' => [
            'class' => 'common\components\Aliyunoss',
        ],
        'wechat' => [
            'class' => 'common\components\Wechat',
        ],
        'wechatJssdk' => [
            'class' => 'common\components\Jssdk'
        ],
    ],

];

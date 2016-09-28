<?php

return array(
    'rootLogger' => array(
        'appenders' => array('email_log')
    ),
//    attribute are normal
//    element are array
    'loggers' => array(
        'syncLogger' => array(
            'level' => 'INFO',
            'appenders' => array('sync_log'),
            'additivity' => false
        ),
        'emailLogger' => array(
            'level' => 'INFO',
            'appenders' => array('email_log'),
            'additivity' => false
        )
    ),
    'appenders' => array(
        'error_log' => array(
            'class' => 'LoggerAppenderDailyFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
                'params' => array(
                    'conversionPattern' => '%date %logger %-5level %msg%n'
                )
            ),
            'params' => array(
                'datePattern' => 'Y-m-d',
                'file' => '/home/edumall/public_html/logs/server-%s.log',
            ),
        ),
        'email_log' => array(
            'class' => 'LoggerAppenderDailyFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
                'params' => array(
                    'conversionPattern' => '%date %logger %-5level %msg%n'
                )
            ),
            'params' => array(
                'datePattern' => 'Y-m-d',
                'file' => '/home/edumall/public_html/logs/email-%s.log',
            ),
        ),
        'sync_log' => array(
            'class' => 'LoggerAppenderDailyFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
                'params' => array(
                    'conversionPattern' => '%date %logger %-5level %msg%n'
                )
            ),
            'params' => array(
                'datePattern' => 'Y-m-d',
                'file' => '/home/edumall/public_html/logs/sync-%s.log',
            ),
        ),
    )
);

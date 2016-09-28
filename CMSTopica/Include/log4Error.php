<?php

return array(
        'rootLogger' => array(
            'appenders' => array('error_log')
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
                    'file' => '/home/edumall/public_html/logs/error-%s.log',
                ),
            )
        )
    );

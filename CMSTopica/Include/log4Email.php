<?php

return array(
        'rootLogger' => array(
            'appenders' => array('email_log')
        ),
    //    attribute are normal
    //    element are array
        'appenders' => array(
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
            )
        )
    );

<?php

$app['translator.domains'] = array(
    'messages' => array(
        'en' => array(
            'hello' => 'Hello %name%',
            'goodbye' => 'Goodbye %name%',
        ),
        'pl' => array(
            'hello' => 'Cześć %name%',
            'goodbye' => 'Żegnaj %name%',
        ),
    ),
    'validators' => array(
        'fr' => array(
            'This value should be a valid number.' => 'Cette valeur doit être un nombre.',
        ),
    ),
    ['menus' => [
        'en' => [
            'interesants' => 'Customers',
            'invoices' => 'Invoices'
        ],
        'pl' => [
            'interesants' => 'Podmioty',
            'invoices' => 'Faktury'
        ]
    ],
    ]
);

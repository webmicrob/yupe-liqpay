<?php

return [
    'module' => [
        'class' => 'application.modules.liqpay.LiqpayModule',
    ],
    'component' => [
        'paymentManager' => [
            'paymentSystems' => [
                'liqpay' => [
                    'class' => 'application.modules.liqpay.components.payments.LiqpayPaymentSystem',
                ]
            ],
        ],
    ],
];

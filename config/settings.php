<?php
return [

    [
        [
            'name' => 'maintenance',
        ],
        [
            "display_name" => "Maintenance Mode",
            "input_type" => 'checkbox',
            'validation' => "nullable",
        ]
    ],
    [
        [
            'name' => 'logo',

        ],
        [
            'validation' => "nullable|image",
            "display_name" => "Logo",
            "input_type" => 'file',
            'value' => 'images/img/logo-dark.png'
        ]
    ],
    [
        [
            'name' => 'credimax_user',
        ],
        [
            "display_name" => "Credimax User",
            "input_type" => 'text',
            'validation' => "",
        ]
    ],
    [
        [
            'name' => 'credimax_password',
        ],
        [
            "display_name" => "Credimax Password",
            "input_type" => 'text',
            'validation' => "required",
        ]
    ],
    [
        [
            'name' => 'subscription_price',
        ],
        [
            "display_name" => "Subscription Price",
            "input_type" => 'number',
            'validation' => "required",
            'value' => 12
        ]
    ],
    [
        [
            'name' => 'trial_days',
        ],
        [
            "display_name" => "Trial Days",
            "input_type" => 'number',
            'validation' => "required",
            'value' => '15'
        ]
    ],
    [
        [
            'name' => 'contact_emails',
        ],
        [
            "display_name" => "Contact Emails (, separated)",
            "input_type" => 'text',
            'validation' => "required",
            'value' => 'support@penpeers.com,admin@penpeers.com'
        ]
    ],
    [
        [
            'name' => 'contact_numbers',
        ],
        [
            "display_name" => "Contact Numbers (, separated)",
            "input_type" => 'text',
            'validation' => "nullable",
            'value' => '+973 17820702'
        ]
    ],
    [
        [
            'name' => 'office_location',
        ],
        [
            "display_name" => "Office Location",
            "input_type" => 'textarea',
            'validation' => "nullable",
            'value' => 'OnAir Commerce WLL, Suite No. 605, 6th floor (Part 4, Municipality 65), Manama Centre Building No. 104, Government Road, Area No. 316, Manama Centre, Kingdom of Bahrain'
        ]
    ],

];

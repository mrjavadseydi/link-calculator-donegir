<?php

use Telegram\Bot\Keyboard\Keyboard;

function signup()
{

    $home = [
        [
            '👤 ثبت نام'

        ],
        [
            '🚸پشتیبانی',
        ],
    ];

    return Keyboard::make(['keyboard' => $home, 'resize_keyboard' => true, 'one_time_keyboard' => false]);

}

function backKey()
{
    $home = [
        [
            'بازگشت ↪️'

        ],
    ];

    return Keyboard::make(['keyboard' => $home, 'resize_keyboard' => true, 'one_time_keyboard' => false]);
}

function acceptOrDeny()
{
    $home = [
        [
            '✅ تایید',
            '❌ رد'
        ],
    ];

    return Keyboard::make(['keyboard' => $home, 'resize_keyboard' => true, 'one_time_keyboard' => false]);
}

function mainMenu()
{
    $home = [
        [
            '🔴دریافت تبلیغ'
        ],
        [
            '👤 حساب کاربری من',
            '💰کیف پول من'
        ], [
            '🕹 جزییات تبلیغات',
            '🛂قوانین و مقررات'
        ],
        [
            '🚸پشتیبانی',
            '🤔 راهنما'

        ],
    ];

    return Keyboard::make(['keyboard' => $home, 'resize_keyboard' => true, 'one_time_keyboard' => false]);
}


function categoryMenu()
{
    $home = [
        [
            'category1', "category2", "category3"
        ], [
            'بازگشت ↪️'
        ]
    ];

    return Keyboard::make(['keyboard' => $home, 'resize_keyboard' => true, 'one_time_keyboard' => false]);
}

if (!function_exists('accept_channel')) {
    function accept_channel($id)
    {
        $arr = [
            [
                'text' => "تایید",
                'callback_data' => "ac_" . $id
            ],
            [
                'text' => 'رد',
                'callback_data' => "de_" . $id
            ]
        ];
        return keyboard::make([
            'inline_keyboard' => [
                $arr
            ],
        ]);
    }
}
function choose_channel($account)
{
    $arr = [];
    $temp_select = [];
    if (!\Illuminate\Support\Facades\Cache::has('active_select' . $account->id)) {
        foreach (\App\Models\Channel::where('account_id', $account->id)->where('status', 1)->pluck('id') as $channel) {
            $temp_select[$channel] = false;
        }
        \Illuminate\Support\Facades\Cache::put('active_select' . $account->id, $temp_select, 600);

    }
    $temp_select = \Illuminate\Support\Facades\Cache::get('active_select' . $account->id);
    foreach (\App\Models\Channel::where('account_id', $account->id)->where('status', 1)->get() as $channel) {
        $status = $temp_select[$channel->id] ? '✅' : '❌';
        $arr[] = [
            [
                'text' => $channel->name . $status,
                'callback_data' => "spchannel_" . $channel->id]

        ];
    }
    $arr[] = [
        [
            'text' => "انتخاب همه",
            'callback_data' => "spchannel_all"
        ]

    ];
    $arr[] = [
        [
            'text' => "دریافت لینک",
            'callback_data' => "sponser_getlink"
        ]

    ];
    return keyboard::make([
        'inline_keyboard' => $arr,
    ]);
}

function choose_sponser()
{
    $arr = [];
    foreach (\App\Models\Sponser::where('status',1)->get() as $channel) {
        $arr[] = [
            [
                'text' => $channel->name ,
                'callback_data' => "spselect_" . $channel->id
            ]

        ];
    }

    return keyboard::make([
        'inline_keyboard' => $arr,
    ]);
}

function recive_wallet()
{
    $arr = [];
        $arr[] = [
            [
                'text' => "برداشت موجودی" ,
                'callback_data' => "getwallet_user"
            ]

        ];

    return keyboard::make([
        'inline_keyboard' => $arr,
    ]);
}



function payoutMenu()
{
    $arr = [];
        $arr[] = [
            [
                'text' => "در حال بررسی" ,
                'callback_data' => "status_0"
            ],
            [
                'text' => "پرداخت شد" ,
                'callback_data' => "status_1"
            ],
            [
                'text' => "رد شد" ,
                'callback_data' => "status_2"
            ],

        ];

    return keyboard::make([
        'inline_keyboard' => $arr,
    ]);
}

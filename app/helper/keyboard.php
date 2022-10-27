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
            'category1',"category2","category3"
        ],[
            'بازگشت ↪️'
        ]
    ];

    return Keyboard::make(['keyboard' => $home, 'resize_keyboard' => true, 'one_time_keyboard' => false]);
}

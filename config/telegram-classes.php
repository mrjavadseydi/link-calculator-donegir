<?php



return[
    'classes'=>[
        1=>[
            \App\Lib\Classes\Signup\Start::class,
            \App\Lib\Classes\Signup\ForwardChannel::class,
            \App\Lib\Classes\Signup\SaveChannel::class,
            \App\Lib\Classes\Signup\ChooseCategory::class,
            \App\Lib\Classes\Start::class,
        ],
        2=>[
            \App\Lib\Classes\Signup\AcceptChannel::class,
            \App\Lib\Classes\Sponser\ChoseSponser::class,
            \App\Lib\Classes\Sponser\GetSponser::class,
            \App\Lib\Classes\Sponser\UpdateChannel::class,
            \App\Lib\Classes\Sponser\GetLink::class,
            \App\Lib\Classes\Sponser\GetSponserState::class,
            App\Lib\Classes\Wallet\GetWallet::class,
//            \App\Lib\Classes\Wallet\AddSponser::class,
            \App\Lib\Classes\Wallet\GetCardName::class,
            \App\Lib\Classes\Wallet\GetCard::class,
            \App\Lib\Classes\Wallet\GetShaba::class,
            \App\Lib\Classes\Wallet\SendMonyAmount::class,
            \App\Lib\Classes\Wallet\WaitForPay::class,
        ],
        3=>[
            \App\Lib\Classes\Wallet\ChangePayStatus::class,
            \App\Lib\Classes\Admin\CancelSponser::class,
            \App\Lib\Classes\Admin\StatusSponser::class,
            \App\Lib\Classes\Text\GetHelp::class,
            \App\Lib\Classes\Text\GetRole::class,

            \App\Lib\Classes\Admin\AddSponser::class

        ]
    ]
];

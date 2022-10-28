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
            \App\Lib\Classes\Sponser\GetSponserState::class
        ],
        3=>[

        ]
    ]
];

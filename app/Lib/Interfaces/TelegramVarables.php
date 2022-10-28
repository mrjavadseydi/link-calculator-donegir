<?php
namespace App\Lib\Interfaces;
use App\Models\Account;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Weidner\Goutte\GoutteFacade;

abstract class TelegramVarables
{
    public $message_type,$data,$text,$chat_id,$from_id,$update;
    public $user = null;
    public $message_id;

    public function __construct($update)
    {
        $this->update = $update;
        $this->message_type = messageType($update);
        if ($this->message_type=="callback_query"){
            $this->data = $update["callback_query"]['data'];
            $this->chat_id = $update["callback_query"]['message']['chat']['id'];
            $this->message_id = $update["callback_query"]["message"]['message_id'];
            $this->text = $update["callback_query"]['message']['text'];
        }else{
            $this->text = $update['message']['text'] ?? "//**";
            $this->chat_id = $update['message']['chat']['id'] ?? "";
            $this->from_id = $update['message']['from']['id'] ?? "";
        }

        $user = Account::query()->firstOrCreate(['chat_id'=>$this->chat_id],[
            'active'=>1,
        ]);
        if ($user->active==0){
            return sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'شما مسدود شده اید'
            ]);
            exit();
        }
        $this->user = $user;
        Wallet::query()->firstOrCreate(['account_id'=>$user->id],[
            'balance'=>0,
        ]);

    }
}

<?php
namespace App\Lib\Interfaces;
use App\Models\Account;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Weidner\Goutte\GoutteFacade;

abstract class TelegramVarables
{
    public $message_type,$data,$text,$chat_id,$from_id,$update,$reply_to_message;
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
//            $username = $update["callback_query"]['from']['username'];
//            $name = $update["callback_query"]['from']['first_name'];
        }elseif($this->message_type=="channel_post" || $this->message_type=="channel_photo"){
            $this->text = $update['channel_post']['text'] ?? "//**";
            $this->chat_id = $update['channel_post']['chat']['id'] ?? "";
            $this->from_id = $update['channel_post']['from']['id'] ?? "";
            $this->message_id = $update['channel_post']['message_id'] ?? "";
            $this->reply_to_message = $update['channel_post']['reply_to_message']['message_id'] ?? "";
            $username = $update['channel_post']['from']['username'] ?? "";
            $name = $update['channel_post']['from']['first_name'] ?? "";
        }else{
            $this->text = $update['message']['text'] ?? "//**";
            $this->chat_id = $update['message']['chat']['id'] ?? "";
            $this->from_id = $update['message']['from']['id'] ?? "";
            $this->message_id = $update['message']['message_id'] ?? "";
            $this->reply_to_message = $update['message']['reply_to_message']['message_id'] ?? "";
            $username = $update['message']['from']['username'] ?? "";
            $name = $update['message']['from']['first_name'] ?? "";
        }

        $user = Account::query()->firstOrCreate(['chat_id'=>$this->chat_id],[
            'active'=>1,
        ]);
        if (isset($username)){
            $user->username = $username;
            $user->account_name = $name;
//        $user->name = $name;
            $user->save();
        }


        if ($user->active==0){
             sendMessage([
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

<?php
namespace App\Lib\Classes\Signup;
use App\Lib\Interfaces\TelegramOprator;

class Start extends TelegramOprator
{

    public function initCheck()
    {
        return (
            $this->message_type=="message"&&
            !check_signup($this->user->id)&&
            (!in_array(get_state($this->chat_id),['forward_channel','save_channel','choose_category','support'])||$this->text==  'بازگشت ↪️')
            &&$this->text!='🚸پشتیبانی'
        );
    }

    public function handel()
    {
        set_state($this->chat_id,"forward_channel");
        sendMessage([
            'chat_id' => $this->chat_id,
            'text'=>config('robot.signup'),
            'reply_markup'=>signup()
        ]);
    }
}

<?php

namespace App\Lib\Classes\Signup;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;

class SaveChannel extends TelegramOprator
{

    public function initCheck()
    {
//        devLog($this->update);
        return (get_state($this->chat_id) == "save_channel");
    }

    public function handel()
    {
        if (isset($this->update['message']['forward_from_chat']['id'])) {
            $channel_id = $this->update['message']['forward_from_chat']['id'];
            if (!isset($this->update['message']['forward_from_chat']['username'])) {
                sendMessage([
                    'chat_id' => $this->chat_id,
                    'text' => 'لطفا ابتدا یوزرنیم کانال را تنظیم کنید و سپس دوباره اقدام به فوروارد کردن کنید یا لینک کانال را ارسال کنید.',
                    'reply_markup' => backKey()
                ]);
                return;

            }
            $channel_username = "@" . $this->update['message']['forward_from_chat']['username'];
            $channel_title = $this->update['message']['forward_from_chat']['title'];
            if (Channel::where('chat_id', $channel_id)->exists()) {
                return sendMessage([
                    'chat_id' => $this->chat_id,
                    'text' => config('robot.duplicate_channel')
                ]);
            }
            $channel =Channel::query()->create([
                'account_id' => $this->user->id,
                'username' => $channel_username,
                'status' => 0,
                'chat_id' => $channel_id,
                'name' => $channel_title,
            ]);
            $text =  config('robot.new_channel');

            $text = str_replace('%username', $channel->username, $text);
            $text = str_replace('%name', $channel->name, $text);
            $text = str_replace('%category', $channel->category, $text);
            $text = str_replace('%id', $channel->chat_id, $text);
            $text = str_replace('%user', $this->chat_id, $text);

            sendMessage([
                'chat_id' => config('telegram.channel_signup'),
                'text' => '<a href="tg://user?id=' . $this->chat_id . '">' . $this->chat_id . '</a>'."\n".$text,
                'reply_markup' => accept_channel($channel->id),
                'parse_mode' => 'HTML'
            ]);

        } elseif (str_contains($this->text, '@') !== false || str_contains($this->text, 'https://t.me/') !== true) {
            $ex = explode("\n", $this->text);
            $ex = array_unique($ex);
            foreach ($ex as $e) {

                if (!str_starts_with($e, 'https://t.me/') && !str_starts_with($e, '@')) {
                    continue;
                }
                if (Channel::where('username', $e)->exists()) {
                    return sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => config('robot.duplicate_channel')
                    ]);
                }
                $channel = Channel::query()->create([
                    'name' => $e,
                    'account_id' => $this->user->id,
                    'username' => $e,
                    'status' => 0
                ]);

                $text = config('robot.new_channel');


                $text = str_replace('%username', $channel->username, $text);
                $text = str_replace('%name', $channel->name, $text);
                $text = str_replace('%category', $channel->category, $text);
                $text = str_replace('%id', $channel->chat_id, $text);
                $text = str_replace('%user', $this->chat_id, $text);

                sendMessage([
                    'chat_id' => config('telegram.channel_signup'),
                    'text' => '<a href="tg://user?id=' . $this->chat_id . '">' . $this->chat_id . '</a>'."\n".$text,
                    'reply_markup' => accept_channel($channel->id),
                    'parse_mode' => 'HTML'
                ]);

            }

        } else {
            return sendMessage([
                'chat_id' => $this->chat_id,
                'text' => config('robot.not_fount_channel'),
                'reply_markup' => backKey()
            ]);
        }
        set_state($this->chat_id, "main");
        if (Channel::where('account_id', $this->user->id)->where('status', 1)->first()) {
            sendMessage([
                'chat_id' => $this->chat_id,
                'text' => config('robot.new_channel_added'),
                'reply_markup' => mainMenu()
            ]);
        } else {
            sendMessage([
                'chat_id' => $this->chat_id,
                'text' => config('robot.after_add'),
                'reply_markup' => signup()
            ]);
        }

    }
}

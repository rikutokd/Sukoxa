<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Webhook;
use Discord\Parts\User\Member;
use Discord\Parts\Guild\Guild;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use Discord\Helpers\Collection;
use Discord\Repository\Channel\WebhookRepository;


// .envを使用する
Dotenv\Dotenv::createImmutable(__DIR__)->load();

$tokenID = $_ENV['tokenID'];
$myGuildID = $_ENV['myGuildID'];

$discord = new \Discord\Discord([
    'token' => $tokenID,
    'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT | Intents::GUILD_MEMBERS,
    'loadAllMembers' => true
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready.", PHP_EOL;

    // Listen for messages
    $discord->on('message', function (Message $message, Discord $discord) {


        if ($message->content == '!sバトルヒーリングスキル') {
            $message->reply('バトルヒーリングスキルによる自動回復が10秒で600ポイントある。'.
            '何時間攻撃しても俺は倒せないよ。');
        }

        if ($message->content == '!sCommand') {
            $message->reply('このコマンドが使えるよ：!sおみくじ, !sバトルヒーリングスキル, システムコールコマンドリスト, システムコールジェネレートイルミネーション');
        }

        if ($message->content == 'システムコールジェネレートイルミネーション') {
            $channel_id = $message->channel->id;
            $guild = $message->guild;
            $channel = $guild->channels->get('id', $channel_id);
            $channel->sendMessage(':dizzy: :dizzy: :dizzy: :dizzy: :dizzy:'. "\n".
            ':dizzy:  '. $message->member->user. '  :dizzy:'. "\n".
            ':dizzy: :dizzy: :dizzy: :dizzy: :dizzy:');
        }

        if ($message->content == 'システムコールコマンドリスト') {
            $message->reply('このコマンドが使えるよ：!sおみくじ, !sバトルヒーリングスキル, システムコールコマンドリスト, システムコールジェネレートイルミネーション');
        }

        if ($message->content == '!shutdown') {
            $discord->close();
        }

        if ($message->content == '!sおみくじ') {
            $channel = $message->channel;
            $channel->broadcastTyping();
            $num = rand(0,99);
            switch ($num) {
                case $num == 0 || $num == 1 :
                    $message->delayedReply('おみくじを引いた…………結果はむすこ',1500);
                    break;
                case $num >= 2 && $num <= 17 :
                    $message->delayedReply('おみくじを引いた…………結果は大すこ',1500);
                    break;
                case $num >= 18 && $num <= 34 :
                    $message->delayedReply('おみくじを引いた…………結果は中すこ',1500);
                    break;
                case $num >= 35 && $num <= 50 :
                    $message->delayedReply('おみくじを引いた…………結果はすこ',1500);
                    break;
                case $num >= 51 && $num <= 66 :
                    $message->delayedReply('おみくじを引いた…………結果は大凶',1500);
                    break;
                case $num >= 67 && $num <= 82 :
                    $message->delayedReply('おみくじを引いた…………結果は中凶',1500);
                    break;
                case $num >= 83 && $num <= 97 :
                    $message->delayedReply('おみくじを引いた…………結果は凶',1500);
                    break;
                case $num == 98 || $num == 99 :
                    $message->delayedReply('おみくじを引いた…………引けなかった！あはは',1500);
                    break;
            }
        }

        if ($message->content == '!s音') {
            try {
                $voiceChannel = $message->member->getVoiceChannel();

                $discord->joinVoiceChannel($voiceChannel, false, false)->then(
                    function (VoiceClient $vc) {  
                        $vc->start();
                        $vc->playFile('sound/1.mp3');
                    }
                );

            } catch (\Throwable $th) {
                //$message->reply('error');
            }
        }

        if ($message->content == '!sDC') {
            try {
                $guild_id = $message->member->guild_id;
                $vc = $discord->getVoiceClient($guild_id);
                $vc->close();
            } catch (\Throwable $th) {
                echo 'disconnect 失敗';
            }

        }

        if ($message->content == '!sDebug') {
                $guild = $discord->guilds->get("id", $myGuildID);
                $members = $guild->members;
                print_r($members);
                $channels = $guild->channels;
                print_r($channels);

        }

    });
});

$discord->run();
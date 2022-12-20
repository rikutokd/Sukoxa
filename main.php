<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Channel\Channel;
use Discord\Parts\User\Member;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

use sukoxa\DiscordUtil;

// .envを使用する
Dotenv\Dotenv::createImmutable(__DIR__)->load();

$tokenID = $_ENV['tokenID'];

$discord = new \Discord\Discord([
    'token' => $tokenID,
    'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT // Required to get message content, enable it on https://discord.com/developers/applications/
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready.", PHP_EOL;

    // Listen for messages
    $discord->on('message', function (Message $message, Discord $discord) {

        // If message is "ping"
        if ($message->content == 'ping') {
            // Reply with "pong"
            $message->reply('pong');
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

        if ($message->content == '!s音楽かけて') {
            try {
                $voiceChannel = $message->member->getVoiceChannel();

                $discord->joinVoiceChannel($voiceChannel, $mute = false, $deaf = false);

                // Create a VoiceClient
                //$voiceClient = new VoiceClient();

            } catch (\Throwable $th) {
                //$message->reply('ボイスチャンネルに接続出来ませんでした');
            }
        }

        if ($message->content == '!sDebug') {
            $channel = $message->channel;
            var_dump($channel);
            throw new Exception();
        }

    });
});

$discord->run();
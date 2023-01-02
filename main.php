<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;
use Discord\Voice\VoiceClient;
use Discord\Parts\Channel\Message;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Webhook;
use Discord\Parts\User\Member;
use Discord\Parts\Guild\Guild;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use Discord\Helpers\Collection;
use Discord\Repository\Channel\WebhookRepository;
use Dotenv\Dotenv;
use React\Promise\Promise;
use React\EventLoop\Loop;
use React\Stream\ReadableStreamInterface;
use React\Stream\ReadableResourceStream;
use function React\Async\async;
use function React\Async\await;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$tokenID = $_ENV['tokenID'];

$discord = new \Discord\Discord([
    'token' => $tokenID,
    'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT | Intents::GUILD_MEMBERS,
    'loadAllMembers' => true
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready.", PHP_EOL;

    // Listen for messages
    $discord->on('message', function (Message $message, Discord $discord) {

        if ($message->content == '!sCommand') {
            $message->reply('このコマンドが使えるよ：!sおみくじ, システムコールコマンドリスト, システムコールジェネレートイルミネーション');
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
            $message->reply('このコマンドが使えるよ：!sおみくじ, システムコールジェネレートイルミネーション');
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

        if ($message->content == '!sクリスマスソング') {
            $voiceChannel = $message->member->getVoiceChannel();
        
            $vc = $discord->joinVoiceChannel($voiceChannel, false, false);
        
            $vc->done(
                function ($vc) {
                    var_dump(file_exists('sound/1.mp3'));
                    $vc->playFile('sound/1.mp3');
                }
            );
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
            //print_r($_ENV);
            $myGuildID = $_ENV['myGuildID'];
            $guild = $discord->guilds->get("id", $myGuildID);
            $members = $guild->members;
            print_r($members);
            $channels = $guild->channels;
            //print_r($channels);

        }

    });
});

$discord->run();

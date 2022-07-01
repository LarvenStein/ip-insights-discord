<?php
use Discord\Discord;
use Discord\Websockets\Event;
use Discord\Websockets\Intents;
use Discord\Parts\User\Activity;
require_once('./vendor/autoload.php');
require_once('./key.php');
$key = getKey();

$discord = new Discord(['token'=>$key]);
$discord->on('ready', function(Discord $discord){
    echo'Bot ist Online';

    $activity = $discord->factory(\Discord\Parts\User\Activity::class);
    $activity->type = \Discord\Parts\User\Activity::TYPE_PLAYING;
    $activity->name = '$help | ip.steinlarve.de';
    $discord->updatePresence($activity, false, "online", false);

    $discord->on('message', function($message, $discord){
        $content = $message ->content;
        if(strpos($content, '$') === false) return;

        if($content === '$help') {
            $help ='
**Commands**
`​$​l​o​o​k​u​p​ [INSERT IP ADRESS / DOMAIN HERE]` - *Lookup Informations about Ip adresses or Domains*

`$website` - *Get a Link to a Website that does the Same* 
`$about` - *Get Informations About this Bot* 
            ';
            $message->reply($help);
        } else {

        }

        if($content === '$website') {
            $websiterp ='
**IP Info Website**
https://ip.steinlarve.de
            ';
            $message->reply($websiterp);
        }

        if($content === '$about') {
            $aboutinfo ='
**About**
➥ DiscordPHP ➨ https://discord-php.github.io/DiscordPHP/
➥ IP-API ➨ https://ip-api.com/
➥ GitHub (Website) ➨ https://github.com/LarvenStein/IP-Lookup
--
Made with ❤️ by 𝚂𝚝𝚎𝚒𝚗𝙻𝚊𝚛𝚟𝚎#2354
            ';
            $message->reply($aboutinfo);
        }


        if(strpos($content, '$lookup') === false) {

        } else {
            $query = end(explode(' ',$content));

            $ignore = '[INSERT IP ADRESS / DOMAIN HERE]';

            if($query == $ignore) {
               echo 'none';
            } else {

            $ipdatajson = file_get_contents('http://ip-api.com/json/'.$query.'?fields=28569599');
            $ipdata = json_decode($ipdatajson, true);

            if($ipdata['status'] == 'success') {

            if($ipdata['hosting'] == 'true') {
                $datacenter = '✔';
            } else {
                $datacenter = '✘';
            }
            if($ipdata['mobile'] == 'true') {
                $mobile = '✔';
            } else {
                $mobile = '✘';
            }
            if($ipdata['proxy'] == 'true') {
                $vpn = '✔';
            } else {
                $vpn = '✘';
            }

            $reply = '
**'.$query.' | '.$ipdata['query'].'**
 *City:*  `'.$ipdata['city'].' ('.$ipdata['zip'].')`
 *Region:*  `'.$ipdata['regionName'].' ('.$ipdata['region'].')`
 *Country:*  `'.$ipdata['country'].' ('.$ipdata['countryCode'].')`
 *Continent:*  `'.$ipdata['continent'].' ('.$ipdata['continentCode'].')`
 *Currency:*  `'.$ipdata['currency'].'`
 *Timezone:*  `'.$ipdata['timezone'].'`
 *ISP:*  `'.$ipdata['isp'].'`
 *ORG:*  `'.$ipdata['org'].'`
 *AS:*  `'.$ipdata['as'].'`
 *VPN/Proxy:*  `'.$vpn.'`
 *Mobile:*  `'.$mobile.'`
 *Datacenter:*  `'.$datacenter.'`

--
*Permanent URL:*
https://ip.steinlarve.de?query='.$query.'
--

*Approximate position: *
 https://www.openstreetmap.org/?mlat='.$ipdata['lat'].'&mlon='.$ipdata['lon'].'#map=15/'.$ipdata['lat'].'/'.$ipdata['lon'].'
            ';
            $message->reply($reply);
        }else {
            $message->reply('**Your request failed!**
Please follow this command scheme `​$​l​o​o​k​u​p​ [INSERT IP ADRESS / DOMAIN HERE]` **(only the Domain/IP Adress. No Protocols or Directories.)** Example: `​$​l​o​o​k​u​p​ google.com`
            ');
        }
        }
        }
    });
});
$discord->run();
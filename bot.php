<?php
use Discord\Discord;
use Discord\Websockets\Event;
use Discord\Websockets\Intents;
use Discord\Parts\User\Activity;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel;
use Unirest\Request;

require_once 'vendor/autoload.php';
require_once 'vendor/mashape/unirest-php/src/Unirest.php';
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
`​$​w​h​o​i​s​ [INSERT IP ADRESS / DOMAIN HERE]` - *Get WHOIS Informations about a IP adress or Domain ᵇᵉᵗᵃ*
`​$​p​i​n​g​ [INSERT IP ADRESS / DOMAIN HERE]` - *Ping an IP adress or Domain*

--

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
➥ GitHub ➨ https://github.com/LarvenStein/ip-insights-discord
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
 *LAT/LON:* `'.$ipdata['lat'].'/'.$ipdata['lon'].'`
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
            ';
            $message->reply($reply);
            $remessage = MessageBuilder::new()
            ->setContent('https://static-maps.yandex.ru/1.x/?lang=en-US&ll='.$ipdata['lon'].','.$ipdata['lat'].'&z=9&l=map,trf&size=650,450')
            ->setTts(false);

            $message->channel->sendMessage($remessage)->done(function (Message $remessage) {
            // ...
            });
        }else {
            $message->reply('**Your request failed!**
Please follow this command scheme `​$​l​o​o​k​u​p​ [INSERT IP ADRESS / DOMAIN HERE]` **(only the Domain/IP Adress. No Protocols or Directories.)** Example: `​$​l​o​o​k​u​p​ google.com`
            ');
        }
        }
        }
        if(strpos($content, '$ping') === false) {

        } else {
            $query = end(explode(' ',$content));

            $ignore = '[INSERT IP ADRESS / DOMAIN HERE]';

            if($query == $ignore) {
               echo 'none';
            } else {
                $statusdata = file_get_contents('http://ip-api.com/json/'.$query.'?fields=16384');

                $statusdata_decode = json_decode($statusdata, true);
    
                if($statusdata_decode['status'] == 'success') {

                # This will not work in Windows
$pingraw = exec("ping -c 3 ".$query."");
$ping = explode("/", $pingraw);

                $pingreply = '
                    Ping Results for **'.$query.'**

*min* => `'.trim($ping['3'], 'mdev =').' ms`
*'.$ping['1'].'* => `'.$ping['4'].' ms`
*'.$ping['2'].'* => `'.$ping['5'].' ms`
*mdev* => `'.trim($ping['6'], 'ms ').' ms`
                ';
                $message->reply($pingreply);
                } else {
                    $message->reply('**Your request failed!**
Please follow this command scheme `​$​p​i​n​g​ [INSERT IP ADRESS / DOMAIN HERE]` **(only the Domain/IP Adress. No Protocols or Directories.)** Example: `​$​p​i​n​g​ google.com`
                ');
                }
            }
        }
        if(strpos($content, '$whois') === false) {

        } else {
            $whoisquery = end(explode(' ',$content));

            $statusdata = file_get_contents('http://ip-api.com/json/'.$whoisquery.'?fields=16384');

            $statusdata_decode = json_decode($statusdata, true);

            if($statusdata_decode['status'] == 'success') {

            # This also will not work in Windows
            $whoisid = 'whois_reports/' . uniqid('WHOIS_') . '.txt';
            
            $customer_id = whoisID();
            $api_key = whoisKey();

            Unirest\Request::auth($customer_id, $api_key);
            $headers = array("Accept" => "application/json");
            $url = "https://jsonwhoisapi.com/api/v1/whois?identifier=".$whoisquery."";
            $response = Unirest\Request::get($url, $headers);

            print_r($response);

            foreach($response->body->nameservers as $ns){
                $nameserver .= $ns . "\n";
            }
            foreach($response->body->contacts->owner as $owner){
                $ownercontact = $owner;
            }
            foreach($response->body->contacts->admin as $admin){
                $admincontact = $admin;
            }
            foreach($response->body->contacts->tech as $tech){
                $techcontact = $tech;
            }



            $whoismessage = '
Name: '.$response->body->name.'
Created: '.$response->body->created.'
Changed: '.$response->body->changed.'
Expires: '.$response->body->expires.'
Dnssec: '.$response->body->dnssec.'
Registerd: '.$response->body->registered.'
Status: '.$response->body->status.'

==Nameservers==
'.$nameserver.'

==Registrar==
ID: '.$response->body->registrar->id.'
Name: '.$response->body->registrar->name.'
Email: '.$response->body->registrar->email.'
URL: '.$response->body->registrar->url.'
Phone: '.$response->body->registrar->phone.'

==Owner Contact==
Handle: '.$ownercontact->handle.'
Type: '.$ownercontact->type.'
Name: '.$ownercontact->name.'
Organization: '.$ownercontact->organization.'
E-Mail: '.$ownercontact->email.'
Adress: '.$ownercontact->adress.'
Zip Code: '.$ownercontact->zipcode.'
City: '.$ownercontact->city.'
State: '.$ownercontact->state.'
Country: '.$ownercontact->country.'
Phone: '.$ownercontact->phone.'
Fax: '.$ownercontact->fax.'
Created: '.$ownercontact->created.'
Changed: '.$ownercontact->changed.'

==Admin Contact==
Handle: '.$admincontact->handle.'
Type: '.$admincontact->type.'
Name: '.$admincontact->name.'
Organization: '.$admincontact->organization.'
E-Mail: '.$admincontact->email.'
Adress: '.$admincontact->adress.'
Zip Code: '.$admincontact->zipcode.'
City: '.$admincontact->city.'
State: '.$admincontact->state.'
Country: '.$admincontact->country.'
Phone: '.$admincontact->phone.'
Fax: '.$admincontact->fax.'
Created: '.$admincontact->created.'
Changed: '.$admincontact->changed.'

==Tech Contact==
Handle: '.$techcontact->handle.'
Type: '.$techcontact->type.'
Name: '.$techcontact->name.'
Organization: '.$techcontact->organization.'
E-Mail: '.$techcontact->email.'
Adress: '.$techcontact->adress.'
Zip Code: '.$techcontact->zipcode.'
City: '.$techcontact->city.'
State: '.$techcontact->state.'
Country: '.$techcontact->country.'
Phone: '.$techcontact->phone.'
Fax: '.$techcontact->fax.'
Created: '.$techcontact->created.'
Changed: '.$techcontact->changed.'

            ';

            file_put_contents($whoisid, $whoismessage);

            $whoismsg = MessageBuilder::new()
                ->setContent('**WHOIS Report for '.$whoisquery.'** ᵇᵉᵗᵃ')
                ->addFile($whoisid);

            $message->reply($whoismsg);

            !unlink($whoisid);
            } else {
                $message->reply('**Your request failed!**
Please follow this command scheme `​$​w​h​o​i​s​​ [INSERT IP ADRESS / DOMAIN HERE]` **(only the Domain/IP Adress. No Protocols or Directories.)** Example: `​$​w​h​o​i​s​​ google.com`
            ');
            }
        }
    });
});
$discord->run();

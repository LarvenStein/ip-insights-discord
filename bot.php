<?php
include './vendor/autoload.php';

use Discord\Discord;
use Discord\Websockets\Event;
use Discord\Websockets\Intents;
use Discord\Parts\User\Activity;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel;
use Unirest\Request;
use Discord\Parts\Interactions\Command\Command;
use Discord\Parts\Interactions\Interaction;

require_once 'vendor/autoload.php';
require_once 'vendor/mashape/unirest-php/src/Unirest.php';
require_once('./vendor/autoload.php');
require_once('./key.php');
$key = getKey();

$discord = new Discord(['token'=>$key]);
$discord->on('ready', function(Discord $discord){
        // Remove this
        
        $command = new Command($discord, ['name' => 'lookup', 'description' => 'Lookup Informations about Ip adresses or Domains', 'options' => [['type' => 3, 'name' => 'query', 'description' => 'Enter a Domain or IP Adress', 'required' => true]]]);
        $discord->application->commands->save($command);
        $command = new Command($discord, ['name' => 'whois', 'description' => 'Get WHOIS Informations about a IP adress or Domain', 'options' => [['type' => 3, 'name' => 'query', 'description' => 'Enter a Domain or IP Adress', 'required' => true]]]);
        $discord->application->commands->save($command);
        $command = new Command($discord, ['name' => 'ping', 'description' => 'Ping an IP adress or Domain', 'options' => [['type' => 3, 'name' => 'query', 'description' => 'Enter a Domain or IP Adress', 'required' => true]]]);
        $discord->application->commands->save($command);
        $command = new Command($discord, ['name' => 'website', 'description' => 'Get a Link to a Website that does the Same']);
        $discord->application->commands->save($command);
        $command = new Command($discord, ['name' => 'about', 'description' => 'Get Informations About this Bot']);
        $discord->application->commands->save($command);
        $command = new Command($discord, ['name' => 'help', 'description' => 'Get a list of Commands']);
        $discord->application->commands->save($command);
    $command = new Command($discord, ['name' => 'dnslookup', 'description' => 'Lookup DNS Records for a specific Domain', 'options' => [['type' => 3, 'name' => 'query', 'description' => 'Enter a Domain', 'required' => true]]]);
    $discord->application->commands->save($command);
        
        // To this 
    echo'Bot ist Online';

        $activity = $discord->factory(\Discord\Parts\User\Activity::class);
        $activity->type = \Discord\Parts\User\Activity::TYPE_PLAYING;
        $activity->name = '$help | ip.steinlarve.de';
        $discord->updatePresence($activity, false, "online", false);


    $discord->on('message', function($message, $discord){
        $content = $message ->content;
        if(strpos($content, '$') === false) return;

        if($content === '$help') {
            $embedjson = '
            {
                "title": "IP Insights Commands",
                "color": 0,
                "description": "`​/​l​o​o​k​u​p​ [INSERT IP ADRESS / DOMAIN HERE]` - *Lookup Informations about Ip adresses or Domains*\n\n`​/​w​h​o​i​s​ [INSERT IP ADRESS / DOMAIN HERE]` - *Get WHOIS Informations about a IP adress or Domain*\n\n`​/d​n​s​l​o​o​k​u​p [INSERT DOMAIN HERE]` - *Lookup DNS Records for a specific Domain*\n\n`​/​p​i​n​g​ [INSERT IP ADRESS / DOMAIN HERE]` - *Ping an IP adress or Domain*\n\n--\n\n\n`​/​w​e​b​s​i​t​e​` - *Get a Link to a Website that does the Same* \n\n`​/​a​b​o​u​t​`​ - *Get Informations About this Bot* \n\n\n",
                "timestamp": "",
                "author": {
                  "name": "",
                  "icon_url": ""
                },
                "image": {},
                "thumbnail": {
                  "url": ""
                },
                "footer": {
                  "text": "If the Slash Commands are not Working, Reinvite the Bot with the new Premissions: https://ip.steinlarve.de/dc-bot/",
                  "icon_url": "https://cdn.discordapp.com/attachments/992074702308782090/1002957251226652723/498591d63b352256a1bf18061eff9d57.png"
                },
                "fields": []
              }
            ';

            $embed = json_decode($embedjson, true);

            $embedmsg = MessageBuilder::new()
            ->addEmbed($embed);

            $message->reply($embedmsg);
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
            $embedjson = '
            {
                "title": "About IP Insights",
                "color": 0,
                "description": "IP Insights is a Discord bot wich allows you to get Informations about IP Addresses and Domains. \n\n*Credits*\n\n    ➥ DiscordPHP ➨ https://slt.k.vu/FT4C\n    ➥ IP-API ➨ https://slt.k.vu/C67W\n    ➥ Json Whois API ➨ https://slt.k.vu/P!I6\n    ➥ DNS Lookup API by hackertarget ➨ https://slt.k.vu/CYZ-\n    ➥ Yandex Static Maps ➨ https://slt.k.vu/HKSO\n    ➥ GitHub ➨ https://slt.k.vu/GCQF\n    ➥ GitHub (Website) ➨ https://slt.k.vu/VQOM",
                "timestamp": "",
                "url": "",
                "author": {
                  "name": ""
                },
                "image": {},
                "thumbnail": {
                  "url": "https://cdn.discordapp.com/app-icons/992069594900611213/bf1b4647ea13794239710a83f002045c.png"
                },
                "footer": {
                  "text": "Made with ❤️ by 𝚂𝚝𝚎𝚒𝚗𝙻𝚊𝚛𝚟𝚎#2354"
                },
                "fields": []
              }
            ';

            $embed = json_decode($embedjson, true);

            $embedmsg = MessageBuilder::new()
            ->addEmbed($embed);

            $message->reply($embedmsg);
        }


        if(strpos($content, '$lookup') === false) {

        } else {
            $query = end(explode(' ',$content));

            $ignore = '[INSERT IP ADRESS / DOMAIN HERE]';

            if($query == $ignore) {
               echo 'none';
            } else {

            $ipdatajson = file_get_contents('http://ip-api.com/json/'.$query.'?fields=28569599');
            $ipdataraw = json_decode($ipdatajson, true);
            $ipdata = str_replace('"', ' ', $ipdataraw);

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

            $embedjson = '
            {
                "title": "'.$query.' | '.$ipdata['query'].'",
                "color": 0,
                "description": " *City:*  `'.$ipdata['city'].' ('.$ipdata['zip'].')`\n *Region:*  `'.$ipdata['regionName'].' ('.$ipdata['region'].')`\n *Country:*  `'.$ipdata['country'].' ('.$ipdata['countryCode'].')`\n *Continent:*  `'.$ipdata['continent'].' ('.$ipdata['continentCode'].')`\n *LAT/LON:* `'.$ipdata['lat'].'/'.$ipdata['lon'].'`\n *Currency:*  `'.$ipdata['currency'].'`\n *Timezone:*  `'.$ipdata['timezone'].'`\n *ISP:*  `'.$ipdata['isp'].'`\n *ORG:*  `'.$ipdata['org'].'`\n *AS:*  `'.$ipdata['as'].'`\n *VPN/Proxy:*  `'.$vpn.'`\n *Mobile:*  `'.$mobile.'`\n *Datacenter:*  `'.$datacenter.'`\n\n\n**Approximate position:**",
                "timestamp": "",
                "url": "https://ip.steinlarve.de?query='.$query.'",
                "author": {},
                "image": {
                  "url": "https://static-maps.yandex.ru/1.x/?lang=en-US&ll='.$ipdata['lon'].','.$ipdata['lat'].'&z=9&l=map,trf&size=650,450"
                },
                "thumbnail": {},
                "footer": {
                  "icon_url": "https://cdn.discordapp.com/app-icons/992069594900611213/bf1b4647ea13794239710a83f002045c.png"
                },
                "fields": []
              }
            ';
            $embed = json_decode($embedjson, true);

            $embedmsg = MessageBuilder::new()
            ->addEmbed($embed);

            $message->reply($embedmsg);
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
                ->setContent('**WHOIS Report for '.$whoisquery.'**')
                ->addFile($whoisid);

            $message->reply($whoismsg);

            !unlink($whoisid);
            } else {
                $message->reply('**Your request failed!**
Please follow this command scheme `​$​w​h​o​i​s​​ [INSERT IP ADRESS / DOMAIN HERE]` **(only the Domain/IP Adress. No Protocols or Directories.)** Example: `​$​w​h​o​i​s​​ google.com`
            ');
            }
        }

        if(strpos($content, '$dnslookup') === false) {

        } else {
            $dnsquery = end(explode(' ',$content));
            $dnsraw = file_get_contents('https://api.hackertarget.com/dnslookup/?q='.$dnsquery.'');
    
            if($dnsraw == 'error input invalid - enter IP or Hostname') {
                $dnsrp = '**Your request failed!**
Please follow this command scheme `​$​d​n​s​l​o​o​k​u​p​ [DOMAIN HERE]` **(only the Domain. No Protocols, IP Adresses or Directories.)** Example: `​$​d​n​s​l​o​o​k​u​p​​ google.com`';
    
            } elseif($dnsraw == 'try reverse dns tool for ipaddress') {
                $dnsrp = '**Your request failed!**
Please follow this command scheme `​$​d​n​s​l​o​o​k​u​p​ [DOMAIN HERE]` **(This command ONLY supports DOMAINS)** Example: `​$​d​n​s​l​o​o​k​u​p​​ google.com`';
    
            } else {
                $dnsrp = '**DNS Lookup for '.$dnsquery.'**
```
'.$dnsraw.'
```
                ';
            }
    
    
            $message->reply($dnsrp);
        }

    });

});

$discord->listenCommand('website', function (Interaction $interaction) {
    $websiterp ='
    **IP Info Website**
https://ip.steinlarve.de
            ';

    $interaction->respondWithMessage(MessageBuilder::new()->setContent($websiterp));
});

$discord->listenCommand('about', function (Interaction $interaction) {
    $embedjson = '
    {
        "title": "About IP Insights",
        "color": 0,
        "description": "IP Insights is a Discord bot wich allows you to get Informations about IP Addresses and Domains. \n\n*Credits*\n\n    ➥ DiscordPHP ➨ https://slt.k.vu/FT4C\n    ➥ IP-API ➨ https://slt.k.vu/C67W\n    ➥ Json Whois API ➨ https://slt.k.vu/P!I6\n    ➥ DNS Lookup API by hackertarget ➨ https://slt.k.vu/CYZ-\n    ➥ Yandex Static Maps ➨ https://slt.k.vu/HKSO\n    ➥ GitHub ➨ https://slt.k.vu/GCQF\n    ➥ GitHub (Website) ➨ https://slt.k.vu/VQOM",
        "timestamp": "",
        "url": "",
        "author": {
          "name": ""
        },
        "image": {},
        "thumbnail": {
          "url": "https://cdn.discordapp.com/app-icons/992069594900611213/bf1b4647ea13794239710a83f002045c.png"
        },
        "footer": {
          "text": "Made with ❤️ by 𝚂𝚝𝚎𝚒𝚗𝙻𝚊𝚛𝚟𝚎#2354"
        },
        "fields": []
      }
    ';

    $embed = json_decode($embedjson, true);

    $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($embed));
});

$discord->listenCommand('help', function (Interaction $interaction) {
    $embedjson = '
    {
        "title": "IP Insights Commands",
        "color": 0,
        "description": "`​/​l​o​o​k​u​p​ [INSERT IP ADRESS / DOMAIN HERE]` - *Lookup Informations about Ip adresses or Domains*\n\n`​/​w​h​o​i​s​ [INSERT IP ADRESS / DOMAIN HERE]` - *Get WHOIS Informations about a IP adress or Domain*\n\n`​/d​n​s​l​o​o​k​u​p [INSERT DOMAIN HERE]` - *Lookup DNS Records for a specific Domain*\n\n`​/​p​i​n​g​ [INSERT IP ADRESS / DOMAIN HERE]` - *Ping an IP adress or Domain*\n\n--\n\n\n`​/​w​e​b​s​i​t​e​` - *Get a Link to a Website that does the Same* \n\n`​/​a​b​o​u​t​`​ - *Get Informations About this Bot* \n\n\n",
        "timestamp": "",
        "author": {
          "name": "",
          "icon_url": ""
        },
        "image": {},
        "thumbnail": {
          "url": ""
        },
        "footer": {
          "text": "If the Slash Commands are not Working, Reinvite the Bot with the new Premissions: https://ip.steinlarve.de/dc-bot/",
          "icon_url": "https://cdn.discordapp.com/attachments/992074702308782090/1002957251226652723/498591d63b352256a1bf18061eff9d57.png"
        },
        "fields": []
      }
    ';

    $embed = json_decode($embedjson, true);

    $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($embed));
});

# Lookup

$discord->listenCommand('lookup', function (Interaction $interaction) {
    $query = $interaction['data']['options']['query']['value']; 

    print_r($interaction->data->options);

    $ignore = '[INSERT IP ADRESS / DOMAIN HERE]';

    if($query == $ignore) {
       echo 'none';
    } else {

    $ipdatajson = file_get_contents('http://ip-api.com/json/'.$query.'?fields=28569599');
    $ipdataraw = json_decode($ipdatajson, true);
    $ipdata = str_replace('"', ' ', $ipdataraw);


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
    if($ipdata['org'] < 1) {
        $org = 'No Information';
    } else {
        $org = $ipdata['org'];
    }

$embedjson = '
            {
                "title": "'.$query.' | '.$ipdata['query'].'",
                "color": 0,
                "description": " *City:*  `'.$ipdata['city'].' ('.$ipdata['zip'].')`\n *Region:*  `'.$ipdata['regionName'].' ('.$ipdata['region'].')`\n *Country:*  `'.$ipdata['country'].' ('.$ipdata['countryCode'].')`\n *Continent:*  `'.$ipdata['continent'].' ('.$ipdata['continentCode'].')`\n *LAT/LON:* `'.$ipdata['lat'].'/'.$ipdata['lon'].'`\n *Currency:*  `'.$ipdata['currency'].'`\n *Timezone:*  `'.$ipdata['timezone'].'`\n *ISP:*  `'.$ipdata['isp'].'`\n *ORG:*  `'.$org.'`\n *AS:*  `'.$ipdata['as'].'`\n *VPN/Proxy:*  `'.$vpn.'`\n *Mobile:*  `'.$mobile.'`\n *Datacenter:*  `'.$datacenter.'`\n\n\n**Approximate position:**",
                "timestamp": "",
                "url": "https://ip.steinlarve.de?query='.$query.'",
                "author": {},
                "image": {
                  "url": "https://static-maps.yandex.ru/1.x/?lang=en-US&ll='.$ipdata['lon'].','.$ipdata['lat'].'&z=9&l=map,trf&size=650,450"
                },
                "thumbnail": {},
                "footer": {
                  "icon_url": "https://cdn.discordapp.com/app-icons/992069594900611213/bf1b4647ea13794239710a83f002045c.png"
                },
                "fields": []
              }
            ';
            $embed = json_decode($embedjson, true);

    $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($embed));
}else {
    $interaction->respondWithMessage(MessageBuilder::new()->setContent('**Your request failed!**
Please follow this command scheme `​$​l​o​o​k​u​p​ [INSERT IP ADRESS / DOMAIN HERE]` **(only the Domain/IP Adress. No Protocols or Directories.)** Example: `​$​l​o​o​k​u​p​ google.com`
    '));
}
}
}
);

# Whois

$discord->listenCommand('whois', function (Interaction $interaction) {
    $whoisquery = $interaction['data']['options']['query']['value'];

    $statusdata = file_get_contents('http://ip-api.com/json/'.$whoisquery.'?fields=16384');

    $statusdata_decode = json_decode($statusdata, true);

    if($statusdata_decode['status'] == 'success') {

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

    $interaction->respondWithMessage(MessageBuilder::new()->setContent('**WHOIS Report for '.$whoisquery.'**')->addFile($whoisid));

    !unlink($whoisid);
    } else {
        $interaction->respondWithMessage(MessageBuilder::new()->setContent('**Your request failed!**
Please follow this command scheme `​$​w​h​o​i​s​​ [INSERT IP ADRESS / DOMAIN HERE]` **(only the Domain/IP Adress. No Protocols or Directories.)** Example: `​$​w​h​o​i​s​​ google.com`
    '));
    }
});

# Ping

$discord->listenCommand('ping', function (Interaction $interaction) {
    $query = $interaction['data']['options']['query']['value'];

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
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($pingreply));
        } else {
            $interaction->respondWithMessage(MessageBuilder::new()->setContent('**Your request failed!**
Please follow this command scheme `​$​p​i​n​g​ [INSERT IP ADRESS / DOMAIN HERE]` **(only the Domain/IP Adress. No Protocols or Directories.)** Example: `​$​p​i​n​g​ google.com`
        '));
        }
    }
});

# DNS Lookup

$discord->listenCommand('dnslookup', function (Interaction $interaction) {
    $dnsquery = $interaction['data']['options']['query']['value'];

    $dnsraw = file_get_contents('https://api.hackertarget.com/dnslookup/?q='.$dnsquery.'');

    if($dnsraw == 'error input invalid - enter IP or Hostname') {
        $dnsrp = '**Your request failed!**
Please follow this command scheme `​$​d​n​s​l​o​o​k​u​p​ [DOMAIN HERE]` **(only the Domain. No Protocols, IP Adresses or Directories.)** Example: `​$​d​n​s​l​o​o​k​u​p​​ google.com`';

    } elseif($dnsraw == 'try reverse dns tool for ipaddress') {
        $dnsrp = '**Your request failed!**
Please follow this command scheme `​$​d​n​s​l​o​o​k​u​p​ [DOMAIN HERE]` **(This command ONLY supports DOMAINS)** Example: `​$​d​n​s​l​o​o​k​u​p​​ google.com`';

    } else {
        $dnsrp = '**DNS Lookup for '.$dnsquery.'**
```
'.$dnsraw.'
```
';
    }


    $interaction->respondWithMessage(MessageBuilder::new()->setContent($dnsrp));
});

$discord->run();

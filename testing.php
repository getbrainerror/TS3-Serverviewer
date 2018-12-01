<?php
require_once(__DIR__ . '/config.inc.php');
require_once(__DIR__ . "/lib/TeamSpeak3/TeamSpeak3.php");
require_once(__DIR__ . '/lib/simplephpcache/cache.class.php');

function getTeamspeakConnection() {
    try {
        global $config;
        $host   = $config['teamspeak']['host'];
        $user  = $config['teamspeak']['user'];
        $passwd = $config['teamspeak']['password'];
        $sport  = $config['teamspeak']['server_port'];
        $qport  = $config['teamspeak']['query_port'];
        $nickname  = $config['teamspeak']['nickname'];
        $conArgs  = $config['teamspeak']['args'];
        $tsNodeHost = TeamSpeak3::factory("serverquery://$host:$qport/?nickname=$nickname&$conArgs");
        $tsNodeHost->login($user, $passwd);
        return $tsNodeHost->serverGetByPort($sport);
    } catch (Exception $e) {
        throw $e;
    }
}
function getServerViewerData(){
  global $config;
  $tsAdmin = getTeamspeakConnection();
  $result = array();

  {
    $serverArr = $tsAdmin->getInfo();
    $serverArr = array(
      'type' => 'Server',
      'ident' => "ts3_s" . $tsAdmin->virtualserver_id,
      'parent' => 'ts3',
    ) + $serverArr;
    $result[] = $serverArr;
  }

  foreach ($tsAdmin->clientList() as $client) {
     $clientArr = $client->getInfo();
     $clientArr = array(
       'type' => 'Client',
       'ident' => "ts3_u" . $client->clid, //Just for better parsing into the recursive loop else it wouldnt work great
       'parent' => $client->cid ? "ts3_c" . $client->cid : "ts3_s" . $this->currObj->getParent()->getId(),
     ) + $clientArr;
     $result[] = $clientArr;
  }
  foreach ($tsAdmin->channelList() as $channel) {
     $channelArr = $channel->getInfo();
     $channelArr = array(
       'type' => 'Channel',
       'ident' => "ts3_c" . $channel->cid,
       'parent' => $channel->pid ? "ts3_c" . $channel->pid : "ts3_s" . $channel->getParent()->getId(),
       'spacer' => getSpacerType($channel),
     ) + $channelArr;
     if($channel instanceof TeamSpeak3_Node_Channel && $channel->isSpacer()){
       $channelArr['channel_name'] = $channel['channel_name']->section("]", 1, 99)->toString();
     }
     $result[] = $channelArr;

  }
  return $result;
}

$data = getServerViewerData();
foreach ($data as $key) {
  switch ($key['type']) {
    case 'Server':
      echo($key['virtualserver_name'] . ' is a Server Node');
      break;
    case 'Client':
      echo($key['client_nickname'] . ' is a Client Node');
      break;
    case 'Channel':
      echo($key['channel_name'] . ' is a Channel Node');
      break;
    default:

      break;
  }
  echo('<hr />');
}


echo(generateTree($data));
echo('<pre>');
print_r($data);
echo('</pre>');


function generateTree($datas, $parent = "ts3", $depth=0){
  global $config;
  $ni=count($datas);
  if($ni === 0 || $depth > 1000) return ''; // Make sure not to have an endless recursion
  $tree = '<ul>';
  for($i=0; $i < $ni; $i++){
      if($datas[$i]['parent'] == $parent){
          switch ($datas[$i]['type']) {
            case 'Server':
              $tree .= '<li class="server">';
              $tree .= htmlspecialchars($datas[$i]['virtualserver_name']);
              $tree .= generateTree($datas, $datas[$i]['ident'], $depth+1);
              $tree .= '</li>';
              break;

            case 'Channel':
              $tree .= '<li class="channel">';
              $tree .= htmlspecialchars($datas[$i]['channel_name']);
              $tree .= generateTree($datas, $datas[$i]['ident'], $depth+1);
              $tree .= '</li>';
              break;

            case 'Client':
              $tree .= '<li class="client">';
              $tree .= htmlspecialchars($datas[$i]['client_nickname']);
              $tree .= generateTree($datas, $datas[$i]['ident'], $depth+1);
              $tree .= '</li>';
              break;

            default:
              $tree .= '<li>';
              $tree .= 'UKNOWN STUFF';
              $tree .= generateTree($datas, $datas[$i]['ident'], $depth+1);
              $tree .= '</li>';
              break;

          }
      }
  }
  $tree .= '</ul>';
  return $tree;
}

function getSpacerType($channel)
{
  $type = "";
  if(!$channel instanceof TeamSpeak3_Node_Channel || !$channel->isSpacer())
  {
    return "none";
  }
  switch($channel->spacerGetType())
  {
    case (string) TeamSpeak3::SPACER_SOLIDLINE:
      $type .= "solidline";
      break;
    case (string) TeamSpeak3::SPACER_DASHLINE:
      $type .= "dashline";
      break;
    case (string) TeamSpeak3::SPACER_DASHDOTLINE:
      $type .= "dashdotline";
      break;
    case (string) TeamSpeak3::SPACER_DASHDOTDOTLINE:
      $type .= "dashdotdotline";
      break;
    case (string) TeamSpeak3::SPACER_DOTLINE:
      $type .= "dotline";
      break;
    default:
      $type .= "custom";
  }

  if($type == "custom")
  {
    switch($channel->spacerGetAlign())
    {
      case TeamSpeak3::SPACER_ALIGN_REPEAT:
        $type .= "repeat";
        break;

      case TeamSpeak3::SPACER_ALIGN_CENTER:
        $type .= "center";
        break;

      case TeamSpeak3::SPACER_ALIGN_RIGHT:
        $type .= "right";
        break;

      default:
        $type .= "left";
    }
  }
  return $type;
}


?>

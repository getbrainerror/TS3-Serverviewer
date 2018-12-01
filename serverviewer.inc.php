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

$tsAdmin = getTeamspeakConnection();

function getServerViewer(){
  global $config;
  $c = new Cache(array(
    'name'      => 'serverviewer',
    'path'      => __DIR__ . '/cache/',
    'extension' => '.cache'
  ));
  $c->eraseExpired();
  if(!$c->isCached('serverviewer')) {
    $tsviewer = generateServerViewer();
      $c->store('serverviewer',$tsviewer, $config['expireTime']);
      return $tsviewer;
  } else {
    return ($c->retrieve('serverviewer'));
  }
}

function generateServerViewer(){
  global $tsAdmin;
  $serverviewer = generateTree(getServerViewerData($tsAdmin));
  return $serverviewer;
}

function generateTree($datas, $parent = "ts3", $depth=0){
  global $config;
  $ni=count($datas);
  if($ni === 0 || $depth > 1000) return ''; // Make sure not to have an endless recursion
  $tree = '<ul>';
  for($i=0; $i < $ni; $i++){
    echo($datas[$i]['parent']);
      if($datas[$i]['parent'] == $parent){
          switch ($datas[$i]['type']) {
            case 'Server':
              $tree .= '<li class="server">' . $config['serverViewer']['icons']['server'] . ' <span title="Online: ' . $datas[$i]['virtualserver_clientsonline'] . '/' . $datas[$i]['virtualserver_maxclients'] . '" data-toggle="tooltip" data-placement="top">';
              $tree .= htmlspecialchars($datas[$i]['virtualserver_name']);
              $tree .= '</span>';
              $tree .= '<span class="badge badge-light float-right">Online: ' . $datas[$i]['virtualserver_clientsonline'] . '/' . $datas[$i]['virtualserver_maxclients'] . '</span>';
              $tree .= generateTree($datas, $datas[$i]['ident'], $depth+1);
              $tree .= '</li>';
              break;

            case 'Channel':
              if(!(in_array($datas[$i]['cid'], $config['serverViewer']['ignoredChannelList']))) {
                switch ($datas[$i]['spacer']) {
                  case 'none':

                    $maxusers = $datas[$i]['channel_maxclients'];
                    if($maxusers == -1)
                    if($datas[$i]->props->famusers <= 0){
                      $tree .= '<li class="channel empty-channel">';
                    } else {
                      $tree .= '<li class="channel">';
                    }
                    if(!empty($datas[$i]->props->topic)) {
                      $tree .= '<span title="' . htmlspecialchars($datas[$i]->props->topic) . '" data-toggle="tooltip" data-placement="top">';
                    }
                    if($datas[$i]->props->flags == 70){
                      $tree .= $config['serverViewer']['icons']['channel-password'] . ' ';
                    } else {
                      if($datas[$i]->props->famusers < $datas[$i]->props->famslots){
                        $tree .= $config['serverViewer']['icons']['channel'] . ' ';
                      } else {
                        $tree .= $config['serverViewer']['icons']['channel-full'] . ' ';
                      }
                    }
                    $tree .= htmlspecialchars($datas[$i]->name);
                    $tree .= '</span>';
                    $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                    $tree .= '</li>';
                    break;
                  case 'customrepeat':
                    if(!$config['serverViewer']['ignoreRepeatSpacer']){
                      $tree .= '<li class="spacer text-nowrap" style="overflow: hidden;">';
                      $tree .= str_repeat(htmlspecialchars($datas[$i]->name), 100);
                      $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                      $tree .= '</li>';

                    }
                    break;
                  case 'customcenter':
                    if(!$config['serverViewer']['ignoreCenterSpacer']){
                      $tree .= '<li class="spacer text-center">';
                      $tree .= htmlspecialchars($datas[$i]->name);
                      $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                      $tree .= '</li>';

                    }
                    break;
                  case 'customright':
                    if(!$config['serverViewer']['ignoreRightSpacer']){
                      $tree .= '<li class="spacer text-right">';
                      $tree .= htmlspecialchars($datas[$i]->name);
                      $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                      $tree .= '</li>';

                    }
                    break;
                  case 'customleft':
                    if(!$config['serverViewer']['ignoreLeftSpacer']){
                      $tree .= '<li class="spacer text-left">';
                      $tree .= htmlspecialchars($datas[$i]->name);
                      $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                      $tree .= '</li>';

                    }
                    break;
                  default:
                    $tree .= '<li class="spacer">';
                    $tree .= htmlspecialchars($datas[$i]->name);
                    $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                    $tree .= '</li>';
                    break;
                }
              }
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

function getGroupIcon($tsAdmin, $group) {
    try {
        return $group->iconDownload();
    } catch (TeamSpeak3_Exception $e) {
        return false;
    }
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

function getServerViewerData($tsAdmin){
  global $config;
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

?>

<?php
$config = array(
  'expireTime' => 10, //Time in Seconds, how long should the data be cached
  'teamspeak' => array(
    'nickname' => 'ServerSlave' . rand(0,100), //Random Nickname to prevent errors
    'host' => 'localhost', 
    'user' => 'serveradmin',
    'password' => 'WsrW78Zf',
    'server_port' => 9987,
    'query_port' => 10011,
    'args' => "no_query_clients=1", //Needed to not display query clients
  ),
  'serverViewer' => array(
    //Here you can ignore spacers
    'ignoreCenterSpacer' => false,
    'ignoreLeftSpacer' => true,
    'ignoreRightSpacer' => true,
    'ignoreRepeatSpacer' => true,
    'ignoredGroupsList' => array("ExampleGroup"), //Paste here every group name that shouldnt get listed
    'ignoredChannelList' => array(15), //Paste here every channel id that shouldnt get listed
    'icons' => array( //You can edit these to whatever you want (Font Icons, Images or whatever)
      'server' => '<i class="fas fa-fw fa-server"></i>',
      'client' => '<i class="fas fa-fw fa-circle text-success"></i>',
      'channel' => '<i class="fas fa-fw fa-comment"></i>',
      'channel-full' => '<i class="fas fa-fw fa-comment text-danger"></i>',
      'channel-password' => '<i class="fas fa-fw fa-key text-warning"></i>',
      'query' => '<i class="fas fa-fw fa-robot"></i>', //You can ignore this, its only used when you remove no_query_clients from the args
    ),
  ),
);

 ?>

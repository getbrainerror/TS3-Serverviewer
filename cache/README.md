# Just needed to create this folder on github ^^

    global $config;
    $ni=count($datas);
    if($ni === 0 || $depth > 1000) return ''; // Make sure not to have an endless recursion
    $tree = '<ul>';
    for($i=0; $i < $ni; $i++){
        if($datas[$i]->parent == $parent){
            switch ($datas[$i]->class) {
              case 'server':
                $tree .= '<li class="server">' . $config['serverViewer']['icons']['server'] . ' <span title="Online: ' . $datas[$i]->props->users . '/' . $datas[$i]->props->slots . '" data-toggle="tooltip" data-placement="top">';
                $tree .= htmlspecialchars($datas[$i]->name);
                $tree .= '</span>';
                //  $tree .= '<a href="ts3server://' . $config['teamspeak']['publicTeamspeakAdress'] . '?port=' . $config['teamspeak']['server_port'] . '" class="badge badge-primary float-right">Join Now!</i></a>';
                $tree .= '<span class="badge badge-light float-right">Online: ' . $datas[$i]->props->users . '/' . $datas[$i]->props->slots . '</span>';
                $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                $tree .= '</li>';
                break;

              case 'channel':
                if(!(in_array($datas[$i]->props->id, $config['serverViewer']['ignoredChannelList']))) {

                  if($datas[$i]->props->spacer == 'none'){
                  } else {
                  }

                  switch ($datas[$i]->props->spacer) {
                    case 'none':
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
                break;

              case 'client':
                foreach ($datas[$i]->props->memberof as $group) {
                  //Flag 16 are Channel Groups we want to skip these for now
                  //Flag 52 are Query Groups
                  if($group->flags != 16 ){
                    if($group->flags != 52 ){
                      if(!in_array($group->name, $config['serverViewer']['ignoredGroupsList'])){
                        if($group->icon > -0){
                          $groupicon = getGroupIconByName($group->name);
                          if($groupicon){
                            $tree .= '<li class="client">' . $config['serverViewer']['icons']['client'] . ' ';
                            $tree .= htmlspecialchars($datas[$i]->name);
                            $tree .= '<span class="badge badge-light float-right" title="' . $group->name . '" data-toggle="tooltip" data-placement="top"><img class="float-right" src="'. $groupicon . '" alt="' . $group->name . '"/></span>';
                          }
                        }
                      }
                    } else {
                      $tree .= '<li class="query">' . $config['serverViewer']['icons']['query'] . ' ';
                      $tree .= htmlspecialchars($datas[$i]->name);
                      $tree .= '<span class="badge badge-light float-right" title="Query" data-toggle="tooltip" data-placement="top">Query</span>';
                    }
                  }

                }
                $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                $tree .= '</li>';
                break;
              default:
                $tree .= '<li>';
                $tree .= htmlspecialchars($datas[$i]->name);
                $tree .= generateTree($datas, $datas[$i]->ident, $depth+1);
                $tree .= '</li>';
                break;

            }
        }
    }
  $tree .= '</ul>';
  return $tree;

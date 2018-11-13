<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TS3-ServerViewer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <style>
    html {
      width: 100%;
      height: 100%;
    }
    /* Server Viewer */
    .server-viewer ul {
        list-style: none;
        padding-left: 1.5em;
    }

    .server-viewer .spacer {
       margin-top: 0.5rem;
       margin-bottom: 0.5rem;
    }
    .server-viewer .client , .server-viewer .channel {
     margin-top: 0.1rem;
     margin-bottom: 0.1rem;
    }
    </style>
  </head>
  <body>

    <main role="main">
      <div class="card">
        <div class="card-header">
          <h5>
            Server Viewer
            <span class="float-right">
              <a href="#" id="server-viewer-emptytoggle" onclick="hideEmptyChannel()" class="btn btn-light btn-sm" title="Leere Channel ausblenden" data-toggle="tooltip" data-placement="top"><i class="fas fa-eye"></i></a>
              <a href="#" id="server-viewer-refresh" onclick="refreshViewer()" class="btn btn-light btn-sm" title="Aktuallisieren" data-toggle="tooltip" data-placement="top"><i class="fas fa-sync"></i></a>
            </span>
          </h5>
        </div>
        <div class="card-body">
          <div class="server-viewer">
            <?php
              require_once(__DIR__ . '/serverviewer.inc.php');
              echo(getServerViewer());
            ?>
          </div>
        </div>
    </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
      // ServerViewer Hide/Show Empty Channel
      function hideEmptyChannel() {
        $(document).ready(function() {
            $('.empty-channel').attr('hidden', 'hidden');
            $("#server-viewer-emptytoggle").attr("onclick","showEmptyChannel()");
            $("#server-viewer-emptytoggle").attr("data-original-title","Leere Channel einblenden");
            $("#server-viewer-emptytoggle").tooltip('hide');
            $("#server-viewer-emptytoggle").html('<i class="fas fa-eye-slash"></i>');
        });
      }

      function showEmptyChannel() {
        $(document).ready(function() {
            $('.empty-channel').removeAttr('hidden');
            $("#server-viewer-emptytoggle").attr("onclick","hideEmptyChannel()");
            $("#server-viewer-emptytoggle").attr("data-original-title","Leere Channel ausblenden");
            $("#server-viewer-emptytoggle").tooltip('hide');
            $("#server-viewer-emptytoggle").html('<i class="fas fa-eye"></i>');
        });
      }

      function refreshViewer(){
        $.ajax({
            url: "serverviewer.php",
            success: function (result) {
              $('.server-viewer').html(result);
            },
            error: function (result) {
              displayErrorServerInfo('<div class="alert alert-warning" role="alert">' + result + '</div>);');
            }
        })
      }

    </script>
  </body>
</html>

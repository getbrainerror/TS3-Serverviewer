<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Just the description">
    <meta name="author" content="getBrainError@github.io">
    <link rel="icon" href="favicon.ico">

    <title>Another TS Website - Server Viewer</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
         <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
         <style>
         html {
           position: relative;
           min-height: 100%;
         }
         body {
           margin-bottom: 60px;
         }
         .footer {
           position: absolute;
           bottom: 0;
           width: 100%;
           height: 60px;
           line-height: 60px;
           background-color: #f5f5f5;
         }

         body > .container {
           padding: 110px 15px 0;
         }

         .footer > .container {
           padding-right: 15px;
           padding-left: 15px;
         }

         </style>
  </head>

  <body>
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary">
        <a class="navbar-brand" href="?page=home"><i class="fab fa-teamspeak"></i> Another TS Website</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                  <a class="nav-link" href="?page=home"><i class="fas fa-fw fa-home"></i> Startseite</a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link" href="?page=server-viewer"><i class="fas fa-fw fa-list-ul"></i> Server Viewer <span class="sr-only">(aktuell Ausgewählt)</span></a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="?page=rules"><i class="fas fa-fw fa-gavel"></i> Regeln</a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="?page=group-assigner"><i class="fas fa-fw fa-bolt"></i> Gruppenzuweiser</a>
                </li>
              </ul>
              <ul class="navbar-nav ml-auto">
                <li class="nav-item pull-right ">
                  <a class="nav-link" href="?page=login"><i class="fas fa-fw fa-sign-in-alt"></i> Login</a>
                </li>
                <li class="nav-item pull-right ">
                  <a class="nav-link" href="?page=register"><i class="fas fa-fw fa-user-plus"></i> Registrieren</a>
                </li>
              </ul>
        </div>
      </nav>
    </header>

    <main role="main" class="container">
      <div class="row">
        <div class="col-md-8 mb-4">
          <object type="text/html" data="index.php" style="width: 100%; height: 100%;"></object>
        </div>


  <div class="col-md-4">
  <div class="card mb-4">
  <div class="card-header"><h5><i class="fas fa-fw fa-server"></i> Server Status <span id="status-last-refresh" class="float-right" data-toggle="tooltip" data-placement="top" title="Zuletzt aktuallisiert:"><i class="fas fa-fw fa-info-circle"></i></span></h5></div>
    <div class="card-body">
      <p class="card-text">
        <ul class="list-group" id="serverstatus" hidden>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="fas fa-fw fa-link"></i> Server Adresse: </span><span id="status-server-address"><a href="ts3server://localhost:9987" class="badge badge-pill badge-primary">localhost</a></span>
          </li>
          <li id="status-all-users" class="list-group-item d-flex justify-content-between align-items-center" data-html="true" data-toggle="tooltip" data-placement="top" title="">
            <span><i class="fas fa-fw fa-user"></i> Online: </span><span id="status-online-users" class="badge badge-pill badge-primary"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="fas fa-fw fa-chart-line"></i> Uptime: </span><span id="status-uptime" class="badge badge-pill badge-primary"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="fas fa-fw fa-signal"></i> Ping: </span><span id="status-ping" class="badge badge-pill badge-primary"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="fas fa-fw fa-dolly-flatbed"></i> Paketverlust: </span><span id="status-packet-loss" class="badge badge-pill badge-primary"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="fas fa-fw fa-code-branch"></i> Version: </span><span id="status-version" class="badge badge-pill badge-primary"></span>
          </li>
        </ul>
        <div id="serverstatus-spinner" class="alert center text-center mb-4">
          <i class="fas fa-fw fa-4x fa-spinner fa-spin"></i>
        </div>
        <div id="serverstatus-error" class="alert alert-danger text-center" role="alert" hidden>
          <i class="fas fa-2x fa-exclamation-triangle m"></i>
          <p>Error:</p>
        </div>

      </p>
    </div>
</div>
<div class="card mb-4">
  <div class="card-header"><h5><i class="fas fa-fw fa-users"></i> Team <span class="float-right"data-toggle="tooltip" data-placement="top" title="Zuletzt aktuallisiert: 08-08-2018 19:13:56"><i class="fas fa-fw fa-info-circle"></i></span></h5></div>
    <div class="card-body">
      <p class="card-text">
        <div id="grouplist">

        </div>
        <div id="grouplist-spinner" class="alert center text-center mb-4">
          <i class="fas fa-fw fa-4x fa-spinner fa-spin"></i>
        </div>

        <div id="grouplist-error" class="alert alert-danger text-center" role="alert" hidden>
          <i class="fas fa-2x fa-exclamation-triangle"></i>
          <p>Error:</p>
        </div>

      </p>
    </div>
</div>
<div class="card mb-4">
  <div class="card-header"><h5><i class="fas fa-fw fa-globe-americas"></i> Links</h5></div>
    <div class="card-body">
      <div class="card-text">
        <ul class="list-unstyled text-primary">
                        <li><i class="fab fa-fw fa-teamspeak"></i> <a href="#" class="text-primary">TeamSpeak: Join Me</li>
                            <li><i class="fab fa-fw fa-facebook-f"></i> <a href="#" class="text-primary">Facebook Page</li>
                            <li><i class="fab fa-fw fa-whatsapp"></i> <a href="#" class="text-primary">Whatsapp Gruppe</li>
                            <li><i class="fab fa-fw fa-telegram-plane"></i> <a href="#" class="text-primary">Telegram Gruppe</li>
                            <li><i class="fas fa-fw fa-envelope"></i> <a href="#" class="text-primary">E-Mail</li>
                      </ul>
      </div>
    </div>
</div>
</div>
</div>
    </main>
    <footer class="footer">
      <div class="container text-center text-muted">
        <span>
          <a href="#" class="text-muted"><i class="far fa-copyright"></i> getBrainError</a> |
          <a href="https://github.com/getbrainerror/Yet-Another-TS-Website" class="text-muted"><i class="fab fa-github"></i> Github</a> |
          <a href="#" class="text-muted">Impressum</a> |
          <a href="#" class="text-muted">Datenschutz</a>
        </span>
      </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
      </body>
</html>

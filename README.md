# TS3 Server Viewer
## Setup

1. Download the latest release (or clone the repo)
2. Unzip the files in a subfolder on your website
3. Edit the config.inc.php
4. Add the ServerViewer to your website via object or directly via php
5. ???
6. Profit

## Add the ServerViewer via object

Add the following to your website

```
<object type="text/html" data="PUBLIC PATH TO INDEX.PHP" style="width: 100%; height: 100%;"></object>
```


## Add the ServerViewer via PHP

You need to make sure that you have bootstrap 4 jquerry and fontawesome 4
Else you can edit the serverviewer.inc.php directly
Add the following inside your header or inside your custom.css file

```
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
```

Then add the following at the point where you want have your serverviewer

```
<div class="server-viewer">
<?php
	require_once(__DIR__ . '/serverviewer.inc.php');
	echo(getServerViewer());
?>
</div>
```

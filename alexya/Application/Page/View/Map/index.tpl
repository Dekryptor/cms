<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>BlackEye | Private Server</title>
    <link href="css/globalForms.css" media="screen" rel="stylesheet" type="text/css">
    <script src="js/ga.js" async="" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
    //<!--
    $_jq=jQuery.noConflict(true);
    //-->
    </script>
    <meta name="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <link rel="SHORTCUT ICON" href="http://darkorbit.com/favicon.ico" type="image/x-icon">

    <style type="text/css" media="screen">@import "css/darkorbit.css"; </style>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script src="js/function.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery-1.4.4.js"></script>
    <script type="text/javascript" src="js/jquery.flashembed.js"></script>
</head>
<body>
    <div id="container"></div>
    <iframe src="{$URL}Map" name="reloader" width="0" height="0" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>

    <script type='text/javascript'>
        function onFailFlashembed() {
            var inner_html = '<div class="flashFailHead">{t("Install Flash Player")}</div>\n\
            <div class="flashFailHeadText">{t("To play BlackEye you need the latest Flash Player. Just install it and start playing!")}\n\
            <div class="flashFailHeadLink" style="cursor: pointer">{t("Download Flash Player for free from here:")} <a href=\"http://www.adobe.com/go/getflashplayer\" style=\"text-decoration: underline; color:#A0A0A0;\">{t("Download Flash Player")}<\/a> </div></div>';

            document.getElementById('container').innerHTML = inner_html;
        }

        function expressInstallCallback(info) {
            // possible values for info: loadTimeOut|Cancelled|Failed
            onFailFlashembed();
        }

        jQuery(document).ready(
            function(){
                flashembed("container", {
                    "src": "spacemap/main.swf",
                    "version": [11,0],
                    "expressInstall": "swf_global/expressInstall.swf",
                    "width": 1024,
                    "height": 576,
                    "wmode": "direct",
                    "bgcolor": "#000000",
                    "id": "main"
                }, {
                    "dynamicHost": "{$ip}",
                    "userID": {$user->id},
                    "factionID": {$user->Faction->id},
                    "sessionID": "{$user->session_id}",
                    "mapID": {$user->Map->id},
                    "basePath": "spacemap",
                    "cdn": "",
                    "lang": "{$lang}",
                    "pid": 563,
                    // "antstart": 0, idk
                    "resolutionID": 1,
                    "boardLink": "{$URL}",
                    "helpLink": "{$URL}",
                    "loadingClaim": "{t("LOADING...")}",
                    "localGS": "{$ip}", // If you're not using `localhost` as localhost
                    "chatHost": "{$ip}",
                    "supportedResolutions": "1,2,3,4,5",
                    "autoStartEnabled": 0,
                    "instantLogEnabled": 1,
                    "hpNumbersOnMapEnabled": 1,
                    "jsEventTrackingEnabled": 1,
                    "doubleClickAttackEnabled": 1,
                    "resourcesXmlHash": "4f5d6e23ebb06278f110ba358dde28ec",
                    "gameXmlHash": "060b9c86992a12a6d343395f64852876",
                    "profileXmlHash": "18287bc38698431e80f7cca05e6df2ca",
                    "maxLoadingRetries": 5,
                    "logConfig": "16,200,4,5", // y,width,maxEntries,displayTime
                    "allowChat": 1
                });
            }
        );
    </script>

    <style>
        #container {
            color: #FFFFFF;
            background : #000000;
    }
    </style>
</body>
</html>

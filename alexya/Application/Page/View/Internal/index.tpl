<html lang="en">
<head>
    <title>BlackEye | {$name}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="icon" type="image/ico" href="{$URL}favicon.html">

    <link href="{$URL}css/stylesheets.css" rel="stylesheet" type="text/css">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <script type='text/javascript' src='{$URL}js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='{$URL}js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='{$URL}js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='{$URL}js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='{$URL}js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='{$URL}js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='{$URL}js/plugins/uniform/jquery.uniform.min.js'></script>

    <script type='text/javascript' src='{$URL}js/plugins/sparkline/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='{$URL}js/plugins/flot/jquery.flot.js'></script>
    <script type='text/javascript' src='{$URL}js/plugins/flot/jquery.flot.resize.js'></script>

    <script type='text/javascript' src='{$URL}js/plugins.js'></script>
    <script type='text/javascript' src='{$URL}js/actions.js'></script>

    <style type="text/css">
        .jqstooltip {
            position: absolute;
            left: 0px;
            top: 0px;
            visibility: hidden;
            background: rgb(0, 0, 0) transparent;
            background-color: rgba(0,0,0,0.6);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
            color: white;
            font: 10px arial, san serif;
            text-align: left;
            white-space: nowrap;
            padding: 5px;
            border: 1px solid white;
            z-index: 10000;
        }
        .jqsfield {
            color: white;
            font: 10px arial, san serif;
            text-align: left;
        }
        .news-slider {
            white-space: nowrap;
            overflow: hidden;
            width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .news-slider li {
            margin-right: 10px;
            opacity: 0.88;
            display: inline-block;
        }
        .top-right {
             position: absolute;
             top: 0px;
             right: 0px;
        }
        .nav-box a {
             padding: 10px 10px 10px 22px;
        }
        .nav-box ul {
            width: 314px;
            padding: 10px;
            background-color: rgb(14, 44, 78);
        }
        .nav-box ul li {
            border-bottom: 1px solid;
            margin-bottom: 10px;
        }
        .nav-box ul li:last-child {
            border: 0px;
            margin: 0px;
        }
        .circle-warning {
            margin-left: 5px;
            vertical-align: text-top;
            border-radius: 50%;
            width: 18px;
            background-color: rgb(248, 172, 89);
            color: rgb(255, 255, 255);
        }
        .item p {
            margin-top: -18px;
            height: 18px;
            text-align: center;
            width: 100px;
            font-weight: bold;
        }
        #buy {
            width: 100%;
            margin-top: 10px;
            height: 50px;
            padding-top: 12.5px;
        }
        .page-navigation li ul li a {
            padding-left: 50px;
        }
    </style>
</head>
<body class="bg-img-num1">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar brb" role="navigation">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="{$URL}Internal/Start">
                            <img src="{$URL}img/logo.png"/>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <div class="news-slider">
                            <ul id="news-slider" class="nav nav-bar">
                                {foreach from=$news item=n}
                                <li>
                                    <a href="{$URL}Internal/News/{$n["id"]}">{$n["title"]}</a>
                                </li>
                                {/foreach}
                            </ul>
                        </div>
                        <ul class="nav navbar-nav navbar-right top-right">
                            <li class="dropdown nav-box">
                                <a href="#" class="drodown-toggle" data-toggle="dropdown">
                                    <span class="fa fa-envelope fa-3x"></span>
                                    {if $user->Messaging->hasUnreadMessages()}
                                        <small class="circle-warning top-right">{$user->Messaging->unreadMessagesCount()}</small>
                                    {/if}
                                </a>
                                <ul class="dropdown-menu">
                                    {foreach from=$user->Messaging->unreadMessages() item=message}
                                    <li>
                                        <h4><a href="{$URL}Internal/Messages/{$message->id}">{$message->title}</a></h4>
                                    </li>
                                    {foreachelse}
                                    <li>
                                        <h4>No new messages!</h4>
                                    </li>
                                    {/foreach}
                                </ul>
                            </li><li class="nav-box dropdown">
                                <a href="#" class="drodown-toggle" data-toggle="dropdown">
                                    {$user->name}
                                </a>
                                <ul class="dropdown-menu">
                                    <div class="block block-drop-shadow">
                                        <div class="head np">
                                            <div class="user">
                                                <div class="info">
                                                <img src="{$URL}img/logob.png" class="img-circle img-thumbnail">
                                                    <a href="#" class="informer informer-one">
                                                        <span>{$user->experience}</span>
                                                        {t("Experience")}
                                                    </a>
                                                    <a href="#" class="informer informer-two">
                                                        <span>{$user->honor}</span>
                                                        {t("Honor")}
                                                    </a>
                                                    <a href="#" class="informer informer-three">
                                                        <span>{$user->credits}</span>
                                                        {t("Credits")}
                                                    </a>
                                                    <a href="#" class="informer informer-four">
                                                        <span>{$user->uridium}</span>
                                                        {t("Uridium")}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="block block-drop-shadow page-navigation-hide">
                                        <div class="head bg-dot20">
                                            <div class="side pull-left">
                                                <div class="head-panel nm">
                                                    <div class="hp-info hp-simple pull-left hp-inline">
                                                        <span class="hp-sm">{t("Level")}</span>
                                                        <span class="hp-sm">{t("Experience needed for next level")}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="side pull-right">
                                                <div class="head-panel nm">
                                                    <div class="hp-info hp-simple pull-left hp-inline">
                                                        <span class="hp-sm">{$user->levels_id}</span>
                                                        <span class="hp-sm">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </ul>
                            </li>
                            <li>
                                <a href="{$URL}Internal/Logout">
                                    <i class="fa fa-sign-out"></i> {t("Logout")}
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <div class="page-container">
        <div class="page-sidebar">
            <div class="page-navigation-panel logo"></div>
            <ul class="page-navigation">
                <li>
                    <a href="{$URL}Internal/Start">
                        {t("Home")}
                        <span class="fa fa-home fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
                <li>
                    <a href="{$URL}Internal/Dock">
                        {t("Dock")}
                        <span class="fa fa-fighter-jet fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        {t("Shop")}
                        <span class="fa fa-dollar fa-lg fa-pull-left" style="float: right"></span>
                    </a>
                    <ul>
                        <li><a href="{$URL}Internal/Shop/Ships">{t("Ships")}</a></li>
                        <li><a href="{$URL}Internal/Shop/Drones">{t("Drones")}</a></li>
                        <li><a href="{$URL}Internal/Shop/Weapons">{t("Weapons")}</a></li>
                        <li><a href="{$URL}Internal/Shop/Generators">{t("Generators")}</a></li>
                        <li><a href="{$URL}Internal/Shop/Amunition">{t("Amunition")}</a></li>
                        <li><a href="{$URL}Internal/Shop/PET">{t("PET")}</a></li>
                        <li><a href="{$URL}Internal/Shop/Extras">{t("Extras")}</a></li>
                        <li><a href="{$URL}Internal/Shop/Desings">{t("Designs")}</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{$URL}Internal/Clan">
                        {t("Clan")}
                        <span class="fa fa-users fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
                <li>
                    <a href="{$URL}Internal/Laboratory">
                        {t("Laboratory")}
                        <span class="fa fa-flask fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
                <li>
                    <a href="{$URL}Internal/Quests">
                        {t("Quests")}
                        <span class="fa fa-star fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
                <li>
                    <a href="{$URL}Internal/Bank">
                        {t("Bank")}
                        <span class="fa fa-bank fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
                <li>
                    <a href="{$URL}Internal/Messages">
                        {t("Messages")}
                        <span class="fa fa-envelope-o fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
                <li>
                    <a href="{$URL}Internal/HallOfFame">
                        {t("Hall of Fame")}
                        <span class="fa fa-bookmark fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
                <li>
                    <a href="{$URL}Map">
                        {t("Spacemap")}
                        <span class="fa fa-play fa-lg fa-pull-right" style="float: right;"></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="page-content">
            <div class="container">
                {$module}
            </div>
        </div>
    </div>
    <script>
    function ScrollerChangeFirst()
    {
        NewsController.scroller.append(NewsController.scrollItems.eq(0).remove()).css('margin-left', 0);
        NewsController.scrollTicker();
    }

    var NewsController = {
        scrollSpeed: 5,
        scrollerWidth: 0,
        scroller: undefined,
        scrollItems: undefined,
        scrollMargin: 0,

        /**
         * Inits scroller and sets onclick events
         */
        init: function()
        {
            this.initScroller();

            jQuery("#news-slider > li").click(function() {
                console.log("Object: "+ JSON.stringify(jQuery(this).attr("id")));
            });

            $('.news_content').slimscroll({
                height: '200px'
            })
        },

        /**
         * Inits scroller
         */
        initScroller: function()
        {
            this.scroller = jQuery('#news-slider');

            this.scroller.children().each(function() {
                NewsController.scrollerWidth += jQuery(this).outerWidth(true);
            });
            this.scrollerWidth = this.scroller.outerWidth();

            this.scrollTicker();

            this.scroller.mouseover(function() {
                NewsController.scroller.stop();
            });
            this.scroller.mouseout(function() {
                NewsController.scrollMargin = parseInt(NewsController.scroller.css('margin-left').replace(/px/, ''));
                NewsController.scrollTicker();
            });
        },

        /**
         * Scrolls news
         */
        scrollTicker: function()
        {
            this.scrollItems  = this.scroller.children();
            var scrollWidth   = this.scrollItems.eq(0).outerWidth(true);
            var scrollMargin  = this.scrollMargin;
            this.scrollMargin = 0;

            this.scroller.animate({
                    'margin-left': (scrollWidth * -1) + scrollMargin
                },
                scrollWidth * 100 / this.scrollSpeed,
                'linear',
                ScrollerChangeFirst
            );
        }
    }
    NewsController.init();
    </script>
</body>
</html>

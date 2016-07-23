<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Trang quản trị</title>
    </head>
    <link rel="stylesheet" type="text/css" href="../../css/w2ui-1.4.css" />
    <link rel="stylesheet" type="text/css" href="../../css/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="../../css/jquery-ui.structure.min.css" />
    <link rel="stylesheet" type="text/css" href="../../css/jquery-ui.theme.min.css" />
    <link rel="stylesheet" type="text/css" href="../../css/defaulteditor.css" />
    <link rel="stylesheet" type="text/css" href="../../css/jquery.jqplot.min.css" />
    <link rel="stylesheet" type="text/css" href="../../css/jquery.contextMenu.css" />
    <link rel="stylesheet" type="text/css" href="../../css/main.css" />
    
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../js/w2ui-1.4.js"></script>
    <script type="text/javascript" src="../../js/jquery.sceditor.bbcode.js"></script>
    <script type="text/javascript" src="../../js/notify.js"></script>
    <script type="text/javascript" src="../../js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="../../js/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="../../js/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="../../js/jqplot.barRenderer.min.js"></script>
    <script type="text/javascript" src="../../js/jqplot.pieRenderer.min.js"></script>
    <script type="text/javascript" src="../../js/jqplot.categoryAxisRenderer.min.js"></script>
    <script type="text/javascript" src="../../js/jqplot.pointLabels.min.js"></script>
    <script type="text/javascript" src="../../js/jqplot.canvasTextRenderer.min.js"></script>
    <script type="text/javascript" src="../../js/jqplot.canvasAxisLabelRenderer.min.js"></script>
    <script type="text/javascript" src="../../js/jqplot.pieRenderer.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.contextMenu.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.position.min.js"></script>
    <script type="text/javascript" src="../../js/prettify.js"></script>
    <script type="text/javascript" src="../../js/jquery.loadTemplate-1.5.0.min.js"></script>
    <script type="text/javascript" src="../../js/main.js"></script>
    <script type="text/javascript" src="../../js/productmanage.js?v=2358091815"></script>
    <script type="text/javascript" src="../../js/customermanage.js?v=2244230915"></script>
    <script type="text/javascript" src="../../js/statistic.js?v=2315230915"></script>
    
    <style>
        #layout{
            margin-top: -10px;
            margin-left: -5px;
        }
        body{
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
    </style>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).bind("contextmenu", function(e){
               e.preventDefault(); 
            });
        });
    </script>
    <body>
        <div id="layout"></div>
        <div class="waiting_modal"></div>
        <?php
            $inDirectCall = TRUE;
            require_once("../../models/config.php");
            $isLogin = securePage($_SERVER['PHP_SELF']);
            if (!$isLogin){
                die();
            }else{
                echo '<script type="text/javascript">',
                 'initPage("'.$loggedInUser->username.'");',
                 '</script>';
            }
        ?>
    </body>
</html>

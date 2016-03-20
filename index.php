<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <title>minipos</title>
    <link rel="shortcut icon" href="img/minipos.ico">
    <link href="lib/bootstrap.css" rel="stylesheet">
    <link href="css/abc.css" rel="stylesheet" type="text/css" />



    <!-- Include jQuery Mobile stylesheets -->
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

    <!-- Include the jQuery library -->
    <script src="lib/jquery.js"></script>

    <!-- Include the jQuery Mobile library -->
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>



    <script src="dist/jquery.floatThead.js"></script>

    <script src="lib/ui-bootstrap-tpls-0.12.0.js"></script>
    <script src="lib/bootstrap.js"></script>


    <script>

        var key = 0;
        $(document).ready(function(){
            var ti = 'getcatalog.php';
            $("#foot1").load(ti); //JQUERY
            ti = 'viewcart.php';
            $("#s2").load(ti);

        });



        function closewindow(){
            var form1 = document.getElementById("form1");
                form1.submit();
            setTimeout(seestock, 1000)
            $('#myModal').modal('hide')
        }


        function seestock() {
            var ti = 'viewstock.php';
            $("#s2").load(ti); //JQUERY
        }
        function seesales() {
            var ti = 'viewtodaysales.php';
            $("#s2").load(ti); //JQUERY
        }
        function seesalesperday() {
            var ti = 'viewsalesperday.php';
            $("#s2").load(ti); //JQUERY
        }
        function addtocart(i) {
            var qty = document.getElementById("quantity").value;
//          alert(qty);
            var ti = 'addtocart.php?idp='+i+'&q='+qty;
            $("#s2").load(ti);
        }
        function pos() {
            var ti = 'viewcart.php';
            $("#s2").load(ti);
        }
        function closeday() {
            var ti = 'closeday.php';
            $("#s2").load(ti);
        }
        function delrec(i) {
            var ti = 'delitem.php?ide='+i;
            $("#s2").load(ti);
        }
        function clearcart() {

            var ti = 'clearcart.php';
            $("#s2").load(ti);
        }
        function sale(i) {
            var ti = 'applysale.php?idd='+i;
            $("#s2").load(ti);
        }

        function cancelsale(i) {
            var ti = 'cancelsale.php?idd='+i;
            $("#s2").load(ti);
        }

        function signme() {
            var ti = 'login.php';
            $("#s2").load(ti);
        }

        function signout() {
            var ti = 'logout.php';
            $("#s2").load(ti);
        }

        function prodedit(i) {
            var ti = 'prodEdit.php?idp='+i;
            $("#subModal").load(ti);
        }


    </script>


</head>

<body>
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset=UTF-8");
header("Content-Language: HE");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

include_once("conn/connect.php");
include_once("conn/check_user.php");

?>


<div  id="jspage">

    <nav class="navbar navbar-inverse  fixhdr" >
            <?php
            if(isset($_COOKIE['id'])){
                ?>
            <div style="float:left; padding-left:6px; padding-top: 12px;">
                <input type="number" name="quantity" id="quantity" min="1" max="999" value="1">
            </div>
            <div style="float:left; padding-left:6px; padding-top: 12px;">
                    <span class="boton4"  id="posme" name="posme" onclick="pos();">POS</span>
                    <span class="boton4"  id="stockme" name="stockme" onclick="seestock();">STOCK</span>
                    <span class="boton4"  id="mysales" name="mysales" onclick="seesales();">VTA-DIA</span>
                    <span class="boton4"  id="signout" name="signout"><a href="logout.php">LOGOUT</a></span>
                    <?php echo " $log_uname ";?>
            </div>


                <?php
            }
            else{
                ?>
                <div style="float:left; padding-left:6px; padding-top: 12px;">
                    <span class="boton4"  id="signme" name="signrme"><a href="login.php">LOGIN</a></span>
                    <a href="register.php"  target="_blank" title="Register" > R </a>
                </div>

                <?php
            }
            ?>

        </div>

    </nav>

    <div class="container-fluid cuerpo">

        <div class="row content">


            <div id="s2" class="col-sm-12 mysm12"   >
F5 PARA ACTUALIZAR
            </div>

        </div>
    </div>

    <footer class="container text-center fixfooter minileter" id="foot">
        <iframe id="up" name="up" width="100%" height="25" class="ifr"></iframe>
<div id="foot1"></div>
<div id="foot2"></div>

    </footer>


    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">Close</button>
                </div>
                <div id="subModal" class="modal-body">
                </div>
                <div class="modal-footer">
                    <p>Filter:</p>
                    <input type="search" id="search"  placeholder="Filter" class="form-control" />

                    <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>



</body>
</html>
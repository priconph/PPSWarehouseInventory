<?php
require_once('./includes.php');
//SET IP RESTRICTIONS
/*
$PCIP = $_SERVER['REMOTE_ADDR'];
if($PCIP != '192.168.20.103') {
header('Location: ../');
}
*/
// echo '<br><br>
		// <br><br><br><br><br><br><br><br><br><br><br>
		// <h1><center>On-going Data Loading - ISS</center></h1>';
// exit;
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	$('#li_Resin').click(function(){
		$('#iFrm_Main').attr('src', "./Resin.php");
		$('#li_Resin').attr('class', "active");
	});
});

</script>

</head>
<body>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./index.php">PPS Warehouse</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <!--<li class="" id="li_Resin"><a href="#">RESIN</a></li>-->
        </ul>
      <ul class="nav navbar-nav navbar-right">
        <!--<li><a href="">Reserved</a></li>-->
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<iframe src="./Resin.php" id="iFrm_Main" style="padding:1%;" frameborder="0"></iframe>

</body>
</html>
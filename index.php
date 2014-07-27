<?php
 set_time_limit (0);
 //ignore_user_abort(TRUE);
 error_reporting(0);
 ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>اسکریپت جستجوگر ایمیل</title>

<!-- Internet Explorer HTML5 enabling script: -->

<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<link rel="stylesheet" type="text/css" href="theme/styles.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="theme/script.js"></script>
</head>

<body>

<div id="rocket"></div>

<hgroup>

<?php
require("email_parser.class.php");
$parser = new parser();
$parser->setPassword("bitsoftwaregroup");   // set your favorite password to restrict other's access!
	$parser->showForm();
    $parser->parseEmails();
    ?> 
</hgroup>



</body>
</html>

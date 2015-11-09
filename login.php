<? //<!--add by kuaicho 2012-6-7--> ?>
<?php require_once('ori_config/ezhome.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?><?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['id'])) {
  $loginUsername=$_POST['id'];
  $password=$_POST['pwd'];
  $MM_fldUserAuthorization = "";
  //$MM_redirectLoginSuccess = "index.php";
  if(isset($_SESSION['PrevUrl'])){
  	$MM_redirectLoginSuccess=$_SESSION['PrevUrl'];
  }else{
  	$MM_redirectLoginSuccess="user/news.php";
  }
  //$MM_redirectLoginSuccess=$_SESSION['PrevUrl'];
  
  $MM_redirectLoginFailed = "login.php?msg=login";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_ezhome, $ezhome);
  
  $LoginRS__query=sprintf("SELECT email, pwd FROM `user` WHERE email=%s AND pwd=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $ezhome) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      //$MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
	  $MM_redirectLoginSuccess = "user/news.php";	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}

?>
<? //<!--add by kuaicho 2012-6-7--> ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>iEzhome 易搜樓</title>
		<link href="css/common.css" rel="stylesheet">
		<script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript">PageIdx = null;</script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript">
			$(function(){				
				setPos();
				$(window).resize(function(){
					setPos();
				});
			});
		</script>
	</head>
	<body>
		<div id="header"></div>
        
        <!--add by kuaicho 2012-6-7-->
        <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
        <!--add by kuaicho 2012-6-7-->
        
		<div class="bodyContainer">	
	
			<div class="container">
				<div id="loginContainer">
					<h1>會員登入</h1>
                    
                    <h2><center>
                    <?
						if($_GET['msg']=='login'){
							echo "必須登入才能瀏覽頁面!";
						}
					?>	
                    </center></h2>
                    <ul>
						<li><b>Email：</b><input type="text" name="id" class="left" size="25" placeholder="user123@yahoo.com.tw" /></li>
						<li><b>密碼：</b><input type="password" name="pwd" class="left" size="25" /></li>
						<li>
							<!--input type="checkbox" name="remember" id="remember" /><label for="remember">記住我</label-->
						</li>
						<li>
							<input type="submit" class="importBtn" value="登入" />
							<!--input type="submit" class="normalBtn" value="回上頁" /-->
						</li>
						<li>
							<!--a class="left" href="#">&#x25c0; 忘記密碼?</a-->
							<a class="right" href="register.php">註冊 &#x25B6;</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
        
        <!--add by kuaicho 2012-6-7-->
        </form>
        <!--add by kuaicho 2012-6-7-->
        
		<div id="footer"></div>
	</body>
</html>


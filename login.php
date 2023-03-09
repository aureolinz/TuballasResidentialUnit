<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('admin/db_connect.php');

$msg = "";
if(isset($_POST['email']) && isset($_POST['password'])) {
	$statement = $conn->prepare("SELECT password FROM tenants WHERE email = ?;");
	
	$statement->bind_param("s", $_POST["email"]);
	if($statement->execute()){
		$statement->bind_result($result);
		$statement->fetch();
		if($result === md5($_POST['password'])) {
			$_SESSION['SESSION_EMAIL'] = $_POST['email'];
			echo "<script> location.replace('index.php'); </script>";
		}
		$msg = "<div class='alert alert-danger'>Incorrect email or password!</div>";
	} else {
		$msg = "<div class='alert alert-danger'>Something went wrong! Please try again!</div>";
	}
	
}
 
ob_start();
if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
}
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $_SESSION['system']['name'] ?></title>
 	
<?php
include('header.php');
 
if(isset($_SESSION['SESSION_EMAIL'])) 
	header("location:index.php");

?>

</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
	}
	main#main{
		width:100vw;
		/*height: calc(100%)*/;
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:60%;
		height: calc(100%);
		background:#59b6ec61;
		display: flex;
		align-items: center;
		/*background: url(assets/uploads/blood-cells.jpg);
	    background-repeat: no-repeat;
	    background-size: cover;*/
	}
	.img-mobile {
		display: none;
	}
	@media screen and (max-width: 1020px) {
		#login-right {
			width: 90%;
		}
		#login-left {
			width: 20%;
		}
		#login-left img {
			display: none;
		}
		.img-mobile {
			width: 350px;
			display: block;
			margin: auto;
		}
	}
	@media screen and (max-width: 760px) {
		#login-right {
			width: 90%;
		}
		#login-left {
			width: 10%;
		}
	}
	#login-right .card{
		margin: auto;
		z-index: 1
	}
	.logo {
    margin: auto;
    font-size: 8rem;
    background: white;
    padding: .5em 0.7em;
    border-radius: 50% 50%;
    color: #000000b3;
    z-index: 10;
}
div#login-right::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: calc(100%);
    height: calc(100%);
    /*background: #000000e0;*/
}

</style>

<body>

  <main id="main" class="bg-light">
  		<div id="login-left" class="bg-dark d-flex justify-content-center align-items-center">
			<div class="text-center" style="">
				<img src="images/logo.png" style="height: 400px; height: 200px;">
			</div>
  		</div>

  		<div id="login-right" class="bg-light">
  			<div class="w-100">
			<img src="images/logo.png" style="height: 200px;" class="img-mobile">
			<h4 class="text-center"><b><?php echo $_SESSION['system']['name'] ?></b></h4>
			<br>
			<br>
  			<div class="card col-md-8">
  				<div class="card-body">
  					<form id="login-form" action="" method="POST">
						<?php echo $msg ?>
  						<div class="form-group">
  							<label for="email" class="control-label">Email</label>
  							<input type="email" id="email" name="email" class="form-control">
  						</div>
  						<div class="form-group">
  							<label for="password" class="control-label">Password</label>
  							<input type="password" id="password" name="password" class="form-control">
  						</div>
  						<center><button class="btn-sm btn-block btn-wave btn-primary">Log in</button></center>
  					</form>
  				</div>
  			</div>
  			</div>
  		</div>
   

  </main>

</body>
</html>
<style>
	.logo {
    margin: auto;
    font-size: 20px;
    background: white;
    padding: 7px 11px;
    border-radius: 50% 50%;
    color: #000000b3;
}
</style>
<?php 
	require_once 'admin/db_connect.php';
	ob_start();
	if(!isset($_SESSION['system'])){
		$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
		}
	}
	ob_end_flush();
 ?>
<nav class="navbar navbar-light fixed-top" style="padding:0;min-height: 3.5rem; background:white;">
  <div class="container-fluid">
  	<div class="col-lg-12">
  		<div class="col-md-1 float-md-left" style="display: flex; justify-content: center;">
			<img src="images/logo.png" style="height: 100px; height: 50px;">
  		</div>
      <div class="<!--col-md-4--> float-md-left" style="color: maroon; text-align:center">
        <large><b><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></b></large>
      </div>
	  	<div class="float-md-right" style="text-align: center;">
        <div class=" dropdown mr-4">
            <a href="#" class="dropdown-toggle" style="color: maroon;"  id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['SESSION_EMAIL'] ?> </a>
              <div class="dropdown-menu" aria-labelledby="account_settings" style="left: 2.5em;">
                <!--<a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>-->
                <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
              </div>
        </div>
      </div>
  </div>
  
</nav>
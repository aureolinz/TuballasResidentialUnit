
<style>
	.collapse a{
		text-indent:10px;
	}
	nav#sidebar{
		/*background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>) !important*/
	}
	.hamburger-menu {
		display: none;
	}
	@media screen and (max-width: 1000px) {
		.hide-sidebar {
			display: none;
			z-index: 1000;
		}
		.hamburger-menu {
			display: block;
			position: fixed;
			top: 120px;
			right: 10px;
			font-size: 30px;
			z-index: 99;
			color: maroon;
		}
		.hamburger-menu:hover {
			color:maroon;
		}
		#sidebar {
			top: 115px;
		}
	}
	body{
        background: #80808045;
  }
  .active, .nav-item:hover{
	  background: hsl(240, 100%, 50%) !important;
  }
	
</style>

<a href="#0" class="hamburger-menu" onclick="showMenu()"><i class="fa fa-bars" aria-hidden="true"></i>
</a>
<nav id="sidebar" class='mx-lt-5 bg-dark hide-sidebar mt-2' >
		
		<div class="sidebar-list">
				<a href="index.php" class="nav-item nav-home <?php echo $page == 'dashboard' ? 'active' : ''?>"><span class='icon-field'><i class="fa fa-tachometer-alt "></i></span> Dashboard</a>
				<a href="view-balance.php" class="nav-item <?php echo $page == 'viewBalance' ? 'active' : ''?>"><i class="fa fa-wallet"></i> View Balance</a>
				<a href="to-pay.php" class="nav-item <?php echo $page == 'toPay' ? 'active' : ''?>"><i class="fa fa-file-invoice "></i> To Pay</a>
				<?php
					require_once 'admin/db_connect.php';
					$tenant_id = $conn->query("SELECT id FROM tenants WHERE email = '{$_SESSION['SESSION_EMAIL']}'")->fetch_assoc()['id'];
					$results = $conn->query("SELECT * FROM announcement WHERE id NOT IN (SELECT notification_id FROM notification 
					WHERE tenant_id = {$tenant_id} AND type = 'announcement' AND status = 1) ");
					$notifArray = array();
					while($row = $results->fetch_assoc()) {
						array_push($notifArray, $row['id']);
					}
				?>
				<a href="announcements.php" class="nav-item <?php echo $page == 'announcements' ? 'active' : ''?>"><i class="fa fa-bullhorn"></i>
				Announcements <b style="color: white;border-radius: 50%;background: red; padding: 5px 10px;"><?php echo count($notifArray); ?></b></a>
				<a href="chat.php" class="nav-item <?php echo $page == 'chat' ? 'active' : ''?>"><i class="fas fa-comment-alt"></i> Chat Staff</a>
		</div>

</nav>
<script>
	/*($('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')*/
	
	function showMenu() {
		sidebar = document.getElementById('sidebar');
		if(sidebar.classList.contains('hide-sidebar')) {
			sidebar.classList.remove('hide-sidebar');
		}
		else {
			sidebar.classList.add('hide-sidebar')
		}
	}
</script>

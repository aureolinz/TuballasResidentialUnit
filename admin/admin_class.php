<?php
session_start();
ini_set('display_errors', 1);
require_once 'sendEmail.php';
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		
			extract($_POST);		
			$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				if($_SESSION['login_type'] == 1){
					return 1;
				}
				else if($_SESSION['login_type'] == 2) {
					return 2;
				}
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2 ;
				exit;
			}else{
				return 3;
			}
	}
	function login2(){
		
			extract($_POST);
			if(isset($email))
				$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					foreach ($bio->fetch_array() as $key => $value) {
						if($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		//$data .= ", id = '$id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			return 1;
				}
	}

	
	function save_category(){
		extract($_POST);
		$data = " name = '$name' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO categories set $data");
			}else{
				$save = $this->db->query("UPDATE categories set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_bedspacer_category(){
		extract($_POST);
		$data = " name = '$name' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO bedspacer_categories set $data");
			}else{
				$save = $this->db->query("UPDATE bedspacer_categories set $data where bedspacer_cat_id = $id");
			}
		if($save)
			return 1;
	}
	function delete_bedspacer_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM bedspacer_categories where bedspacer_cat_id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_house(){
		extract($_POST);
		$data = " house_no = '$house_no' ";
		$data .= ", description = '$description' ";
		$data .= ", category_id = '$category_id' ";
		$data .= ", price = '$price' ";
		$chk = $this->db->query("SELECT * FROM houses where house_no = '$house_no' ")->num_rows;
		if($chk > 0 ){
			return 2;
			exit;
		}
			if(empty($id)){
				$save = $this->db->query("INSERT INTO houses set $data");
			}else{
				$save = $this->db->query("UPDATE houses set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_house(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM houses where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_bedspacer(){
		extract($_POST);
		$data = " bedspacer_no = '$bedspacer_no' ";
		$data .= ", bedspacer_description = '$description' ";
		$data .= ", bedspacer_category = '$category_id' ";
		$data .= ", amount = '$amount' ";
		$chk = $this->db->query("SELECT * FROM bedspacer where bedspacer_no = '$bedspacer_no' ")->num_rows;
		if($chk > 0 ){
			return 2;
			exit;
		}
			if(empty($id)){
				$save = $this->db->query("INSERT INTO bedspacer set $data");
			}else{
				$save = $this->db->query("UPDATE bedspacer set $data where bedspacer_id = $id");
			}
		if($save)
			return 1;
	}
	function delete_bedspacer(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM bedspacer where bedspacer_id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_tenant(){
		extract($_POST);
		$data = " firstname = '$firstname' ";
		$data .= ", lastname = '$lastname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", suffix = '$suffix' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", contact_person = '$contact_person' ";
		$data .= ", emergency_contact = '$emergency_contact' ";
		
		if(empty($id)) {
			$data .= ", house_id = '$house_id' ";
			$data .= ", date_in = '$date_in' ";
			$data .= ", type = '$type' ";
		}
		
		if(empty($id)) {
			if($this->db->query("SELECT * FROM tenants WHERE email = '{$email}'")->num_rows > 0)
				return 2;
		}
		if(file_exists($_FILES['government_id']['tmp_name']) && is_uploaded_file($_FILES['government_id']['tmp_name'])) {
			move_uploaded_file($_FILES['government_id']['tmp_name'], "..\\governmentIdImages\\{$_FILES['government_id']['name']}");
			$data .= ", government_id = '{$_FILES['government_id']['name']}'";
		}
		
		if(empty($id)){
			$save = $this->db->query("INSERT INTO tenants set $data");
			if($save) {
				$amount = $_POST['downpayment'];
				$tenant_id = $this->db->query("SELECT LAST_INSERT_ID();")->fetch_row()[0];
				$this->save_payment($amount, $tenant_id, $date_in, "Rent", 2, $date_in);
				$url = "http://localhost/hs/setup-account.php?email={$email}";
				sendEmail($email, "You can set your account here: {$url}", "House Rental");
				require_once 'createChatXML.php';
				return 1;
			}
		}else{
			$save = $this->db->query("UPDATE tenants set $data where id = $id");
		}
		
		if($save)
			return 1;
		else 
			return 0;
	}
	function delete_tenant(){
		extract($_POST);
		$delete = $this->db->query("UPDATE tenants set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_tdetails(){
		extract($_POST);
		$data =array();
		$tenants =$this->db->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.id = {$id} ");
		foreach($tenants->fetch_array() as $k => $v){
			if(!is_numeric($k)){
				$$k = $v;
			}
		}
		$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($date_in." 23:59:59"));
		$months = floor(($months) / (30*60*60*24));
		$data['months'] = $months;
		$payable= abs($price * $months);
		$data['payable'] = number_format($payable,2);
		$paid = $this->db->query("SELECT SUM(amount) as paid FROM payments where id != '$pid' and tenant_id =".$id);
		$last_payment = $this->db->query("SELECT * FROM payments where id != '$pid' and tenant_id =".$id." order by unix_timestamp(date_created) desc limit 1");
		$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
		$data['paid'] = number_format($paid,2);
		$data['last_payment'] = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
		$data['outstanding'] = number_format($payable - $paid,2);
		$data['price'] = number_format($price,2);
		$data['name'] = ucwords($name);
		$data['rent_started'] = date('M d, Y',strtotime($date_in));

		return json_encode($data);
	}
	
	function save_payment($amount, $tenantId, $due_date, $payment_type, $status, $payment_date = NULL){
		$statement = $this->db->prepare("INSERT INTO payments (tenant_id, amount, due_date, payment_type, payment_date, status) 
		VALUE (?, ?, ?, ?, ?, ?)");
		$statement->bind_param("idsssi", $tenantId, $amount, $due_date, $payment_type, $payment_date, $status);
		if($statement->execute())
			return 1;
		else
			return 0;
	}
	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete){
			return 1;
		}
	}
}
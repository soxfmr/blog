<?php
	session_start();
	include '../Blog/config.php';
	include '../Blog/module/Security.php';
	include '../Blog/controller/UserManager.php';

	if(!empty($_GET['act']) && $_GET['act'] == "login" && !empty($_POST['password'])) {
		$pwd = Security::validate($_POST['password']);

		$sqlMgr = new SQLManager($DB_HOST, $DB_PORT, $DB_USER, $DB_PWD, $DB_DBNAME);
		if($sqlMgr->is_connect()) {
			$userMgr = new UserManager($sqlMgr);
			if($userMgr->login($pwd))
				echo "<html><head><meta ></head></html>";
			else
				echo "login Failed!";
		}
	}else {

	}
?>
<?php
	class UserManager extends Manager
	{
		const TABLE_NAME = "root";
		const LOGIN_SESSION_NAME = "auth";

		function __construct($sqlMgr) {
			parent::__construct($sqlMgr, self::TABLE_NAME);
		}

		function login($pwd) {
			$Ret = false;
			if($this->is_login()) {
				$Ret = true;
			}else {
				$pwd = MD5($pwd);
				$count = $this->tableQuery->record_count_condition_where("password", $pwd);
				if($count > 0) {
					// 再进行一次md5并存储到会话中
					$this->set_login_session(md5($pwd));
				}
				$Ret = true;
			}
			return $Ret;
		}

		function logout() {
			$this->set_login_session();
		}

		function is_login() {
			return (!empty($_SESSION[self::LOGIN_SESSION_NAME])) ? true : false;
		}

		function set_login_session($tag) {
			$_SESSION[self::LOGIN_SESSION_NAME] = $tag;
		}

		function set_logout_session() {
			unset($_SESSION[self::LOGIN_SESSION_NAME]);
		}
	}

?>
<?php
	class Manager
	{
		protected $tableQuery = null;

		function __construct($sqlMgr, $tbName) {
			$this->tableQuery = $this->init($sqlMgr, $tbName);
		}

		// 这里可能需要改进
		private function init($sqlMgr, $tbName) {
			include '../Blog/module/TableQuery.php';
			$Ret = new TableQuery($sqlMgr, $tbName);
			return $Ret;
		}

		private function current_time() {
			return date("Y-m-d H:i:s a");
		}

		/**
			生成唯一标识，因为可能会对自增字段进行优化，用此识别文章、评论等关联状态
		*/
		private function fingerprint() {
			return md5(microtime());
		}

	}
?>
<?php
	include 'Manager.php';

	class ArticleManager extends Manager {
		const TABLE_NAME = "articles";

		function __construct($sqlMgr) {
			parent::__construct($sqlMgr, self::TABLE_NAME);
		}

		/**
			返回当前页面所有数据
		*/
		function pagedata($pageIndex, $maxCount) {
			$index = $pageIndex * $maxCount;
			$cols = array("_id", "title", "content", "visited", "praise");
			$result = $this->tableQuery->query_colums_limit($cols, $index, $maxCount);

			$data = array();
			while ($row = mysql_fetch_array($result)) {
				$ele = array();
				$ele['_id'] = $row['_id'];
				$ele['title'] = $row['title'];
				$ele['content'] = $row['content'];
				$ele['visited'] = $row['visited'];
				$ele['praise'] = $row['praise'];
				$data[] = $ele;
			}

			return $data;
		}

		/**
			返回总页面数
		*/
		function pagecount($maxCount) {
			$count = $this->tableQuery->record_count();

			$pageCount = floor($count / $maxCount);			
			$remain = $count % $maxCount;

			if($pageCount == 0) 
				$pageCount = 1;
			else 
				$pageCount = $pageCount + ($remain > 0 ? 1 : 0);

			return $pageCount;
		}

		function add($title, $content) {
			$createdTime = $this->current_time();
			$fp = $this->fingerprint();

			return $this->tableQuery->query_add(array("title", "content", "createdTime", "visied", "praise", "fingerprint"),
				array($title, $content, $createdTime, 0, 0, $fp));
		}

		function delete($articleId) {
			return $this->tableQuery->query_delete("_id", $articleId);
		}

		function modify($articleId, $title, $content) {
			$modifiedTime = $this->current_time();
			$fieldArray = array("title", "content", "modifiedTime");
			$valueArray = array($title, $content, $modifiedTime);
			return $this->tableQuery->query_modify("_id", $articleId, $fieldArray, $valueArray);
		}

		/**
			递增文章访问人数
		*/
		function inc_visited($articleId) {
			$reslut = $this->tableQuery->query_colums(array("visited"));
			$reslut = mysql_fetch_array($reslut);
			$visited = intval($reslut["visited"]) + 1;
			return $this->tableQuery->query_modify("_id", $articleId, array("visited"), array($visited));
		}

		/**
			递增赞人数
		*/
		function inc_praise($articleId) {
			$reslut = $this->tableQuery->query_colums(array("praise"));
			$reslut = mysql_fetch_array($reslut);
			$praise = intval($reslut["praise"]) + 1;
			return $this->tableQuery->query_modify("_id", $articleId, array("praise"), array($praise));
		}

	}
?>
<?php
	/**
		[评论对应方式]
		1、文章 - 评论
			使用 parentFingerprint 匹配相应的文章
		2、评论 - 跟随评论
			使用 parentFingerprint 匹配相应的根评论
			根评论：	父指纹对应一篇文章
			跟随评论：	父指纹对应一个根评论
	*/

	include 'Manager.php';

	class CommentManager extends Manager
	{
		const TABLE_NAME = "comments";

		function __construct($sqlMgr) {
			parent::__construct($sqlMgr, self::TABLE_NAME);
		}

		function commentdata($parentFingerprint) {
			return $this->tableQuery->query_all_condition_where("parentFingerprint", $parentFingerprint);
		}

		function commentcount($parentFingerprint) {
			return $this->tableQuery->record_count_condition_where("parentFingerprint", $parentFingerprint);
		}

		function add($content, $parentFingerprint) {
			$createdTime = $this->current_time();
			$fp = $this->fingerprint();

			return $this->tableQuery->query_add(
				array("content", "createdTime", "parentFingerprint", "fingerprint"), 
				array($content, $createdTime, $parentFingerprint, $fp));
		}

		function delete($commentId) {
			return $this->tableQuery->query_delete("_id", $commentId);
		}
	}
?>
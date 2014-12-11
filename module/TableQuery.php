<?php
	class TableQuery
	{
		private $sqlMgr = null;
		private $tbName = null;

		function __construct($sqlMgr, $tbName) {
			$this->sqlMgr = $sqlMgr;
			$this->tbName = $tbName;
		}

		/////////////////////////////////////////////// start 数据查询 ///////////////////////////////////////////////

		/**
			返回表中所有字段数据
		*/
		function query_all($extra = "")
		{
			$sqlStr = trim("select * from $this->tbName $extra");
			return $this->_query($sqlStr);
		}

		/**
			返回指定字段数据
		*/
		function query_columns($columnArray, $extra = "") {
			$Ret = false;
			if($columnArray) {
				$columns = $this->parse($columnArray);

				$sqlStr = trim("select $columns from $this->tbName $extra");
				$Ret = $this->_query($sqlStr);
			}	
			return $Ret;		
		}

		/**
			返回指定数量数据
		*/
		function query_limit($index, $count) {
			return $this->query_all("limit $index,$count");
		}

		/**
			返回指定字段、数量数据
		*/
		function query_colums_limit($columnArray, $index, $count) {
			return $this->query_columns($columnArray, "limit $index,$count");
		}

		/**
			返回表中所有记录数
		*/
		function record_count($extra = "") {
			$sqlStr = trim("select count(*) from $this->tbName $extra");
			$result = mysql_fetch_array($this->_query($sqlStr));
			return $result[0];
		}

		/**
			返回表中符合条件记录数，只能匹配个条件 PS:有点破坏封装性了 :(
		*/
		function query_condition_where($wherekey, $wherevalue) {
			return $this->query_all("where $wherekey = '$wherevalue'");
		}

		function query_columns_condition_where($columnArray, $wherekey, $wherevalue) {
			return $this->query_colums($columnArray, "where $wherekey = '$wherevalue'");
		}

		function record_count_condition_where($wherekey, $wherevalue) {
			$extra = "where $wherekey = '$wherevalue'";
			return $this->record_count($extra);
		}

		/////////////////////////////////////////////// 数据查询 end ///////////////////////////////////////////////


		/////////////////////////////////////////////// start 数据修改 ///////////////////////////////////////////////

		/**
			增加一条新纪录，将字段和值封装为数组
		*/
		function query_add($fieldArray, $valueArray) {
			$Ret = false;

			if($fieldArray && $valueArray) {
				$fields = $this->parse($fieldArray);
				$values = $this->parse($valueArray, true);

				$Ret = $this->_query("insert into $this->tbName ($fields) values ($values)");
			}

			return $Ret;
		}

		function query_modify($field, $recordId, $fieldArray, $valueArray) {
			$keyvalues = fill_key_value($fieldArray, $valueArray, true);
			return $this->_query("update $this->tbName set $keyvalues where $field = '$recordId'");
		}

		function query_delete($field, $recordId) {
			return $this->_query("delete from $this->tbName where $field = '$recordId'");
		}

		/////////////////////////////////////////////// end 数据修改 ///////////////////////////////////////////////


		/////////////////////////////////////////////// start 相关函数 ///////////////////////////////////////////////

		/**
			自定义数据库语句
		*/
		function query($sqlStr) {
			return $this->_query($sqlStr);
		}

		/**
			避免在其他函数中使用冗长的指针引用语句
		*/
		private function _query($sqlStr) {
			return $this->sqlMgr->query($sqlStr);
		}

		/**
			将数组解析为逗号分隔的字符串
			$sqlvalue 判断是否为插入数据时的 values 字段，为 true 时每个值自动添加单引号
		*/
		private function parse($srcArray, $sqlvalue = false) {
			$interStr = "";
			for($i = 0, $size = count($srcArray) - 1; $i <= $size; $i++) {
				// 牺牲性能减少代码
				if($sqlvalue) $interStr .= "'";
				$interStr .= $srcArray[$i];
				if($sqlvalue) $interStr .= "'";

				if($i < $size) $interStr .= ",";
			}
			return $interStr;
		}

		/**
			组装键值对，返回类似 key = value 并用逗号分隔的字符串 
			PS:本来用 parse() 进行组装，太麻烦直接修改 parse() 来的快 :P
		*/
		private function fill_key_value($keyArray, $valueArray, $sqlvalue = false) {
			$keyvalues = "";
			for($i = 0, $size = count($keyArray) - 1; $i <= $size; $i++) {
				$keyvalues .= $keyArray[$i];

				// 牺牲性能减少代码
				if($sqlvalue) $keyvalues .= "'";
				$keyvalues .= $valueArray[$i];
				if($sqlvalue) $keyvalues .= "'";

				if($i < $size) $keyvalues .= ",";
			}
			return $keyvalues;
		}

		/////////////////////////////////////////////// end 相关函数 ///////////////////////////////////////////////
	}
?>
<?php
	include 'config.php';
	include 'module/SQLManager.php';
	include 'controller/ArticleManager.php';

	$sqlMgr = new SQLManager($DB_HOST, $DB_PORT, $DB_USER, $DB_PWD, $DB_DBNAME);
	if($sqlMgr->is_connect()) {
		$articleMgr = new ArticleManager($sqlMgr);
		$data = $articleMgr->pagedata(0, 2);

		echo "<table>";
		for($i = 0, $size = count($data); $i < $size; $i++) {
			echo "<tr>";
			echo "<td>";
			echo $data[$i]['_id'];
			echo "</td>";
			echo "<td>";
			echo $data[$i]['title'];
			echo "</td>";
			echo "<td>";
			echo $data[$i]['content'];
			echo "</td>";
			echo "<td>";
			echo $data[$i]['visited'];
			echo "</td>";
			echo "<td>";
			echo $data[$i]['praise'];
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";

		echo "<br />count: " . $articleMgr->pagecount(2);

		$sqlMgr->close();
	}else {
		echo "Cannot open the database!";
	}
?>
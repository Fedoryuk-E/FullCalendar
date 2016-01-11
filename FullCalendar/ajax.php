<?php
$db = mysql_pconnect('sql113.byethost9.com', 'b9_17158999', 'Qwerty');
mysql_select_db('b9_17158999_fullcalendar');

mysql_query("SET NAMES 'utf8'");
$start = $_POST['start'];
$end = $_POST['end'];
$type = $_POST['type'];
$op = $_POST['op'];
$id = $_POST['id'];
switch ($op) {
	case 'add':
		$sql = 'INSERT INTO events (
			start, 
			end, 
			type) 
			VALUES 
			("' . date("Y-m-d H:i:s", strtotime($start)) . '",
			"' . date("Y-m-d H:i:s", strtotime($end)) . '", 
			"' . $type . '")';
		if (mysql_query($sql)) {
			echo mysql_insert_id();
		}
		break;
	case 'edit':
		$sql = 'UPDATE events SET 	start = "' . date("Y-m-d H:i:s", strtotime($start)) . '",
									end	  = "' . date("Y-m-d H:i:s", strtotime($end)) . '",
									type  = "' . $type . '"
									WHERE id = "' . $id . '"';
		if (mysql_query($sql)) {
			echo $id;
		}
		break;
	case 'source':
		$sql = 'SELECT * FROM events';
		$result = mysql_query($sql);
		$json = Array();
		while ($row = mysql_fetch_assoc($result)) {
		    $json[] = array(
				'id' => $row['id'],
				'title' => $row['type'],
				'start' => $row['start'],
				'end' => $row['end'],
				'allDay' => false
			);
		}
		echo json_encode($json);
		break;
	case 'delete':
		$sql = 'DELETE FROM events WHERE id = "' . $id . '"';
		if (mysql_query($sql)) {
			echo $id;
		}
		break;
}

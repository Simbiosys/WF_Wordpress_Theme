<?php
require_once('utils.php');

class BoardsModel {
	function BoardsModel(){
	}

	function get($labels = NULL) {
   	   	$data = Array();

   	   	$team = getTeamInfo('board_member');
   	   	$data["team-1"] = array_slice($team, 0, 24);
   	   	$data["team-2"] = array();

   	   	if (count($team) > 24)
   	   		$data["team-2"] = array_slice($team, 24);

		return $data;
	}
}
?>

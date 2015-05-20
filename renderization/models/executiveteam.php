<?php
require_once('utils.php');

class ExecutiveteamModel {
	function ExecutiveteamModel(){
	}

	function get($labels = NULL) {
   	   	$data = Array();

   	   	$team = getTeamInfo('team_member');
   	   	$data["team-1"] = array_slice($team, 0, 36);
   	   	$data["team-2"] = array();

   	   	if (count($team) > 24)
   	   		$data["team-2"] = array_slice($team, 36);

		return $data;
	}
}
?>

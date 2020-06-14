<?php

class Admin extends CI_Controller {

	public function get_users() {
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$users = $this->db->query("SELECT * FROM `user` ORDER BY `first_name` ASC LIMIT " . $start . "," . $length);
		for ($i=0; $i<sizeof($users); $i++) {
		}
		echo json_encode($users);
	}
}

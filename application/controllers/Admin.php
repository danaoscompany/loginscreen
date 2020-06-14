<?php

class Admin extends CI_Controller {

	public function login() {
		$phone = $this->input->post('phone');
		$password = $this->input->post('password');
		$expiry = $this->input->post('expiry');
		$users = $this->db->query("SELECT * FROM `user` WHERE `phone`='" . $phone . "' AND `password`='" . $password . "'")->result_array();
		if (sizeof($users) > 0) {
			$user = $users[0];
			echo json_encode(array(
				'response_code' => 1,
				'user_id' => intval($user['id'])
			));
		} else {
			echo json_encode(array(
				'response_code' => -2
			));
		}
	}

	public function get_users() {
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$users = $this->db->query("SELECT * FROM `user` ORDER BY `first_name` ASC LIMIT " . $start . "," . $length);
		for ($i=0; $i<sizeof($users); $i++) {
		}
		echo json_encode($users);
	}
}

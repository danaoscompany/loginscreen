<?php

class Admin extends CI_Controller {

	public function login() {
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$expiry = $this->input->post('expiry');
		
		$superAdmins = $this->db->query("SELECT * FROM `superadmin` WHERE `email`='" . $email . "' AND `password`='" . $password . "'")->result_array();
		if (sizeof($superAdmins) > 0) {
			$superAdmin = $superAdmins[0];
			echo json_encode(array(
				'response_code' => 1,
				'user_id' => intval($superAdmin['id']),
				'super_admin' => 1
			));
		} else {
			$admins = $this->db->query("SELECT * FROM `admin` WHERE `email`='" . $email . "' AND `password`='" . $password . "'")->result_array();
			if (sizeof($admins) > 0) {
				$admin = $admins[0];
				echo json_encode(array(
					'response_code' => 1,
					'user_id' => intval($admin['id']),
					'super_admin' => 0
				));
			} else {
				echo json_encode(array(
					'response_code' => -2
				));
			}
		}
	}
	
	public function add_user() {
		$adminID = intval($this->db->post('admin_id'));
		$phone = $this->db->post('phone');
		$password = $this->db->post('password');
		$androidID = $this->db->post('android_id');
		$expiry = $this->db->post('expiry');
		echo "SELECT * FROM `admin` WHERE `id`=" . $adminID;
		$maxUsers = intval($this->db->query("SELECT * FROM `admin` WHERE `id`=" . $adminID)->row_array()['max_users']);
		echo "Max users: " . $maxUsers;
		if ($maxUsers <= 0) {
			echo json_encode(array('response_code' => -1, 'max_users' => $maxUsers));
			return;
		}
		if ($maxUsers > 0) {
			$this->db->query("INSERT INTO `user` (`admin_id`, `phone`, `password`, `android_id`, `expiry`) VALUES (" . $adminID . ", '" . $phone . "', '" . $password . "', '" . $androidID . "', '" . $expiry . "')");
			$userID = intval($this->db->insert_id());
			$maxUsers--;
			$this->db->query("UPDATE `admin` SET `max_users`=" . $maxUsers . " WHERE `id`=" . $adminID);
			echo json_encode(array('response_code' => 1, 'user_id' => $userID, 'max_users' => $maxUsers));
		}
	}

	public function get_users() {
		$adminID = intval($this->input->post('admin_id'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$users = $this->db->query("SELECT * FROM `user` WHERE `admin_id`=" . $adminID . " ORDER BY `first_name` ASC LIMIT " . $start . "," . $length)->result_array();
		for ($i=0; $i<sizeof($users); $i++) {
			$row = $this->db->query("SELECT * FROM `user` WHERE `id`=" . $users[$i]['id'])->row_array();
			$users[$i]['admin_name'] = $row['first_name'] . " " . $row['last_name'];
		}
		echo json_encode($users);
	}
	
	public function get_all_users() {
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$users = $this->db->query("SELECT * FROM `user` ORDER BY `first_name` ASC LIMIT " . $start . "," . $length)->result_array();
		for ($i=0; $i<sizeof($users); $i++) {
			$row = $this->db->query("SELECT * FROM `user` WHERE `id`=" . $users[$i]['id'])->row_array();
			$users[$i]['admin_name'] = $row['first_name'] . " " . $row['last_name'];
		}
		echo json_encode($users);
	}

	public function get_admins() {
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$admins = $this->db->query("SELECT * FROM `admin` ORDER BY `email` ASC LIMIT " . $start . "," . $length)->result_array();
		for ($i=0; $i<sizeof($admins); $i++) {
		}
		echo json_encode($admins);
	}
}

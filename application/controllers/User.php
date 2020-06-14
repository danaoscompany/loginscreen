<?php

class User extends CI_Controller {

	public function login() {
		$phone = $this->input->post('phone');
		$password = $this->input->post('password');
		$date = $this->input->post('date');
		$androidID = $this->input->post('android_id');
		$users = $this->db->query("SELECT * FROM `user` WHERE `phone`='" . $phone . "' AND `password`='" . $password . "'")->result_array();
		if (sizeof($users) > 0) {
			$user = $users[0];
			/*if ($user['android_id'] == NULL || $user['android_id'] == null || $user['android_id'] == '') {
				$this->db->where('id', intval($user['id']));
				$this->db->update('user', array(
					'android_id' => $androidID
				));
				echo json_encode(array(
					'response_code' => 1,
					'user_id' => intval($user['id'])
				));
			} else {*/
				if ($user['android_id'] != $androidID) {
					echo json_encode(array(
						'response_code' => -1
					));
				} else {
					$expiry = $user['expiry'];
					if (strtotime($date) >= strtotime($expiry)) {
						echo json_encode(array(
							'response_code' => -3,
							'user_id' => intval($user['id'])
						));
					} else {
						echo json_encode(array(
							'response_code' => 1,
							'user_id' => intval($user['id'])
						));
					}
				}
			/*}*/
		} else {
			echo json_encode(array(
				'response_code' => -2
			));
		}
		header("Cache-Control: no-store, no-cache, must-revalidate,\"public, no-transform, must-revalidate\"");
	}

	public function login_with_google() {
		$phone = $this->input->post('phone');
		$googleID = $this->input->post('google_id');
		$androidID = $this->input->post('android_id');
		$expiry = $this->input->post('expiry');
		$users = $this->db->query("SELECT * FROM `user` WHERE `google_id`='" . $googleID . "'")->result_array();
		if (sizeof($users) > 0) {
			$user = $users[0];
			$userID = intval($user['id']);
			$this->db->query("UPDATE `user` SET `android_id`='" . $androidID . "', `phone`='" . $phone . "', `expiry`='" . $expiry . "' WHERE `id`=" . $userID);
			echo json_encode(array(
				'response_code' => 1,
				'user_id' => $userID,
				'profile_completed' => intval($user['profile_completed']),
				'user' => $user
			));
		} else {
			$this->db->query("INSERT INTO `user` (`android_id`, `google_id`, `phone`, `expiry`) VALUES ('" . $androidID . "', '" . $googleID . "', '" . $phone . "', '" . $expiry . "')");
			$userID = intval($this->db->insert_id());
			echo json_encode(array(
				'response_code' => -1,
				'user_id' => $userID
			));
		}
	}
	
	public function complete_data() {
		$userID = intval($this->input->post('id'));
		$firstName = $this->input->post('first_name');
		$lastName = $this->input->post('last_name');
		$email = $this->input->post('email');
		$this->db->where('id', $userID);
		$this->db->update('user', array(
			'first_name' => $firstName,
			'last_name' => $lastName,
			'email' => $email,
			'profile_completed' => 1
		));
	}
	
	public function is_profile_completed() {
		$userID = intval($this->input->post('id'));
		$users = $this->db->query("SELECT * FROM `user` WHERE `id`=" . $userID)->result_array();
		if (sizeof($users) > 0) {
			$user = $users[0];
			echo json_encode(array(
				'profile_completed' => intval($user['profile_completed']),
				'user' => $user
			));
		} else {
			echo json_encode(array(
				'profile_completed' => 0
			));
		}
	}
}

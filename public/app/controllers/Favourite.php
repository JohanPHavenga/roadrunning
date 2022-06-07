<?php
class Favourite extends Frontend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('favourite_model');
		$this->load->model('user_model');
	}

	public function add_fav()
	{		
		$data = array(
			'user_id' => $this->input->post('user_id'),
			'edition_id' => $this->input->post('edition_id')
		);
		// set favourite		
		$fav_result = $this->favourite_model->save_data($data);
		// set subsciption
		$user_info = $this->user_model->get_user_name($data['user_id']);
		$success = $this->subscribe_user($user_info, 'edition', $data['edition_id']);

		if ($fav_result) {
			echo  1;
		} else {
			echo  0;
		}
	}

	public function remove_fav()
	{
        $this->load->model('usersubscription_model');
		$data = array(
			'user_id' => $this->input->post('user_id'),
			'edition_id' => $this->input->post('edition_id')
		);
		// remove favourite
		$result = $this->favourite_model->delete_data($data);
		// remove subscription
		$user_data = $this->user_model->get_user_detail($data['user_id']);
        $remove = $this->usersubscription_model->remove_usersubscription($data['user_id'], 'edition', $data['edition_id']);
		if ($result) {
			echo  1;
		} else {
			echo  0;
		}
	}
}

<?php
class Favourite extends Frontend_Controller
{
	public function add_fav()
	{
		$this->load->model('favourite_model');

		$data = array(
			'user_id' => $this->input->post('user_id'),
			'edition_id' => $this->input->post('edition_id')
		);

		$this->load->model('favourite_model');
		$result = $this->favourite_model->save_data($data);
		if ($result) {
			echo  1;
		} else {
			echo  0;
		}
	}

	public function remove_fav()
	{
		$this->load->model('favourite_model');

		$data = array(
			'user_id' => $this->input->post('user_id'),
			'edition_id' => $this->input->post('edition_id')
		);

		$this->load->model('favourite_model');
		$result = $this->favourite_model->delete_data($data);
		if ($result) {
			echo  1;
		} else {
			echo  0;
		}
	}
}

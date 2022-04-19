<?php
class Favourite_model extends Frontend_model
{
	public function get_favourite_edition($user_id, $edition_id)
	{
		$query = $this->db->get_where('favourite', array('user_id' => $user_id, 'edition_id' => $edition_id));
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_favourite_list($user_id)
	{
		$query = $this->db->get_where('favourite', array('user_id' => $user_id));
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $fav) {
				$edition_arr[]=$fav['edition_id'];
			}
			return $edition_arr;
			//			return $query->result_array();
		} else {
			return false;
		}
	}

	public function save_data($data)
	{
		if ($this->db->insert('favourite', $data)) {
			return 1;
		} else {
			return 0;
		}
	}

	public function delete_data($data)
	{
		if ($this->db->delete('favourite', $data)) {
			return 1;
		} else {
			return 0;
		}
	}
}

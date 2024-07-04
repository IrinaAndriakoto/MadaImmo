<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_md extends CI_Model {

    public function get_admin($nom, $pwd) {
        $this->db->where('role', $nom);
        $this->db->where('pwd', $pwd);
        $query = $this->db->get('profils');

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function auth($pwd){
        $this->db->where('pwd', $pwd);
        $query = $this->db->get('profils');

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }
}

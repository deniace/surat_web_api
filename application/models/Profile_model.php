<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Profile_model extends CI_Model
{
    // mengambil semua data
    public function get_all()
    {
        $this->db->select('*');
        $this->db->from('tb_user');
        $this->db->join('tb_jabatan', 'tb_user.id_jabatan = tb_jabatan.id_jabatan');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * mengambil semua data user berdasarkan id user
     * @param id_user
     */
    public function get_by_id($id_user)
    {
        $this->db->select('*');
        $this->db->from('tb_user');
        $this->db->join('tb_jabatan', 'tb_user.id_jabatan = tb_jabatan.id_jabatan');
        $this->db->where('id_user', $id_user);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * mendapatkan data user yang sudah terpaging
     * @param limit = jumlah data per page
     * @param offset = jumlah data yang dilewati
     */
    public function get_page($limit = null, $offset = null, $where = "")
    {
        if ($limit !== null || $offset !== null) {
            $this->db->limit($limit, $offset);
            $this->db->where($where);
            $this->db->from('tb_user');
            $this->db->join('tb_jabatan', 'tb_user.id_jabatan = tb_jabatan.id_jabatan');
            $this->db->order_by('id_user', 'DESC');
            $query = $this->db->get();

            $this->db->where($where);
            $total_data = $this->db->count_all_results('tb_user');

            $data = array(
                "total_data" => $total_data,
                "data" => $query->result()
            );

            return $data;
        } else {
            $response = false;
        }
        return $response;
    }

    /**
     * mengupdate user
     * @param data = data user yang akan di update
     */
    public function update($data)
    {
        $update = $this->db->update('tb_user', $data, ['id_user' => $data['id_user']]);
        return $update;
    }
}

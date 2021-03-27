<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Surat_model extends CI_Model
{
    // mengambil semua data
    public function get_all()
    {
        $this->db->select('*');
        $this->db->from('tb_surat_pengantar');
        $this->db->join('tb_user', 'tb_surat_pengantar.id_user = tb_user.id_user');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * mengambil semua data surat berdasarkan id surat
     * @param id_surat
     */
    public function get_by_id($id_surat)
    {
        $this->db->select('*');
        $this->db->from('tb_surat_pengantar');
        $this->db->join('tb_user', 'tb_surat_pengantar.id_user = tb_user.id_user');
        $this->db->where('id_surat', $id_surat);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * mengambil semua data surat berdasarkan id user
     * @param id_user
     */
    public function get_by_id_user($id_user)
    {
        $this->db->select('*');
        $this->db->from('tb_surat_pengantar');
        $this->db->join('tb_user', 'tb_surat_pengantar.id_user = tb_user.id_user');
        $this->db->where('tb_surat_pengantar.id_user', $id_user);
        $this->db->order_by('tb_surat_pengantar.id_surat', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * mendapatkan data surat yang sudah terpaging
     * @param limit = jumlah data per page
     * @param offset = jumlah data yang dilewati
     */
    public function get_page($limit = null, $offset = null, $where = "")
    {
        if ($limit !== null || $offset !== null) {
            $this->db->limit($limit, $offset);
            $this->db->where($where);
            $this->db->from('tb_surat_pengantar');
            $this->db->join('tb_user', 'tb_surat_pengantar.id_user = tb_user.id_user');
            $this->db->order_by('id_surat', 'DESC');
            $query = $this->db->get();

            $this->db->where($where);
            $this->db->join('tb_user', 'tb_surat_pengantar.id_user = tb_user.id_user');
            $total_data = $this->db->count_all_results('tb_surat_pengantar');

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
     * menginsert surat
     * @param data = data surat yang akan di insert
     */
    public function insert($data)
    {
        $insert = $this->db->insert('tb_surat_pengantar', $data);
        return $insert;
    }

    /**
     * mengupdate surat
     * @param data = data surat yang akan di update
     */
    public function update($data)
    {
        $update = $this->db->update('tb_surat_pengantar', $data, ['id_surat' => $data['id_surat']]);
        return $update;
    }

    /**
     * mengupdate surat
     * @param data = data surat yang akan di update
     */
    public function delete_by_id_user($id_user)
    {
        $delete = $this->db->delete('tb_surat_pengantar', ['id_user' => $id_user]);
        return $delete;
    }
}

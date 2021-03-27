<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Jabatan_model extends CI_Model
{
    // mengambil semua data jabatan
    public function getAll()
    {
        $query = $this->db->get('tb_jabatan');
        return $query->result();
    }

    /**
     * mengambil semua data jabatan berdasarkan id jabatan
     * @param id_jabatan
     */
    public function getById($id)
    {
        $query = $this->db->get_where('tb_jabatan', ['id' => $id]);
        // return $query->result();
        return $query->row();
    }

    /**
     * menginsert / input data jabatan ke database
     * @param data = data jabatan yang akan di insert
     */
    public function insert($data)
    {
        $insert = $this->db->insert('tb_jabatan', $data);
        return $insert;
    }

    /**
     * mengupdate jabatan
     * @param data = data jabatan yang akan di update
     */
    public function update($data)
    {
        $update = $this->db->update('tb_jabatan', $data, ['id' => $data['id']]);
        return $update;
    }

    /**
     * menghapus jabatan berdasarkan id jabatan
     * @param id_jabatan = id jabatan yang akan di hapus
     */
    public function delete($id)
    {
        $delete = $this->db->delete('tb_jabatan', ['id' => $id]);
        return $delete;
    }
}

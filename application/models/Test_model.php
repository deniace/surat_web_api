<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Test_model extends CI_Model
{
    public function getAllTest()
    {
        $query = $this->db->get('test01');
        return $query->result();
    }

    public function getTest($id)
    {
        $query = $this->db->get_where('test01', ['id' => $id]);
        // return $query->result();
        return $query->row();
    }

    public function insert($data)
    {
        $insert = $this->db->insert('test01', $data);
        return $insert;
    }

    public function update($data)
    {
        $update = $this->db->update('test01', $data, ['id' => $data['id']]);
        return $update;
    }

    public function delete($id)
    {
        $delete = $this->db->delete('test01', ['id' => $id]);
        return $delete;
    }
}

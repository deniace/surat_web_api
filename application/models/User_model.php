<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends CI_Model
{
    // mengambil semua data tamu
    public function login($email, $password)
    {
        $query_login = $this->db->get_where('tb_login', ["email" => $email]);
        if ($query_login->num_rows() == 1) {
            // ada di database
            // mengambil data
            $row_login = $query_login->row();

            if (password_verify($password, $row_login->password)) {
                // password oke
                // mengambil data join dari tb_login_pegawai, tb_pegawai, dan tb_jabatan
                $this->db->select("tb_login_user.id_user, no_hp, nama_user, tb_jabatan.id_jabatan, nama_jabatan");
                $this->db->from('tb_login_user');
                $this->db->join('tb_user', 'tb_login_user.id_user = tb_user.id_user');
                $this->db->join('tb_jabatan', 'tb_user.id_jabatan = tb_jabatan.id_jabatan');
                $this->db->where('id_login', $row_login->id_login);
                $query = $this->db->get();

                $result = array(
                    "status" => true,
                    "data" => $query->row()
                );
            } else {
                // password tidak oke
                $result = array(
                    "status" => false
                );
            }
        } else {
            // tidak ada di database
            $result = array(
                "status" => false
            );
        }
        return $result;
    }

    /**
     * @param = data yang akan di register 
     */
    public function register($data)
    {
        $data_login = array("email" => $data["email"], "password" => $data["password"]);
        // insert login ambil id nya
        $insert_login = $this->db->insert('tb_login', $data_login);
        $id_login = $this->db->insert_id();

        // insert user ambil id nya
        $insert_user = $this->db->insert('tb_user', [
            "nama_user" => $data["nama_user"],
            "no_hp" => $data["no_hp"],
            "alamat" => $data["alamat"],
            "id_jabatan" => $data["id_jabatan"],
            "rt" => $data["rt"],
            "rw" => $data["rw"],
            "jenis_kelamin" => $data["jenis_kelamin"],
            "agama" => $data["agama"],
            "tempat_lahir" => $data["tempat_lahir"],
            "tanggal_lahir" => $data["tanggal_lahir"],
            "status_perkawinan" => $data["status_perkawinan"],
            "pekerjaan" => $data["pekerjaan"]
        ]);
        $id_user = $this->db->insert_id();
        // insert login uaer
        $insert = $this->db->insert('tb_login_user', ["id_login" => $id_login, "id_user" => $id_user]);
        $data = array("id_user" => $id_user, "insert" => $insert);
        return $data;
    }

    /**
     * mengambil data user berdasarkan id user
     * @param id_user
     */
    public function get_user_by_id($id_user)
    {
        $query = $this->db->get_where('tb_user', ['id_user' => $id_user]);
        return $query->row();
    }

    /**
     * mengambil profile user berdasarkan id user
     * @param id_user
     */
    public function getProfileUser($id_user)
    {
        $this->db->select("id_user, no_hp, nama_user, tb_user.id_jabatan, nama_jabatan");
        $this->db->from('tb_user');
        $this->db->join('tb_jabatan', 'tb_user.id_jabatan = tb_jabatan.id_jabatan');
        $this->db->where('id_user', $id_user);
        $query = $this->db->get();

        return $query->row();
    }

    public function cek_email($email)
    {
        $query_email = $this->db->get_where('tb_login', ["email" => $email]);
        if ($query_email->num_rows() > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * mengecek ketua rt udah ada apa belum
     * @return true jika sudah sudah ada
     * @return false jika tidak ada ketua rt
     */
    public function cek_rt($rt, $rw)
    {
        $query_rt = $this->db->get_where("tb_user", ["id_jabatan" => 2, "rt" => $rt, "rw" => $rw]);
        if ($query_rt->num_rows() > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * mengecek ketua rt udah ada apa belum
     * @return true jika sudah sudah ada
     * @return false jika tidak ada ketua rt
     */
    public function cek_rw($rw)
    {
        $query_rt = $this->db->get_where("tb_user", ["id_jabatan" => 3, "rw" => $rw]);
        if ($query_rt->num_rows() > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * mendapatkan data dari tabel login user
     * @param : id_user
     */
    public function get_login_user($id_user)
    {
        $query = $this->db->get_where("tb_login_user", ["id_user" => $id_user]);
        return $query->row();
    }

    /**
     * menghapus data login_user dari tabel login_user
     * @param : id_login_user
     */
    public function delete_login_user($id_login_user)
    {
        $delete = $this->db->delete('tb_login_user', ['id_login_user' => $id_login_user]);
        return $delete;
    }

    /**
     * menghapus data login dari tabel login
     * @param : id_login
     */
    public function delete_login($id_login)
    {
        $delete = $this->db->delete('tb_login', ['id_login' => $id_login]);
        return $delete;
    }

    /**
     * menghapus data user dari tabel user
     * @param : id_user
     */
    public function delete_user($id_user)
    {
        $delete = $this->db->delete('tb_user', ['id_user' => $id_user]);
        return $delete;
    }
}

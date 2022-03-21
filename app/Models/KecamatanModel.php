<?php

namespace App\Models;

class KecamatanModel extends \App\Models\BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function getKecamatan($where) {
        $sql = 'SELECT * FROM wil_kec' . $where;
        $result = $this->db->query($sql)->getResultArray();
        return $result;
    }

    public function getKecamatanById($id) {
        $sql = 'SELECT * FROM wil_kec WHERE id = ?';
        $result = $this->db->query($sql, $id)->getRowArray();
        return $result;
    }

    public function saveData($id) {
        $data_db['nama'] = $_POST['nama_produk'];
        $data_db['deskripsi_produk'] = $_POST['deskripsi_produk'];
        $data_db['id_user_input'] = $this->session->get('user')['id_user'];
        $id_produk = $id;

        $builder = $this->db->table('Provinsi');
        if (empty($id)) {
            $builder->insert($data_db);
            $id_produk = $this->db->insertID();
        } else {
            $builder->update($data_db, ['id' => $_POST['id']]);
        }

        return ['query' => $this->db->error(), 'id' => $id_produk];
    }

    public function deleteProvinsiById($id) {
        $delete = $this->db->table('Provinsi')->delete(['id' => $id]);
        // $delete = true;
        return $delete;
    }

}

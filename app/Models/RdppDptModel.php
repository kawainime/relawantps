<?php

namespace App\Models;

class RdppDptModel extends \App\Models\BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function getRdppDpt($idPro, $idKab, $where) {
        $sql = 'SELECT * FROM rdpp_dpt_' . $idPro . '_' . $idKab . ' ' . $where;
//        print_r($sql); exit;
        $result = $this->db->query($sql)->getResultArray();
        return $result;
    }

    public function getTpsByKelurahan($idPro, $idKab, $idKec = null, $idKel) {
        if ($idKel) {
            $where = '';
            $where .= $idKec ? "idKec = $idKec and " : '';
            $where .= "idKel = $idKel";
        } else {
            $where = 'false';
        }

        $sql = 'SELECT distinct idKec, idKel, noTps, namaTps FROM rdpp_dpt_' . $idPro . '_' . $idKab . ' where ' . $where;
//        print_r($sql); exit;
        $result = $this->db->query($sql)->getResultArray();
        return $result;
    }

    public function getRdppDptById($id) {
        $sql = 'SELECT * FROM rdpp_dpt_' . $idPro . '_' . $idKab . ' WHERE idDpt = ?';
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

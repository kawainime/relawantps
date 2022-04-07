<?php

namespace App\Models;

//use App\Models\PemilihModel;

class ProvinsiModel extends \App\Models\BaseModel {

    public function __construct() {
        parent::__construct();
        
//        $this->modPemilih = new PemilihModel;
    }

    public function getProvinsi($where) {
        $sql = 'SELECT * FROM wil_pro' . $where;
        $result = $this->dbpemilu->query($sql)->getResultArray();
        return $result;
    }
    
    public function getProvinsiPemilih() {
        $sql = 'SELECT id_prov FROM pemilih group by id_prov';
        $result = $this->db->query($sql)->getResultArray();
        $aProv = array();
        foreach ($result as $key => $value) {
            $aProv[] = $value['id_prov'];
        }
        $prov = implode(',', $aProv);
        
        $result = $this->getProvinsi(" where id in ($prov)");
        
        return $result;
    }

    public function getProvinsiById($id) {
        $sql = 'SELECT * FROM wil_pro WHERE id = ?';
        $result = $this->dbpemilu->query($sql, $id)->getRowArray();
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

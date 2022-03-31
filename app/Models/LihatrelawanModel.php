<?php

namespace App\Models;

class LihatrelawanModel extends \App\Models\BaseModel {

    public function __construct() {
        parent::__construct();
    }
    
    public function getTotalRelawanPerkecamatanPercaleg($caleg) {
        $sql = "select ur.id_kec, count(*) as total from user_relawan ur where ur.id_caleg = $caleg group by ur.id_kec";
        $result = $this->db->query($sql)->getResultArray();
        
        return $result;
    }
    
    public function getTotalTpsPerdapil($dapil, $jmlReal = 0) {
        $sql = "select count(*) as total_tps, round($jmlReal/count(*), 2)*100 as capaian from wil_tps wt where (wt.dapil like '%,$dapil]' or wt.dapil like '[$dapil,%' or wt.dapil like '%,$dapil,%')";
        $result = $this->dbpemilu->query($sql)->getRowArray();
                
        return $result;
    }
    
    public function getRelawanKabupatenPerCaleg($kab, $caleg, $dapil) {
        $sql = "select kec.id, kec.nama as wilayah, sum(if(ur.id_kec is not null, 1, 0)) as total from wil_kec kec left join (select vur.id_kec from v_user_relawan vur where vur.id_caleg = $caleg) ur on ur.id_kec = kec.id where (kec.dapil like '%,$dapil]' or dapil like '[$dapil,%' or dapil like '%,$dapil,%') and kec.kab_id = $kab group by kec.id, kec.nama";

        $result = $this->db->query($sql)->getResultArray();
        $relawan[] = $result;
        
        $jml_tps = 0;
        foreach ($result as $key => $value) {
            $jml_tps = $jml_tps + $value['total'];
        }
        
        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from wil_tps wt where wt.kab_id = $kab and (wt.dapil like '%,$dapil]' or wt.dapil like '[$dapil,%' or wt.dapil like '%,$dapil,%')";
        $result = $this->db->query($sql)->getRowArray();
        $relawan[] = $result;
                
        return $relawan;
    }
    
    public function getTotalRelawanPerkelurahanPercaleg($caleg, $kec) {
        $sql = "select ur.id_kel, count(*) as total from user_relawan ur where ur.id_caleg = $caleg and ur.id_kec = $kec group by ur.id_kel";
        $result = $this->db->query($sql)->getResultArray();
        
        return $result;
    }
    
    public function getTotalTpsPerkecamatan($kec, $jmlReal = 0) {
        $sql = "select count(*) as total_tps, round($jmlReal/count(*), 2)*100 as capaian from wil_tps wt where wt.kec_id = $kec";
        $result = $this->dbpemilu->query($sql)->getRowArray();
                
        return $result;
    }
    
    public function getRelawanKecamatanPerCaleg($kec, $caleg, $dapil) {
        $sql = "select kel.id, kel.nama as wilayah, sum(if(ur.id_kel is not null, 1, 0)) as total from wil_kel kel left join (select vur.id_kel from v_user_relawan vur where vur.id_caleg = $caleg) ur on ur.id_kel = kel.id where (kel.dapil like '%,$dapil]' or kel.dapil like '[$dapil,%' or kel.dapil like '%,$dapil,%') and kel.kec_id = $kec group by kel.id, kel.nama";

        $result = $this->db->query($sql)->getResultArray();
        $relawan[] = $result;
        
        $jml_tps = 0;
        foreach ($result as $key => $value) {
            $jml_tps = $jml_tps + $value['total'];
        }
        
        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from wil_tps wt where wt.kec_id = $kec and (wt.dapil like '%,$dapil]' or wt.dapil like '[$dapil,%' or wt.dapil like '%,$dapil,%')";
        $result = $this->db->query($sql)->getRowArray();
        $relawan[] = $result;
        
        return $relawan;
    }
    
    public function getTotalRelawanPertpsPercaleg($caleg, $kel) {
        $sql = "select ur.noTps, count(*) as total from user_relawan ur where ur.id_caleg = $caleg and ur.id_kel = $kel group by ur.noTps";
        $result = $this->db->query($sql)->getResultArray();
        
        return $result;
    }
    
    public function getTotalTpsPerkelurahan($kel, $jmlReal = 0) {
        $sql = "select count(*) as total_tps, round($jmlReal/count(*), 2)*100 as capaian from wil_tps wt where wt.kel_id = $kel";
        $result = $this->dbpemilu->query($sql)->getRowArray();
                
        return $result;
    }
    
    public function getRelawanKelurahanPerCaleg($prov, $kab, $kel, $caleg) {
        $sql = "select rd.idKel, rd.noTps as wilayah, sum(if(ur.id is not null, 1, 0)) as total from (select distinct idKel, noTps from rdpp_dpt_".$prov."_".$kab." where idKel = $kel) rd left join (select id, id_kel, noTps from user_relawan where id_caleg = $caleg) ur on ur.noTps = rd.noTps and ur.id_kel = rd.idKel group by rd.idKel, rd.noTps order by rd.noTps";

        $result = $this->db->query($sql)->getResultArray();
        $relawan[] = $result;
        
        $jml_tps = 0;
        foreach ($result as $key => $value) {
            $jml_tps = $jml_tps + $value['total'];
        }
        
        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from wil_tps wt where wt.kel_id = $kel";
        $result = $this->db->query($sql)->getRowArray();
        $relawan[] = $result;
        
        return $relawan;
    }

    public function getPenjualan($tahun) {

        $sql = 'SELECT MONTH(tgl_trx) AS bulan, COUNT(id_trx) as JML, SUM(total_harga) total
				FROM penjualan
				WHERE tgl_trx >= "' . $tahun . '-01-01" AND tgl_trx <= "' . $tahun . '-12-31"
				GROUP BY MONTH(tgl_trx)';

        $penjualan = $this->db->query($sql, $tahun)->getResultArray();
        return $penjualan;
    }

    public function getItemTerjual($tahun) {
        $sql = 'SELECT id_produk, nama, COUNT(id_produk) AS jml
				FROM penjualan_detail
				LEFT JOIN penjualan USING(id_trx)
				LEFT JOIN barang USING(id_produk)
				WHERE tgl_trx >= "' . $tahun . '-01-01" AND tgl_trx <= "' . $tahun . '-12-31"
				GROUP BY id_produk
				ORDER BY jml DESC LIMIT 7';

        $item_terjual = $this->db->query($sql)->getResultArray();
        return $item_terjual;
    }

}

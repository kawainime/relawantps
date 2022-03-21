<?php

namespace App\Models;

class LihatpemilihModel extends \App\Models\BaseModel {

    public function __construct() {
        parent::__construct();
    }
    
    public function getPemilihKabupatenPerCaleg($prov, $kab, $caleg, $dapil) {
        $sql = "select kec.id, kec.nama as wilayah, sum(if(p.id is not null, 1, 0)) as total from wil_kec kec left join (select vur.id_kec, vur.id_user from v_user_relawan vur where vur.id_caleg = $caleg) ur on ur.id_kec = kec.id left join pemilih p on p.id_relawan = ur.id_user and p.id_kec = kec.id where (kec.dapil like '%,$dapil]' or dapil like '[$dapil,%' or dapil like '%,$dapil,%') and kec.kab_id = $kab group by kec.id, kec.nama order by kec.nama";

        $result = $this->db->query($sql)->getResultArray();
        $relawan[] = $result;
        
        $jml_tps = 0;
        foreach ($result as $key => $value) {
            $jml_tps = $jml_tps + $value['total'];
        }
        
        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from rdpp_dpt_".$prov."_".$kab." rd join wil_kec kec on kec.id = rd.idKec where (kec.dapil like '%,$dapil]' or kec.dapil like '[$dapil,%' or kec.dapil like '%,$dapil,%')";
//        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from wil_tps wt where wt.kab_id = $kab and (wt.dapil like '%,$dapil]' or wt.dapil like '[$dapil,%' or wt.dapil like '%,$dapil,%')";
        $result = $this->db->query($sql)->getRowArray();
        $relawan[] = $result;
                
        return $relawan;
    }
    
    public function getPemilihKecamatanPerCaleg($prov, $kab, $kec, $caleg, $dapil) {
        $sql = "select kec.id, kec.nama as wilayah, sum(if(p.id is not null, 1, 0)) as total from wil_kel kec left join (select vur.id_kel, vur.id_user from v_user_relawan vur where vur.id_caleg = $caleg) ur on ur.id_kel = kec.id left join pemilih p on p.id_relawan = ur.id_user and p.id_kel = kec.id where (kec.dapil like '%,$dapil]' or dapil like '[$dapil,%' or dapil like '%,$dapil,%') and kec.kec_id = $kec group by kec.id, kec.nama order by kec.nama";

        $result = $this->db->query($sql)->getResultArray();
        $relawan[] = $result;
        
        $jml_tps = 0;
        foreach ($result as $key => $value) {
            $jml_tps = $jml_tps + $value['total'];
        }
        
        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from rdpp_dpt_".$prov."_".$kab." rd join wil_kec kec on kec.id = rd.idKec where (kec.dapil like '%,$dapil]' or kec.dapil like '[$dapil,%' or kec.dapil like '%,$dapil,%') and kec.id = $kec";
//        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from wil_tps wt where wt.kab_id = $kab and (wt.dapil like '%,$dapil]' or wt.dapil like '[$dapil,%' or wt.dapil like '%,$dapil,%')";
        $result = $this->db->query($sql)->getRowArray();
        $relawan[] = $result;
                
        return $relawan;
    }
    
    public function getPemilihKelurahanPerCaleg($prov, $kab, $kel, $caleg, $dapil) {
        $sql = "select rd.idKel, rd.noTps as wilayah, sum(if(p.id is not null, 1, 0)) as total
from (select distinct idKel, noTps from rdpp_dpt_".$prov."_".$kab." where idKel = $kel) rd left join (select id, id_kel, noTps, id_user from user_relawan where id_caleg = $caleg) ur on ur.noTps = rd.noTps and ur.id_kel = rd.idKel left join pemilih p on p.id_relawan = ur.id_user and p.id_kel = rd.idKel group by rd.idKel, rd.noTps order by rd.noTps";

        $result = $this->db->query($sql)->getResultArray();
        $relawan[] = $result;
        
        $jml_tps = 0;
        foreach ($result as $key => $value) {
            $jml_tps = $jml_tps + $value['total'];
        }
        
        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from rdpp_dpt_".$prov."_".$kab." rd join wil_kel kel on kel.id = rd.idKel where (kel.dapil like '%,$dapil]' or kel.dapil like '[$dapil,%' or kel.dapil like '%,$dapil,%') and kel.id = $kel";
//        $sql = "select count(*) as total_tps, round($jml_tps/count(*), 2)*100 as capaian from wil_tps wt where wt.kab_id = $kab and (wt.dapil like '%,$dapil]' or wt.dapil like '[$dapil,%' or wt.dapil like '%,$dapil,%')";
        $result = $this->db->query($sql)->getRowArray();
        $relawan[] = $result;
                
        return $relawan;
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

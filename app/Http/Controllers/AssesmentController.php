<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AssesmentController extends Controller
{
    function getData(){
        // try {
        //     $pdo = new \PDO("pgsql:host=aws-0-ap-southeast-1.pooler.supabase.com;port=6543;dbname=postgres", "postgres.nswplylaltjiawexhoso", "uIq63GIWn7jjZa4P");
        //     dd("Connection successful!") ;
        // } catch (\PDOException $e) {
        //     dd("Connection failed: " . $e->getMessage());
        // }

        $sumMuat = 0;
        $sumBongkar = 0;
        $sumSusut = 0;
        $sumToleransi = 0;
        $sumSusutAtasToleransi = 0;
        $sumDendaSusut = 0;
        $sumKontribusiTidakSusut = 0;
        $sumKontribusiBonus = 0;
        $sumBonusAntarTeman = 0;
        $commodityPrice = 10000;
        $sisaDendaSusutSopir = 0;

        $data = DB::select('
        SELECT
            do_kecil_header."no_do_kecil" AS do_kecil,
            do_kecil_header."id" AS do_kecil_id,
            do_kecil_header."createdAt" AS tanggal_do_kecil,
            do_besar."no_do_besar" AS do_besar,
            do_besar."id" AS do_besar_id,
            komoditi."nama_komoditi" AS komoditi,
            user_karyawan."nama" AS driver,
            customer_pks."kode" AS PKS,
            do_kecil_header."tgl_muat" AS tanggal_muat,
            do_kecil_detail."muat" AS netto_muat,
            do_kecil_header."tgl_bongkar" AS tanggal_bongkar,
            do_kecil_detail."bongkar" AS netto_bongkar,
            do_kecil_detail."bongkar" - do_kecil_detail."muat" AS susut,
            do_kecil_detail."muat" * ongkos_angkut."ongkos_angkut" AS value_muat,
            do_kecil_detail."bongkar" * ongkos_angkut."ongkos_angkut" AS value_bongkar,
            do_kecil_header."isKenaDenda"
        FROM bkm_do_kecil_header AS do_kecil_header
        LEFT JOIN bkm_do_kecil_detail AS do_kecil_detail ON do_kecil_detail."headerId" = do_kecil_header."id"
        LEFT JOIN bkm_do_besar AS do_besar ON do_besar."id" = do_kecil_detail."dOBesarId"
        LEFT JOIN bkm_customer_pks AS customer_pks ON customer_pks."id" = do_besar."customerPKSId"
        LEFT JOIN bkm_komoditi AS komoditi ON komoditi."id" = do_besar."komoditiId"
        LEFT JOIN bkm_ongkos_angkut AS ongkos_angkut ON ongkos_angkut."id" = do_kecil_detail."ongkosAngkutId"
        LEFT JOIN bkm_kendaraan AS kendaraan ON kendaraan."id" = do_kecil_header."kendaraanId"
        LEFT JOIN bkm_tipe_kendaraan AS tipe_kendaraan ON tipe_kendaraan."id" = kendaraan."tipeKendaraanId"
        LEFT JOIN bkm_assign_driver_kendaraan AS assign_driver ON assign_driver."kendaraanId" = do_kecil_header."kendaraanId"
        LEFT JOIN bkm_user_karyawan AS user_karyawan ON user_karyawan."id" = assign_driver."karyawanId"
        LEFT JOIN bkm_tujuan_bongkar AS tujuan_bongkar ON tujuan_bongkar."id" = do_besar."tujuanBongkarId"
        LEFT JOIN bkm_sites AS sites ON sites."id" = customer_pks."businessSiteId"
        WHERE do_kecil_header."status" NOT IN (\'DELETIONAPPROVAL\', \'DELETED\', \'DECLINED\', \'REJECTIONAPPROVAL\', \'REJECTED\')
        AND do_kecil_header."tgl_bongkar" >= \'2024-08-05\'
        AND  do_kecil_header."tgl_bongkar" <= \'2024-09-05\'
    ');
        // dd($data);

        // Tambahkan perhitungan di backend
        foreach($data as &$item){
            $item->batasToleransi = ceil(($item->netto_muat * 0.0025) / 10) * -10;

            $item->susutToleransi = ($item->batasToleransi - $item->susut) * -1;

            if($item->isKenaDenda == false){
                $item->dendaSusut = 0;
            }
            if($item->isKenaDenda == true) {
                $item->dendaSusut = max(20000 * ($item->susut - $item->batasToleransi) * -1, 0);

                if($item->dendaSusut < 0){
                    $item->dendaSusut = 0;
                }
            }

            $item->kontribusiTidakSusut = $item->susut - $item->batasToleransi;
            if($item->kontribusiTidakSusut < 0){
                $item->kontribusiTidakSusut = 0;
            }

            $sumMuat += $item->netto_muat;
            $sumBongkar += $item->netto_bongkar;
            $sumSusut += $item->susut;
            $sumToleransi += $item->batasToleransi;
            $sumSusutAtasToleransi += $item->susutToleransi;
            $sumDendaSusut += $item->dendaSusut;
            $sumKontribusiTidakSusut += $item->kontribusiTidakSusut;
        }

        foreach ($data as &$item) {
            $item->kontribusiBonus = round((($item->kontribusiTidakSusut / $sumKontribusiTidakSusut) * 100), 0);
            $sumKontribusiBonus += ($item->kontribusiTidakSusut / $sumKontribusiTidakSusut) * 100;
        }

        $dendaFR = ($sumKontribusiTidakSusut * $commodityPrice * 3) * - 1;

        $totalBonus = $sumDendaSusut - $dendaFR;

        foreach ($data as &$item) {
            $item->bonusAntarTeman = ($item->kontribusiBonus * 0.01) * $totalBonus;
            $sumBonusAntarTeman += $item->bonusAntarTeman;
        }

        $sisaDendaSusutSopir = $sumDendaSusut + $dendaFR;



        return view('welcome',
        [
                'data' => $data,
                'dendaFR' => $dendaFR,
                'sumKontribusiTidakSusut' => $sumKontribusiTidakSusut,
                'sumDendaSusut' => $sumDendaSusut,
                'sumMuat' => $sumMuat,
                'sumBongkar' => $sumBongkar,
                'sumSusut' => $sumSusut,
                'sumToleransi' => $sumToleransi,
                'sumSusutAtasToleransi' => $sumSusutAtasToleransi,
                'sumBonusAntarTeman' => $sumBonusAntarTeman,
                'sumKontribusiBonus' => $sumKontribusiBonus,
                'sisaDendaSusutSopir' => $sisaDendaSusutSopir,
            ]);
    }
}

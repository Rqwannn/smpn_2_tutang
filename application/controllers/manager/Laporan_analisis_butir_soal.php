<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_analisis_butir_soal extends Member_Controller {
	private $kode_menu = 'laporan-analisis-butir-soal';
	private $kelompok = 'laporan';
	private $url = 'manager/laporan_analisis_butir_soal';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_user_model');
		$this->load->model('cbt_user_grup_model');
		$this->load->model('cbt_tes_model');
		$this->load->model('cbt_tes_token_model');
		$this->load->model('cbt_tes_topik_set_model');
		$this->load->model('cbt_tes_user_model');
		$this->load->model('cbt_tesgrup_model');
		$this->load->model('cbt_soal_model');
		$this->load->model('cbt_jawaban_model');
		$this->load->model('cbt_tes_soal_model');
		$this->load->model('cbt_tes_soal_jawaban_model');

        parent::cek_akses($this->kode_menu);
	}
	
    public function index(){
		$data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $username = $this->access->get_username();
		$user_id = $this->users_model->get_login_info($username)->id;

        $query_group = $this->cbt_user_grup_model->get_group();
        $select = '';
        if($query_group->num_rows()>0){
        	$query_group = $query_group->result();
        	foreach ($query_group as $temp) {
        		$select = $select.'<option value="'.$temp->grup_id.'">'.$temp->grup_nama.'</option>';
        	}

        }else{
        	$select = '<option value="0">Tidak Ada Group</option>';
        }
        $data['select_group'] = $select;
		
		$query_tes = $this->cbt_tes_user_model->get_by_group();
        $select = '';
        if($query_tes->num_rows()>0){
        	$query_tes = $query_tes->result();
        	foreach ($query_tes as $temp) {
        		$select = $select.'<option value="'.$temp->tes_id.'">'.$temp->tes_nama.'</option>';
        	}
        }else{
			$select = '<option value="kosong">Belum Ada Tes yang Dilakukan</option>';
		}
        $data['select_tes'] = $select;
        
        $this->template->display_admin($this->kelompok.'/laporan_analisis_butir_soal_view', 'Analisis Butir Soal', $data);
    }

    public function export(){
    	$this->load->library('form_validation');
        
        $this->form_validation->set_rules('pilih-grup', 'Grup','required|strip_tags');
        $this->form_validation->set_rules('nama-grup', 'Grup','required|strip_tags');
        $this->form_validation->set_rules('pilih-tes', 'Nama Tes','required|strip_tags');
		$this->form_validation->set_rules('nama-tes', 'Nama Tes','required|strip_tags');

        $this->load->library('excel');
            
        $inputFileName = './public/form/form-data-analisis-butir-soal.xlsx';
        $excel = PHPExcel_IOFactory::load($inputFileName);
        $worksheet = $excel->getSheet(0);
		
		$nama_grup = '';
        
        // if($this->form_validation->run() == TRUE){
        if(TRUE == TRUE){
            $tes = $this->input->post('pilih-tes', true);
            $grup = $this->input->post('pilih-grup', true);
            $nama_grup = $this->input->post('nama-grup', true);
			$nama_tes = $this->input->post('nama-tes', true);

            // Mengambil Data Peserta berdasarkan grup
            $query_user = $this->cbt_tes_user_model->get_by_tes_group($tes, $grup);
			
            $worksheet->setCellValueByColumnAndRow(2, 3, $nama_grup);
            $worksheet->setCellValueByColumnAndRow(2, 4, $nama_tes);

			if($query_user->num_rows()>0){
            	$query_user = $query_user->result();
				$data_jawaban = [];

            	foreach ($query_user as $user) {

					//Mengambil data jawaban yang telah dikerjakan
					$query_soal_tes = $this->cbt_tes_soal_model->get_by_testsoal_soal($user->tesuser_id);
					
					if($query_soal_tes->num_rows()){

						$query_soal_tes = $query_soal_tes->result();

						foreach ($query_soal_tes as $soal_tes) {

							if($soal_tes->tessoal_jawaban_text == null){
								$jawaban = $soal_tes->soaljawaban_answer;
							}else{
								$jawaban = $soal_tes->tessoal_jawaban_text;
							}

							$wadah_sementara = [
								$user->user_name => [
									"nama" => $user->user_firstname,
									"jawaban" => $jawaban,
									"nilai" => $soal_tes->tessoal_nilai // 1 dan 0
								]
							];

							if (array_key_exists($soal_tes->tessoal_soal_id, $data_jawaban)) {
								// Jika ID sudah ada, ganti data dengan data baru
								$data_jawaban[$soal_tes->tessoal_soal_id] += $wadah_sementara;
							} else {
								$data_jawaban[$soal_tes->tessoal_soal_id] = $wadah_sementara;
							}


						}


					}
            	}

				$kolom_id_soal = 3;
				$nomer = 1;
				$row_user = 8;

				ksort($data_jawaban);
		
				$tingkat_kesukaran = [];
				$posisi_user = [];

				$if_pg = ["A","B","C","D","E","F"];

				foreach ($data_jawaban as $id_soal => $nilai) {
					
					$worksheet->setCellValueByColumnAndRow(0, $row_user, ($nomer));
					$worksheet->setCellValueByColumnAndRow($kolom_id_soal, 7, $id_soal);

					foreach($nilai as $username => $item){

						$baris_user = !empty($posisi_user[$username]["row"]) ? $posisi_user[$username]["row"] : $row_user;

						$worksheet->setCellValueByColumnAndRow(1, $baris_user, $username);
                    	$worksheet->setCellValueByColumnAndRow(2, $baris_user, stripslashes($item["nama"]));

						if(in_array($item["jawaban"], $if_pg)){
							$worksheet->setCellValueByColumnAndRow($kolom_id_soal, $baris_user, $item["nilai"]);
						} else {
							$worksheet->setCellValueByColumnAndRow($kolom_id_soal, $baris_user, "-");
						}


						$nilai = intval($item["nilai"]);
					
						// Inisialisasi data kesukaran jika belum ada
						if (!isset($tingkat_kesukaran[$id_soal])) {
							$kesukaran[$id_soal] = [
								"total" => 0,
								"benar" => 0,
								"salah" => 0,
								"column" => 0,
							];
						}

						$kesukaran[$id_soal]["column"] = $kolom_id_soal;
					
						
						if(array_key_exists($kolom_id_soal, $tingkat_kesukaran)){
							$tingkat_kesukaran[$id_soal]["total"] += 1;

							if ($nilai == 1) {
								$tingkat_kesukaran[$id_soal]["benar"] += 1;
							} else {
								$tingkat_kesukaran[$id_soal]["salah"] += 1;
							}
						} else {
							$kesukaran[$id_soal]["total"] += 1;

							// Update data kesukaran berdasarkan nilai
							if ($nilai == 1) {
								$kesukaran[$id_soal]["benar"] += 1;
							} else {
								$kesukaran[$id_soal]["salah"] += 1;
							}

							$tingkat_kesukaran = $kesukaran;
						}


						if(array_key_exists($username, $posisi_user)){
							continue;
						} else {

							$posisi_user += [
								$username => [
									"row" => $row_user
								]
							];

							$row_user++;
							$nomer++;
						}

					}

					$kolom_id_soal++;					
				}

				foreach($tingkat_kesukaran as $column => $item){

					// Kesukaran

					$totalSoal = $item["total"];
					$jumlahSoalBenar = $item["benar"];
					$jumlahSoalSalah = $item["salah"];
					$tingkatKesulitan = ''; // Tingkat Kesulitan belum diketahui dalam contoh ini
					$daya_beda = '-';

					$analisa = "Gunakan";
		
					if($jumlahSoalBenar == 0 && $jumlahSoalSalah > 0){
						$tingkatKesulitan = "Sukar";
						$analisa = "Ganti";
						$daya_beda = 0.0;
					} else {

						if ($totalSoal > 0) {
							$presentaseKebenaran = ($jumlahSoalBenar / $totalSoal) * 100;
	
							if ($presentaseKebenaran > 70) {
								$tingkatKesulitan = "Mudah";
							} elseif ($presentaseKebenaran >= 30 && $presentaseKebenaran <= 70) {
								$tingkatKesulitan = "Sedang";
							} else {
								$tingkatKesulitan = "Sukar";
							}

							if($presentaseKebenaran <= 5){
								$tingkatKesulitan = "Sangat Sukar";
								$analisa = "Ganti";
							} else if ($presentaseKebenaran > 5 && $presentaseKebenaran <= 10){
								$analisa = "Revisi";
							}
	
							// Menghitung Daya Beda
							$daya_beda = abs($presentaseKebenaran / 100.0);
						}

					}

					$worksheet->setCellValueByColumnAndRow(0, $row_user, ($nomer));
					$worksheet->setCellValueByColumnAndRow(2, $row_user, "Daya Beda");
					$worksheet->setCellValueByColumnAndRow(2, $row_user + 1, "TK. Kesulitan");
					$worksheet->getStyleByColumnAndRow($item["column"], $row_user)->getNumberFormat()->setFormatCode('0.00');

					$worksheet->setCellValueByColumnAndRow($item["column"], $row_user, $daya_beda); // Mengisi nilai daya beda
					$worksheet->setCellValueByColumnAndRow($item["column"], $row_user + 1, $tingkatKesulitan);

					$worksheet->setCellValueByColumnAndRow(2, $row_user + 2, "Kriteria Soal");
					$worksheet->setCellValueByColumnAndRow($item["column"], $row_user + 2, $analisa);
				}

            }

        }

        $filename = 'Analisis Butir Soal - '.$nama_grup.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                 
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
}
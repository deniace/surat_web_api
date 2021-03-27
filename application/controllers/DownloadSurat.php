<?php
defined('BASEPATH') or exit('No direct script access allowed');


class DownloadSurat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pdf');
        $this->load->model("surat_model");
    }

    /**
     * mengambil data user berdasarkan id
     */
    public function index($id_surat = null)
    {
        $surat = $this->surat_model->get_by_id($id_surat);

        // cek surat apakah ada
        if ($surat) {
            // surat ada
            if ($surat->id_status_rt == 1) {
                // sudah di acc rt
                if ($surat->id_status_rw == 1) {
                    // sudah di setujui RW
                    $tanggal = $this->tgl_indo($surat->tgl_rt);
                    $logo = "image/logo_kabupaten_bekasi.jpg";
                    $ttd_rt = "image/ttd_rt.png";
                    $pdf = new FPDF('p', 'mm', 'a5');
                    // membuat halaman baru
                    $pdf->AddPage();
                    $pdf->SetMargins(5.0, 2.0);
                    // setting jenis font yang akan digunakan
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Image($logo, 10, 10, -300);
                    $pdf->Cell(10, 4, '', 0, 1);
                    $pdf->Cell(160, 6, 'PEMERINTAH KABUPATEN BEKASI', 0, 1, 'C');
                    $pdf->Cell(160, 6, 'KECAMATAN BABELAN - KELURAHAN BAHAGIA', 0, 1, 'C');
                    $pdf->SetFont('Arial', 'B', 14);
                    $pdf->Cell(160, 7, "RUKUN TETANGGA $surat->rt / $surat->rw", 0, 1, 'C');

                    // garis
                    $pdf->Line(5, 36, 142, 36);
                    $pdf->Line(5, 36.5, 142, 36.5);
                    $pdf->Line(5, 36.6, 142, 36.6);
                    $pdf->Line(5, 37.1, 142, 37.1);

                    $pdf->SetFont('Arial', 'BU', 14);
                    $pdf->Cell(10, 5, '', 0, 1);
                    $pdf->Cell(140, 6, "SURAT PENGANTAR", 0, 1, 'C');

                    $pdf->SetFont('Arial', 'I', 12);
                    $pdf->Cell(140, 7, "Nomor : ......... /     /     /...... /.......", 0, 1, 'C');

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Cell(130, 4, "Yang bertanda tangan dibawah ini Ketua Rukun Tetangga $surat->rt / $surat->rw Kelurahan Bahagia", 0, 1, 'L');
                    $pdf->Cell(130, 4, "Kecamatan Babelan menerangkan dengan sebenarnya bahwa :", 0, 1, 'L');
                    $pdf->Cell(10, 5, '', 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Nama", 0, 0, '');
                    $pdf->Cell(43, 6, ": $surat->nama_user", 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Tempat / Tgl Lahir", 0, 0);
                    $pdf->Cell(43, 6, ": $surat->tempat_lahir", 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Jenis Kelamin", 0, 0);
                    $pdf->Cell(43, 6, ": $surat->jenis_kelamin", 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Agama", 0, 0);
                    $pdf->Cell(43, 6, ": $surat->agama", 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Pekerjaan", 0, 0);
                    $pdf->Cell(43, 6, ": $surat->pekerjaan", 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Status Perkawinan", 0, 0);
                    $pdf->Cell(43, 6, ": $surat->status_perkawinan", 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Nomor KTP / NIK", 0, 0);
                    $pdf->Cell(43, 6, ": $surat->nik", 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Alamat", 0, 0);
                    $pdf->Cell(43, 6, ": $surat->alamat", 0, 1);

                    $pdf->Cell(38, 6, "", 0, 0, '');
                    $pdf->Cell(100, 6, "Kecamatan Babelan Kel. Bahagia - Bekasi", 0, 1);

                    $pdf->Cell(5, 6, "", 0, 0, '');
                    $pdf->Cell(31, 6, "Keperluan", 0, 0);
                    $pdf->Cell(43, 6, ": $surat->keperluan", 0, 1);

                    $pdf->Cell(10, 6, "", 0, 1, 'L');
                    $pdf->Cell(130, 4, "Benar nama tersebut adalah warga di Rukun Tetangga $surat->rt / $surat->rw Kelurahan Bahagia", 0, 1, 'L');
                    $pdf->Cell(130, 4, "Kecamatan Babelan", 0, 1, 'L');
                    $pdf->Cell(10, 7, "", 0, 1, 'L');
                    $pdf->Cell(130, 4, "Demikian Surat Pengantar ini dibuat untuk dapat dipergunakan sebagai mana mestinya.", 0, 1, 'L');

                    $pdf->Cell(88, 5, "", 0, 0, 'L');
                    $pdf->Cell(50, 5, "Bekasi, $tanggal.", 0, 1, 'L');

                    $pdf->Cell(10, 3, "", 0, 0, 'L');
                    $pdf->Cell(78, 5, "Mengetahui", 0, 0, 'L');
                    $pdf->Cell(50, 5, "Ketua Rukun Tetangga $surat->rt / $surat->rw", 0, 1, 'L');
                    $pdf->Cell(90, 5, "Pengurus Rukun Warga $surat->rw", 0, 1, 'L');

                    $pdf->Cell(10, 20, "", 0, 1, 'L');
                    $pdf->Cell(90, 5, "(........................................)", 0, 0, 'L');
                    $pdf->Cell(50, 5, "(........................................)", 0, 0, 'L');

                    // Image($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='')
                    // 1400 480
                    $pdf->Image($ttd_rt, 80, 160, 63, 21);
                    $pdf->Output();
                } else {
                    // belum di setujui RW
                    echo "Harap Hubungi RW anda";
                }
            } else {
                // surat belum di setujui RT
                echo "Silahkan hubungi Ketua RT anda";
            }
        } else {
            // surat tidak ada
            echo "surat tidak ada";
        }
    }

    /**
     * fungsi untuk menjadikan tanggal bahasa indonesia
     */
    private function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
    }
}

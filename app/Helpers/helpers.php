<?php

use App\Models\SSP;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

if (!function_exists('klien')) {
  function klien($data)
  {
    $siklien = DB::table("perusahaan")->where('id', auth()->user()->perusahaan_id)->select($data)->first();
    return $siklien->$data;
  }
}
if (!function_exists('subtitle')) {
  function subtitle($subtitle, $icon)
  {
    $subtitle = ucwords(strtolower($subtitle));
    echo '<div class="page-header-icon"><i data-feather="' . $icon . '"></i></div>' . $subtitle;
    return;
  }
}
function kalender($tanggalDiDb)
{
  $bln   = array('');
  switch (date('m', strtotime($tanggalDiDb))) {
    case 1:
      $bln = array("Januari");
      break;
    case 2:
      $bln = array("Februari");
      break;
    case 3:
      $bln = array("Maret");
      break;
    case 4:
      $bln = array("April");
      break;
    case 5:
      $bln = array("Mei");
      break;
    case 6:
      $bln = array("Juni");
      break;
    case 7:
      $bln = array("Juli");
      break;
    case 8:
      $bln = array("Agustus");
      break;
    case 9:
      $bln = array("September");
      break;
    case 10:
      $bln = array("Oktober");
      break;
    case 11:
      $bln = array("November");
      break;
    case 12:
      $bln = array("Desember");
      break;
    default:
      break;
  }
  $tanggal = date('d', strtotime($tanggalDiDb)) . " " . $bln[0] . " " . date('Y', strtotime($tanggalDiDb));
  if ($tanggalDiDb == "0000-00-00" || $tanggalDiDb == "0000-00-00 00:00:00") {
    $tanggal = '';
  }
  return $tanggal;
}
function kal($tanggalDiDb)
{
  $bln   = array('');
  switch (date('m', strtotime($tanggalDiDb))) {
    case 1:
      $bln = array("Jan");
      break;
    case 2:
      $bln = array("Feb");
      break;
    case 3:
      $bln = array("Mar");
      break;
    case 4:
      $bln = array("Apr");
      break;
    case 5:
      $bln = array("Mei");
      break;
    case 6:
      $bln = array("Jun");
      break;
    case 7:
      $bln = array("Jul");
      break;
    case 8:
      $bln = array("Agt");
      break;
    case 9:
      $bln = array("Sep");
      break;
    case 10:
      $bln = array("Okt");
      break;
    case 11:
      $bln = array("Nov");
      break;
    case 12:
      $bln = array("Des");
      break;
    default:
      break;
  }
  $tanggal = date('d', strtotime($tanggalDiDb)) . " " . $bln[0] . " " . date('Y', strtotime($tanggalDiDb));
  if ($tanggalDiDb == "0000-00-00" || $tanggalDiDb == "0000-00-00 00:00:00") {
    $tanggal = '';
  }
  return $tanggal;
}
function tgldmY($tanggalDiDb)
{
  $tanggal = date('d-m-Y', strtotime($tanggalDiDb));
  if ($tanggalDiDb == "0000-00-00" || $tanggalDiDb == "0000-00-00 00:00:00") {
    $tanggal = '';
  }
  return $tanggal;
}
function tgljam($tanggalDiDb)
{
  $tanggal = date('d-m-Y H:i:s', strtotime($tanggalDiDb));
  if ($tanggalDiDb == "0000-00-00" || $tanggalDiDb == "0000-00-00 00:00:00") {
    $tanggal = '';
  }
  return $tanggal;
}
function romawi($tanggalDiDb)
{
  $bln   = '';
  $date = explode("-", $tanggalDiDb);
  if ($date[2] == 00) {
    $tanggal = "";
  } else {
    switch ($date[1]) {
      case 1:
        $bln = "I";
        break;
      case 2:
        $bln = "II";
        break;
      case 3:
        $bln = "III";
        break;
      case 4:
        $bln = "IV";
        break;
      case 5:
        $bln = "V";
        break;
      case 6:
        $bln = "VI";
        break;
      case 7:
        $bln = "VII";
        break;
      case 8:
        $bln = "VIII";
        break;
      case 9:
        $bln = "IX";
        break;
      case 10:
        $bln = "X";
        break;
      case 11:
        $bln = "XI";
        break;
      case 12:
        $bln = "XII";
        break;
      default:
        break;
    }
    $tanggal = $bln;
  }
  return $tanggal;
}

function tglYmd($tanggalDiDb)
{
  if ($tanggalDiDb <> '') :
    $date = explode("-", $tanggalDiDb);
    $tanggal = $date[2] . "-" . $date[1] . "-" . $date[0];
  else : $tanggal = '0000-00-00';
  endif;
  return $tanggal;
}

function hari($tanggalDiDb)
{
  $hr   = array('');
  $date = date("N", strtotime($tanggalDiDb));
  switch ($date) {
    case 1:
      $hr = array("Senin");
      break;
    case 2:
      $hr = array("Selasa");
      break;
    case 3:
      $hr = array("Rabu");
      break;
    case 4:
      $hr = array("Kamis");
      break;
    case 5:
      $hr = array("Jum'at");
      break;
    case 6:
      $hr = array("Sabtu");
      break;
    case 7:
      $hr = array("Minggu");
      break;
    default:
      break;
  }
  $tanggal = $hr[0];
  return $tanggal;
}
function bulan($tanggalDiDb)
{
  $bln   = '';
  $date = explode("-", $tanggalDiDb);
  if ($date[2] == 00) {
    $tanggal = "";
  } else {
    switch ($date[1]) {
      case 1:
        $bln = "Januari";
        break;
      case 2:
        $bln = "Februari";
        break;
      case 3:
        $bln = "Maret";
        break;
      case 4:
        $bln = "April";
        break;
      case 5:
        $bln = "Mei";
        break;
      case 6:
        $bln = "Juni";
        break;
      case 7:
        $bln = "Juli";
        break;
      case 8:
        $bln = "Agustus";
        break;
      case 9:
        $bln = "September";
        break;
      case 10:
        $bln = "Oktober";
        break;
      case 11:
        $bln = "November";
        break;
      case 12:
        $bln = "Desember";
        break;
      default:
        break;
    }
    $tanggal = $bln;
  }
  return $tanggal;
}
function enumselect($table = '', $field = '')
{
  $enums = array();
  if ($table == '' || $field == '') return $enums;
  $type = DB::select(DB::raw("SHOW COLUMNS FROM {$table} LIKE '{$field}'"))[0]->Type;
  preg_match_all("/'(.*?)'/", $type, $matches);
  foreach ($matches[1] as $value) {
    $enums[$value] = $value;
  }
  return $enums;
}
function warnaStatus($var)
{
  switch ($var) {
    case 'draft':
      $bgc = 'yellow';
      break;
    case 'pending review':
      $bgc = 'blue';
      break;
    case 'rejected':
      $bgc = 'red';
      break;
    case 'published':
      $bgc = 'green';
      break;
    case 'settlement':
      $bgc = 'green';
      break;
    case 'pending':
      $bgc = 'red';
      break;
    case 'proses':
      $bgc = 'yellow';
      break;
    case 'dikirim':
      $bgc = 'blue';
      break;
    case 'selesai':
      $bgc = 'green';
      break;
    case 'disetujui':
      $bgc = 'green';
      break;
    case 'ditolak':
      $bgc = 'black';
      break;
    case 'suspend':
      $bgc = 'black';
      break;
    default:
      $bgc = 'gray';
      break;
  }
  return $bgc;
}
function getFolderSize($folderPath)
{
  $totalSize = 0;
  $totalSpace = disk_total_space($folderPath);
  $freeSpace = disk_free_space($folderPath);
  $totalSize = $totalSpace - $freeSpace;
  return $totalSize;
}
function formatBytes($bytes, $precision = 2)
{
  $units = array('B', 'KB', 'MB', 'GB', 'TB');
  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);
  $bytes /= pow(1024, $pow);
  return round($bytes, $precision) . ' ' . $units[$pow];
}
function convertToIndonesianDay($englishDay)
{
  $indonesianDays = [
    "Sunday" => "Minggu",
    "Monday" => "Senin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu",
  ];
  return $indonesianDays[$englishDay];
}
function convertToColor($englishDay)
{
  $colorOfDays = [
    "Sunday" => "#ff0009",
    "Monday" => "#ff9900",
    "Tuesday" => "#63ed7a",
    "Wednesday" => "#6799aa",
    "Thursday" => "#99544b",
    "Friday" => "#6777ef",
    "Saturday" => "#117711",
  ];
  return $colorOfDays[$englishDay];
}

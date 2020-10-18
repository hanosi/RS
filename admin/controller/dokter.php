<?php
$con->auth();
$conn=$con->koneksi();
switch (@$_GET['page']){
    case 'add':
        $pendidikan="select * from ref_pendidikan";
        $pendidikan=$conn->query($pendidikan);
        $sql="select * from ref_spesialisasi";
        $spesialis=$conn->query($sql);
        $content="views/dokter/tambah.php";
        include_once 'views/template.php';
    break;
    case 'save':
        if($_SERVER['REQUEST_METHOD']=="POST"){
            //validasi
            if(empty($_POST['nama_dokter'])){
                $err['nama_dokter']="Nama Dokter Wajib";
            }
            if(!is_numeric($_POST['no_id'])){
                $err['no_id']="No IDI Wajib Angka";
            }
            if(!is_numeric($_POST['id_pendidikan'])){
                $err['id_pendidikan']="Pendidikan Wajib Terisi";
            }
            if(!is_numeric($_POST['id_spesialisasi'])){
                $err['id_spesialisasi']="Pendidikan Wajib Terisi";
            }
            if(!isset($err)){
                $id_pegawai=$_SESSION['login']['id'];
                if(!empty($_POST['id_dokter'])){
                    //update
                    $sql="update dokter set nama_dokter='$_POST[nama_dokter]',no_id='$_POST[no_id]', nip='$_POST[nip]',id_pendidikan='$_POST[id_pendidikan]',
                    id_spesialisasi='$_POST[id_spesialisasi]' where id_dokter='$_POST[id_dokter]'";
                }else{
                    //save
                    $sql = "INSERT INTO dokter (nama_dokter, no_id, nip,id_pendidikan,id_spesialisasi) 
                    VALUES ('$_POST[nama_dokter]','$_POST[no_id]','$_POST[nip]','$_POST[id_pendidikan]','$_POST[id_spesialisasi]')";
                }
                    if ($conn->query($sql) === TRUE) {
                        header('Location: '.$con->site_url().'/admin/index.php?mod=dokter');
                    } else {
                        $err['msg']= "Error: " . $sql . "<br>" . $conn->error;
                    }
            }
        }else{
            $err['msg']="tidak diijinkan";
        }
        if(isset($err)){
            $pendidikan="select * from ref_pendidikan";
            $pendidikan=$conn->query($pendidikan);
            $sql="select * from ref_spesialisasi";
            $spesialis=$conn->query($sql);
            $content="views/dokter/tambah.php";
            include_once 'views/template.php';
        }
    break;
    case 'edit':
        $dokter ="select * from dokter where id_dokter='$_GET[id]'";
        $dokter=$conn->query($dokter);
        $_POST=$dokter->fetch_assoc();
        $_POST['no_id']=$_POST['no_id'];
        $_POST['id_dokter']=$_POST['id_dokter'];
        //var_dump($dokter);
        $pendidikan="select * from ref_pendidikan";
        $pendidikan=$conn->query($pendidikan);
        $sql="select * from ref_spesialisasi";
        $spesialis=$conn->query($sql);
        $content="views/dokter/tambah.php";
        include_once 'views/template.php';
    break;
    case 'delete';
        $dokter ="delete from dokter where id_dokter='$_GET[id]'";
        $dokter=$conn->query($dokter);
        header('Location: '.$con->site_url().'/admin/index.php?mod=dokter');
    break;
    default:
        $sql="select dokter.nama_dokter,dokter.id_dokter,dokter.nip,ref_pendidikan.pendidikan,ref_spesialisasi.nama_spesialisasi from dokter
        inner join ref_pendidikan on dokter.id_pendidikan=ref_pendidikan.id_pendidikan
        inner join ref_spesialisasi on dokter.id_spesialisasi=ref_spesialisasi.id_spesialisasi";
        $dokter=$conn->query($sql);
        $conn->close();
        $content="views/dokter/tampil.php";
        include_once 'views/template.php';
}
?>
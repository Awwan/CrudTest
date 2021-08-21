<?php 
session_start();
//koneksi ke database
$conn = mysqli_connect("localhost","root","","stockbarang");

//tambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query ($conn,"INSERT INTO barang (namabarang, deskripsi, stock) VALUES('$namabarang','$deskripsi','$stock')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
};


//tambah transaksi
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekbarangsekarang = mysqli_query($conn, "SELECT * From barang Where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekbarangsekarang);

    $barangsekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $barangsekarang - $qty;

    $addtokeluar = mysqli_query($conn, "INSERT INTO transaksi (idbarang, penerima, qty) VALUES('$barangnya','$penerima','$qty')");
    $updatestockkeluar = mysqli_query($conn,"UPDATE barang Set stock='$tambahkanstocksekarangdenganquantity' Where idbarang='$barangnya'");
    if($addtokeluar&&$updatestockkeluar){
        header('location:keluar.php');
    } else {
        echo 'gagal';
        header('location:keluar.php');
    }
}

//update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "UPDATE barang Set namabarang='$namabarang', deskripsi='$deskripsi' Where idbarang = '$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//hapus info barang
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "DELETE FROM barang WHERE idbarang='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//mengubah data transaksi
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idtransaksi'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatbarang = mysqli_query($conn, "SELECT * From barang Where idbarang= '$idb'");
    $barangnya = mysqli_fetch_array($lihatbarang);
    $barangsekarang = $qtynya['barang'];

    $qtysekarang = mysqli_query($conn, "SELECT * From transaksi Where idtransaksi='$idk'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    if($qty>$qtysekarang){
        $selisih = $qty-$qtysekarang;
        $kurangin = $barangsekarang - $selisih;
        $kurangibarangnya = mysqli_query($conn, "UPDATE barang set stock= '$kurangin' Where idbarang='idb'");
        $updatenya = mysqli_query($conn, "UPDATE transaksi set qty= '$deskripsi' Where idtransaksi='idk'");
            if($kurangibarangnya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'Gagal';
                header('location:keluar.php');
            }
          } else  {
            $selisih = $qty - $qtysekarang;
            $kurangin = $barangsekarang + $selisih;
            $kurangibarangnya = mysqli_query($conn, "UPDATE barang set stock= '$kurangin' Where idbarang='idb'");
            $updatenya = mysqli_query($conn, "UPDATE transaksi set qty= '$deskripsi' Where idtransaksi='idk'");
            if($kurangibarangnya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'Gagal';
                header('location:keluar.php');
            }
      }
    }

//hapus barang keluar
    if(isset($_POST['updatebarangkeluar'])){
        $idb = $_POST['idb'];
        $idk = $_POST['idtransaksi'];
        $qty = $_POST['qty']; 

        $getdatabarang = mysqli_query($conn, "SELECT * From barang Where idbarang= '$idb'");
        $data = mysqli_fetch_array($getdatabarang);
        $stok = $data['barang'];

        $selisih = $stok - $qty;

        $update = mysqli_query($conn, "UPDATE barang set stock='$selisih' Where idbarang='$idb'");
        $hapusdata = mysqli_query($conn, "DELETE  FROM transaksi WHERE idtransaksi='$idk'");

        if($update&&$hapusdata){
            header('location:keluar.php');
        } else {
            header('location:keluar.php');

        }
}
?>
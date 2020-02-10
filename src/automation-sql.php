<html>
<head></head>
<body>
<?php

// Author : Kubilay CAN 2018

//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; //Debug için kullan
switch(@ $_GET['is']){//İstenilen fonksiyonları çağırmak için kullan
	case 'guncelleFormu': 
		guncelleFormu($_GET['no'],$_GET['ad'],$_GET['soyad'],$_GET['bolum']);  
		break;
    case 'ogrenciGuncelle': 
        ogrenciGuncelle($_GET['no'],$_GET['ad'],$_GET['soyad'],$_GET['bolum']); 
		ogrenciListele();  
		break; 
	case 'ogrenciSil'	:  
		ogrenciSil($_GET['no']);
		ogrenciListele();
		break;
	case 'bolumEklemeFormu': 
		bolumEklemeFormu();  
		break; 
	case 'bolumEkle': 
		bolumEkle($_GET['no'],$_GET['ad']); 
		bolumListele(); 
		break;
	case 'ogrenciListele': 
		ogrenciListele(); 
		break;  
	case 'bolumListele': 
		bolumListele(); 
		break; 
	case 'bolumdekiOgrenciler':
		bolumdekiOgrenciler($_GET['no']); 
		break; 
	case 'ogrenciEklemeFormu': 
		ogrenciEklemeFormu(); 
		break; 
	case 'ogrenciEkle': 
		ogrenciEkle($_GET['no'],$_GET['ad'],$_GET['soyad'],$_GET['bolum']); 
		ogrenciListele(); 
        break; 
    case 'bolumSil': 
        bolumSil($_GET['no']); 
        bolumListele(); break; 
    case 'bolumDegistir':
        bolumDegistir($_GET['no'],$_GET['ad']);
        bolumListele(); break;
    case 'bolumDegistirmeFormu':
		bolumDegistirmeFormu($_GET['no'],$_GET['ad']);
		break;
	default: anaSayfa();
}
exit;

function anaSayfa(){
	echo "<a href=?is=ogrenciListele>OGRENCILER</a> <br/>
			<a href=?is=bolumListele>BOLUMLER</a>";  // Öğrenci ve bölüm listesini gösteren konumlara gider
}
function ogrenciListele(){	
	echo "<h1>Ogrenci listesi</h1> 
	<a href='?is=ogrenciEklemeFormu'>Yeni</a>  <a href='?is='>Ana sayfa</a>
	<table> <thead><tr> <th>No</th> <th>Adi</th> <th>Soyadi</th> <th>Bölüm</th> <th>Sil</th> <th>Guncelle</th> </tr></thead><tbody>"; //Öğrencileri listelemek için tablo oluşturur
	$baglanti = mysqli_connect('localhost', 'root', '', 'okul'); //Mysql e bağlanır
	$kayitKumesi = mysqli_query($baglanti, "SELECT * FROM s"); //Mysql ile istenilen tabloyu çağırır
	while($satir = mysqli_fetch_array($kayitKumesi)){ //Tablodaki değerleri parçalayıp bir dizide tutar
		print "<tr> 
			<td>{$satir[0]}</td> 
			<td>{$satir[1]}</td> 
			<td>{$satir[2]}</td> 
			<td> <a href='?is=bolumdekiOgrenciler&no={$satir[3]}'>{$satir[3]}</a> </td> 
			<td> <a href='?is=ogrenciSil&no={$satir[0]}'>Sil</a></td>
			<td> <a href='?is=guncelleFormu&no={$satir[0]}&ad={$satir[1]}&soyad={$satir[2]}&bolum={$satir[3]}'>Guncelle</a>
			</td></tr>";//Dizideki değerleri tabloya ekler
	}
	print "</tbody></table>";
}
function bolumListele(){
	echo "<h1>Bolum listesi</h1> 
	<a href='?is=bolumEklemeFormu'>Yeni</a> <a href='?is='>Ana sayfa</a>
	<table class='table table-dark'> <thead><tr> <th>No</th> <th>Adi</th> <th>Ogr. Sayisi</th> <th>Sil</th> <th>Guncelle</th> <th>Bolumdeki Ogrenciler</th> </tr></thead><tbody>"; //Bölümleri listelemek için tablo oluşturur
	$baglanti = mysqli_connect('localhost', 'root', '', 'okul') or exit(mysqli_error($baglanti));//Mysql e bağlanır bağlanamazsa hata mesajı verir
	$kayitKumesi = mysqli_query($baglanti, "SELECT did, b.ad, COUNT(sid) FROM s, b WHERE s.did=b.no GROUP BY did") or exit(mysqli_error($baglanti));//Mysql ile istenilen tabloyu çağırır
	while($satir = mysqli_fetch_array($kayitKumesi)){ //Tablodaki değerleri parçalayıp bir dizide tutar
		print "<tr> 
			<td>{$satir[0]}</td> 
			<td>{$satir[1]}</td> 
			<td>{$satir[2]}</td>
 			<td> <a href='?is=bolumSil&no={$satir[0]}'>Sil</a>
			<td> <a href='?is=bolumDegistirmeFormu&no={$satir[0]}&ad={$satir[1]}'>Guncelle</a>
			<td> <a href='?is=bolumdekiOgrenciler&no={$satir[0]}'>Ogrenciler</a>
			</td></tr>";//Dizideki değerleri tabloya ekler
	}
	print "</tbody></table>";
}
function ogrenciSil($no){
	$sql = "DELETE FROM s WHERE sid=$no;";//Sql e yazılacak kodu bir değişkene atar
	$baglanti = mysqli_connect('localhost', 'root', '', 'okul');//Sql e bağlanır
	if(! $baglanti) //Bağlantı başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	$sonuc = mysqli_query($baglanti, $sql); //Sql komutunu çalıştırır
	if(! $sonuc) //Sonuç başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
}
function bolumSil($no){
    $sql = "DELETE FROM b WHERE no=$no;";//Sql e yazılacak ilk komutu bir değişkene atar
	$sql2 = "DELETE FROM s WHERE did=$no;";//Sql e yazılacak ikinci komutu başka bir değişkene atar
	$baglanti = mysqli_connect('localhost', 'root', '', 'okul');//Sql e bağlanır
	if(! $baglanti){  //Bağlantı başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
	$sonuc = mysqli_query($baglanti, $sql); //Sql komutunu çalıştırır
	if(! $sonuc){ //Sonuç başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
	
	$sonuc2 = mysqli_query($baglanti, $sql2); //İkinci sql komutunu çalıştırır
	if(! $sonuc){ //Sonuç başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
}
function ogrenciEklemeFormu(){
	//Eklenecek öğrencinin değerlerini almak için bir form oluşturur
	echo "
	<form action='?'  method=get>
	<h3>Yeni Ogrenci</h3>
	<table>
	<tr><td>No</td> <td><input name=no type=text></td></tr>
	<tr><td>Adi</td> <td><input name=ad type=text></td></tr>
	<tr><td>Soyadi</td> <td><input name=soyad type=text></td></tr>
	<tr><td>Bolum</td> <td><input name=bolum type=text></td></tr>
	<tr><td></td> <td><input name=tamam type=submit value=Ekle></td></tr>
	</table>
	<input name=is type=hidden value=ogrenciEkle>
	</form>";
}
function ogrenciEkle($no, $ad, $soyad, $bolumNo){
	$baglanti1 = mysqli_connect('localhost', 'root', '', 'okul');//Sql e bağlanır
	$bolumler = mysqli_query($baglanti1, "SELECT no FROM b");//Sql de istenilen tabloyu getiren komutu çalıştırır
	$bolum = array();//Bölüm no larını yazmak için boş bir dizi açar
	while($satir = mysqli_fetch_array($bolumler)){
		$bolum[] = $satir[0];//Bölüm no larını diziye aktarır
	}
	if(in_array($bolumNo, $bolum)){//Bölümün varlığını kontrol eder 
		$sql = "INSERT INTO s VALUE($no, '$ad', '$soyad', $bolumNo);"; //Sql de çalıştırılacak komutu bir değişkene atar
		$baglanti = mysqli_connect('localhost', 'root', '', 'okul'); //Sql e bağlanır
		if(! $baglanti){ //Bağlantı başarısız olursa hata mesajı verir
			exit(mysqli_error($baglanti));
		}
		$sonuc = mysqli_query($baglanti, $sql); //Sql komutunu çalıştırır
		if(! $sonuc){//Sonuç başarısız olursa hata mesajı verir
			exit(mysqli_error($baglanti));
		}
	}else{
		echo "Bolum bulunamadi!";//Bölüm yoksa ekrana uyarı verir
	}
}
function bolumEklemeFormu(){
	//Eklenecek bölümün bilgilerini almak için bir form oluşturur 
    echo "
	<form action='?'  method=get>
	<h3>Yeni Bolum</h3>
	<table>
	<tr><td>Bolum No</td> <td><input name=no type=text></td></tr>
	<tr><td>Bolum Adi</td> <td><input name=ad type=text></td></tr>
	<tr><td></td> <td><input name=tamam type=submit value=Ekle></td></tr>
	</table>
	<input name=is type=hidden value=bolumEkle>
	</form>";
}
function bolumEkle($no,$ad){
    $sql = "INSERT INTO b VALUE($no, '$ad');";//Sql de çalıştırılacak komutu bir değişkene atar
	$baglanti = mysqli_connect('localhost', 'root', '', 'okul');//Sql e bağlanır
	if(! $baglanti){//Bağlantı başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
	$sonuc = mysqli_query($baglanti, $sql); //Sql komutunu çalıştırır
	if(! $sonuc){//Sonuç başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
}
function guncelleFormu($no,$ad,$soyad,$bolum){ 
	//Güncellenecek öğrencinin bilgilerini öğrenci no sunu değiştirmeye izin vermeyen bir form halinde ekranda gösterir 
	echo "
	<form action='?'  method=get>
	<h3>Ogrenci Guncelle</h3>
	<table>
	<tr><td>No</td> <td><input name=no type=text value='$no' readonly></td></tr>
	<tr><td>Adi</td> <td><input name=ad type=text value='$ad'></td></tr>
	<tr><td>Soyadi</td> <td><input name=soyad type=text value='$soyad'></td></tr>
	<tr><td>Bolum</td> <td><input name=bolum type=text value='$bolum'></td></tr>
	<tr><td></td> <td><input name=tamam type=submit value=Guncelle></td></tr>
	</table>
	<input name=is type=hidden value=ogrenciGuncelle>
	</form>";
}
function ogrenciGuncelle($no, $ad, $soyad, $bolumNo){
    $sql = "UPDATE s SET ad='$ad',soyad='$soyad',did='$bolumNo' WHERE sid='$no';";//Sql de çalıştırılacak komutu bir değişkene atar
	$baglanti = mysqli_connect('localhost', 'root', '', 'okul'); //Sql e bağlanır
	if(! $baglanti){//Bağlantı başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
	$sonuc = mysqli_query($baglanti, $sql); //Sql komutunu çalıştırır
	if(! $sonuc){//Sonuç başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
}
function bolumDegistirmeFormu($no,$ad){
	//Güncellenecek bölümün bilgilerini bölüm no sunu değiştirmeye izin vermeyen bir form halinde ekranda gösterir     
	echo "
	<form action='?'  method=get>
	<h3>Bolum Guncelle</h3>
	<table>
	<tr><td>No</td> <td><input name=no type=text value='$no' readonly></td></tr>
	<tr><td>Adi</td> <td><input name=ad type=text value='$ad'></td></tr>
	<tr><td></td> <td><input name=tamam type=submit value=Guncelle></td></tr>
	</table>
	<input name=is type=hidden value=bolumDegistir>
	</form>";
}
function bolumDegistir($no,$ad){
    $sql = "UPDATE b SET ad='$ad' WHERE no='$no';";//Sql de çalıştırılacak komutu bir değişkene atar
	$baglanti = mysqli_connect('localhost', 'root', '', 'okul');//Sql e bağlanır
	if(! $baglanti){//Bağlantı başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
	$sonuc = mysqli_query($baglanti, $sql); //Sql komutunu çalıştırır
	if(! $sonuc){//Sonuç başarısız olursa hata mesajı verir
		exit(mysqli_error($baglanti));
	}
}
function bolumdekiOgrenciler($bolumNo){
	echo "<h1>$bolumNo Bolumdeki Ogrenciler</h1> 
	<a href='eklemeFormu.php'>Yeni</a>  <a href='?is='>Ana sayfa</a>
	<table class='table table-dark'> <thead><tr> <th>No</th> <th>Adi</th> <th>Soyadi</th> <th>Bölüm</th> <th>Sil</th> </tr></thead><tbody>";//Bir tablo oluşturur
	$baglanti = mysqli_connect('localhost', 'root', '', 'okul');//Sql e bağlanır
	$sql = "SELECT * FROM s WHERE did=$bolumNo;";//Sql de istenilen tabloyu getiren komutu bir değişkene atar
	$kayitKumesi = mysqli_query($baglanti, $sql);//Sql komutunu çalıştırır
	while($satir = mysqli_fetch_array($kayitKumesi)){//Satırdaki değerleri bir dizide tutar
		print "<tr> 
			<td>{$satir[0]}</td> 
			<td>{$satir[1]}</td> 
			<td>{$satir[2]}</td> 
			<td> <a href='?is=bolumdekiOgrenciler&no={$satir[3]}'>{$satir[3]}</a> </td> 
			<td> <a href='?is=ogrenciSil&no={$satir[0]}'>Sil</a> </td>
			<td> <a href='?is=guncelleFormu&no={$satir[0]}&ad={$satir[1]}&soyad={$satir[2]}&bolum={$satir[3]}'>Degistir</a>
			</td></tr>";
	}
	print "</tbody></table>";//Dizideki değerleri tabloya ekler
}
?>
</body>
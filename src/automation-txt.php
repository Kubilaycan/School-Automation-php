<html>
<head></head>
<body>
<?php

// Author : Kubilay CAN 2018

//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; //debug için kullan
switch(@ $_GET['is']){ //İstenilen fonksiyonları çağırmak için kullan
	case 'guncelleFormu': 
		ogrguncelleFormu($_GET['no'],$_GET['ad'],$_GET['soyad'],$_GET['bolum']);  
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
	<table> <thead><tr> <th>No</th> <th>Adi</th> <th>Soyadi</th> <th>Bölüm</th> <th>Sil</th> <th>Guncelle</th> </tr></thead><tbody>";
    $dosya = fopen("ogrenci.txt","r");  //Öğrencileri listelemek için tablo oluşturur
    while(! feof($dosya)){
        $satir = fgets($dosya);  
        $satir = explode(" ",$satir);  //Tablodaki satırları tek tek okuyup satir dizisine yazar
        print "<tr> 
			<td>{$satir[0]}</td> 
			<td>{$satir[1]}</td> 
			<td>{$satir[2]}</td> 
			<td> <a href='?is=bolumdekiOgrenciler&no={$satir[3]}'>{$satir[3]}</a> </td> 
			<td> <a href='?is=ogrenciSil&no={$satir[0]}'>Sil</a></td>
			<td> <a href='?is=guncelleFormu&no={$satir[0]}&ad={$satir[1]}&soyad={$satir[2]}&bolum={$satir[3]}'>Guncelle</a>
			</td></tr>";
    }
    print "</tbody></table>";
    fclose($dosya);
}
function bolumListele(){
	echo "<h1>Bolum listesi</h1> 
	<a href='?is=bolumEklemeFormu'>Yeni</a> <a href='?is='>Ana sayfa</a>
	<table class='table table-dark'> <thead><tr> <th>No</th> <th>Adi</th> <th>Ogr. Sayisi</th> <th>Sil</th> <th>Guncelle</th> <th>Bolumdeki Ogrenciler</th>  </tr></thead><tbody>";
    $dosya = fopen("bolum.txt","r"); //Bölüm listesini açar
    $dosya2 = fopen("ogrenci.txt","r"); //Öğrenci listesini açar
    $sayilar = array(); //Boş bir dizi oluşturur
    while(! feof($dosya2)){
        $satir2 = fgets($dosya2);
        $satir2 = explode(" ",$satir2);
        $sayilar[] = $satir2[3]; //Öğrenci listesindeki bölüm numaralarını sayila dizisine yazar
    }
    while(! feof($dosya)){
        $i = 0;
        $satir = fgets($dosya);
        $satir = explode(" ",$satir);
        for($j = 0;$j < count($sayilar);$j++){ 
            if(trim($sayilar[$j]) == trim($satir[0])){ //Her bir bölüm için dizideki öğrencilerin bölümlerini sayıp tabloya yazmak için bir değişkene atar 
                $i = $i + 1;
            }
        }
		print "<tr> 
			<td>{$satir[0]}</td> 
			<td>{$satir[1]}</td> 
			<td>{$i}</td>
 			<td> <a href='?is=bolumSil&no={$satir[0]}'>Sil</a>
			<td> <a href='?is=bolumDegistirmeFormu&no={$satir[0]}&ad={$satir[1]}'>Guncelle</a>
			<td> <a href='?is=bolumdekiOgrenciler&no={$satir[0]}'>Ogrenciler</a>
            </td></tr>";
	}
    print "</tbody></table>";//Tabloyu web sayfasına yazdırır
}
function ogrenciSil($no){
    $dosya = fopen("ogrenci.txt","r");
	$satirsayisi = 0;
	while(! feof($dosya)){ 
	    $satir = fgets($dosya);
	    $kume2[$satirsayisi] = $satir; //Satır sayısını sayıp satırı bütün olarak bir diziye atar
	    $satirsayisi++;
	}
	fclose($dosya);
	$dosya = fopen("ogrenci.txt","r");
	$data = array();
	for($i = 0;$i<$satirsayisi;$i++){
		$satir = fgets($dosya);
		$data[] = explode(" ",$satir); //Satırdaki değerleri parçalayıp bir matrise atar
	}
	fclose($dosya);
	$tmpdata = array(); //Geçici bir dizi oluşturur
	for($j = 0;$j<$satirsayisi;$j++){
		if($data[$j][0] != $no){
			$tmpdata[] = $data[$j]; //Silinecek öğrenci hariç her öğrenciyi bu dizide saklar
		}
	}
	$temp = fopen("temp.txt","w");//Geçici bir txt dosyası açar
	for($o=0;$o<($satirsayisi-1);$o++){
		if($o != 0){
			$tmpsatir = "\n".$tmpdata[$o][0]." ".$tmpdata[$o][1]." ".$tmpdata[$o][2]." ".trim($tmpdata[$o][3]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
		if($o == 0){
			$tmpsatir = $tmpdata[$o][0]." ".$tmpdata[$o][1]." ".$tmpdata[$o][2]." ".trim($tmpdata[$o][3]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
	}
	fclose($temp);
	unlink('ogrenci.txt');//Eski öğrenci dosyasını siler
	rename("temp.txt","ogrenci.txt");//Temp dosyasının adını değiştirir
}
function bolumSil($no){
	$dosya = fopen("bolum.txt","r");
	$satirsayisi = 0;
	while(! feof($dosya)){ 
	    $satir = fgets($dosya);
	    $kume2[$satirsayisi] = $satir;  //Satır sayısını sayıp satırı bütün olarak bir diziye atar
	    $satirsayisi++;
	}
	fclose($dosya);
	$dosya = fopen("bolum.txt","r");
	$data = array();
	for($i = 0;$i<$satirsayisi;$i++){
		$satir = fgets($dosya);
		$data[] = explode(" ",$satir); //Satırdaki değerleri parçalayıp bir matrise atar
	}
	fclose($dosya);
	$tmpdata = array(); //Geçici bir dizi oluşturur
	for($j = 0;$j<$satirsayisi;$j++){
		if($data[$j][0] != $no){
			$tmpdata[] = $data[$j]; //Silinecek bölüm hariç her bölümü bu dizide saklar
		}
	}
	$temp = fopen("temp.txt","w");//Geçici bir txt dosyası açar
	for($o=0;$o<($satirsayisi-1);$o++){
		if($o != 0){
			$tmpsatir = "\n".$tmpdata[$o][0]." ".trim($tmpdata[$o][1]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
		if($o == 0){
			$tmpsatir = $tmpdata[$o][0]." ".trim($tmpdata[$o][1]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
	}
	fclose($temp);
	unlink('bolum.txt');//Eski bölüm dosyasını siler
	rename("temp.txt","bolum.txt");//Temp dosyasının adını değiştirir
	$dosya = fopen("ogrenci.txt","r");
	$satirsayisi = 0;
	//Öğrenci silerken yapılan işlemlerin aynısı bölümdeki öğrencileri silmek için bölüm numarasıyla tekrarlanır
	while(! feof($dosya)){ 
	    $satir = fgets($dosya);
	    $kume2[$satirsayisi] = $satir;   //Satır sayısını sayıp satırı bütün olarak bir diziye atar
	    $satirsayisi++;
	}
	fclose($dosya);
	$dosya = fopen("ogrenci.txt","r");
	$data = array();
	for($i = 0;$i<$satirsayisi;$i++){
		$satir = fgets($dosya);
		$data[] = explode(" ",$satir);//Satırdaki değerleri parçalayıp bir matrise atar
	}
	fclose($dosya);
	$tmpdata = array(); //Geçici bir dizi oluşturur
	for($j = 0;$j<$satirsayisi;$j++){
		if($data[$j][3] != $no){
			$tmpdata[] = $data[$j];//Silinecek öğrenciler hariç her öğrenciyi bu dizide saklar
		}
	}
	$temp = fopen("temp.txt","w");//Geçici bir txt dosyası açar
	for($o=0;$o<($satirsayisi-1);$o++){
		if($o != 0){
			$tmpsatir = "\n".$tmpdata[$o][0]." ".$tmpdata[$o][1]." ".$tmpdata[$o][2]." ".trim($tmpdata[$o][3]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
		if($o == 0){
			$tmpsatir = $tmpdata[$o][0]." ".$tmpdata[$o][1]." ".$tmpdata[$o][2]." ".trim($tmpdata[$o][3]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
	}
	fclose($temp);
	unlink('ogrenci.txt');//Eski öğrenci dosyasını siler
	rename("temp.txt","ogrenci.txt");//Temp dosyasının adını değiştirir
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
	$dosya = fopen("ogrenci.txt","r");
	$satirsayisi = 0;
	while(! feof($dosya)){ 
	    $satir = fgets($dosya);
	    $kume2[$satirsayisi] = $satir;   //Satır sayısını sayıp satırı bütün olarak bir diziye atar
	    $satirsayisi++;
	}
	fclose($dosya);
	$dosya = fopen("ogrenci.txt","r");
	$data = array();
	for($i = 0;$i<$satirsayisi;$i++){
		$satir = fgets($dosya);
		$data[] = explode(" ",$satir); //Satırdaki değerleri parçalayıp bir matrise atar
	}
	fclose($dosya);
	$ekleme = "\n".$no." ".$ad." ".$soyad." ".trim($bolumNo);//Eklenecek öğrencinin bilgilerini bir satır haline getirir
	$temp = fopen("temp.txt","w");//Geçici bir txt dosyası açar
	for($o=0;$o<$satirsayisi;$o++){
		if($o != 0){
			$tmpsatir = "\n".$data[$o][0]." ".$data[$o][1]." ".$data[$o][2]." ".trim($data[$o][3]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
		if($o == 0){
			$tmpsatir = $data[$o][0]." ".$data[$o][1]." ".$data[$o][2]." ".trim($data[$o][3]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
	}
	fputs($temp,"$ekleme");//Eklenecek öğrenciyi geçici dosyaya atar
	fclose($temp);
	unlink('ogrenci.txt');//Eski öğrenci dosyasını siler
	rename("temp.txt","ogrenci.txt");//Temp dosyasının adını değiştirir
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
	$dosya = fopen("bolum.txt","r");
	$satirsayisi = 0;
	while(! feof($dosya)){ 
	    $satir = fgets($dosya);
	    $kume2[$satirsayisi] = $satir;   //Satır sayısını sayıp satırı bütün olarak bir diziye atar
	    $satirsayisi++;
	}
	fclose($dosya);
	$dosya = fopen("bolum.txt","r");
	$data = array();
	for($i = 0;$i<$satirsayisi;$i++){
		$satir = fgets($dosya);
		$data[] = explode(" ",$satir); //Satırdaki değerleri parçalayıp bir matrise atar
	}
	fclose($dosya);
	$ekleme = "\n".$no." ".trim($ad);//Eklenecek bölümün bilgilerini bir satır haline getirir
	$temp = fopen("temp.txt","w"); //Geçici bir txt dosyası açar
	for($o=0;$o<$satirsayisi;$o++){
		if($o != 0){
			$tmpsatir = "\n".$data[$o][0]." ".trim($data[$o][1]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
		if($o == 0){
			$tmpsatir = $data[$o][0]." ".trim($data[$o][1]);
			fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
		}
	}
	fputs($temp,"$ekleme");
	fclose($temp);
	unlink('bolum.txt');//Eski bölüm dosyasını siler
	rename("temp.txt","bolum.txt");//Temp dosyasının adını değiştirir
}
function ogrguncelleFormu($no,$ad,$soyad,$bolum){  
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
function ogrenciGuncelle($no,$ad,$soyad,$bolum){
	$dosya = fopen("ogrenci.txt","r");
	$satirsayisi = 0;
	while(! feof($dosya)){ 
	    $satir = fgets($dosya);
	    $kume2[$satirsayisi] = $satir;   //Satır sayısını sayıp satırı bütün olarak bir diziye atar
	    $satirsayisi++;
	}
	fclose($dosya);
	$dosya = fopen("ogrenci.txt","r");
	$data = array();
	for($i = 0;$i<$satirsayisi;$i++){
		$satir = fgets($dosya);
		$data[] = explode(" ",$satir); //Satırdaki değerleri parçalayıp bir matrise atar
	}
	fclose($dosya);
	$guncelle = "\n".$no." ".$ad." ".$soyad." ".trim($bolum); //Güncellenecek öğrencinin bilgilerini bir satır haline getirir
	$temp = fopen("temp.txt","w");//Geçici bir txt dosyası açar
	for($o=0;$o<$satirsayisi;$o++){
		if($data[$o][0] != $no){ //Güncellenecek öğrenci hariç her öğrenciyi geçici dosyaya yazar
			if($o != 0){
				$tmpsatir = "\n".$data[$o][0]." ".$data[$o][1]." ".$data[$o][2]." ".trim($data[$o][3]);
				fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
			}
			if($o == 0){
				$tmpsatir = $data[$o][0]." ".$data[$o][1]." ".$data[$o][2]." ".trim($data[$o][3]);
				fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
			}
		}
	}
	fputs($temp,"$guncelle");//Güncellenecek öğrenciyi txt dosyasına ekler
	fclose($temp);
	unlink('ogrenci.txt');//Eski öğrenci dosyasını siler
	rename("temp.txt","ogrenci.txt");//Temp dosyasının adını değiştirir
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
	$dosya = fopen("bolum.txt","r");
	$satirsayisi = 0;
	while(! feof($dosya)){ 
	    $satir = fgets($dosya);
	    $kume2[$satirsayisi] = $satir;  //Satır sayısını sayıp satırı bütün olarak bir diziye atar
	    $satirsayisi++;
	}
	fclose($dosya);
	$dosya = fopen("bolum.txt","r");
	$data = array();
	for($i = 0;$i<$satirsayisi;$i++){
		$satir = fgets($dosya);
		$data[] = explode(" ",$satir); //Satırdaki değerleri parçalayıp bir matrise atar
	}
	fclose($dosya);
	$guncelle = "\n".$no." ".trim($ad); //Güncellenecek bölümün bilgilerini bir satır haline getirir
	$temp = fopen("temp.txt","w");//Geçici bir txt dosyası açar
	for($o=0;$o<$satirsayisi;$o++){
		if($data[$o][0] != $no){  //Güncellenecek bölüm hariç her bölümü geçici dosyaya yazar
			if($o != 0){
				$tmpsatir = "\n".$data[$o][0]." ".trim($data[$o][1]);
				fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
			}
			if($o == 0){
				$tmpsatir = $data[$o][0]." ".trim($data[$o][1]);
				fputs($temp,"$tmpsatir");//Boş satır oluşmamasıiçin ilk satır hariç her satırı bir satır aşağı inerek geçici dosyaya yazar
			}
		}
	}
	fputs($temp,"$guncelle");//Güncellenecek bölümü txt dosyasına ekler
	fclose($temp);
	unlink('bolum.txt');//Eski bölüm dosyasını siler
	rename("temp.txt","bolum.txt");//Temp dosyasının adını değiştirir
}
function bolumdekiOgrenciler($no){
	echo "<h1>$no Bolumdeki Ogrenciler</h1> 
	<a href='?is=ogrenciEklemeFormu'>Yeni</a>  <a href='?is='>Ana sayfa</a>
	<table> <thead><tr> <th>No</th> <th>Adi</th> <th>Soyadi</th> <th>Bölüm</th> <th>Sil</th> <th>Guncelle</th> </tr></thead><tbody>";//Bir tablo oluşturur
    $dosya = fopen("ogrenci.txt","r");
    while(! feof($dosya)){
        $satir = fgets($dosya);
		$satir = explode(" ",$satir); //Öğrenci dosyasından seçilen bölümün öğrencilerini tabloya ekler
		if(trim($satir[3]) == trim($no)){
        	print "<tr> 
				<td>{$satir[0]}</td> 
				<td>{$satir[1]}</td> 
				<td>{$satir[2]}</td> 
				<td> <a href='?is=bolumdekiOgrenciler&no={$satir[3]}'>{$satir[3]}</a> </td> 
				<td> <a href='?is=ogrenciSil&no={$satir[0]}'>Sil</a></td>
				<td> <a href='?is=guncelleFormu&no={$satir[0]}&ad={$satir[1]}&soyad={$satir[2]}&bolum={$satir[3]}'>Guncelle</a>
				</td></tr>";
		}
	}
    print "</tbody></table>";
    fclose($dosya);
}
?>
</body>
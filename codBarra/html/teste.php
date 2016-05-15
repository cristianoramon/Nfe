<?

require_once('cCodBarraNf.php');

$codBarra = new cCodBarraNf();

echo $codBarra->fCodBarra('1234569098','testess.jpg');
//echo  '<img src="image.php?code=code128&o=2&t=45&r=1&text=45679890909090909090&f=2&a1=C&a2=" alt="Error? Cant display image!" />';

 //ImageJPEG ('image.php?code=code128&o=2&t=45&r=1&text=45679890909090909090&f=2&a1=C&a2=" alt="Error? Cant display image!','test.jpg');
 //imagecreatefromjpeg("image.php?code=code128&o=2&t=45&r=1&text=45679890909090909090&f=2&a1=C&a2=");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Pitch</title>
	</head>
	<body>
	<form>
	First name: 
	<input type="text" name="firstname" />
	<br />
	Last name: 
	<input type="text" name="lastname" />
	</form>
	<br />
	<form>
<?php
header('Content-type: image/png');
$points = array(
            50, 50,	// Point 1 (x, y)
            100, 50, 	// Point 2 (x, y)
            150, 100, 	// Point 3 (x, y)
            150, 150,	// Point 4 (x, y)
            100, 150, 	// Point 5 (x, y)
            50, 100	// Point 6 (x, y)
            );
$im = imagecreatetruecolor(200, 200);
$red = imagecolorallocate($im, 255, 0, 0);
imagefilledpolygon($im, $points, 6, $red);
imagepng($im);
imagedestroy($im);
?>
	</form>
	</body>
</html>
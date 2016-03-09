<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Pitch</title>
	</head>
	<body>
	<div align="middle">
	<form action="./pinch2.php" method="post">
	<?php
	@$Nh=$_POST['hot_stream'];
	@$Nc=$_POST['cold_stream'];
	// 热流股
	echo 'Hot Stream','<br />' ;
	echo '<table border="1">';
	echo '<tr>';
	echo '<th>T-in</th>', '<th>T-out</th>', '<th>CP</th>' ;
	echo '</tr>';
	for($i=1; $i<=$Nh;$i++)
		{
		echo '<tr>';
		echo '<th><input type="text" name="Hi',$i,'" /></th>' ;
		echo '<th><input type="text" name="Ho',$i,'" /></th>' ;
		echo '<th><input type="text" name="Hcp',$i,'" /></th>' ;
		echo '</tr>';
		}
	echo '</table>';
	echo '<br />','<br />';
	// 冷流股
	echo 'Cold Stream','<br />' ;
	echo '<table border="1">';
	echo '<tr>';
	echo '<th>T-in</th>', '<th>T-out</th>', '<th>CP</th>' ;
	echo '</tr>';
	for($i=1; $i<=$Nc;$i++)
		{
		echo '<tr>';
		echo '<th><input type="text" name="Ci',$i,'" /></th>' ;
		echo '<th><input type="text" name="Co',$i,'" /></th>' ;
		echo '<th><input type="text" name="Ccp',$i,'" /></th>' ;
		echo '</tr>';
		}
	echo '</table>';
	?>
	<br /><br />
	deltaT
	<br /><br />
	<input name="deltaT" type="text" value="20">
	<input name="No-H" type="hidden" value="<?php echo $Nh ?>">
	<input name="No-C" type="hidden" value="<?php echo $Nc ?>">
	<br /><br />
	<input name="Button2" type="submit" value="Next Step" onClick="window.location.reload('pinch2.php')"/>
	</form>
	</div>
	</body>
</html>
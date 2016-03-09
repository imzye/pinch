<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Pitch</title>
	</head>
	<body>
	<form align="middle">
	<?php
	//冷、热流股数
	@$Nh=$_POST['No-H'];
	@$Nc=$_POST['No-C'];
	@$dT=$_POST['deltaT'];
	//热流股性质
	//入口温度
	for($i=1;$i<=$Nh;$i++)
		{
		$tmp = 'Hi'.$i;
		@$H[$i][1] = $_POST[$tmp];
		}
	//出口温度
	for($i=1;$i<=$Nh;$i++)
		{
		$tmp = 'Ho'.$i;
		@$H[$i][2] = $_POST[$tmp];
		}
	//热容流量
	for($i=1;$i<=$Nh;$i++)
		{
		$tmp = 'Hcp'.$i;
		@$H[$i][3] = $_POST[$tmp];
		}
	//冷流股性质
	//入口温度
	for($i=1;$i<=$Nc;$i++)
		{
		$tmp = 'Ci'.$i;
		@$C[$i][1] = $_POST[$tmp];
		}
	//出口温度
	for($i=1;$i<=$Nc;$i++)
		{
		$tmp = 'Co'.$i;
		@$C[$i][2] = $_POST[$tmp];
		}
	//热容流量
	for($i=1;$i<=$Nc;$i++)
		{
		$tmp = 'Ccp'.$i;
		@$C[$i][3] = $_POST[$tmp];
		}
	//转换虚拟温度
	for($i=1;$i<=$Nh;$i++)
		{
		$H[$i][1] = $H[$i][1] - $dT/2;
		}
	for($i=1;$i<=$Nh;$i++)
		{
		$H[$i][2] = $H[$i][2] - $dT/2;
		}
	for($i=1;$i<=$Nc;$i++)
		{
		$C[$i][1] = $C[$i][1] + $dT/2;
		}
	for($i=1;$i<=$Nc;$i++)
		{
		$C[$i][2] = $C[$i][2] + $dT/2;
		}
	//确定流股温度最值
	$H_max=$H[1][1]; $H_min=$H[1][1]; $C_max=$C[1][1]; $C_min=$C[1][1];
	for($i=1;$i<=$Nh;$i++)
		{
		if($H[$i][1]>=$H_max)
		$H_max = $H[$i][1];
		elseif($H[$i][2]>=$H_max)
		$H_max = $H[$i][2];
		
		if($H[$i][1]<=$H_min)
		$H_min = $H[$i][1];
		elseif($H[$i][2]<=$H_min)
		$H_min = $H[$i][2];
		
		if($C[$i][1]>=$C_max)
		$C_max = $C[$i][1];
		elseif($C[$i][2]>=$H_max)
		$C_max = $C[$i][2];
		
		if($C[$i][1]<=$C_min)
		$C_min = $C[$i][1];
		elseif($C[$i][2]<=$C_min)
		$C_min = $C[$i][2];
		}
	//构建SNv
	$j=1;
	for($i=1;$i<=$Nh;$i++)
		{
		$SNV[$j]=$H[$i][1];
		$j=$j+1;
		}
	for($i=1;$i<=$Nh;$i++)
		{
		$SNV[$j]=$H[$i][2];
		$j=$j+1;
		}
	for($i=1;$i<=$Nc;$i++)
		{
		$SNV[$j]=$C[$i][1];
		$j=$j+1;
		}
	for($i=1;$i<=$Nc;$i++)
		{
		$SNV[$j]=$C[$i][2];
		$j=$j+1;
		}
	//print_r($SNV);
	//删除重复温度
	$SNV = array_unique($SNV);
	//温度从高到低排序
	rsort($SNV);
	//print_r($SNV);
	//填充热容流量
	for($i=1;$i<=count($SNV);$i++)
		{
		$SNv[$i][1]=$SNV[$i-1];
		$SNv[$i][2]=0;
		$SNv[$i][3]=0;
		}
	//print_r($SNv);
	for($i=1;$i<=count($SNv);$i++)
		{
		for($j=1;$j<=$Nc;$j++)
			{
			if($SNv[$i][1]>=$C[$j][1] && $SNv[$i][1]<=$C[$j][2])
				$SNv[$i][2] += $C[$j][3];
			else
				$SNv[$i][2] += 0;
			}
		}
	for($i=1;$i<=count($SNv);$i++)
		{
		for($j=1;$j<=$Nh;$j++)
			{
			if($SNv[$i][1]<=$H[$j][1] && $SNv[$i][1]>=$H[$j][2])
				$SNv[$i][3] += $H[$j][3];
			else
				$SNv[$i][3] += 0;
			}
		}
	//print_r($SNv);
	//echo count($SNv);
	//构建SN表 /温度差/冷CP和/热CP和
	for($i=1;$i<=count($SNv)-1;$i++)
		{
		$SN[$i][1] = $SNv[$i][1] - $SNv[$i+1][1];
		if($SNv[$i][2]==0 || $SNv[$i+1][2]==0)
			$SN[$i][2] = 0;
		else
			$SN[$i][2] = min($SNv[$i][2],$SNv[$i+1][2]);
			
		if($SNv[$i][3]==0 || $SNv[$i+1][3]==0)
			$SN[$i][3] = 0;
		else
			$SN[$i][3] = min($SNv[$i][3],$SNv[$i+1][3]);
		}
	//print_r($SN);
	//构建最终PS表
	for($i=1;$i<=count($SN);$i++)
		{
		$PS[$i][1] = 0;
		$PS[$i][2] = $SN[$i][1]*$SN[$i][2];
		$PS[$i][3] = $SN[$i][1]*$SN[$i][3];
		$PS[$i][1] = $PS[$i][2]-$PS[$i][3];
		if($i==1)
			{ $PS[$i][4] = 0; $PS[$i][5] = -$PS[$i][1]; }
		else
			{ $PS[$i][4] = $PS[$i-1][5]; $PS[$i][5] = $PS[$i][4] - $PS[$i][1]; }
		}
	//查找Ik最小值
	$Qm=$PS[1][4];
	for($i=1;$i<=count($PS);$i++)
		{
		if($PS[$i][4]<$Qm)
			$Qm=$PS[$i][4];		
		}
	for($i=1;$i<=count($PS);$i++)
		{
		$PS[$i][6]=$PS[$i][4]+abs($Qm);
		$PS[$i][7]=$PS[$i][5]+abs($Qm);
		}
	//增补PS
	$j=count($PS);
	for($i=1;$i<=$j+1;$i++)
		$PS[$j+1][$i] = 0;
	//增加温度至最后一列
	for($i=1;$i<=count($PS);$i++)
		{
		$PS[$i][8]=$SNv[$i][1];
		}
	//print_r($PS);
	
	//冷组合曲线赤字差值
	for($i=1;$i<=count($PS);$i++)
		{
		$figC[$i][1]=$PS[$i][2];
		$figC[$i][2]=$PS[$i][8];
		}
	$figC=array_reverse($figC);  //此时角标从0起
	//图像坐标值
	
	for($i=1;$i<=count($figC)-1;$i++)
		{
		$figC[$i][1]+=$figC[$i-1][1];
		}
	//print_r($figC);
	
	//热组合曲线赤字差值
	for($i=1;$i<=count($PS);$i++)
		{
		$figH[$i][1]=$PS[$i][3];
		$figH[$i][2]=$PS[$i][8];
		}
	$figH=array_reverse($figH);
	for($i=1;$i<=count($figH)-1;$i++)
		{
		$figH[$i][1]+=$figH[$i-1][1];
		}
	//print_r($figH);
	//确定分开距离 mov = mov + deltaT
	$mov = $PS[count($PS)-1][7];
	
	//使冷组合曲线右移
	for($i=0;$i<=count($figC)-1;$i++)
		{
		$figC[$i][1] += $mov;
		}
	//print_r($figC);
	
	//绘图
	require_once ('jpgraph/jpgraph.php');
	require_once ('jpgraph/jpgraph_scatter.php');
	
	//设置图像大小
	$width=600;
	$height=400;
	//初始化jpgraph并创建画布
	$graph = new Graph($width,$height);
	$graph->SetScale('intlin');
	//设置左右上下距离
	$graph->SetMargin(40,20,20,40);
	//设置大标题
	$graph->title->Set('T-H');
	//设置小标题
	$graph->subtitle->Set('');
	//设置x轴title
	$graph->xaxis->title->Set('Q');
	//设置y轴title
	$graph->yaxis->title->Set('T');
	//x.y数据
	for($i=0;$i<=count($figC)-1;$i++)
		{
		$xdata[$i] = $figC[$i][1];
		$ydata[$i] = $figC[$i][2];
		}
	for($i=0;$i<=count($figH)-1;$i++)
		{
		$xxdata[$i] = $figH[$i][1];
		$yydata[$i] = $figH[$i][2];
		}
	for($i=0;$i<=count($figH)-1;$i++)
		{
		$xxxdata[$i] = $figC[$i][1] - $figH[$i][1];
		$yyydata[$i] = $figC[$i][2];
		}
	
	$sp1 = new ScatterPlot($ydata,$xdata);
	$sp1->link->Show();
	//$sp1->link->SetWeigth(1);
	$sp1->link->SetColor('blue');
	//$sp1->link->SetStyle('dotted');
	$graph->Add($sp1);
	
	$sp2 = new ScatterPlot($yydata,$xxdata);
	$sp2->link->show();
	$sp2->link->SetColor('red');
	$graph->Add($sp2);
	
	$sp3 = new ScatterPlot($yyydata,$xxxdata);
	$sp3->link->show();
	$sp3->link->SetColor('gray');
	$graph->Add($sp3);
	
	if(file_exists("output.png"))
		unlink("output.png");
	$graph->Stroke("./output.png");
	?>
	<br />
	<img src="output.png"  alt="output" />
	</form>
	</body>
</html>
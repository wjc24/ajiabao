<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>付款单</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style></style>
</head>
<body>
<?php for($t=1; $t<=$countpage; $t++){?>
		<table  width="800"  align="center">

			<tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:50px;"></td>
			</tr>
		    <tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;"><?php echo $system['companyName']?></td>
			</tr>
			<tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:25px;">付款单</td>
			</tr>
		</table>


		<table width="800" align="center">
			<tr height="15" align="left" >
				<td width="250" style="font-family:'宋体'; font-size:14px;height:20px;">购货单位：<?php echo $contactNo.' '.$contactName?> </td>
				<td width="10" ></td>
				<td width="150" >单据日期：<?php echo $billDate?></td>
				<td width="250" >单据编号：<?php echo $billNo?></td>
				<td width="60" >币别：RMB</td>

			</tr>
		</table>


		<table width="800" border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;">

				<tr style="height:20px">
                    <td width="30" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;"  align="center">序号</td>
				    <td width="220" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;"  align="center">结算账户</td>
					<td width="100" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">付款金额</td>
					<td width="90" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">结算方式</td>
					<td width="90" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">结算号</td>
					<td width="90" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">备注</td>
				</tr>

		       <?php
			   $i = ($t-1)*$num + 1;
			   foreach($list as $arr=>$row) {
			       if ($row['i']>=(($t-1)*$num + 1) && $row['i'] <=$t*$num) {
			   ?>
				<tr style="height:20px">
				     <td width="30"  style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;" align="center"><?php echo $row['i']?></td>
					<td width="220" ><?php echo $row['account'];?></td>
					<td width="40" align="center"><?php echo $row['payment']?></td>
					<td width="30" align="center"><?php echo $row['wayId']?></td>
					<td width="40" align="center"><?php echo $row['settlement']?></td>
					<td width="40" align="center"><?php echo $row['remark']?></td>
				</tr>
				<?php
				    $s = $row['i'];
				    }
				    $i++;
				}
				?>

	            <?php
				//补全
				if ($t==$countpage) {
				    for ($m=$s+1;$m<=$t*$num;$m++) {
				?>
				<tr style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">

					<td width="30" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;" align="center"><?php echo $m?></td>
					<td width="220" ></td>
					<td width="30" align="center"></td>
					<td width="40" align="center"></td>
					<td width="40" align="center"></td>
					<td width="40" align="center"></td>
				</tr>
				<?php }}?>

				 <?php if ($t==$countpage) {?>
				 <tr style="height:20px">
                     <td width="60" align="right" ></td>
                    <td width="30" align="right" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">合计：</td>
					<td width="30" align="right" ><?php echo $count?></td>
					<td width="60" align="right" ></td>
					<td width="60" align="right" ></td>
					<td width="60" align="right" ></td>
				</tr>


				<tr target="id">
				    <td colspan="6" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;">合计 金额大写： <?php echo str_num2rmb(abs($totalAmount))?> </td>
				</tr>
				<?php }?>
		</table>


<!--表2-->
    &nbsp;
    <table width="800" border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;">

        <tr style="height:20px">
            <td width="30" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;"  align="center">序号</td>
            <td width="150" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;"  align="center">源单编号</td>
            <td width="50" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">业务类型</td>
            <td width="90" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">单据日期</td>
            <td width="80" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">单据金额</td>
            <td width="80" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">已核销金额</td>
            <td width="80" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">未核销金额</td>
            <td width="80" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">本次核销金额</td>
        </tr>

        <?php
        $i = ($t-1)*$num + 1;
        foreach($list2 as $arr=>$row) {
            if ($row['i']>=(($t-1)*$num + 1) && $row['i'] <=$t*$num) {
                ?>
                <tr style="height:20px">
                    <td width="30"  style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;" align="center"><?php echo $row['i']?></td>
                    <td width="150" ><?php echo $row['billNo'];?></td>
                    <td width="40" align="center"><?php echo $row['transType']?></td>
                    <td width="30" align="center"><?php echo $row['billDate']?></td>
                    <td width="40" align="center"><?php echo $row['billPrice']?></td>
                    <td width="40" align="center"><?php echo $row['hasCheck']?></td>
                    <td width="40" align="center"><?php echo $row['notCheck']?></td>
                    <td width="40" align="center"><?php echo $row['nowCheck']?></td>
                </tr>
                <?php
                $s = $row['i'];
            }
            $i++;
        }
        ?>

        <?php
        //补全
        if ($t==$countpage) {
            for ($m=$s+1;$m<=$t*$num;$m++) {
                ?>
                <tr style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">

                    <td width="30" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;" align="center"><?php echo $m?></td>
                    <td width="150" ></td>
                    <td width="30" align="center"></td>
                    <td width="40" align="center"></td>
                    <td width="40" align="center"></td>
                    <td width="40" align="center"></td>
                    <td width="40" align="center"></td>
                    <td width="40" align="center"></td>
                </tr>
            <?php }}?>

        <?php if ($t==$countpage) {?>
            <tr style="height:20px">
                <td width="60" align="right" ></td>
                <td width="30" align="right" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">合计：</td>
                <td width="30" align="right" ></td>
                <td width="60" align="right" ></td>
                <td width="60" align="right" ></td>
                <td width="60" align="right" ></td>
                <td width="60" align="right" ></td>
                <td width="60" align="right" ><?php echo $count2?></td>
            </tr>


            <tr target="id">
                <td colspan="8" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;">合计 金额大写： <?php echo str_num2rmb(abs($totalAmount))?> </td>
            </tr>
        <?php }?>
    </table>


		<table  width="800" align="center">
		  <tr height="25" align="left">
				<td align="left" width="200" style="font-family:'宋体'; font-size:14px;height:25px;">整单折扣：<?php echo $discount?></td>
				<td width="200" style="font-family:'宋体'; font-size:14px;height:25px;">本次预付款:<?php echo $payment?></td>
				<td width="200" style="font-family:'宋体'; font-size:14px;height:25px;"></td>
				<td width="200" style="font-family:'宋体'; font-size:14px;height:25px;"></td>

		  </tr>
		</table>

		<table  width="800" align="center">
		  <tr height="25" align="left">
				<td align="left" width="960" style="font-family:'宋体'; font-size:14px;height:25px;">备注： <?php echo $description?></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>

		  </tr>
		</table>

		<table  width="800" align="center">
			<tr height="25" align="left">
				<td align="left" width="250" style="font-family:'宋体'; font-size:14px;height:25px;">制单人：<?php echo $userName?> </td>
				<td width="250" style="font-family:'宋体'; font-size:14px;height:25px;">付款方签字：____________</td>
				<td width="250" style="font-family:'宋体'; font-size:14px;height:25px;">收款方签字：____________</td>
				<td width="100" ></td>
				<td width="100" ></td>

			</tr>
		</table>
<?php echo $t==$countpage?'':'<br><br>';}?>



</body>
</html>
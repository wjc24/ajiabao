<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
  		<table width="1440px" class="list" border="1">
  			<tr><td class='H' align="center" colspan="14"><h3>商品销售明细表<h3></td></tr>
  			<tr><td colspan="14">日期：<?php echo $beginDate?>至<?php echo $endDate?></td></tr>
  		</table>
  		<table width="1440px" class="list" border="1">
  			<thead>
  				<tr>
  				<th>销售日期</th>
  				<th>销售单据号</th>
  				<th>业务类别</th>
				<th>销售人员</th>
  				<th>客户</th>
  				<th>商品编号</th>
  				<th>商品名称</th>
  				<th>规格型号</th>
  				<th>单位</th>
  				<th>品牌</th>
  				<th>仓库</th>
  				<th>数量</th>
  				<th>单价</th>
  				<th>销售收入</th>
  				<th>课税前</th>
				<th>备注</th>
                <th>收款情况</th>
  				</tr>
  			</thead>
  			<tbody>
				 <?php 
				 $sum1 = $sum2 = $sum3 = 0;
				 foreach($list as $arr=>$row){
				     $sum1 += $qty    = $row['qty']>0 ? -abs($row['qty']) : abs($row['qty']);   
					 $sum3 += $amount = $row['amount'];        
				 ?>
  			       <tr>
  			       <td><?php echo $row['billDate']?></td>
  			       <td><?php echo $row['billNo']?></td>
  			       <td><?php echo $row['transTypeName']?></td>
				   <td><?php echo $row['salesName']?></td>
  			       <td><?php echo $row['contactName']?></td>
  			       <td><?php echo $row['invNumber']?></td>
  			       <td><?php echo $row['invName']?></td>
  			       <td><?php echo $row['invSpec']?></td>
  			       <td><?php echo $row['mainUnit']?></td>
  			       <td><?php echo $row['invBrand']?></td>
  			       <td><?php echo $row['locationName']?></td>
				   <td class="R"><?php echo str_money($qty,$this->systems['qtyPlaces'])?></td>
  			       <td class="R"><?php echo str_money($row['price'],$this->systems['qtyPlaces'])?></td>
  			       <td class="R"><?php echo str_money($amount,2)?></td>
  			       <td class="R"><?php if ($row['amountType'] == '1'){
                           echo '是';
                       }else{
                           echo '';
                       }?></td>
				   <td class="R"><?php echo $row['description']?></td>
                   <td class="R"><?php echo abs($row['hasCheck']) <= 0 ? '未收款' : (abs($row['hasCheck'])>=abs($row['amount'])? '全部收款' : '部分收款')?></td>
  			       </tr>
				 <?php 
				 }
				 ?>
  				<tr>
  				<td colspan="11" class="R B">合计：</td>
				<td class="R B"><?php echo str_money($sum1,$this->systems['qtyPlaces'])?></td>
  				<td class="R B"><?php echo $sum1>0 ? str_money($sum3/$sum1,$this->systems['qtyPlaces']) : 0?></td>
  				<td class="R B"><?php echo str_money($sum3,2)?></td>
				<td class="R B"></td>
  				</tr>
  			</tbody>
  		</table>
 
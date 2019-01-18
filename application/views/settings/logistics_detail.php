<?php $this->load->view('header');?>

<script type="text/javascript">
    var DOMAIN = document.domain;
    var WDURL = "";
    var SCHEME= "<?php echo sys_skin()?>";
    try{
        document.domain = '<?php echo base_url()?>';
    }catch(e){
    }
    //ctrl+F5 增加版本号来清空iframe的缓存的
    $(document).keydown(function(event) {
        /* Act on the event */
        if(event.keyCode === 116 && event.ctrlKey){
            var defaultPage = Public.getDefaultPage();
            var href = defaultPage.location.href.split('?')[0] + '?';
            var params = Public.urlParam();
            params['version'] = Date.parse((new Date()));
            for(i in params){
                if(i && typeof i != 'function'){
                    href += i + '=' + params[i] + '&';
                }
            }
            defaultPage.location.href = href;
            event.preventDefault();
        }
    });
</script>
<link href="<?php echo base_url()?>statics/css/<?php echo sys_skin()?>/bills.css?ver=20150427" rel="stylesheet" type="text/css">
<style>
    .label-wrap>label{
        font: 12px/1.5 arial, \5b8b\4f53;
        color: #555;
    }
    .ui-input{
        width: 150px;
        height: 16px;
    }
    .row-item{
        float: left;
        width: 30%;
        padding: 0;
        margin: 0;
    }
    .btn{
        width: 70px;
        height: 30px;
        color: #555;
        border: 1px solid #c1c1c1;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,.15);
        font: 14px/2 \5b8b\4f53;
        background: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#f4f4f4));
        vertical-align: middle;
        cursor: pointer;
    }
    .clearfix::before,
    .clearfix::after{
        content:'';
        display: block;
        line-height: 0;
        height: 0;
        visibility: hidden;
        clear: both;
    },
    .table{
        width: 100%;
    }
    table{
        border-collapse:collapse;
    }
    .table tr{
        border: 1px solid #000;
        height: 33px;
    }
    .table th{
        background-color: #f1f1f1;
        height: 30px;
    }
    .table th,td{
        border-right: 1px solid #e2e2e2;
        border-bottom: 1px solid #e2e2e2;
        border-top: 1px solid #fff;
        border-left: 1px solid #fff;
        /*width: 100px;*/
        height: 33px;
        text-align: center;
    }
    .table td:last-child{
        text-align: left;
    }
    .table tr:hover{
        background-color: #f8ff94;
    }
    .table td>span{
        display: inline-block;
        width: 100px;
        height: 33px;
        line-height: 33px;
        margin-bottom: -6px;
        overflow: hidden;
        text-overflow:ellipsis;
    }
    .table .write,
    .table .add,
    .table .delete{
        display: inline-block;
        width: 16px;
        height: 16px;
        background-image: url(<?php echo base_url()?>statics/css/img/ui-icons_20150410.png);
    }
    .table .write{
        background-position: -112px -16px;
    }
    .table .delete{
        background-position: -64px -16px;
    }
    .table .add{
        background-position: -80px -0px;
    }
    .one_category{
        text-align: left;
    }
    .two_category{
        text-align: center;
    }
    .three_category{
        text-align: right;
    }
</style>
</head>
<body>
<div class="wrapper">
    <span id="config" class="ui-icon ui-state-default ui-icon-config"></span>
    <span style="margin-left: 2%;color: red;">本页面的内容不可修改，如需修改请前往物流管理。</span>
    <input type="hidden" id="invoice_info_id" value="<?php echo $id ?>">
    <input type="hidden" id="max_num" value="<?php echo $max_num ?>">
    <?php foreach ($data as $k=>$v) :?>

    <div class="bills">
        <div class="grid-wrap mb10" id="acGridWrap">
            <ul style="font-size: 20px;font-weight: bold">发货公司资料</ul>
            <ul class="mod-form-rows base-form clearfix" id="base-form">

                <li class="row-item">
                    <div class="label-wrap" style="width: 21%;"><label for="name">船运/航空公司名称:</label></div>
                    <div class="ctn-wrap"><input type="text" readonly value="<?php echo  $v->shipping_name ?>" class="ui-input" name="shipping_name" id="shipping_name"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="birthday">订舱号:</label></div>
                    <div class="ctn-wrap"><input type="text" readonly value="<?php echo  $v->booking_number ?>" class="ui-input" name="booking_number" id="booking_number"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="birthday">到达港口:</label></div>
                    <div class="ctn-wrap"><input type="text" readonly value="<?php echo  $v->port ?>" class="ui-input" name="port" id="port"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="birthday">箱子个数:</label></div>
                    <div class="ctn-wrap"><input type="number" readonly min="0" value="<?php echo  $v->boxes ?>" class="ui-input" name="boxes" id="boxes"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap" style="width: 21%;"><label for="birthday">箱子总体积(立方米):</label></div>
                    <div class="ctn-wrap"><input type="number" readonly min="0" value="<?php echo  $v->box_volume ?>" class="ui-input" name="box_volume" id="box_volume"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap" style="width: 21%;"><label for="birthday">运输商品数:</label></div>
                    <div class="ctn-wrap"><input type="number" min="0" readonly value="<?php echo  $v->good_logistics ?>" class="ui-input" name="good_logistics" id="good_logistics"></div>
                </li>
                <li class="row-item" style="width: 40%;">
                    <div class="label-wrap" style="width: 10%;"><label for="name">物流单:</label></div>
                    <div class="ctn-wrap"><input type="text" readonly value="<?php echo  $v->logistics ?>" class="ui-input" name="logistics" id="logistics">（建议格式:WL+年月日+日流水号，如WL201901180001）</div>
                </li>
            </ul>
            <div class="label-wrap" style="width: 10%;"><label for="trade_remarks">贸易公司备注:</label></div>
            <textarea name="" id="trade_remarks" readonly cols="250" rows="10"><?php echo  $v->trade_remarks ?></textarea>
            <br>
            <div class="table">
                <table style="width: 100%;">

                    <thead>
                    <tr>
                        <th style="width: 10%;">操作</th>
                        <th style="width: 20%;">类别</th>
                        <th style="width: 20%;">名称</th>
                        <th style="width: 40%;">添加</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">
                    <?php foreach (json_decode($v->content) as $key=>$val) :?>
                        <tr class="one">
                            <td>
                                <span class="add"></span>
                                <span class="delete"></span>
                            </td>
                            <td class="one_category">集装箱号</td>
                            <td><input type="text" readonly value="<?php echo $val->container_number?>" class="container_number"></td>
                            <td><span></span></td>
                        </tr>
                        <?php if($val->child) :?>
                            <?php foreach ($val->child as $k1=>$v1) :?>
                                <tr class="two">
                                    <td>
                                        <span class="add"></span>
                                        <span class="delete"></span>
                                    </td>
                                    <td class="two_category">托盘号</td>
                                    <td><input type="text" readonly value="<?php echo $v1->tray_number ?>" class="tray_number"></td>
                                    <td><span></span></td>
                                </tr>
                                <?php if($v1->child) :?>
                                    <?php foreach ($v1->child as $k2=>$v2) :?>

                                        <tr class="three">
                                            <td>
                                                <span class="add"></span>
                                                <span class="delete"></span>
                                            </td>
                                            <td class="three_category">箱子号</td>
                                            <td><input type="text" readonly value="<?php echo $v2->single_box ?>" class="single_box"></td>
                                            <td>长(米)：<input type="number" readonly min="0" value="<?php echo $v2->long ?>" class="long" style="width: 10%;" > 宽(米)：<input type="number" min="0" readonly class="wide" value="<?php echo $v2->wide ?>" style="width: 10%;"> 高(米)：<input type="number" min="0" readonly class="high" style="width: 10%;" value="<?php echo $v2->high ?>"> 体积(立方米)：<input type="number" min="0" style="width: 15%;" readonly class="volume" value="<?php echo $v2->volume ?>" readonly></td>
                                        </tr>

                                    <?php endforeach;?>
                                <?php endif;?>
                            <?php endforeach;?>
                        <?php endif;?>
                    <?php endforeach;?>

                    </tbody>
                </table>
            </div>
            <br>
            <div class="label-wrap" style="width: 10%;"><label for="logistics_remarks">物流公司备注:</label></div>
            <textarea name="" id="logistics_remarks" cols="250" rows="10" readonly><?php echo  $v->logistics_remarks ?></textarea>
        </div>

    </div>
    <?php endforeach;?>
</div>


</body>

</html>


 
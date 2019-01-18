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
    .table .adds,
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
    .table .adds{
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
    .one{
        background-color: Pink;
    }
    .two{
        background-color: NavajoWhite;
    }
    .three{
        background-color: PapayaWhip;
    }
    /*施工人员弹框*/
    #add_people .add_content .parts_l{
        float: left;
        height: 100%;
        width: 100%;
        border: 1px solid #f1f1f1;
        overflow-y: auto;
    }
    #add_people .add_content .parts_l table{
        /*height: 99.99%;*/
        width: 99.99%;
    }
    #add_people .add_content .parts_l table thead tr{
        /*width: 453px;*/
        width: 100%;
        height: 40px;
        background-color: #ddd;
        /*position: fixed;*/
        /*top: 57px;*/
    }
    #add_people .add_content .parts_l table tbody tr:hover{
        background-color: #f8ff94;
    }
    #add_people .add_content .parts_l table tbody tr td{
        width: 11.111%;
        height: 30px;
        border: none;
        border-bottom: 1px solid #f1f1f1;
        border-right: 1px solid #f1f1f1;
    }
    #add_people .add_content .parts_r{
        float: right;
        height: 100%;
        width: 30%;
        border: 1px solid #f1f1f1;
        overflow-y: auto;
    }
    .add{
        position: fixed;
        width: 770px;
        height: 500px;
        background-color: #fff;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        box-shadow: 1px 1px 10px 10px #a9a9a9;
        border-radius: 3px;
        z-index: 1998;
    }
    .add>.add_header{
        background-color: #f5f5f5;
        height: 32px;
        width: 100%;
        border-radius: 3px;
    }
    .add>.add_header>.add_title{
        float: left;
        height: 32px;
        line-height: 32px;
        font-size: 14px;
        font-weight: 700;
        margin-left: 10px;
    }
    .add>.add_header>.add_close{
        float: right;
        height: 32px;
        line-height: 32px;
        color: #aaa;
        font-size: 18px;
        width: 20px;
        cursor: pointer;
    }
    .add>.add_content{
        width: 100%;
        height: 435px;
        box-sizing: border-box;
        padding: 25px;
    }
    .add>.add_content>.content_title{
        height: 18px;
        width: 100%;
        border-bottom: 1px solid #ccc;
    }
    .add>.add_content>.content_main{
        width: 100%;
        box-sizing: border-box;
        padding: 20px 0;
    }
    .add>.add_content>.content_main:first-child{
        height: 50%;
    }
    .add>.add_content>.content_main:last-child{
        height: 20%;
    }
    .add>.add_content>.content_main>li{
        width: 50%;
        float: left;
        margin-bottom: 5px;
    }
    .add>.add_content>.content_main>li>span{
        display: inline-block;
        width: 70px;
        height: 30px;
    }
    .add>.add_content>.content_main>li>input{
        width: 140px;
        height: 24px;
        border: 1px solid #ddd;
    }
    .add>.add_content>.content_main>li>span>select{
        border: none;
        width: 100%;
        height: 100%;
    }
    .add>.add_content>.content_main>li>.sel{
        display: inline-block;
        border: 1px solid #ddd;
        height: 24px;
        line-height: 24px;
        width: 140px;
        margin-left: -3px;
        outline: none;
    }
    .add_footer{
        position: absolute;
        width: 770px;
        height: 33px;
        bottom: 0;
        right: 0;
    }
</style>
</head>
<body>
<div class="wrapper">
    <span id="config" class="ui-icon ui-state-default ui-icon-config"></span>
    <input type="hidden" id="logistics_id" value="<?php echo $data->logistics_id ?>">
    <input type="hidden" id="invoice_info_id" value="<?php echo $id ?>">
    <input type="hidden" id="max_num" value="<?php echo $max_num ?>">

        <div class="bills">
            <div class="grid-wrap mb10" id="acGridWrap">
                <ul style="font-size: 20px;font-weight: bold">发货公司资料</ul>  <span style="margin-left: 90%;"><a class="ui-btn mrb add_people" id="">提醒</a></span>
                <ul class="mod-form-rows base-form clearfix" id="base-form">

                    <li class="row-item">
                        <div class="label-wrap" style="width: 21%;"><label for="name">船运/航空公司名称:</label></div>
                        <div class="ctn-wrap"><input type="text" value="<?php echo  $data->shipping_name ?>" class="ui-input" name="shipping_name" id="shipping_name"></div>
                    </li>
                    <li class="row-item">
                        <div class="label-wrap"><label for="birthday">订舱号:</label></div>
                        <div class="ctn-wrap"><input type="text" value="<?php echo  $data->booking_number ?>" class="ui-input" name="booking_number" id="booking_number"></div>
                    </li>
                    <li class="row-item">
                        <div class="label-wrap"><label for="birthday">到达港口:</label></div>
                        <div class="ctn-wrap"><input type="text" value="<?php echo  $data->port ?>" class="ui-input" name="port" id="port"></div>
                    </li>
                    <li class="row-item">
                        <div class="label-wrap"><label for="birthday">箱子个数:</label></div>
                        <div class="ctn-wrap"><input type="number" min="0" value="<?php echo  $data->boxes ?>" class="ui-input" name="boxes" id="boxes"></div>
                    </li>
                    <li class="row-item">
                        <div class="label-wrap" style="width: 21%;"><label for="birthday">箱子总体积(立方米):</label></div>
                        <div class="ctn-wrap"><input type="number" readonly min="0" value="<?php echo  $data->box_volume ?>" class="ui-input" name="box_volume" id="box_volume"></div>
                    </li>
                    <li class="row-item">
                        <div class="label-wrap" style="width: 21%;"><label for="birthday">运输商品数:</label></div>
                        <div class="ctn-wrap"><input type="number" min="0" readonly value="<?php echo  $data->good_logistics ?>" class="ui-input" name="good_logistics" id="good_logistics"></div>
                    </li>
                    <li class="row-item" style="width: 40%;">
                        <div class="label-wrap" style="width: 10%;"><label for="name">物流单:</label></div>
                        <div class="ctn-wrap"><input type="text" readonly value="<?php echo  $data->logistics ?>" class="ui-input" name="logistics" id="logistics">（建议格式:WL+年月日+日流水号，如WL201901180001）</div>
                    </li>
                </ul>
                <div class="label-wrap" style="width: 10%;"><label for="trade_remarks">贸易公司备注:</label></div>
                <textarea name="" id="trade_remarks" cols="250" rows="10"><?php echo  $data->trade_remarks ?></textarea>
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
                        <?php foreach (json_decode($data->content) as $key=>$val) :?>
                            <tr class="one">
                                <td>
                                    <span class="adds"></span>
                                    <span class="delete"></span>
                                </td>
                                <td class="one_category">集装箱号</td>
                                <td><input type="text" value="<?php echo $val->container_number?>" class="container_number"></td>
                                <td><span></span></td>
                            </tr>
                            <?php if($val->child) :?>
                                <?php foreach ($val->child as $k1=>$v1) :?>
                                    <tr class="two">
                                        <td>
                                            <span class="adds"></span>
                                            <span class="delete"></span>
                                        </td>
                                        <td class="two_category">托盘号</td>
                                        <td><input type="text" value="<?php echo $v1->tray_number ?>" class="tray_number"></td>
                                        <td><span></span></td>
                                    </tr>
                                    <?php if($v1->child) :?>
                                        <?php foreach ($v1->child as $k2=>$v2) :?>

                                            <tr class="three">
                                                <td>
                                                    <span class="adds"></span>
                                                    <span class="delete"></span>
                                                </td>
                                                <td class="three_category">箱子号</td>
                                                <td><input type="text" value="<?php echo $v2->single_box ?>" class="single_box"></td>
                                                <td>长(米)：<input type="number" min="0" value="<?php echo $v2->long ?>" class="long" style="width: 10%;" > 宽(米)：<input type="number" min="0" class="wide" value="<?php echo $v2->wide ?>" style="width: 10%;"> 高(米)：<input type="number" min="0" class="high" style="width: 10%;" value="<?php echo $v2->high ?>"> 体积(立方米)：<input type="number" min="0" style="width: 15%;" class="volume" value="<?php echo $v2->volume ?>" readonly></td>
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
                <textarea name="" id="logistics_remarks" cols="250" rows="10"><?php echo  $data->logistics_remarks ?></textarea>
            </div>

            <div style="height: 30px;text-align: center;">
                <?php if($data->status == 1) :?>
                    <button type="button" class="btn" id="submit" style="border: 1px solid #3279a0;background: -webkit-gradient(linear,0 0,0 100%,from(#4994be),to(#337fa9));color: #fff;width: 8%;">保存并确认领货</button>
                <?php else:?>
                    <button type="button" class="btn" id="submit" style="border: 1px solid #3279a0;background: -webkit-gradient(linear,0 0,0 100%,from(#4994be),to(#337fa9));color: #fff;">保存</button>
                <?php endif;?>
            </div>
        </div>

</div>
<div id="ldg_lockmask" style="position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; overflow: hidden; z-index: 1977;display: none;"></div>

<!--选择提醒人员弹框-->
<div id="add_people" class="add" style="display: none;">
    <div class="add_header clearfix">
        <div class="add_title">选择提醒人员</div>
        <div class="add_close close_add">&times;</div>
    </div>
    <div class="add_content clearfix">
        物流单号：<input type="text" id="logistic" placeholder="请输入物流单号">
        <div class="parts_l">

            <table>
                <thead>
                <tr>
                    <th style="width: 5%;">
                        <input type="checkbox" id="all" checked>
                    </th>
                    <th>名字</th>
                </tr>
                </thead>
                <tbody class="parts_main">
                <?php foreach ($customer as $k=>$v) :?>
                    <tr class="people_tr">
                        <td class="check">
                            <input type="checkbox" checked class="check_child" value="<?php echo $v->id ?>"><!--放id-->
                        </td>
                        <td class="people_td userName"><?php echo $v->nickname ?></td>

                    </tr>
                <?php endforeach;?>
                </tbody>
                </thead>
            </table>
        </div>
    </div>
    <div class="add_footer">
        <td colspan="2">
            <div class="ui_buttons">

                <input type="button" id="add_people_val" value="确定" class="ui_state_highlight" />
                <input type="button" class="close_add" value="关闭" />
            </div>
        </td>
    </div>
</div>

</body>
<script>
    $(function () {
        var box_number = 0;
        var box_all_volume = 0;
        $("#tbody").on('click','.adds',function () {
            var category = $(this).parent().parent().attr('class');

            if(category == "one"){

                var add ='<tr class="one">\n' +
                    '                                <td>\n' +
                    '                                    <span class="adds"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="one_category" >集装箱号</td>\n' +
                    '                                <td><input type="text" value="" class="container_number"></td>\n' +
                    '                                <td><span></span></td>\n' +
                    '                            </tr>\n' +
                    '                            <tr class="two">\n' +
                    '                                <td>\n' +
                    '                                    <span class="adds"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="two_category" >托盘号</td>\n' +
                    '                                <td><input type="text" value="" class="tray_number"></td>\n' +
                    '                                <td><span></span></td>\n' +
                    '                            </tr>\n' +
                    '                            <tr class="three">\n' +
                    '                                <td>\n' +
                    '                                    <span class="adds"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="three_category" >箱子号</td>\n' +
                    '                                <td><input type="text" value="" class="single_box"></td>\n' +
                    '                                <td>长(米)：<input type="number" min="0" value="0" class="long" style="width: 10%;" > 宽(米)：<input type="number" min="0" value="0" class="wide" style="width: 10%;"> 高(米)：<input type="number" min="0" class="high" value="0" style="width: 10%;"> 体积(立方米)：<input type="number" min="0" value="0" style="width: 15%;" class="volume" readonly></td>\n' +
                    '                            </tr>' ;

                $('#tbody').append(add);
            }else if(category == "two"){
                var add =' <tr class="two">\n' +
                    '                                <td>\n' +
                    '                                    <span class="adds"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="two_category" >托盘号</td>\n' +
                    '                                <td><input type="text" value="" class="tray_number"></td>\n' +
                    '                                <td><span></span></td>\n' +
                    '                            </tr>\n' +
                    '                            <tr class="three">\n' +
                    '                                <td>\n' +
                    '                                    <span class="adds"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="three_category" >箱子号</td>\n' +
                    '                                <td><input type="text" value="" class="single_box"></td>\n' +
                    '                                <td>长(米)：<input type="number" min="0" value="0" class="long" style="width: 10%;" > 宽(米)：<input type="number" min="0" value="0" class="wide" style="width: 10%;"> 高(米)：<input type="number" min="0" class="high" value="0" style="width: 10%;"> 体积(立方米)：<input type="number" min="0" value="0" style="width: 15%;" class="volume" readonly></td>\n' +
                    '                            </tr>' ;
                $(this).parent().parent().before(add);
            }else if(category == "three"){
                var add ='<tr class="three">\n' +
                    '                                <td>\n' +
                    '                                    <span class="adds"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="three_category" >箱子号</td>\n' +
                    '                                <td><input type="text" value="" class="single_box"></td>\n' +
                    '                                <td>长(米)：<input type="number" min="0" value="0" class="long" style="width: 10%;" > 宽(米)：<input type="number" min="0" value="0" class="wide" style="width: 10%;"> 高(米)：<input type="number" min="0" class="high" value="0" style="width: 10%;"> 体积(立方米)：<input type="number" min="0" value="0" style="width: 15%;" class="volume" readonly></td>\n' +
                    '                            </tr>';
                $(this).parent().parent().after(add);
            }

        });

        $("#tbody").on('click','.delete',function () {
            category = $(this).parent().parent().attr('class');

            if(category == "one"){
                if($(this).parent().parent().next().attr('class') == "two"){
                    parent.Public.tips({
                        type:1,
                        content:"请先删除集装箱内的箱子！"
                    });
                }else{
                    $(this).parent().parent().remove();
                }
            }else if(category == "two"){
                if($(this).parent().parent().next().attr('class') == "three"){
                    parent.Public.tips({
                        type:1,
                        content:"请先删除托盘内的箱子！"
                    });
                }else{
                    $(this).parent().parent().remove();
                }

            }else if(category == "three"){
                $(this).parent().parent().remove();
            }

        });

        //计算每个箱子的体积
        $("#tbody").on('input','.long',function () {
            long = $(this).val();
            wide = $(this).parent().find('.wide').val();
            high = $(this).parent().find('.high').val();
            if(!long){
                long = 0;
            }else if(!wide){
                wide =0;
            }else if(!high){
                high =0;
            }
            $(this).parent().find('.volume').val((long*wide*high).toFixed(4));
            Calculation_box();
        });
        $("#tbody").on('input','.wide',function () {
            wide = $(this).val();
            long = $(this).parent().find('.long').val();
            high = $(this).parent().find('.high').val();
            if(!long){
                long = 0;
            }else if(!wide){
                wide =0;
            }else if(!high){
                high =0;
            }
            $(this).parent().find('.volume').val((long*wide*high).toFixed(4));
            Calculation_box();
        });
        $("#tbody").on('input','.high',function () {
            high = $(this).val();
            wide = $(this).parent().find('.wide').val();
            long = $(this).parent().find('.long').val();
            if(!long){
                long = 0;
            }else if(!wide){
                wide =0;
            }else if(!high){
                high =0;
            }
            $(this).parent().find('.volume').val((long*wide*high).toFixed(4));
            Calculation_box();
        });

        //选择提醒人员
        $('.close_add').on('click',function () {
            $('#ldg_lockmask').css('display','none');
            $('#add').css('display','none');
            $("#invoice_info_id").val('');
            $('#add_people').css('display','none');
        });
        $("#save").click(function(){
            var shipping_name = $("#shipping_name").val();
            var booking_number = $("#booking_number").val();
            var container_number = $("#container_number").val();
            var tray_number = $("#tray_number").val();
            var box_number = $("#box_number").val();
            var boxes = $("#boxes").val();
            var long = $("#long").val();
            var wide = $("#wide").val();
            var high = $("#high").val();
            var box_volume = $("#box_volume").val();
            var port = $("#port").val();
            var invoice_info_id = $("#invoice_info_id").val();


            if(!shipping_name){
                parent.Public.tips({
                    type:1,
                    content:"请填写全发货信息！"
                });
            }else{
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('deliver/doadd');?>",
                    traditional: true,
                    data: {
                        shipping_name: shipping_name,
                        booking_number:booking_number,
                        container_number:container_number,
                        tray_number:tray_number,
                        box_number:box_number,
                        boxes:boxes,
                        long:long,
                        wide:wide,
                        high:high,
                        box_volume:box_volume,
                        port:port,
                        invoice_info_id:invoice_info_id,
                    },
                    dataType: "json",

                    success: function (data) {
                        if(data.code == 1){
                            parent.Public.tips({
                                content:data.text
                            });
                            id = $("#id").val();
                            location.href = "<?php echo site_url('deliver/index')?>";
                        }else if (data.code == 2){
                            parent.Public.tips({
                                type:1,
                                content:data.text
                            });
                        } else{
                            parent.Public.tips({
                                type:1,
                                content:"未知错误"
                            });
                        }

                    },
                });
            }
        });
        $('.add_people').on('click',function () {
            $('#add_people').show();
            $('#ldg_lockmask').show();

        });
        $('#add_people_val').on('click',function () {
            var logistic = $("#logistic").val();
            var userName = new Array();
            $.each($('.check_child'),function(i,val){
                if($(this).is(':checked')){
                    userName.push($(this).val());
                }
            });
            if(!logistic || !userName){
                parent.Public.tips({
                    type:1,
                    content:"请填写物流单号和选择通知人员！"
                });
            }else{

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('deliver/remind');?>",
                    traditional: true,
                    data: {
                        logistic:logistic,
                        userName:JSON.stringify(userName),
                    },
                    dataType: "json",

                    success: function (data) {

                        if(data.code == 1){
                            parent.Public.tips({
                                content:data.text
                            });
                            changes();
                        }else if (data.code == 2){
                            parent.Public.tips({
                                type:1,
                                content:data.text
                            });
                        } else{
                            parent.Public.tips({
                                type:1,
                                content:"未知错误"
                            });
                        }

                    },
                });
            }

        });

        //计算箱子的个数和总体积
        function Calculation_box(){
            $('.volume').each(function (i,val) {
                box_number ++;
                box_all_volume += parseFloat($(this).val());
            });
            $("#box_volume").val(box_all_volume);
            $("#boxes").val(box_number);
            box_number = 0;
            box_all_volume = 0;
        }

        $(".bills").on('click',"#submit",function () {

            var shipping_name = $("#shipping_name").val();
            var booking_number = $("#booking_number").val();
            var port = $("#port").val();
            var boxes = $("#boxes").val();
            var box_volume = $("#box_volume").val();
            var invoice_info_id = $("#invoice_info_id").val();
            var good_name = $("#good_name").val();
            var good_logistics = $("#good_logistics").val();
            var logistics_id = $("#logistics_id").val();
            var logistics = $("#logistics").val();
            var trade_remarks = $("#trade_remarks").val();
            var logistics_remarks = $("#logistics_remarks").val();

            max_num = $("#max_num").val();


            if(!shipping_name || !booking_number || !port || !logistics || !good_logistics){
                parent.Public.tips({
                    type:1,
                    content:"船运/航空公司名称，订舱号，到达港口，物流单,商品数量不能为空！"
                });
            }else{
                var box = new Array();
                one = 0;
                two = 0;
                three = 0;

                $.each($("#tbody>tr"),function(i,val){
                    category = $(this).attr('class');
                    if(category == "one"){
                        if(!box[one]){
                            box[one] = {};
                        }

                        box[one]['container_number'] = $(this).find('.container_number').val();

                        next = $(this).next().attr('class');
                        if(next == "one"){
                            one++;
                            two = 0;
                            three = 0;
                        }
                    }
                    else if(category == "two"){
                        if(!box[one]['child']){
                            box[one]['child'] = {};
                        }

                        if(!box[one]['child'][two]){
                            box[one]['child'][two] = {};
                        }

                        box[one]['child'][two]['tray_number'] = $(this).find('.tray_number').val();

                        next = $(this).next().attr('class');
                        if(next == "one"){
                            one++;
                            two = 0;
                            three = 0;
                        }else if(next == "two"){
                            two++;
                            three = 0;
                        }
                    }else if(category == "three"){
                        if(!box[one]['child'][two]['child']){
                            box[one]['child'][two]['child'] = {};
                        }
                        if(!box[one]['child'][two]['child'][three]){
                            box[one]['child'][two]['child'][three] = {};
                        }

                        box[one]['child'][two]['child'][three]['single_box'] = $(this).find('.single_box').val();
                        box[one]['child'][two]['child'][three]['long'] = $(this).find('.long').val();
                        box[one]['child'][two]['child'][three]['wide'] = $(this).find('.wide').val();
                        box[one]['child'][two]['child'][three]['high'] = $(this).find('.high').val();
                        box[one]['child'][two]['child'][three]['volume'] = $(this).find('.volume').val();
                        next = $(this).next().attr('class');

                        if(next == "one"){
                            one++;
                            two = 0;
                            three = 0;
                        }else if(next == "two"){
                            two++;
                            three = 0;
                        }else if(next == "three"){
                            three++;
                        }

                    }

                });

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('deliver/logistics_edit');?>",
                    cache:false,
                    data: {
                        shipping_name: shipping_name,
                        booking_number: booking_number,
                        port:port,
                        boxes:boxes,
                        box_volume:box_volume,
                        box:box,
                        invoice_info_id:invoice_info_id,
                        good_name:good_name,
                        good_logistics:good_logistics,
                        logistics:logistics,
                        logistics_id:logistics_id,
                        trade_remarks:trade_remarks,
                        logistics_remarks:logistics_remarks,
                    },
                    dataType: "json",

                    success: function (data) {

                        if(data.code == 1){
                            parent.Public.tips({
                                content:data.text
                            });

                            location.reload();
                        }else if (data.code == 2){
                            parent.Public.tips({
                                type:1,
                                content:data.text
                            });
                        } else{
                            parent.Public.tips({
                                type:1,
                                content:"未知错误"
                            });
                        }

                    },
                });
            }

        });
    });
</script>

</html>



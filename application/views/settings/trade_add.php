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
    .one{
        background-color: Pink;
    }
    .two{
        background-color: NavajoWhite;
    }
    .three{
        background-color: PapayaWhip;
    }
</style>
</head>
<body>
<div class="wrapper">
    <span id="config" class="ui-icon ui-state-default ui-icon-config"></span>
    <input type="hidden" id="invoice_info_id" value="<?php echo $id ?>">
    <input type="hidden" id="good_name" value="<?php echo $good_name ?>">
    <input type="hidden" id="max_num" value="<?php echo $max_num ?>">
    <div class="bills">
        <div class="grid-wrap mb10" id="acGridWrap">
            <ul style="font-size: 20px;font-weight: bold">发货公司资料</ul>
            <ul class="mod-form-rows base-form clearfix" id="base-form">

                <li class="row-item">
                    <div class="label-wrap" style="width: 21%;"><label for="name">船运/航空公司名称:</label></div>
                    <div class="ctn-wrap"><input type="text" required value="" class="ui-input" name="shipping_name" id="shipping_name" placeholder="必填" ></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="birthday">订舱号:</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="booking_number" id="booking_number" placeholder="必填"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="birthday">到达港口:</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="port" id="port" placeholder="必填"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="birthday">箱子个数:</label></div>
                    <div class="ctn-wrap"><input type="number" min="0" value="0"  readonly class="ui-input" name="boxes" id="boxes" placeholder="填完箱子可自动计算"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap" style="width: 21%;"><label for="birthday">箱子总体积(立方米):</label></div>
                    <div class="ctn-wrap"><input type="number"  min="0" value="0" readonly class="ui-input" name="box_volume" id="box_volume" placeholder="填完箱子可自动计算"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap" style="width: 21%;"><label for="birthday">运输商品数:</label></div>
                    <div class="ctn-wrap"><input type="number" min="0" value="" class="ui-input" name="good_logistics" id="good_logistics" placeholder="必填"></div>
                </li>
                <li class="row-item" style="width: 40%;">
                    <div class="label-wrap" style="width: 10%;"><label for="name">物流单:</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="logistics" id="logistics" placeholder="必填">（建议格式:WL+年月日+日流水号，如WL201901180001）</div>
                </li>
            </ul>
            <div class="label-wrap" style="width: 10%;"><label for="trade_remarks">贸易公司备注:</label></div>
            <textarea name="" id="trade_remarks" cols="250" rows="10"></textarea>
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
                    <tr class="one">
                        <td>
                            <span class="add"></span>
                            <span class="delete"></span>
                        </td>
                        <td class="one_category">集装箱号</td>
                        <td><input type="text" value="" class="container_number"></td>
                        <td><span></span></td>
                    </tr>
                    <tr class="two">
                        <td>
                            <span class="add"></span>
                            <span class="delete"></span>
                        </td>
                        <td class="two_category">托盘号</td>
                        <td><input type="text" value="" class="tray_number"></td>
                        <td><span></span></td>
                    </tr>
                    <tr class="three">
                        <td>
                            <span class="add"></span>
                            <span class="delete"></span>
                        </td>
                        <td class="three_category">箱子号</td>
                        <td><input type="text" value="" class="single_box"></td>
                        <td>长(米)：<input type="number" min="0" value="0" class="long" style="width: 10%;" > 宽(米)：<input type="number" min="0" class="wide" style="width: 10%;" value="0" > 高(米)：<input type="number" min="0" value="0" class="high" style="width: 10%;"> 体积(立方米)：<input type="number" min="0" style="width: 15%;" value="0" class="volume" readonly></td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <br>
            <div class="label-wrap" style="width: 10%;"><label for="logistics_remarks">物流公司备注:</label></div>
            <textarea name="" id="logistics_remarks" cols="250" rows="10"></textarea>
        </div>

        <div style="height: 30px;text-align: center;">
            <button type="button" class="btn" id="submit" style="border: 1px solid #3279a0;background: -webkit-gradient(linear,0 0,0 100%,from(#4994be),to(#337fa9));color: #fff;">保存</button>
        </div>
    </div>
</div>


</body>
<script>
    $(function () {
        var box_number = 0;
        var box_all_volume = 0;
        $("#tbody").on('click','.add',function () {
            var category = $(this).parent().parent().attr('class');

            if(category == "one"){

                var add ='<tr class="one">\n' +
                    '                                <td>\n' +
                    '                                    <span class="add"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="one_category" >集装箱号</td>\n' +
                    '                                <td><input type="text" value="" class="container_number"></td>\n' +
                    '                                <td><span></span></td>\n' +
                    '                            </tr>\n' +
                    '                            <tr class="two">\n' +
                    '                                <td>\n' +
                    '                                    <span class="add"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="two_category" >托盘号</td>\n' +
                    '                                <td><input type="text" value="" class="tray_number"></td>\n' +
                    '                                <td><span></span></td>\n' +
                    '                            </tr>\n' +
                    '                            <tr class="three">\n' +
                    '                                <td>\n' +
                    '                                    <span class="add"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="three_category" >箱子号</td>\n' +
                    '                                <td><input type="text" value="" class="single_box"></td>\n' +
                    '                                <td>长(米)：<input type="number" min="0" value="0" class="long" style="width: 10%;" > 宽(米)：<input type="number" min="0" value="0" class="wide" style="width: 10%;"> 高(米)：<input type="number" min="0" class="high" style="width: 10%;" value="0" > 体积(立方米)：<input type="number" min="0" value="0" style="width: 15%;" class="volume" readonly></td>\n' +
                    '                            </tr>' ;

                    $('#tbody').append(add);
            }else if(category == "two"){
                var add =' <tr class="two">\n' +
                    '                                <td>\n' +
                    '                                    <span class="add"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="two_category" >托盘号</td>\n' +
                    '                                <td><input type="text" value="" class="tray_number"></td>\n' +
                    '                                <td><span></span></td>\n' +
                    '                            </tr>\n' +
                    '                            <tr class="three">\n' +
                    '                                <td>\n' +
                    '                                    <span class="add"></span>\n' +
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
                    '                                    <span class="add"></span>\n' +
                    '                                    <span class="delete"></span>\n' +
                    '                                </td>\n' +
                    '                                <td class="three_category" >箱子号</td>\n' +
                    '                                <td><input type="text" value="" class="single_box"></td>\n' +
                    '                                <td>长(米)：<input type="number" min="0" value="0" class="long" style="width: 10%;" > 宽(米)：<input type="number" min="0" class="wide" value="0" style="width: 10%;"> 高(米)：<input type="number" min="0" class="high" value="0" style="width: 10%;"> 体积(立方米)：<input type="number" min="0" value="0" style="width: 15%;" class="volume" readonly></td>\n' +
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


        //判断商品发货的最大数量
        $("#acGridWrap").on('input','#good_logistics',function (){
            max_num = $("#max_num").val();
            good_logistics = $("#good_logistics").val();
            if(good_logistics > max_num){
                parent.Public.tips({
                    type:1,
                    content:"商品数量已超出！"
                });
            }
        });
        $(".bills").on('click',"#submit",function () {

            var shipping_name = $("#shipping_name").val();
            var booking_number = $("#booking_number").val();
            var port = $("#port").val();
            var boxes = $("#boxes").val();
            var box_volume = $("#box_volume").val();
            var invoice_info_id = $("#invoice_info_id").val();
            var good_name = $("#good_name").val();
            var good_logistics = $("#good_logistics").val();
            var logistics = $("#logistics").val();
            var trade_remarks = $("#trade_remarks").val();
            var logistics_remarks = $("#logistics_remarks").val();

            max_num = $("#max_num").val();


            if(!shipping_name || !booking_number || !port || !logistics || !good_logistics){
                parent.Public.tips({
                    type:1,
                    content:"船运/航空公司名称，订舱号，到达港口，物流单,商品数量不能为空！"
                });
            }else if(good_logistics > max_num){
                parent.Public.tips({
                    type:1,
                    content:"商品数量已超出！"
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
                    url: "<?php echo site_url('trade/doadd');?>",
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


 
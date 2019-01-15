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

<style>
    .clearfix::before,
    .clearfix::after{
        content:'';
        display: block;
        line-height: 0;
        height: 0;
        visibility: hidden;
        clear: both;
    }
    .grid-wrap{
        background-color: #fff;
        height: 800px;
        width: 100%;
        overflow: auto;
        position: relative;
    }
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
        width: 100px;
        height: 33px;
        text-align: center;
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

    }
    #page{
        position: absolute;
        bottom: 0;
        width: 100%;
        background-color: #f1f1f1;
        line-height: 30px;
    }
    #page>div{
        float: left;
        width: 33.333%;
        text-align: center;
    }
    #page>div:last-child{
        text-align: right;
    }
    .page_center>div{
        float: left;
        margin-left: 10px;
    }
    .page_center>div:first-child{
        background-image: url(<?php echo base_url()?>statics/css/img/ui-icons_20150410.png);
        background-repeat: no-repeat;
        background-position: -48px 0px;
        width: 16px;
        height: 16px;
        margin-top: 8px;
    }
    .page_center>div:nth-child(2){
        background-image: url(<?php echo base_url()?>statics/css/img/ui-icons_20150410.png);
        background-repeat: no-repeat;
        background-position: -16px 0px;
        width: 16px;
        height: 16px;
        margin-top: 8px;
    }
    .page_center>div:nth-child(3){
        width: 42px;
        height: 18px;
    }
    .page_center>div:nth-child(3)>input{
        width: 100%;
        height: 100%;
    }
    .page_center>div:nth-child(5){
        background-image: url(<?php echo base_url()?>statics/css/img/ui-icons_20150410.png);
        background-repeat: no-repeat;
        background-position: 0px 0px;
        width: 16px;
        height: 16px;
        margin-top: 8px;
    }
    .page_center>div:nth-child(6){
        background-image: url(<?php echo base_url()?>statics/css/img/ui-icons_20150410.png);
        background-repeat: no-repeat;
        background-position: -32px 0px;
        width: 16px;
        height: 16px;
        margin-top: 8px;
    }
    .detail{
        background: #78cd51;
        border-color: #78cd51;
        color: #fff;
        font-weight: bold;
    }
    .detail:hover{
        background: #78cd51;
        color: #fff;
        font-weight: bold;
    }
    #add{
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
    #add>#add_header{
        background-color: #f5f5f5;
        height: 32px;
        width: 100%;
        border-radius: 3px;
    }
    #add>#add_header>#add_title{
        float: left;
        height: 32px;
        line-height: 32px;
        font-size: 14px;
        font-weight: 700;
        margin-left: 10px;
    }
    #add>#add_header>#add_close{
        float: right;
        height: 32px;
        line-height: 32px;
        color: #aaa;
        font-size: 18px;
        width: 20px;
        cursor: pointer;
    }
    #add>#add_content{
        width: 100%;
        height: 435px;
        box-sizing: border-box;
        padding: 25px;
    }
    #add>#add_content>.content_title{
        height: 18px;
        width: 100%;
        border-bottom: 1px solid #ccc;
    }
    #add>#add_content>.content_main{
        width: 100%;
        box-sizing: border-box;
        padding: 20px 0;
    }
    #add>#add_content>.content_main:first-child{
        height: 50%;
    }
    #add>#add_content>.content_main:last-child{
        height: 20%;
    }
    #add>#add_content>.content_main>li{
        width: 50%;
        float: left;
        margin-bottom: 5px;
    }
    #add>#add_content>.content_main>li>span{
        display: inline-block;
        width: 70px;
        height: 30px;
    }
    #add>#add_content>.content_main>li>input{
        width: 140px;
        height: 24px;
        border: 1px solid #ddd;
    }
    #add>#add_content>.content_main>li>span>select{
        border: none;
        width: 100%;
        height: 100%;
    }
    #add>#add_content>.content_main>li>.sel{
        display: inline-block;
        border: 1px solid #ddd;
        height: 24px;
        line-height: 24px;
        width: 140px;
        margin-left: -3px;
        outline: none;
    }
    #add_footer{
        position: absolute;
        width: 770px;
        height: 33px;
        bottom: 0;
        right: 0;
    }
    #sel{
        width: 200px;
        height: 30px;
        border: 1px solid #ddd;
    }
</style>
</head>
<body>
<div class="wrapper">
    <form action="<?php echo site_url('deliver/index');?>" method="post" id="form">
        <div class="mod-search cf">
<!--            <div class="fl">-->
<!--                <ul class="ul-inline">-->
<!--                    <li>-->
<!--                        <span id="catorage"></span>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        --><?php //if ($like) :?>
<!--                            <input type="text" id="matchCon"  name="matchCon" class="ui-input ui-input-ph matchCon" value ="--><?php //echo $like ?><!--" style="width: 280px;">-->
<!--                        --><?php //else:?>
<!--                            <input type="text" id="matchCon"  name="matchCon" class="ui-input ui-input-ph matchCon" placeholder="输入卡号" style="width: 280px;">-->
<!--                        --><?php //endif; ?>
<!--                    </li>-->
<!--                    <li><a class="ui-btn mrb" id="search" >查询</a></li>-->
<!--                    <li>-->
<!--                        <select name="sel" id="sel" onchange="changes()">-->
<!--                            --><?php //if($sel == 0) :?>
<!--                                <option value="0" selected>请选择</option>-->
<!--                            --><?php //else :?>
<!--                                <option value="0" >请选择</option>-->
<!--                            --><?php //endif ;?>
<!--                            --><?php //if($sel == 1) :?>
<!--                                <option value="1" selected>未售出</option>-->
<!--                            --><?php //else :?>
<!--                                <option value="1">未售出</option>-->
<!--                            --><?php //endif ;?>
<!--                            --><?php //if($sel == 2) :?>
<!--                                <option value="2" selected>售出未充值</option>-->
<!--                            --><?php //else :?>
<!--                                <option value="2">售出未充值</option>-->
<!--                            --><?php //endif ;?>
<!--                            --><?php //if($sel == 3) :?>
<!--                                <option value="3" selected>售出已充值</option>-->
<!--                            --><?php //else :?>
<!--                                <option value="3">售出已充值</option>-->
<!--                            --><?php //endif ;?>
<!--                        </select>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </div>-->
        </div>
        <div class="grid-wrap">
            <div class="table">
                <input type="hidden" id="id" value="<?php echo $id ?>">
                <table style="width: 100%;">
                    <thead >
                    <tr>
                        <th>订单号</th>
                        <th>商品名称</th>
                        <th>商品数量</th>
                        <th>船运/航空公司名称</th>
                        <th>订舱号</th>
                        <th>集装箱号</th>
                        <th>托盘号</th>
                        <th>箱子编号</th>
                        <th>箱子个数</th>
                        <th>箱子总体积</th>
                        <th>到达港口</th>
                        <th>修改</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($data) :?>
                        <?php foreach ($data as $k=>$v): ?>
                            <tr>
                                <td class="billNo"><span><?php echo $v['billNo'] ?></span></td>
                                <td><span><?php echo $v['name'] ?></span></td>
                                <td><span><?php echo $v['num'] ?></span></td>
                                <td><span><?php echo $v['shipping_name'] ?></span></td>
                                <td><span><?php echo $v['booking_number'] ?></span></td>
                                <td><span><?php echo $v['container_number'] ?></span></td>
                                <td><span><?php echo $v['tray_number'] ?></span></td>
                                <td><span><?php echo $v['box_number'] ?></span></td>
                                <td><span><?php echo $v['boxes'] ?></span></td>
                                <td><span><?php echo $v['box_volume'] ?></span></td>
                                <td><span><?php echo $v['port'] ?></span></td>
                                <td><span><a href="javascript:void(0);" class="ui-btn ui-btn-sp edit">修改</a><input type="hidden"  value="<?php echo $v['good_id'] ?>"></span></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else:?>
                        <tr><td colspan="11">暂无记录</td></tr>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
            <div id="page">
                <div class="page_left">&nbsp;</div>
                <div class="page_center">
                    <div id="first"></div>
                    <div id="previous"></div>
                    <div>
                        <?php if ($page_now) :?>
                            <input type="number" name="page_now" id="page_now" oninput="changes()" value="<?php echo $page_now ?>" min="1" max="<?php echo $page_all ?>">
                        <?php else:?>
                            <input type="number" name="page_now" id="page_now" oninput="changes()" value="1" min="1" max="<?php echo $page_all ?>">
                        <?php endif;?>
                    </div>
                    <div>共 <span id="page_all"><?php echo $page_all ?></span> 页</div>
                    <div id="next"></div>
                    <div id="last"></div>
                    <div>
                        <select name="page_num" id="page_num" onchange="changes()">
                            <?php if ($page_num == 20) :?>
                                <option value="20" selected>20</option>
                            <?php else:?>
                                <option value="20">20</option>
                            <?php endif;?>
                            <?php if ($page_num == 50) :?>
                                <option value="50" selected>50</option>
                            <?php else:?>
                                <option value="50">50</option>
                            <?php endif;?>
                            <?php if ($page_num == 100) :?>
                                <option value="100" selected>100</option>
                            <?php else:?>
                                <option value="100">100</option>
                            <?php endif;?>

                        </select>
                    </div>
                </div>
                <!--                <div class="page_right">1 -  1 &nbsp;&nbsp; 共  1  条</div>-->
            </div>
        </div>
    </form>
</div>

<div id="ldg_lockmask" style="position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; overflow: hidden; z-index: 1977;display: none;"></div>
<div id="add" style="display: none;">
    <div id="add_header" class="clearfix">
        <div id="add_title">修改发货信息</div>
        <div id="add_close" class="close_add">&times;</div>
    </div>
    <div id="add_content">
        <ul class="content_title"><h3>基本资料</h3></ul>
        <ul class="content_main clearfix">
            <input type="hidden" value="" id="good_id">
            <input type="hidden" value="" id="billNo">
            <li><span>船运/航空公司名称:</span><input type="text" id="shipping_name"></li>
            <li><span>订舱号:</span><input type="text" id="booking_number"></li>
            <li><span>集装箱号:</span><input type="text" id="container_number"></li>
            <li><span>托盘号:</span><input type="text"  id="tray_number"></li>
            <li><span>箱子编号:</span><input type="text" id="box_number"></li>
            <li><span>箱子个数:</span><input type="number" min="0" id="boxes"></li>
            <li><span>箱子长(米):</span><input oninput="volume();" type="number" min="0" id="long"></li>
            <li><span>箱子宽(米):</span><input oninput="volume()" type="number" min="0" id="wide"></li>
            <li><span>箱子高(米):</span><input oninput="volume()" type="number" min="0" id="high"></li>
            <li ><span>箱子总体积(立方米):</span><input readonly type="text" id="box_volume"></li>
            <li><span>到达港口:</span><input type="text" id="port"></li>
        </ul>
    </div>
    <div id="add_footer">
        <td colspan="2">
            <div class="ui_buttons">
                <input type="hidden" value="" id="id">
                <input type="button" id="save" value="保存" class="ui_state_highlight" />
                <input type="button" class="close_add" value="关闭" />
            </div>
        </td>
    </div>
</div>

<script>
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
        var good_id = $("#good_id").val();
        var billNo = $("#billNo").val();


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
                    good_id:good_id,
                    billNo:billNo,
                },
                dataType: "json",

                success: function (data) {
                    console.log(data);
                    if(data.code == 1){
                        parent.Public.tips({
                            content:data.text
                        });
                         id = $("#id").val();
                        location.href = "<?php echo site_url('deliver/add?id=')?>"+id;
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

    $("#search").click(function () {
        $("#form").submit();
    });
    //计算箱子体积
    function volume() {

        long = $('#long').val();
        wide = $('#wide').val();
        high = $('#high').val();
        if(!long){
            long = 0;
        }else if(!wide){
            wide =0;
        }else if(!high){
            high =0;
        }
        $('#box_volume').val((long*wide*high).toFixed(4));
    }
    function changes() {
        document.getElementById("search").click();
    }
    $("#first").click(function () {
        var page_now = parseInt($("#page_now").val());
        if(page_now !== 1){
            $("#page_now").val(1);
            changes();
        }

    });
    $("#previous").click(function () {
        var page_now = parseInt($("#page_now").val());
        if(page_now >1){
            $("#page_now").val(page_now-1);
            changes();
        }

    });
    $("#next").click(function () {
        var page_now = parseInt($("#page_now").val());
        var page_all = parseInt($("#page_all").text());
        if(page_now<page_all){
            $("#page_now").val(page_now+1);
            changes();
        }
    });
    $("#last").click(function () {
        var page_all = parseInt($("#page_all").text());
        var page_now = parseInt($("#page_now").val());
        if(page_now < page_all){
            $("#page_now").val(page_all);
            changes();
        }
    });
</script>
<script>
    $(function () {
        // 单选框
        $('#all').on('click',function () {
            var thisChecked = $(this).prop('checked');
            $('.check_child').prop('checked',thisChecked);

        });

        $('.check_child').on('click',function(){
            var totalNum =  $('.check_child').length;
            var checkedNum =  $('.check_child:checked').length;
            $('#all').prop('checked',totalNum==checkedNum);
        });

        $('#btn-batchDel').on('click',function () {
            var checkitems = new Array();
            $.each($('.check_child:checked'),function(){
                checkitems.push($(this).val());
            });
            if (checkitems != ''){
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('card/del');?>",
                    data: {
                        id: checkitems,
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if(data){
                            parent.Public.tips({
                                content:"删除成功"
                            });
                            location.href = "<?php echo site_url('card')?>";
                        } else{
                            parent.Public.tips({
                                type:1,
                                content:"删除失败"
                            });
                        }

                    },
                    error:function () {
                        parent.Public.tips({
                            type:1,
                            content:"出错啦！"
                        });
                    }
                })
            } else{
                parent.Public.tips({
                    type:2,
                    content:"未选择要删除的项！"
                });
            }
        });

        // 添加
        $('.edit').on('click',function () {
            $('#ldg_lockmask').css('display','');
            $('#add').css('display','');
            good_id = $(this).parent().find('input').val();
            $("#good_id").val(good_id);
            billNo = $(this).parent().parent().parent().find('.billNo').text();
            $("#billNo").val(billNo);
        });
        $('.close_add').on('click',function () {
            $('#ldg_lockmask').css('display','none');
            $('#add').css('display','none');
            $("#good_id").val('');
            $("#billNo").val('');
        });

        $("#search").click(function () {
            $("#form").submit();
        });
    });
</script>
<script>
    Public.pageTab();
    reportParam();
    function reportParam(){
        $("[tabid^='report']").each(function(){
            var dateParams = "beginDate="+parent.SYSTEM.beginDate+"&endDate="+parent.SYSTEM.endDate;
            var href = this.href;
            href += (this.href.lastIndexOf("?")===-1) ? "?" : "&";
            if($(this).html() === '商品库存余额表'){
                this.href = href + "beginDate="+parent.SYSTEM.startDate+"&endDate="+parent.SYSTEM.endDate;
            }
            else{
                this.href = href + dateParams;
            }
        });
    }

    var goodsCombo = Business.goodsCombo($('#goodsAuto'), {
        extraListHtml: ''
    });

</script>
</body>
</html>



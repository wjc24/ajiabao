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
        border: 1px solid #ddd;
        height: 720px;
        width: 98.5%;
        overflow: auto;
        position: relative;
    }
    .table{
        width: 100.5%;
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

        height: 33px;
        line-height: 33px;

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

    #sel{
        width: 200px;
        height: 30px;
        border: 1px solid #ddd;
    }
</style>
</head>
<body>
<div class="wrapper">
    <form action="<?php echo site_url('sign/index');?>" method="post" id="form">
        <div class="mod-search cf">
            <div class="fl">
                <ul class="ul-inline">
                    <li>
                        <span id="catorage"></span>
                    </li>
                    <li>
                        <?php if ($like) :?>
                            <input type="text" name="matchCon" id="matchCon" class="ui-input ui-input-ph matchCon" value ="<?php echo $like ?>" style="width: 280px;">
                        <?php else:?>
                            <input type="text" name="matchCon" id="matchCon" class="ui-input ui-input-ph matchCon" placeholder="输入用户名 查询" style="width: 280px;">
                        <?php endif; ?>

                    </li>
                    <li><a class="ui-btn mrb" id="search">查询</a></li>
                    <li>
                        <select name="sel" id="sel" onchange="changes()">
                            <?php if($sel == 0) :?>
                                <option value="0" selected>请选择</option>
                            <?php else :?>
                                <option value="0" >请选择</option>
                            <?php endif ;?>
                            <?php if($sel == 1) :?>
                                <option value="1" selected>一个月内</option>
                            <?php else :?>
                                <option value="1">一个月内</option>
                            <?php endif ;?>
                            <?php if($sel == 2) :?>
                                <option value="2" selected>六个月内</option>
                            <?php else :?>
                                <option value="2">六个月内</option>
                            <?php endif ;?>
                            <?php if($sel == 3) :?>
                                <option value="3" selected>一年内</option>
                            <?php else :?>
                                <option value="3">一年内</option>
                            <?php endif ;?>

                        </select>
                    </li>
                </ul>
            </div>

        </div>
        <div class="grid-wrap">
            <div class="table">
                <table style="width: 100%;">
                    <thead style="width: 100%;">
                    <tr style="width: 100%;">

                        <th style="width: 25%;">用户名</th>
                        <th style="width: 25%;">签到时间</th>
                        <th style="width: 25%;">总共签到次数</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($data) :?>
                        <?php foreach ($data as $k=>$v) :?>
                            <tr>
                                <td><span><?php echo $v->username ?></span></td>
                                <td><span><?php echo date('Y-m-d H:i',$v->time) ?></span></td>
                                <td><span><?php echo $v->sign_all ?></span></td>
                            </tr>
                        <?php endforeach;?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">暂无记录</td>
                        </tr>
                    <?php endif ;?>

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
                    url: "",
                    type: "POST",
                    data:{id:checkitems},
                    dataType: "JSON",
                    success:function (res) {
                        if (res == 1){
                            parent.Public.tips({
                                content:'删除成功！'
                            });
                        } else{
                            parent.Public.tips({
                                type:1,
                                content:'删除失败！'
                            });
                        }

                    },
                    error:function () {
                        parent.Public.tips({
                            type:1,
                            content:'出错啦！'
                        });
                    }
                })
            } else{
                parent.Public.tips({
                    type:2,
                    content:'未选择要删除的项！'
                });
            }
        });

        $("#search").click(function () {
            $("#form").submit();
        });
    });
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


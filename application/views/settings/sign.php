<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/statics/sign/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/statics/sign/css/htmleaf-demo.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/statics/sign/css/style.css">
</head>
<body>
<div class="htmleaf-container">

    <div class="qiandao-warp">
        <div class="qiandap-box">
            <div class="qiandao-con clear">
                <div class="qiandao-left">
                    <div class="qiandao-left-top clear">
                        <div class="current-date"></div>

                        <div class="qiandao-history qiandao-tran qiandao-radius" id="js-qiandao-history">我的签到</div>
                        <div class="qiandao-history" id="go" style="margin-right: 20px;" hidden>进入系统</div>
                        <input type="hidden" id="user_id" value="<?php echo $user_id ?>">
                        <input type="hidden" id="username" value="<?php echo $username ?>">
                    </div>
                    <div class="qiandao-main" id="js-qiandao-main">
                        <ul class="qiandao-list" id="js-qiandao-list">
                        </ul>
                    </div>
                </div>
                <div class="qiandao-right">
                    <div class="qiandao-top">
                        <div class="just-qiandao qiandao-sprits" id="js-just-qiandao">
                        </div>
                        <p class="qiandao-notic">每日签到，否则无法进入系统</p>
                    </div>
                    <div class="qiandao-bottom">
                        <div class="qiandao-rule-list">
                            <h4>签到规则</h4>
                            <p>每位员工每日进入系统需签到，否则无法进入系统。</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 我的签到 layer start -->
    <div class="qiandao-layer qiandao-history-layer">
        <div class="qiandao-layer-con qiandao-radius" style="height:130px;">
            <a href="javascript:;" class="close-qiandao-layer qiandao-sprits"></a>
            <ul class="qiandao-history-inf clear">
                <li style="margin-left: 10%; ">
                    <p>连续签到</p>
                    <h4></h4>
                </li>
                <li>
                    <p>本月签到</p>
                    <h4></h4>
                </li>
                <li>
                    <p>总共签到数</p>
                    <h4></h4>
                </li>
            </ul>

        </div>
        <div class="qiandao-layer-bg"></div>
    </div>
    <!-- 我的签到 layer end -->
    <!-- 签到 layer start -->
    <div class="qiandao-layer qiandao-active">
        <div class="qiandao-layer-con qiandao-radius" style="height:130px;">
            <a href="javascript:;" class="close-qiandao-layer qiandao-sprits"></a>
            <div class="yiqiandao clear">
                <div class="yiqiandao-icon qiandao-sprits" style="margin-left: 20%; "></div>
            </div>
        </div>
        <div class="qiandao-layer-bg"></div>
    </div>
</div>

<script src="http://cdn.bootcss.com/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
<script>window.jQuery || document.write('<script src="<?php echo base_url()?>/statics/sign/js/jquery-1.11.0.min.js"><\/script>')</script>
<script type="text/javascript">
    $(function(){
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth()+1;
        var day = date.getDate();
        $(".current-date").text(year+"年"+month+"月"+day+"日");
    });
    $(function() {
        var dateArray = [] ;// 假设已经签到的
        var $dateBox = $("#js-qiandao-list"),
            $currentDate = $(".current-date"),
            $qiandaoBnt = $("#js-just-qiandao"),
            _html = '',
            _handle = true,
            myDate = new Date();
        $currentDate.text(myDate.getFullYear() + '年' + parseInt(myDate.getMonth() + 1) + '月' + myDate.getDate() + '日');

        var signFun = function() {
            user_id = $("#user_id").val();
            $.ajax({
                url: "<?php echo site_url('home/signStart');?>",
                type: "POST",
                async:false,
                data:{user_id:user_id},
                dataType: "JSON",
                success:function (res) {

                    if(res.length != 0){
                        var myDate = new Date();
                        day = myDate.getDate();        //获取当前日(1-31)

                        $('.qiandao-history-inf').find('li:first>h4').html(res[0].continuity);
                        $('.qiandao-history-inf').find('li:nth-child(2)>h4').html(res.length);
                        $('.qiandao-history-inf').find('li:nth-child(3)>h4').html(res[0].sign_all);
                        for(var i=0,l=res.length;i<l;i++){

                            var index = res[i].sign_time .lastIndexOf("-");
                            str  = res[i].sign_time .substring(index + 1, res[i].sign_time .length);
                            dateArray.push(str -1);

                        }

                        if (dateArray.indexOf(day-1) !== -1) {

                            $qiandaoBnt.addClass('actived');
                            _handle = false;
                            $('.qiandao-notic').html("今日已签，请明日继续签到");
                            $("#go").show();
                        }
                    }

                },
                error:function () {
                    parent.Public.tips({
                        type:1,
                        content:"出错啦！"
                    });
                }
            });




            var monthFirst = new Date(myDate.getFullYear(), parseInt(myDate.getMonth()), 1).getDay();

            var d = new Date(myDate.getFullYear(), parseInt(myDate.getMonth() + 1), 0);
            var totalDay = d.getDate(); //获取当前月的天数

            for (var i = 0; i < 42; i++) {
                _html += ' <li><div class="qiandao-icon"></div></li>'
            }
            $dateBox.html(_html) //生成日历网格

            var $dateLi = $dateBox.find("li");
            for (var i = 0; i < totalDay; i++) {
                $dateLi.eq(i + monthFirst).addClass("date" + parseInt(i + 1));
                for (var j = 0; j < dateArray.length; j++) {
                    if (i == dateArray[j]) {
                        $dateLi.eq(i + monthFirst).addClass("qiandao");
                    }
                }
            } //生成当月的日历且含已签到

            $(".date" + myDate.getDate()).addClass('able-qiandao');

            $dateBox.on("click", "li", function() {
                if ($(this).hasClass('able-qiandao') && _handle) {
                    $(this).addClass('qiandao');
                    qiandaoFun();
                }
            }); //签到

            $qiandaoBnt.on("click", function() {

                if (_handle) {
                    qiandaoFun();
                }
            }); //签到

            function qiandaoFun() {

                user_id = $("#user_id").val();
                username = $("#username").val();

                $.ajax({
                    url: "<?php echo site_url('home/sign');?>",
                    type: "POST",
                    async:false,
                    data:{user_id:user_id,username:username},
                    dataType: "JSON",
                    success:function (res) {

                        if (res){
                            $qiandaoBnt.addClass('actived');
                            openLayer("qiandao-active", qianDao);
                            _handle = false;
                            $('.qiandao-notic').html("今日已签，请明日继续签到");
                            $("#go").show();
                            a = $('.qiandao-history-inf').find('li:first>h4').html();
                            b = $('.qiandao-history-inf').find('li:nth-child(2)>h4').html();
                            c = $('.qiandao-history-inf').find('li:nth-child(3)>h4').html();
                            $('.qiandao-history-inf').find('li:first>h4').html(parseInt(a)+1);
                            $('.qiandao-history-inf').find('li:nth-child(2)>h4').html(parseInt(b)+1);
                            $('.qiandao-history-inf').find('li:nth-child(3)>h4').html(parseInt(c)+1);
                        } else{
                            parent.Public.tips({
                                type:1,
                                content:"签到失败！"
                            });
                        }

                    },
                    error:function () {
                        parent.Public.tips({
                            type:1,
                            content:"出错啦！"
                        });
                    }
                });

            }

            function qianDao() {
                $(".date" + myDate.getDate()).addClass('qiandao');
            }
        }();

        function openLayer(a, Fun) {
            $('.' + a).fadeIn(Fun)
        } //打开弹窗

        var closeLayer = function() {
            $("body").on("click", ".close-qiandao-layer", function() {
                $(this).parents(".qiandao-layer").fadeOut()
            })
        }() //关闭弹窗

        $("#js-qiandao-history").on("click", function() {
            openLayer("qiandao-history-layer", myFun);

            function myFun() {
                console.log(1)
            } //打开弹窗返回函数
        });
        
        $("#go").click(function () {
            location.href = "<?php echo site_url('home/index')?>";
        });

    });
</script>
</body>
</html>
<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<title>在线进销存</title>
<link href="<?php echo base_url()?>statics/css/common.css?ver=20140430" rel="stylesheet">
<link href="<?php echo base_url()?>statics/css/<?php echo sys_skin()?>/ui.min.css?ver=20140430" rel="stylesheet">
<script src="<?php echo base_url()?>statics/js/common/seajs/2.1.1/sea.js?ver=20140430" id="seajsnode"></script>
<script src="<?php echo base_url()?>statics/js/common/libs/jquery/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
var WDURL = "";
var SCHEME= "<?php echo sys_skin()?>";
try{
	document.domain = '<?php echo base_url()?>';
}catch(e){
	//console.log(e);
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
<link rel="stylesheet" href="<?php echo base_url()?>statics/css/report.css?2" />
<link href="<?php echo base_url()?>statics/css/help/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
<style>
    .select-menu{
        margin-bottom: 1%;
        background-color: #fff;
        width: 226px;
    }
    .select-menu-ul{
        margin-top:50px;
        list-style:none;
        opacity:0;
        display:none;
        width:256px;
        text-align:left;
        border:1px solid #ddd;
        background:white;
        position:absolute;
        z-index:1;
        height: 120px;
        /*overflow-y: scroll;*/
        /*overflow-x: hidden;*/
        cursor: pointer;
    }
    .select-menu-ul li{
        width: 248px;
        height: 22px;
        line-height: 22px;
        overflow: hidden;
        padding:2% 0 2% 3%;
        font: 12px/1.5 arial, 宋体;
        color: rgb(85, 85, 85);
    }
    .select-menu-ul li:hover{
        background:#eee;

    }
    .select-menu-div{
        position:relative;
        height:30px;
        width:256px;
        background-color: white;
        border:1px solid #ddd;
        line-height:30px;
    }
    .select-this{
        background:#d2d2d2;
    }
    .select-this:hover{
        background:#d2d2d2!important;
    }
    i{
        margin-right:5px;
        position:absolute;
        right:0;
        top:7px;

    }
    .select-menu-input{
        margin-left:3%;
        border:0;
        height:15px;
        cursor:pointer;
        user-select:none;
        background-color: white;
    }
    .select-menu-i{
        transform:rotate(180deg);

    }
</style>
</head>
<body>
<div class="mod-report">
  <div class="search-wrap" id="report-search">
    <div class="s-inner cf">
      <div class="fl"> <strong class="tit mrb fl">选择查询条件：</strong>
        <div class="ui-btn-menu fl" id="filter-menu"> <span class="ui-btn menu-btn"> <strong id="selected-period">请选择查询条件</strong><b></b> </span>
          <div class="con">
            <ul class="filter-list">
              <li>
                <label class="tit">日期:</label>
                <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-fromDate" id="filter-fromDate" maxlength="10" />
                <span>至</span>
                <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-toDate" id="filter-toDate" maxlength="10" />
              </li>
            </ul>
            <ul class="filter-list" id="more-conditions">
              <li>
                <label class="tit">客户:</label>
                <span class="mod-choose-input" id="filter-customer"><input type="text" class="ui-input" id="customerAuto"/><span class="ui-icon-ellipsis"></span></span>
              </li>
              <li style="height:60px; ">
                <label class="tit">商品:</label>
                <span class="mod-choose-input" id="filter-goods"><input type="text" class="ui-input" id="goodsAuto"/><span class="ui-icon-ellipsis"></span></span>
                <p style="color:#999; padding:3px 0 0 0; ">（可用,分割多个编码如1001,1008,2001，或直接输入编码段如1001--1009查询）</p>
              </li>
                <li>
                    <label class="tit">品牌:</label>
                    <span class="mod-choose-input" id="filter-brand"><input type="text" class="ui-input" id="brandAuto"/><span class="ui-icon-ellipsis"></span></span>
                </li>
              <li>
                <label class="tit">仓库:</label>
                <span class="mod-choose-input" id="filter-storage"><input type="text" class="ui-input" id="storageAuto"/><span class="ui-icon-ellipsis"></span></span>
              </li>
              <li>
                <label class="tit">销售人员:</label>
                <span class="mod-choose-input" id="filter-saler"><input type="text" class="ui-input" id="salerAuto"><span class="ui-icon-ellipsis"></span></span>
              </li>
              <!--<li class="chk-list dn" id="profit-wrap">
                <label class="chk">
                  <input type="checkbox" value="1" name="profit" />
                  计算毛利</label>
              </li>-->
                <li>
                    <label class="tit">付款情况:</label>
                    <div class="select-menu ui-btn mrb" style="padding: 0;">
                        <div class="select-menu-div" id="filter-status" >
                            <input readonly class="select-menu-input" style="font: 12px/1.5 arial, 宋体;" />
                        </div>
                        <ul class="select-menu-ul" >
                            <li class="select-this">全部</li>
                            <li>全部收款</li>
                            <li>未收款</li>
                            <li>部分收款</li>
                        </ul>
                    </div>
                </li>
            </ul>
            <div class="btns"> <a href="#" id="conditions-trigger" class="conditions-trigger" tabindex="-1">更多条件<b></b></a> <a class="ui-btn ui-btn-sp" id="filter-submit" href="#">确定</a> <a class="ui-btn" id="filter-reset" href="#" tabindex="-1">重置</a> </div>
          </div>
        </div>
        <a id="refresh" class="ui-btn ui-btn-refresh fl mrb"><b></b></a> <span class="txt fl" id="cur-search-tip"></span> </div>
      <div class="fr"><!--<a href="#" class="ui-btn ui-btn-sp mrb fl" id="btn-print">打印</a>--><a href="#" class="ui-btn fl" id="btn-export">导出</a></div>
    </div>
  </div>
	<div class="no-query"></div>
	<div class="ui-print">
    <span id="config" class="ui-icon ui-state-default ui-icon-config"></span>
		<div class="grid-wrap" id="grid-wrap">
			<div class="grid-title">商品销售明细表</div>
			<div class="grid-subtitle"></div>
	    	<table id="grid"></table>
	   	</div>
	</div>

</div>

<script>
	seajs.use("dist/salesDetail");
</script>
<script>
    $(function(){
        selectMenu(0);
        selectMenu(1);
        function selectMenu(index){
            $(".select-menu-input").eq(index).val($(".select-this").eq(index).html());//在输入框中自动填充第一个选项的值
            $(".select-menu-div").eq(index).on("click",function(e){
                e.stopPropagation();
                if($(".select-menu-ul").eq(index).css("display")==="block"){
                    $(".select-menu-ul").eq(index).hide();
                    $(".select-menu-div").eq(index).find("i").removeClass("select-menu-i");
                    $(".select-menu-ul").eq(index).animate({marginTop:"50px",opacity:"0"},"fast");
                }else{
                    $(".select-menu-ul").eq(index).show();
                    $(".select-menu-div").eq(index).find("i").addClass("select-menu-i");
                    $(".select-menu-ul").eq(index).animate({marginTop:"5px",opacity:"1"},"fast");
                }
                for(var i=0;i<$(".select-menu-ul").length;i++){
                    if(i!==index&& $(".select-menu-ul").eq(i).css("display")==="block"){
                        $(".select-menu-ul").eq(i).hide();
                        $(".select-menu-div").eq(i).find("i").removeClass("select-menu-i");
                        $(".select-menu-ul").eq(i).animate({marginTop:"50px",opacity:"0"},"fast");
                    }
                }

            });
            $(".select-menu-ul").eq(index).on("click","li",function(){//给下拉选项绑定点击事件
                $(".select-menu-input").eq(index).val($(this).html());//把被点击的选项的值填入输入框中
                $(".select-menu-div").eq(index).click();
                $(this).siblings(".select-this").removeClass("select-this");
                $(this).addClass("select-this");
            });
            $("body").on("click",function(event){
                event.stopPropagation();
                if($(".select-menu-ul").eq(index).css("display")==="block"){
                    $(".select-menu-ul").eq(index).hide();
                    $(".select-menu-div").eq(index).find("i").removeClass("select-menu-i");
                    $(".select-menu-ul").eq(index).animate({marginTop:"50px",opacity:"0"},"fast");
                    a
                }
            });
        }
    })
</script>
</body>
</html>

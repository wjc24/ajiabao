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
body{overflow-y:hidden;}
.matchCon{width:280px;}
#tree{background-color: #fff;width: 225px;border: solid #ddd 1px;margin-left: 5px;height:100%;}
h3{background: #EEEEEE;border: 1px solid #ddd;padding: 5px 10px;}
.grid-wrap{position:relative;}
.grid-wrap h3{border-bottom: none;}
#tree h3{border-style:none;border-bottom:solid 1px #D8D8D8;}
.quickSearchField{padding :10px; background-color: #f5f5f5;border-bottom:solid 1px #D8D8D8;}
#searchCategory input{width:165px;}
.innerTree{overflow-y:auto;}
#hideTree{cursor: pointer;color:#fff;padding: 0 4px;background-color: #B9B9B9;border-radius: 3px;position: absolute;top: 5px;right: 5px;}
#hideTree:hover{background-color: #AAAAAA;}
#clear{display:none;}

/*品牌*/
.select-menu{
    margin-bottom: 1%;
}
.select-menu-ul{
    margin-top:50px;
    list-style:none;
    opacity:0;
    display:none;
    width:200px;
    text-align:left;
    border:1px solid #ddd;
    background:white;
    position:absolute;
    z-index:1;
    height: 100px;
    overflow-y: scroll;
    overflow-x: hidden;
    cursor: pointer;
}
.select-menu-ul li{
    width: 100%;
    overflow: hidden;
    padding:2% 0 2% 3%;
}
.select-menu-ul li:hover{
    background:white;

}
.select-menu-div{
    position:relative;
    height:30px;
    width:200px;
    background-color: white;
    border:1px solid #ddd;
    line-height:30px;
}
.select-this{
    background:#6892cc;
}
.select-this:hover{
    background:#6892cc!important;
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
    height:29px;
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
<div class="wrapper">
	<div class="mod-search cf">
	    <div class="fl">
	      <ul class="ul-inline">
	        <li>
	          <input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" value="按商品编号，商品名称，品牌，规格型号等查询">
	        </li>
              <li>
                  <!--品牌-->
                  <div class="select-menu ui-btn mrb" style="background-color: #f5f5f5;">
                      <div class="select-menu-div">
                          <input id="No1" readonly class="select-menu-input" placeholder="品牌"/>

                          <i class="fa fa-caret-down"></i>
                      </div>
                      <ul class="select-menu-ul" >
                          <li class="select-this">全部</li>
                          <?php foreach ($list as $list): ?>
                          <li><?php echo $list['name'] ?></li>
                          <?php endforeach;?>
                      </ul>
                  </div>
                  <!--品牌end-->
              </li>


	        <li><a class="ui-btn mrb" id="search">查询</a></li>
	      </ul>
	    </div>
	    <div class="fr"><a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增</a><!--<a href="#" class="ui-btn mrb" id="btn-print">打印</a>--><a class="ui-btn mrb" id="btn-disable">禁用</a><a class="ui-btn mrb" id="btn-enable">启用</a><a href="#" class="ui-btn mrb" id="btn-import">导入</a><a href="#" class="ui-btn mrb" id="btn-export">导出</a><a href="#" class="ui-btn" id="btn-batchDel">删除</a></div>
	  </div>
	  <div class="cf">
	    <div class="grid-wrap fl cf">
	    	<h3>当前分类：<span id='currentCategory'></span><a href="javascript:void(0);" id='hideTree'>&gt;&gt;</a></h3>
		    <table id="grid">
		    </table>
		    <div id="page"></div>
		</div>
		<div class="fl cf" id='tree'>
			<h3>快速查询</h3>
			<div class="quickSearchField dn">
				<form class="ui-search" id="searchCategory">
					<input type="text" class="ui-input" /><button type="submit" title="点击搜索" >搜索</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url()?>statics/js/dist/goodsList.js?ver=20140430"></script>
<script>
    $(function(){
        selectMenu(0);
        selectMenu(1);
        function selectMenu(index){
            // $(".select-menu-input").eq(index).val($(".select-this").eq(index).html());//在输入框中自动填充第一个选项的值
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

                //将选中的内容填入搜索框
                $('#matchCon').val($(this).html());
                if ($(this).html() == '全部') {
                    $('#matchCon').val('');
                    $(".select-menu-input").eq(index).val('');
                }
                $('#search').click();
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

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
<link href="<?php echo base_url()?>statics/css/help/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
<style>
.mod-search{ position:relative; }
#custom{ position:absolute; top:0; right:0; }
.ui-jqgrid-bdiv .ui-state-highlight { background: none; }
#manager li{margin: 8px 0;}
.ui-label{width: 204px;display: inline-block;line-height: 18px;font-size: 14px;text-align: center;}

.ui-label-warning:hover{background-color: #FFBA5A;}
.no-query{border: none;}


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
    width:200px;
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

<body class="min-w">
<div class="wrapper">
  <div class="mod-search cf">
    <div class="fl">
      <ul class="ul-inline cf">
        <li>
          <span id="storage"></span>
        </li>
        <li>
          <span id="category"></span>
        </li>
        <li>
          <label>商品/品牌:</label>
          <input type="text" id="goods" class="ui-input w200">
        </li>
        <li id="chkField">
          <label class="chk" style="margin-top:6px; " title="显示零库存"><input type="checkbox" name="box"
 value='showZero'>零库存</label>
          <label class="chk" style="margin-top:6px; " title="显示含序列号商品"><input type="checkbox" name="box"
 value='isSerNum'>序列号商品</label>
        </li>
          <li>
              <!--品牌-->
              <div class="select-menu ui-btn mrb" style="background-color: #f5f5f5;">
                  <div class="select-menu-div">
                      <input id="No1" readonly class="select-menu-input" placeholder="品牌" style="font: 12px/1.5 arial, 宋体;"/>

                      <i class="fa fa-caret-down" style="color:#888"></i>
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
        <li><a class="ui-btn ui-btn-sp mrb" id="search">查询</a></li>
      </ul>
    </div>
    <div class="fr dn">
        <a class="ui-btn mrb" id="export">导出系统库存</a><!--<a class="ui-btn mrb" id="import">导入盘点库存</a>--><a class
="ui-btn" id="save">生成盘点单据</a>
    </div>
  </div>
  <div class="grid-wrap">
    <table id="grid">
    </table>
    <div id="page"></div>
  </div>
  <div style="margin:10px 18px 0 0; " class="dn"  id="handleDom">
    <div class="fl">
      <label>备注:</label>
      <input type="text" id="note" class="ui-input" style="width:560px;">
    </div>
  </div>
</div>
<script src="<?php echo base_url()?>statics/js/dist/inventory.js?ver=20140430"></script>
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
                $('#goods').val($(this).html());
                if ($(this).html() == '全部') {
                    $('#goods').val('');
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


 
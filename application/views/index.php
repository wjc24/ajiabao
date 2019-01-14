<?php $this->load->view('header');?>

<script type="text/javascript">
var DOMAIN = document.domain;
var WDURL = "<?php echo site_url()?>";
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

<link href="<?php echo base_url()?>statics/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url()?>statics/css/<?php echo sys_skin()?>/default.css?ver=20150522" rel="stylesheet" type="text/css" id="defaultFile">
<script src="<?php echo skin_url()?>/js/common/tabs.js?ver=20150522"></script>
<!-- author：16958398 -->


<script>
var CONFIG = {
	DEFAULT_PAGE: true,
	SERVICE_URL: '<?php echo base_url()?>'
};
//系统参数控制
var SYSTEM = {
	version: 1,
	skin: "<?php echo sys_skin()?>",
	language:"",
	site:"",
	curDate: "1033737952010",  //系统当前日期
	DBID: "88886683", //账套ID
	serviceType: "12", //账套类型，13：表示收费服务，12：表示免费服务
	realName: "<?php echo $name;?>", //真实姓名
	userName: "<?php echo $username;?>", //用户名
	companyName: "<?php echo $system['companyName']?>",	//公司名称
	companyAddr: "<?php echo $system['companyAddr']?>",	//公司地址
	phone: "<?php echo $system['phone']?>",	//公司电话
	fax: "<?php echo $system['fax']?>",	//公司传真
	postcode: "<?php echo $system['postcode']?>",	//公司邮编
	startDate: "", //启用日期
	currency: "RMB",	//本位币
	qtyPlaces: "<?php echo $system['qtyPlaces']?>",	//数量小数位
	pricePlaces: "<?php echo $system['pricePlaces']?>",	//单价小数位
	amountPlaces: "<?php echo $system['amountPlaces']?>", //金额小数位
	valMethods:	"<?php echo $system['valMethods']?>",	//存货计价方法
	invEntryCount: "",//试用版单据分录数
	rights: <?php echo $rights?>,          //权限列表
	billRequiredCheck: 1, //是否启用单据审核功能  1：是、0：否
	requiredCheckStore: <?php echo $system['requiredCheckStore']?>, //是否检查负库存  1：是、0：否
	hasOnlineStore: 0,	//是否启用网店
	enableStorage: 0,	//是否启用仓储
	genvChBill: 0,	//生成凭证后是否允许修改单据
	requiredMoney: 1, //是否启用资金功能  1：是、0：否
	taxRequiredCheck: 0,
	taxRequiredInput: 17,
	isAdmin:<?php echo $roleid==0 ? 'true' : 'false'?>, //是否管理员
	siExpired:false,//是否过期
	siType:2, //服务版本，1表示基础版，2表示标准版
	siVersion:1, //1表示试用、2表示免费（百度版）、3表示收费，4表示体验版
	Mobile:"",//当前用户手机号码
	isMobile:true,//是否验证手机
	isshortUser:false,//是否联邦用户
	shortName:"",//shortName
	isOpen:false,//是否弹出手机验证
	enableAssistingProp:0, //是否开启辅助属性功能  1：是、0：否
	ISSERNUM: 0, //是否启用序列号 1：是、0：否 （与enableAssistingProp对立，只能启用其一）
	ISWARRANTY: 0 //是否启用保质期  1：是、0：否
};
//区分服务支持
SYSTEM.servicePro = SYSTEM.siType === 2 ? 'forbscm3' : 'forscm3';
var cacheList = {};	//缓存列表查询
//全局基础数据
(function(){
	/*
	 * 判断IE6，提示使用高级版本
	 */
	if(Public.isIE6) {
		 var Oldbrowser = {
			 init: function(){
				 this.addDom();
			 },
			 addDom: function() {
			 	var html = $('<div id="browser">您使用的浏览器版本过低，影响网页性能，建议您换用<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" target="_blank">谷歌</a>、<a href="http://download.microsoft.com/download/4/C/A/4CA9248C-C09D-43D3-B627-76B0F6EBCD5E/IE9-Windows7-x86-chs.exe" target="_blank">IE9</a>、或<a href=http://firefox.com.cn/" target="_blank">火狐浏览器</a>，以便更好的使用！<a id="bClose" title="关闭">x</a></div>').insertBefore('#container').slideDown(500);
			 	this._colse();
			 },
			 _colse: function() {
				  $('#bClose').click(function(){
						 $('#browser').remove();
				 });
			 }
		 };
		 Oldbrowser.init();
	};
	getPageConfig();
	getGoods();
	getStorage();
	getCustomer();
	getSupplier();
	getAddr();
	getUnit();
	getUnitGroup();
	getAccounts();
	getAssistingPropType();
	getAssistingProp();
	getAssistingPropGroup();
	getStaff();
	getBatch();
})();
//缓存用户配置
function getPageConfig(){
	//return;
	Public.ajaxGet('<?php echo site_url()?>/basedata/userSetting?action=list', {}, function(data){
		if(data.status === 200) {
			SYSTEM.pageConfigInfo = {};
			for (var i = 0; i < data.data.rows.length; i++) {
				var conf = data.data.rows[i];
				SYSTEM.pageConfigInfo[''+conf.key] = conf['value'] || {};
				for(var gridId in conf.grids){
					var g = conf.grids[gridId];
					if(typeof g != 'function' && g.isReg){
						var colModel = g.colModel;
						var tmpArr = [];
						for (var i = 0; i < colModel.length; i++) {
							var col = colModel[i];
							tmpArr.push({
								 name: col['name']//列名,唯一标识
								,label: col['label']//列名
								,hidden: col['hidden']//显示与隐藏
								,width: col['width']//宽度
							})
						};
						g.colModel = tmpArr;
					}
				}
			};
		} else if (data.status === 250){
			SYSTEM.pageConfigInfo = {};
		} else {
			Public.tips({type: 1, content : data.msg});
		}
	});
};
//缓存商品信息
function getGoods() {
	if(SYSTEM.isAdmin || SYSTEM.rights.INVENTORY_QUERY) {
		//&isDelete=2 获取全部，很奇葩的定义。。。
		Public.ajaxGet('<?php echo site_url()?>/basedata/inventory?action=list&isDelete=2', { rows: 5000 }, function(data){
			if(data.status === 200) {
				SYSTEM.goodsInfo = data.data.rows;
			} else if (data.status === 250){
				SYSTEM.goodsInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.goodsInfo = [];
	}
};
//缓存仓库信息
function getStorage() {
	if(SYSTEM.isAdmin || SYSTEM.rights.INVLOCTION_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/invlocation?action=list&isDelete=2', {}, function(data){
			if(data.status === 200) {
				SYSTEM.storageInfo = data.data.rows;
			} else if (data.status === 250){
				SYSTEM.storageInfo = [];
			}  else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.storageInfo = [];
	}
};
//缓存客户信息
function getCustomer() {
	if(SYSTEM.isAdmin || SYSTEM.rights.BU_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/contact?action=list&simple=1&isDelete=2', { rows: 5000 }, function(data){
			if(data.status === 200) {
				SYSTEM.customerInfo = data.data.rows;
			} else if (data.status === 250){
				SYSTEM.customerInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.customerInfo = [];
	}
};
//缓存供应商信息
function getSupplier() {
	if(SYSTEM.isAdmin || SYSTEM.rights.PUR_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/contact?action=list&simple=1&type=10&isDelete=2', { rows: 5000 }, function(data){
			if(data.status === 200) {
				SYSTEM.supplierInfo = data.data.rows;
			} else if (data.status === 250){
				SYSTEM.supplierInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.supplierInfo = [];
	}
};
//缓存地址信息
function getAddr() {
	if(SYSTEM.isAdmin || SYSTEM.rights.DELIVERYADDR_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/deliveryAddr?action=list&isDelete=2', { rows: 5000 }, function(data){
			if(data.status === 200) {
				SYSTEM.addrInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.addrInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.addrInfo = [];
	}
};
//缓存职员
function getStaff() {
	if(true) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/employee?action=list&isDelete=2', {}, function(data){
			if(data.status === 200) {
				SYSTEM.salesInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.salesInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.salesInfo = [];
	}
};
//缓存账户信息
function getAccounts() {
	if(SYSTEM.isAdmin || SYSTEM.rights.SettAcct_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/settAcct?action=list&isDelete=2', {}, function(data){
			if(data.status === 200) {
				SYSTEM.accountInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.accountInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.accountInfo = [];
	}
};
//缓存结算方式
function getPayments() {
	if(true) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/assist?action=list&typeNumber=PayMethod&isDelete=2', {}, function(data){
			if(data.status === 200) {
				SYSTEM.paymentInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.paymentInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.paymentInfo = [];
	}
};
//缓存计量单位
function getUnit(){
	if(SYSTEM.isAdmin || SYSTEM.rights.UNIT_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/unit?action=list&isDelete=2', {}, function(data){
			if(data.status === 200) {
				SYSTEM.unitInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.unitInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.unitInfo = [];
	}
}
//缓存计量单位组
function getUnitGroup(){
	if(SYSTEM.isAdmin || SYSTEM.rights.UNIT_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/unitType?action=list', {}, function(data){
			if(data.status === 200) {
				SYSTEM.unitGroupInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.unitGroupInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.unitGroupInfo = [];
	}
}
//缓存计量单位
function getAssistingProp(){
	if(SYSTEM.isAdmin || SYSTEM.rights.UNIT_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/unitType?action=list', {}, function(data){
			if(data.status === 200) {
				SYSTEM.unitGroupInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.unitGroupInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.unitGroupInfo = [];
	}
}
//缓存辅助属性分类
function getAssistingPropType(){
	if(SYSTEM.isAdmin || SYSTEM.rights.FZSX_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/assistType?action=list', {}, function(data){
			if(data.status === 200) {
				SYSTEM.assistPropTypeInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.assistPropTypeInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.assistPropTypeInfo = [];
	}
}
//缓存辅助属性
function getAssistingProp(){
	if(SYSTEM.isAdmin || SYSTEM.rights.FZSX_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/assist?action=list&isDelete=2', {}, function(data){
			if(data.status === 200) {
				SYSTEM.assistPropInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.assistPropInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.assistPropInfo = [];
	}
}
//缓存辅助属性组合
function getAssistingPropGroup(){
	if(SYSTEM.isAdmin || SYSTEM.rights.FZSX_QUERY) {
		Public.ajaxGet('<?php echo site_url()?>/basedata/assistSku?action=list', {}, function(data){
			if(data.status === 200) {
				SYSTEM.assistPropGroupInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.assistPropGroupInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.assistPropGroupInfo = [];
	}
}
//缓存辅助属性组合
function getBatch(){
	return;
	if(SYSTEM.isAdmin || SYSTEM.ISWARRANTY) {
		Public.ajaxGet('/warranty.do?action=getBatchNoList', {}, function(data){
			if(data.status === 200) {
				SYSTEM.batchInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.batchInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.batchInfo = [];
	}
}
//左上侧版本标识控制
//function markupVension(){
//	var imgSrcList = {
//				base:'<?php //echo base_url()?>//statics/css/<?php //echo sys_skin()?>///img/icon_v_b.png',	//基础版正式版
//				baseExp:'<?php //echo base_url()?>//statics/css/<?php //echo sys_skin()?>///img/icon_v_b_e.png',	//基础版体验版
//				baseTrial:'<?php //echo base_url()?>//statics/css/<?php //echo sys_skin()?>///img/icon_v_b_t.png',	//基础版试用版
//				standard:'<?php //echo base_url()?>//statics/css/<?php //echo sys_skin()?>///img/icon_v_s.png', //标准版正式版
//				standardExp:'<?php //echo base_url()?>//statics/css/<?php //echo sys_skin()?>///img/icon_v_s_e.png', //标准版体验版
//				standardTrial :'<?php //echo base_url()?>//statics/css/<?php //echo sys_skin()?>///img/icon_v_s_t.png' //标准版试用版
//			};
//	var imgModel = $("<img id='icon-vension' src='' alt=''/>");
//	if(SYSTEM.siType === 1){
//		switch(SYSTEM.siVersion){
//			case 1:	imgModel.attr('src',imgSrcList.baseTrial).attr('alt','基础版试用版');
//				break;
//			case 2:	imgModel.attr('src',imgSrcList.baseExp).attr('alt','免费版（百度版）');
//				break;
//			case 3: imgModel.attr('src',imgSrcList.base).attr('alt','基础版');//标准版
//				break;
//			case 4: imgModel.attr('src',imgSrcList.baseExp).attr('alt','基础版体验版');//标准版
//				break;
//		};
//	} else {
//		switch(SYSTEM.siVersion){
//			case 1:	imgModel.attr('src',imgSrcList.standardTrial).attr('alt','标准版试用版');
//				break;
//			case 3: imgModel.attr('src',imgSrcList.standard).attr('alt','标准版');//标准版
//				break;
//			case 4: imgModel.attr('src',imgSrcList.standardExp).attr('alt','标准版体验版');//标准版
//				break;
//		};
//	};
//
//	$('#col-side').prepend(imgModel);
//};

</script>
<!--<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?0613c265aa34b0ca0511eba4b45d2f5e";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>-->
<style>
    /*header*/
    #col-main #main-hd{
        background-color: rgb(87,132,187);
        position: relative;
        box-shadow: 0px 0px 5px rgb(19,57,139);
        height: 60px;
    }
    #col-main #main-hd ul{
        position: absolute;
        bottom: 20%;
        right: 1%;
        margin-top: -10%;
    }
    #col-main #main-hd ul li a{
        color: #fff;
        font-weight: bolder;
        padding-right: 10px;
    }
    #col-main #main-hd ul li i{
        line-height: 20px;
        font-size: 20px;
    }
    /*side*/
    #col-side{
        background-color: rgb(104,146,204);
    }
    #container{
        background-color: rgb(104,146,204);
    }
    #col-side::before{
        content: '';
        background: url("<?php echo base_url()?>statics/css/img/top.png") no-repeat center;
        background-color: rgb(238,243,249);
        margin: 0 auto;
        margin-top: 20%;
        display: block;
        width: 60px;
        height: 60px;
        border: 1px solid #eee;
        border-radius: 50%;
        box-shadow: 0px 0px 5px #eee;
        margin-bottom: 30px;
    }
    #nav {
        padding-top: 0px;
        margin-top: -20%;
    }
    #nav>li>a{
        font-weight: bolder;
    }
    #nav li a i{
        font-weight: normal;
    }
    /*nav*/
    .nav_top{
        width: 100%;
        height: 35px;
        background-color: rgb(38,88,149);
        position: relative;
        box-shadow: -2px 2px 5px rgb(27,62,139);
    }
    .nav_top ul li{
        color: #fff;
    }
    /*nav左侧*/
    .nav_top .nav_l{
        height: 100%;
        width: 10%;
        position: absolute;
        left: 1%;
        overflow: hidden;
    }
    .nav_top .nav_l ul{
        height: 100%;
    }
    .nav_top .nav_l ul li{
        height: 100%;
        line-height: 35px;
        float: left;
        font-size: large;
    }
    .nav_top .nav_l ul li:last-child{
        color: rgb(64,115,177);
        text-shadow: 1px 1px 1px #eee;
    }
    /*nav中间*/
    .nav_top .nav_c{
        height: 100%;
        width: 85%;
        overflow: hidden;
        position: absolute;
        left: 8%;
    }
    .nav_top .nav_c ul{
        height: 100%;
        width: 5000px;
        /*transform: translateX(-100px);*/
        transition: all 1s linear;
    }
    .nav_top .nav_c ul li{
        height: 100%;
        line-height: 35px;
        float: left;
        padding-left: 10px;
    }
    .nav_top .nav_c ul li a{
        color: #fff;
        font-weight: bolder;
    }
    .nav_top .nav_c ul li a:hover{
        color: #bbb;
    }
    /*nav右侧*/
    .nav_top .nav_r{
        height: 100%;
        width: 50px;
        position: absolute;
        right: 2%;
    }
    .nav_top .nav_r ul{
        height: 100%;
    }
    .nav_top .nav_r ul li{
        height: 100%;
        width: 10px;
        line-height: 35px;
        float: left;
    }
    .nav_top .nav_r ul li:nth-child(2){
        float: right;
    }
    .nav_top .nav_r ul li a{
        color: #fff;
    }
    .nav_top .nav_r ul li a:hover{
        color: #bbb;
    }

    .nav_bottom{
        height: 25px;
        width: 100%;
        border-top: 1px solid #999;
        background-color: rgb(38,88,149);
        margin-bottom: 10px;
    }
    .clearfix::before,
    .clearfix::after{
        content:'';
        display: block;
        line-height: 0;
        height: 0;
        visibility: hidden;
        clear: both;
    }

    /*侧栏图标*/
    @font-face {
        font-family: 'iconfont';
        src: url('<?php echo base_url()?>statics/css/font/iconfont.eot');
        src: url('<?php echo base_url()?>statics/css/font/iconfont.eot?#iefix') format('embedded-opentype'),
        url('<?php echo base_url()?>statics/css/font/iconfont.woff') format('woff'),
        url('<?php echo base_url()?>statics/css/font/iconfont.ttf') format('truetype'),
        url('<?php echo base_url()?>statics/css/font/iconfont.svg#iconfont') format('svg');
    }
    .iconfont{
        font-family:"iconfont" !important;
        font-size:16px;font-style:normal;
        -webkit-font-smoothing: antialiased;
        -webkit-text-stroke-width: 0.2px;
        -moz-osx-font-smoothing: grayscale;
    }
    #nav>li{
        height: 7.5%;
        text-align: center;
        padding-top: 20%;
    }
    #nav>li:hover{
        /*background-color: rgb(104,146,204);*/
        background-color: rgb(38,88,149);

    }
    #nav>li:hover>a{
        /*background-color: rgb(38,88,149);*/
    }
    .sub-nav{
        text-align: left;
    }
    #nav .item>a{
        color: #fff;
        /*font-weight: bolder;*/
        font-size: 15px;
        display: inline-block;
        height: 40px;
        border-bottom: 1px solid #fff;
    }
    #nav .item>a i{
        padding-right: 5px;
    }
    #nav .item>a .arrow{
        display: none;
        top: 2px;
    }
    #nav .item:hover .arrow{
        display: block;
        top: 33px;
    }
</style>
</head>
<body>
<div id="container" class="cf">
  <div id="col-side">
    <ul id="nav" class="cf">
      <li class="item item-vip"> <a href="javascript:void(0);" class="vip main-nav">高级<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap group-nav group-nav-t0 vip-nav cf">
          <div class="nav-item nav-onlineStore">
            <h3>网店</h3>
            <ul class="sub-nav" id="vip-onlineStore">
          	</ul>
          </div>
          <div class="nav-item nav-JDstore last">
            <h3>京东仓储</h3>
            <ul class="sub-nav" id="vip-JDStorage">
          	</ul>
          </div>
          </div>
      </li>
      <li class="item item-purchase"> <a href="javascript:void(0);" class="purchase"><i class="iconfont">&#xe613;</i>购货<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap single-nav">
          <ul class="sub-nav" id="purchase">
          </ul>
        </div>
      </li>
      <li class="item item-sales"> <a href="javascript:void(0);" class="sales"><i class="iconfont">&#xe665;</i>销货<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap single-nav">
          <ul class="sub-nav" id="sales">
          </ul>
        </div>
      </li>
      <li class="item item-storage"> <a href="javascript:void(0);" class="storage"><i class="iconfont">&#xe6ea;</i>仓库<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap single-nav">
          <ul class="sub-nav" id="storage">
          </ul>
        </div>
      </li>
      <li class="item item-money"> <a href="javascript:void(0);" class="money"><i class="iconfont">&#xe65f;</i>资金<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap single-nav">
          <ul class="sub-nav" id="money">
          </ul>
        </div>
      </li>


        <li class="item item-money"> <a href="javascript:void(0);" class="customer"><i class="iconfont">&#xe62a;</i>客户<span class="arrow">&gt;</span></a>
            <div class="sub-nav-wrap single-nav">
                <ul class="sub-nav" id="customer">
                </ul>
            </div>
        </li>
        <li class="item item-money"> <a href="javascript:void(0);" class="supplier"><i class="iconfont">&#xe628;</i>供应商<span class="arrow">&gt;</span></a>
            <div class="sub-nav-wrap single-nav">
                <ul class="sub-nav" id="supplier">
                </ul>
            </div>
        </li>


      <li class="item item-report"> <a href="javascript:void(0);" class="report"><i class="iconfont">&#xe736;</i>报表<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap group-nav group-nav-b0 report-nav cf">
          <div class="nav-item nav-pur">
            <h3>采购报表</h3>
            <ul class="sub-nav" id="report-purchase">
            </ul>
          </div>
          <div class="nav-item nav-sales">
            <h3>销售报表</h3>
            <ul class="sub-nav" id="report-sales">
            </ul>
          </div>
          <div class="nav-item nav-fund">
            <h3>仓存报表</h3>
            <ul class="sub-nav" id="report-storage">
            </ul>
          </div>

          <div class="nav-item nav-fund last">
            <h3>资金报表</h3>
            <ul class="sub-nav" id="report-money">
            </ul>
          </div>

       </div>
      </li>
      <li class="item item-setting"> <a href="javascript:void(0);" class="setting" style="border: none;"><i class="iconfont">&#xe63b;</i>设置<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap cf group-nav group-nav-b0 setting-nav">
          <div class="nav-item">
            <h3>基础资料</h3>
            <ul class="sub-nav" id="setting-base">
            </ul>
          </div>
          <div class="nav-item">
            <h3>辅助资料</h3>
            <ul class="sub-nav" id="setting-auxiliary">
            </ul>
          </div>
          <div class="nav-item cf last">
            <h3>高级设置</h3>
            <ul class="sub-nav" id="setting-advancedSetting">
            </ul>
            <ul class="sub-nav" id="setting-advancedSetting-right">
            </ul>
          </div>
        </div>
      </li>
    </ul>
    <!--<div id="navScroll" class="cf"><span id="scollUp"><i>dd</i></span><span id="scollDown"><i>aa</i></span></div>-->
    <!--<a href="#" class="side_fold">收起</a>-->
  </div>
  <div id="col-main">
    <div id="main-hd" class="cf">
        <ul style="position: absolute;left: 20px;bottom: 30px;color: #fff;font-size: 10px">今日汇率：1人民币 = <span><?php echo $CNYtoJPY?></span>日元</ul>
        <ul style="position: absolute;left: 80px;bottom: 5px;color: #fff;font-size: 10px">1日元 = <span><?php echo $JPYtoCNY?></span>人民币  <span style="font-size: 10px;color: #ddd;">(更新时间：<?php echo $time?>)</span></ul>
      <ul class="user-menu">
          <li class="space"><i class="iconfont" style="color: #fff">&#xe732;</i></li>
          <li><a href="javascript:void(0);"><?php echo $username?></a></li>
          <li class="space"><i class="iconfont" style="color: #fff">&#xe608;</i></li>
          <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('help/index')?>">帮助</a></li>
          <li class="space"><i class="iconfont" style="color: #fff">&#xe606;</i></li>
          <li id="manageAcct"><a href='javascript:void(0);'>修改密码</a></li>
          <li class="space"><i class="iconfont" style="color: #fff;font-size: 30px">&#xe64b;</i></li>
        <li><a href="<?php echo site_url('login/out')?>">退出</a></li>
      </ul>
    </div>
    <div id="main-bd">
        <div class="nav">
            <div class="nav_top">
                <div class="nav_l">
                    <ul class="clearfix">
                        <li>我的工作台</li>
                        <li class="space">｜</li>
                    </ul>
                </div>
                <div class="nav_c">
                    <ul class="clearfix">
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/invPu?action=initPur')?>">购货单</a></li>
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('scm/invPu?action=initPur&transType=150502')?><!--">购货退货单</a></li>-->
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/invSo?action=initSo')?>">客户订单</a></li>
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/invSa?action=initSale')?>">销货单</a></li>
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('scm/invSa?action=initSale&transType=150602')?><!--">销货退货单</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('scm/invTf?action=initTf')?><!--">调拨单</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('storage/inventory')?><!--">库存查询</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('scm/invOi?action=initOi&type=in')?><!--">其他入库单</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('scm/invOi?action=initOi&type=out')?><!--">其他出库单</a></li>-->
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/receipt?action=initReceipt')?>">收款单</a></li>
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('scm/payment?action=initPay')?>">付款单</a></li>
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('scm/ori?action=initInc')?><!--">其他收入单</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('scm/ori?action=initExp')?><!--">其他支出单</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/pu_detail_new')?><!--">采购明细表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/pu_summary_new')?><!--">采购汇总表(按商品)</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/pu_summary_supply_new')?><!--">采购汇总表(按供应商)</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/sales_detail')?><!--">销售明细表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/sales_summary')?><!--">销售汇总表(按商品)</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/sales_summary_customer_new')?><!--">销售汇总表(按客户)</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/contact_debt_new')?><!--">往来单位欠款表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/sales_profit')?><!--">销售利润表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/goods_balance')?><!--">商品库存余额表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/goods_flow_detail')?><!--">商品收发明细表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/goods_flow_summary')?><!--">商品收发汇总表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/cash_bank_journal_new')?><!--">现金银行报表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/account_pay_detail_new?action=detailSupplier')?><!--">应付账款明细表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/account_proceeds_detail_new?action=detail')?><!--">应收账款明细表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/customers_reconciliation_new')?><!--">客户对账单</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/suppliers_reconciliation_new')?><!--">供应商对账单</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('report/other_income_expense_detail')?><!--">其他收支明细表</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/customer_list')?><!--">客户管理</a></li>-->
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('settings/vendor_list')?>">供应商管理</a></li>
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('settings/goods_list')?>">商品管理</a></li>
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('settings/storage_list')?>">仓库管理</a></li>
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/staff_list')?><!--">职员管理</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/settlement_account')?><!--">账户管理</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/category_list?typeNumber=customertype')?><!--">客户类别</a></li>-->
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('settings/category_list?typeNumber=supplytype')?>">供应商类别</a></li>
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('settings/category_list?typeNumber=trade')?>">商品类别</a></li>
                        <li><a parentOpen="true" rel="pageTab" href="<?php echo site_url('settings/brand_list?typeNumber=brand')?>">品牌</a></li>
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/category_list?typeNumber=paccttype')?><!--">支出类别</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/category_list?typeNumber=raccttype')?><!--">收入类别</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/unit_list')?><!--">计量单位</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/settlement_category_list')?><!--">结算方式</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/system_parameter')?><!--">系统参数</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/authority')?><!--">权限设置</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/log')?><!--">操作日志</a></li>-->
<!--                        <li><a parentOpen="true" rel="pageTab" href="--><?php //echo site_url('settings/backup')?><!--">备份与恢复</a></li>-->
                    </ul>
                </div>
                <div class="nav_r">
                    <ul class="clearfix">
<!--                        <li><a href="javascript:void(0);"><i class="iconfont" style="color: #fff">&#xe504;</i></a></li>-->
<!--                        <li><a href="javascript:void(0);"><i class="iconfont" style="color: #fff">&#xe653;</i></a></li></a></li>-->
                    </ul>
                </div>

            </div>
            <div class="nav_bottom"></div>
        </div>

      <div class="page-tab" id="page-tab">
      </div>
    </div>
  </div>
</div>
<!--<div id="selectSkin" class="shadow dn">-->
<!--	<ul class="cf">-->
<!--    	<li><a id="skin-default"><span></span><small>经典</small></a></li>-->
<!--        <li><a id="skin-blue"><span></span><small>丰收</small></a></li>-->
<!--        <li><a id="skin-green"><span></span><small>小清新</small></a></li>-->
<!--    </ul>-->
<!--</div>-->
<script src="<?php echo base_url()?>statics/js/dist/default.js?ver=20170711"></script>
<script>
    $('#manageAcct').click(function(e){
        e.preventDefault();
        var updateUrl = location.protocol + '//' + location.host + '/update_info';
        $.dialog({
            min: false,
            max: false,
            cancle: false,
            lock: true,
            width: 500,
            height: 380,
            title: '修改密码',
            //content: 'url:' + url
            content: 'url:../home/set_password'
        });
    });

    var i = 0;
    $('.nav_r ul li:nth-child(1)').click(function () {
        if(i != 0){
            i += 500;
        }
        $(".nav_c ul").css("transform","translateX("+i+"px)");
    });
    $('.nav_r ul li:nth-child(2)').click(function () {
        if (i >= -2500){
            i -= 500;
        }
        $(".nav_c ul").css("transform","translateX("+i+"px)");
    });
</script>
<script>
    parent.dataReflush = function(){
        if(parent.SYSTEM.isAdmin || parent.SYSTEM.rights.INDEXREPORT_QUERY) {
            template.openTag = '<#';
            template.closeTag = '#>';
            Public.ajaxGet('../report/index?action=getInvData', {finishDate: parent.SYSTEM.endDate, beginDate: parent.SYSTEM.beginDate, endDate: parent.SYSTEM.endDate }, function(data){
                if(data.status === 200) {
                    var html = template.render('profile', data.data);
                    document.getElementById('profileDom').innerHTML = html;
                    reportParam();
                } else {
                    parent.Public.tips({type: 1, content : data.msg});
                }
            });
        };
    };
    parent.dataReflush();
    $('#profileDom').on('click','i',function(){
        parent.dataReflush();
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
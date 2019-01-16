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
    .sel{
        width: 160px;
        height: 30px;
        line-height: 30px;
        border: 1px solid #ddd;
        color: #555;
        outline: 0;
        /*margin-bottom: 5px;*/
    }
    #gender,#source{
        border: none;
        outline: none;
        width: 100%;
        height: 20px;
        line-height: 30px;
        /*appearance: none;*/
        /*-webkit-appearance: none;*/
        /*-moz-appearance: none;*/
        /*padding-left: 60px;*/
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
    }
</style>
</head>
<body>
    <div class="wrapper">
        <span id="config" class="ui-icon ui-state-default ui-icon-config"></span>
        <div class="bills">
            <div class="grid-wrap mb10" id="acGridWrap">
                <form id="manage-form" action="">
                    <ul style="font-size: 20px;font-weight: bold">发货资料</ul>
                    <ul class="mod-form-rows base-form clearfix" id="base-form">
                        <input type="hidden" id="invoice_info_id" value="<?php echo $id ?>">
                        <li class="row-item">
                            <div class="label-wrap" style="width: 21%;"><label for="name">船运/航空公司名称:</label></div>
                            <div class="ctn-wrap"><input type="text" value="<?php echo $data->shipping_name?>" class="ui-input" name="shipping_name" id="shipping_name"></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap"><label for="birthday">订舱号:</label></div>
                            <div class="ctn-wrap"><input type="text" value="<?php echo $data->booking_number?>" class="ui-input" name="booking_number" id="booking_number"></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap"><label for="birthday">集装箱号:</label></div>
                            <div class="ctn-wrap"><input type="text" value="<?php echo $data->container_number?>" class="ui-input" name="container_number" id="container_number"></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap" style="width: 21%;"><label for="birthday">托盘号:</label></div>
                            <div class="ctn-wrap"><input type="text" value="<?php echo $data->tray_number?>" class="ui-input" name="tray_number" id="tray_number"></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap"><label for="birthday">到达港口:</label></div>
                            <div class="ctn-wrap"><input type="text" value="<?php echo $data->port?>" class="ui-input" name="port" id="port"></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap"><label for="birthday">箱子个数:</label></div>
                            <div class="ctn-wrap"><input type="number" min="0" value="<?php echo $data->boxes?>" class="ui-input" name="boxes" id="boxes"></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap" style="width: 21%;"><label for="birthday">箱子总体积(立方米):</label></div>
                            <div class="ctn-wrap"><input type="number" readonly min="0" value="<?php echo $data->box_volume?>" class="ui-input" name="box_volume" id="box_volume"></div>
                        </li>
                    </ul>
                    <ul style="font-size: 20px;font-weight: bold">箱子信息</ul>

                    <button type="button" class="btn btn_add"><input type="hidden" value=" <?php echo count(json_decode($data->box))?>" id="num">添加</button>
                    <?php foreach (json_decode($data->box) as $k=>$v) :?>
                        <ul class="mod-form-rows base-form clearfix box" id="ul_<?php echo $k+1 ?>" style="margin-bottom: 20px;border-bottom: 1px solid #eee;">
                            <li class="row-item">
                                <div class="label-wrap"><label for="box_number">箱子编号:</label></div>
                                <div class="ctn-wrap"><input type="text" value="<?php echo $v->box_number ?>" class="ui-input" name="box_number" id="box_number_ul_<?php echo $k+1 ?>"></div>
                            </li>
                            <li class="row-item">
                                <div class="label-wrap"><label for="long">箱子长(米):</label></div>
                                <div class="ctn-wrap"><input type="text" oninput="volume(<?php echo $k+1 ?>);" value="<?php echo $v->long ?>" class="ui-input brand" name="long" id="long_ul_<?php echo $k+1 ?>"></div>
                            </li>
                            <li class="row-item">
                                <div class="label-wrap"><label for="wide">箱子宽(米):</label></div>
                                <div class="ctn-wrap"><input type="text" value="<?php echo $v->wide ?>" oninput="volume(<?php echo $k+1 ?>);" class="ui-input" name="wide" id="wide_ul_<?php echo $k+1 ?>"></div>
                            </li>
                            <li class="row-item">
                                <div class="label-wrap"><label for="buytime">箱子高(米):</label></div>
                                <div class="ctn-wrap"><input type="text" value="<?php echo $v->high ?>" oninput="volume(<?php echo $k+1 ?>);" class="ui-input" name="high" id="high_ul_<?php echo $k+1 ?>"></div>
                            </li>
                            <li class="row-item">
                                <div class="label-wrap"><label for="notCheck">箱子体积(立方米)::</label></div>
                                <div class="ctn-wrap"><input type="text" readonly="" value="<?php echo $v->box_single ?>" class="ui-input" name="box_single" id="box_single_ul_<?php echo $k+1 ?>"></div>
                            </li>
                            <button type="button" class="btn btn_cel" onclick="cel(<?php echo $k+1 ?>)" id="<?php echo $k+1 ?>">取消</button>
                        </ul>
                    <?php endforeach;?>
                </form>
                <div style="height: 30px;text-align: center;">
                    <button type="button" class="btn" id="submit" style="border: 1px solid #3279a0;background: -webkit-gradient(linear,0 0,0 100%,from(#4994be),to(#337fa9));color: #fff;">保存</button>
                </div>
            </div>
        </div>
    </div>


</body>
<script>
    $(function () {
        $('.btn_add').on('click',function () {
            var id = parseInt($('#num').val()) + 1;
            $('#num').val(id);
            var add = '<ul class="mod-form-rows base-form clearfix box" id="ul_' + id + '" style="margin-bottom: 20px;border-bottom: 1px solid #eee;">\n' +
                '                        <li class="row-item">\n' +
                '                            <div class="label-wrap"><label for="box_number">箱子编号:</label></div>\n' +
                '                            <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="box_number" id="box_number_ul_' + id + '"></div>\n' +
                '                        </li>\n' +
                '                        <li class="row-item">\n' +
                '                            <div class="label-wrap"><label for="long">箱子长(米):</label></div>\n' +
                '                            <div class="ctn-wrap"><input type="text" oninput="volume(' + id + ');" value="" class="ui-input brand" name="long" id="long_ul_' + id + '"></div>\n' +
                '                        </li>\n' +
                '                        <li class="row-item">\n' +
                '                            <div class="label-wrap"><label for="wide">箱子宽(米):</label></div>\n' +
                '                            <div class="ctn-wrap"><input type="text" value="" oninput="volume(' + id + ');" class="ui-input" name="wide" id="wide_ul_' + id + '"></div>\n' +
                '                        </li>\n' +
                '                        <li class="row-item">\n' +
                '                            <div class="label-wrap"><label for="buytime">箱子高(米):</label></div>\n' +
                '                            <div class="ctn-wrap"><input type="text" value="" oninput="volume(' + id + ');" class="ui-input" name="high" id="high_ul_' + id + '"></div>\n' +
                '                        </li>\n' +
                '                        <li class="row-item">\n' +
                '                            <div class="label-wrap"><label for="notCheck">箱子体积(立方米)::</label></div>\n' +
                '                            <div class="ctn-wrap"><input type="text" readonly value="" class="ui-input" name="box_single" id="box_single_ul_' + id + '"></div>\n' +
                '                        </li>\n' +
                '                        <button type="button" class="btn btn_cel" onclick="cel(&apos;' + id +'&apos;)" id="' + id + '">取消</button>\n' +
                '                    </ul>';
            $('#manage-form').append(add);
        });

    });
    function cel(id) {
        $('#ul_'+id).remove();
    };
</script>
<script>
    $("#submit").click(function () {
        var shipping_name = $("#shipping_name").val();
        var booking_number = $("#booking_number").val();
        var container_number = $("#container_number").val();
        var tray_number = $("#tray_number").val();
        var port = $("#port").val();
        var boxes = $("#boxes").val();
        var box_volume = $("#box_volume").val();
        var invoice_info_id = $("#invoice_info_id").val();

        var box = new Array();

        $.each($(".box"),function(i){

            id = $(this).attr("id");
            box.push({"box_number":$("#box_number_"+id).val(),"long":$("#long_"+id).val(),"wide":$("#wide_"+id).val(),"high":$("#high_"+id).val(),"box_single":$("#box_single_"+id).val()});

        });

        var boxs = JSON.stringify(box);//专业能力数组用JSON序列化

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('deliver/doadd');?>",
            traditional: true,
            data: {
                shipping_name: shipping_name,
                booking_number: booking_number,
                container_number:container_number,
                tray_number:tray_number,
                port:port,
                boxes:boxes,
                box_volume:box_volume,
                boxs:boxs,
                invoice_info_id:invoice_info_id,
            },
            dataType: "json",

            success: function (data) {
                console.log(data);
                //if(data.code == 0){
                //    parent.Public.tips({
                //        content:data.text
                //    });
                //  //  location.href = "<?php //echo site_url('customer')?>//";
                //}else if (data.code == 1){
                //    parent.Public.tips({
                //        type:1,
                //        content:data.text
                //    });
                //} else{
                //    parent.Public.tips({
                //        type:1,
                //        content:"未知错误"
                //    });
                //}

            },
        });
    });
    //计算箱子体积
    function volume(id) {
        long = $('#long_ul_'+id).val();
        wide = $('#wide_ul_'+id).val();
        high = $('#high_ul_'+id).val();
        if(!long){
            long = 0;
        }else if(!wide){
            wide =0;
        }else if(!high){
            high =0;
        }
        $('#box_single_ul_'+id).val((long*wide*high).toFixed(4));
    }
</script>
</html>


 
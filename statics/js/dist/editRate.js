function initField() {
	$("#rate").val(rowData.taxRate)
}
function initEvent() {
	$("#manage-form").submit(function(a) {
		a.preventDefault(), postData()
	}), $("#rate").focus().select(), initValidator()
}
function initPopBtns() {
	var a = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
	api.button({
		id: "confirm",
        name: a[0],
		focus: !0,
		callback: function() {
			return postData(), !1
		}
	}, {
		id: "cancel",
        name: a[1]
	})
}
function initValidator() {
	$("#manage-form").validate({
		rules: {
			rate: {
				required: !0
			}
		},
		messages: {
            rate: {
				required: "名称不能为空"
			}
		},
		errorClass: "valid-error"
	})
}
function postData() {
	if (!$("#manage-form").validate().form()) return void $("#manage-form").find("input.valid-error").eq(0).focus();
	var a = $.trim($("#rate").val()),
		b = {
			taxRate: a
		},
		c = "add" == oper ? "新增税率" : "修改税率";
	Public.ajaxPost("../settings/editRate", b, function(a) {
		200 == a.status ? (parent.parent.Public.tips({
			content: c + "成功！"
		}), callback && "function" == typeof callback && callback(a.data, oper, window)) : parent.parent.Public.tips({
			type: 1,
			content: c + "失败！" + a.msg
		})
	})
}
function resetForm() {
	$("#manage-form").validate().resetForm(), $("#rate").val("").focus().select()
}
var api = frameElement.api,
	oper = api.data.oper,
	rowData = api.data.rowData || {},
	callback = api.data.callback;
initPopBtns(), initField(), initEvent();
function initEvent() {
    $("#btn-add").click(function(a) {
        a.preventDefault(), handle.operate("add")
    }), $("#grid").on("click", ".operating .ui-icon-pencil", function(a) {
        a.preventDefault();
        var b = $(this).parent().data("id");
        handle.operate("edit", b)
    }), $("#grid").on("click", ".operating .ui-icon-trash", function(a) {
        a.preventDefault();
        var b = $(this).parent().data("id");
        handle.del(b)
    }), $("#btn-refresh").click(function(a) {
        a.preventDefault(), $("#grid").jqGrid("setGridParam", {
            url: "../settings/settingRate?type=rate",
            datatype: "json"
        }).trigger("reloadGrid")
    }), $(window).resize(function() {
        Public.resizeGrid()
    })
}
function initGrid() {
    var a = ["操作", "税率"],
        b = [{
            name: "operate",
            width: 60,
            fixed: !0,
            align: "center",
            formatter: this.operFmatter
        }, {
            name: "taxRate",
            index: "taxRate",
            width: 200
        }];
    $("#grid").jqGrid({
        url: "../settings/settingRate?type=rate",
        datatype: "json",
        height: Public.setGrid().h,
        altRows: !0,
        gridview: !0,
        colNames: a,
        colModel: b,
        autowidth: !0,
        viewrecords: !0,
        cmTemplate: {
            sortable: !1,
            title: !1
        },
        page: 1,
        pager: "#page",
        rowNum: 2e3,
        rowList: [300, 500, 1e3],
        shrinkToFit: !1,
        scroll: 1,
        jsonReader: {
            root: "data.items",
            repeatitems: !1,
            id: "id"
        },
        loadComplete: function(a) {
            if (a && 200 == a.status) {
                var b = {};
                a = a.data;
                for (var c = 0; c < a.items.length; c++) {
                    var d = a.items[c];
                    b[d.id] = d
                }
                $("#grid").data("gridData", b)
            } else {
                var e = 250 == a.status ? "没有税率数据！" : "获取税率数据失败！" + a.msg;
                parent.Public.tips({
                    type: 2,
                    content: e
                })
            }
        },
        loadError: function() {
            parent.Public.tips({
                type: 1,
                content: "操作失败了哦，请检查您的网络链接！"
            })
        }
    })
}
this.operFmatter = function (val, opt, row) {
    var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';
    return html_con;
};
var handle = {
    operate: function(a, b) {

        if (!Business.verifyRight("Assist_UPDATE")) return;
        var c = "修改税率",
            d = {
                oper: a,
                rowData: $("#grid").data("gridData")[b],
                callback: this.callback
            };
        $.dialog({
            title: c,
            content: "url:editRate",
            data: d,
            width: 400,
            height: 100,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    },
    callback: function(a, b, c) {
        window.location.reload();
    }
};
initEvent(), initGrid();
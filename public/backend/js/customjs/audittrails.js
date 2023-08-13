var Audittrails = function(){
    var list = function() {
        var dataArr = {};
        var columnWidth = { "width": "5%", "targets": 0 };
        var arrList = {
            'tableID': '#audit-trails-list',
            'ajaxURL': baseurl + "audittrails/audit-trails-ajaxcall",
            'ajaxAction': 'getdatatable',
            'postData': dataArr,
            'hideColumnList': [],
            'noSortingApply': [0, 6, 8],
            'noSearchApply': [0, 6, 8],
            'defaultSortColumn': [0],
            'defaultSortOrder': 'DESC',
            'setColumnWidth': columnWidth
        };
        getDataTable(arrList);

        $('body').on("click", ".viewdata", function() {
            var id = $(this).attr('data-id');
            var data = { id: id, _token: $('#_token').val() };
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url: baseurl + "audittrails/audit-trails-ajaxcall",
                data: { 'action': 'viewdata', 'data': data },
                success: function(data) {
                    var html = '';
                    var details = JSON.parse(data);
                    $.each(details, function( index, value ) {
                        var temp = '';
                        temp  = index + " : " + value + "<br>";
                        html = html + temp;
                    });
                    $("#view-audit-trails").html(html);
                }
            });
        });
    }

    return {
        init:function(){
            list();
        }
    }
}();


function Order(){
    this.service = "/CMSTopica";
//    this.service = getService();
    this.init();
    this.renderTable();
};

Order.prototype.service; //<--Lấy thông tin link service của hệ thống

Order.prototype.constructor = Order;
//Phục vụ việc phân trang, nếu là tìm mới thì reset lại dữ liệu này
Order.prototype.searchParam = {};

/**
 * init some values
 * @type type
 */
Order.prototype.init = function(){
    var self = this;
    var statuslst = [
        {
            text: 'Thành công',
            id: "POD"
        }, 
        { 
            text: 'Không thành công',
            id: "060"
        },
        {
            text: "Tất cả",
            id: "9999"
        }
    ];
    $('#status').w2field('list', { 
        items: statuslst,
        selected: 
            {
                text: 'Tất cả',
                id: "9999"
            }
    });
    //Search button's action
    $("#search_btn").click(function(){
        self.search();
    });
    //Next button
    $("#next_btn").click(function(){
        if(self.searchParam !== null){
            self.searchOrder(self.searchParam.code, self.searchParam.status, self.searchParam.name, self.searchParam.pageSize, self.searchParam.pageIndex + 50);
            self.searchParam.pageIndex += 50;
        }
    });
    //Prev button
    $("#prev_btn").click(function(){
        if(self.searchParam !== null){
            self.searchOrder(self.searchParam.code, self.searchParam.status, self.searchParam.name, self.searchParam.pageSize, (self.searchParam.pageIndex - 50) <= 0 ? 0 : self.searchParam.pageIndex - 50);
            self.searchParam.pageIndex = (self.searchParam.pageIndex - 50) <= 0 ? 0 : self.searchParam.pageIndex - 50;
        }
    });
};


/**
 * 
 * @returns {undefined}
 */
Order.prototype.search = function(){
    var self = this;
    var name = $("#buyer").val();
    var co_number = $("#co_number").val();
    var status = $("#status").data("selected").id;
    self.searchParam = null;
    self.searchParam = {
        code : co_number,
        status : status,
        name : name,
        pageSize : 50,
        pageIndex : 0
    };
    self.searchOrder(co_number, status, name, 50, 0);
};


Order.prototype.renderTable = function(){
    var self = this;
    $("#order_table").w2grid({
        show:{
            header: true,
            footer: true,
            lineNumbers: true
        },
        name: "order_table",
        columns:[
            { field: 'orderCode', caption: 'Mã đơn hàng', size: '10%' },
            { field: 'orderName', caption: 'Tên khách hàng', size: '15%' },
            { field: 'orderEmail', caption: 'Địa chỉ thư', size: '15%' },
            { field: 'orderAddress', caption: 'Địa chỉ nhận', size: '30%' },
            { field: 'orderPhone', caption: 'Số điện thoại', size: '10%' },
            { field: 'orderCost', caption: 'Giá', size: '5%' },
            { field: 'orderStatus', caption: 'Trạng thái', size: '5%' }
        ],
        menu:[
//            http://w2ui.com/web/docs/w2grid.menuClick
            {id: 1, text: "Xem chi tiết", icon: "fa fa-tasks"}
        ],
        onMenuClick: function(event){
            var menu = event.menuItem;
            var recid = event.recid;
            if(menu.id === 1){
                var record = this.get(recid);
                var orderCode = record.orderCode;
//                console.log(orderCode);
                self.showDetailWindow(orderCode);
            }
        }
    });
    //Default, when we load page, we load init data
    this.searchParam = null;
    this.searchParam = {
        code : "",
        status : "9999",
        name : "",
        pageSize : 50,
        pageIndex : 1
    };
    this.searchOrder("", "9999", "", 50, 0);
};


Order.prototype.searchOrder = function(code, status, name, pageSize, pageIndex){
    var self = this;
    var request = {};
    request.code = code;
    request.status = status;
    request.name = name;
    request.pageSize = pageSize;
    request.pageIndex = pageIndex;
    $.ajax({
        type: "POST",
        url: self.service + "/controller/OrderSearch.php",
        data: JSON.stringify(request),
        dataType: "json",
        beforeSend: function(){
            $(".waiting_modal").show("slow");
        },
        error: function(){
            $(".waiting_modal").hide("slow");
        },
        success: function(data, textStatus, jqXHR){
            //Hiển thị kết quả lên màn hình
            $(".waiting_modal").hide("slow");
            var records = data.res;
            var rs = new Array();
            for(var i = 0; i< records.length; i++){
                var record = records[i];
                rs.push({
                    recid: i + 1,
                    orderCode: record.co_number,
                    orderName: record.r_name,
                    orderEmail: record.r_email,
                    orderAddress: record.r_address,
                    orderPhone: record.r_phonenumber,
                    orderCost: typeof record.cost === "" ?  "N/A" : record.cost,
                    orderStatus: typeof record.is_sent === "" ? "N/A" : record.order_status
                });
            }
            w2ui.order_table.clear();
            w2ui.order_table.add(rs);
            w2ui.order_table.refresh();
        }
    });
};

/**
 * Display order's detail window
 * @returns {undefined}
 */
Order.prototype.showDetailWindow = function(id){
    var self = this;
    this.destroyW2uiObject(w2ui.order_detail_table);
    this.destroyW2uiObject(w2ui.layout_orderStatus);
    $().w2layout({
        name: "layout_orderStatus",
        padding: 0,
        panels: [
            {
                type:"main",
                size: "600",
                content: "<div id='layout_main_orderStatus'><div id='order_detail_diagram'></div></div>"
            }
        ]
    });
    //close any open popup
    w2popup.close();
    w2popup.open({
        title: "Chi tiết trạng thái đơn " + id,
        body: "<div id='popup_orderStatus'></div>",
        modal: false,
        width: 600,
        height: 350,
        showClose: true,
        onOpen:function(event){
            event.onComplete = function(event){
                $("#w2ui-popup #popup_orderStatus").w2render("layout_orderStatus");
                $("#order_detail_diagram").w2grid({
                    show:{
                        header: true,
                        footer: true,
                        lineNumbers: true
                    },
                    name: "order_detail_table",
                    columns:[
                        { field: 'orderCode', caption: 'Mã trạng thái', size: '15%' },
                        { field: 'orderDesc', caption: 'Mô tả', size: '25%' },
                        { field: 'orderLocation', caption: 'Địa điểm', size: '15%' },
                        { field: 'orderTime', caption: 'Thời gian cập nhật', size: '35%' }
                    ]
                });
                var request = {};
                request.code = id;
                $.ajax({
                    type: "POST",
                    url: self.service + "/controller/OrderDetail.php",
                    data: JSON.stringify(request),
                    dataType: "json",
                    beforeSend: function(){
                        $(".waiting_modal").show("slow");
                    },
                    error: function(){
                        $(".waiting_modal").hide("slow");
                    },
                    success: function(data, textStatus, jqXHR){
                        $(".waiting_modal").hide("slow");
                        var records = data.res;
                        var rs = new Array();
                        for(var i = 0; i< records.length; i++){
                            var record = records[i];
                            var date_tmp1 = record.status_date.split(" ");
                            var date_tmp2 = date_tmp1[0];
                            var date_tmp3 = date_tmp2.split("-");
                            var date_tmp4 = date_tmp3[2] + "-" + date_tmp3[1] + "-" + date_tmp3[0];
                            var status_date = date_tmp4 + " " + date_tmp1[1];
                            rs.push({
                                recid: i + 1,
                                orderCode: record.status_code,
                                orderDesc: record.status_desc,
                                orderLocation: record.location === "" ?  "N/A" : record.location,
                                orderTime: status_date,
                                style: record.is_newest === 1 ? "background-color: #C2F5B4;color:red;" : ""
                            });
                        }
                        w2ui.order_detail_table.clear();
                        w2ui.order_detail_table.add(rs);
                        w2ui.order_detail_table.refresh();
                    }
                });
                // Nguyen tac: dinh nghia mot khoi cac thao tac -> tao mot dong "", sau do dinh nghia luong
                // Cau truc: object=>object type:object text|object property
//                http://flowchart.js.org/
//                var diagram = flowchart.parse(
//                        "st=>start: Đặt hàng|past\n"
//                        + "e=>end: Gửi hàng thành công\n"
//                        + "op1=>operation: Đơn hàng rời kho|past\n"
//                        + "op2=>operation: Đang chuyển phát|current\n"
//                        + ""
//                        + "st(right)->op1->op2"
//                        );
//                diagram.drawSVG("order_diagram",
//                {
//                    'x': 0,
//                    'y': 0,
//                    'line-width': 2,
//                    'line-length': 30,
//                    'text-margin': 10,
//                    'font-size': 12,
//                    'font-color': 'black',
//                    'line-color': 'black',
//                    'element-color': 'black',
//                    'fill': 'white',
//                    'yes-text': 'yes',
//                    'no-text': 'no',
//                    'arrow-end': 'block',
//                    'scale': 1,
//                    // style symbol types
//                    'symbols': {
//                        'start': {
//                          'font-color': 'red',
//                          'element-color': 'green',
//                          'fill': 'yellow'
//                        },
//                        'end':{
//                            "font-color": "white",
//                            "fill":"green"
//                        }
//                    },
//                    // even flowstate support ;-)
//                    'flowstate' : {
//                        'past' : { 'fill' : '#CCCCCC', 'font-size' : 12},
//                        'current' : {'fill' : 'yellow', 'font-color' : 'red', 'font-weight' : 'bold'},
//                        'future' : { 'fill' : '#FFFF99'},
//                        'request' : { 'fill' : 'blue'},
//                        'invalid': {'fill' : '#444444'},
//                        'approved' : { 'fill' : '#58C4A3', 'font-size' : 12, 'yes-text' : 'APPROVED', 'no-text' : 'n/a' },
//                        'rejected' : { 'fill' : '#C45879', 'font-size' : 12, 'yes-text' : 'n/a', 'no-text' : 'REJECTED' }
//                      }
//                });
            };
        }
    });
};

Order.prototype.destroyW2uiObject = function(ow2ui){
    if(typeof ow2ui !== "undefined"){
        ow2ui.destroy();
    }
};




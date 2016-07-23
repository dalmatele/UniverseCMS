/* 
 * Nhóm chức năng quản lý sản phẩm
 */

function ProductManage(container){
    this.month = (new Date()).getMonth() + 1;
    this.year  = (new Date()).getFullYear();
    this.container = container;
    this.service = getService();
};

ProductManage.prototype.constructor = ProductManage;

ProductManage.prototype.container;

ProductManage.prototype.month;
ProductManage.prototype.year;
ProductManage.prototype.importDate;
//Phục vụ việc phân trang, nếu là tìm mới thì reset lại dữ liệu này
ProductManage.prototype.searchParam = {};

//Cache lưu trữ sản phẩm
//Khi thêm sản phẩm mới, hệ thống sẽ không lưu ngay vào csdl
//Sản phẩm chỉ được lưu khi người dùng bấm lưu gói hàng
ProductManage.prototype.products = [];
ProductManage.prototype.service; //<--Lấy thông tin link service của hệ thống

//Nhóm biến phục vụ cho thao tác sửa thông tin gói hàng
ProductManage.prototype.isEdit;// <-- Kiểm tra xem đây là thao tác update lại thông tin gói hàng hay không?
ProductManage.prototype.packageId = 0;

/*
 * Tạo giao diện cho nhóm chức năng quản lý sản phẩm
 * @returns {undefined}
 */
ProductManage.prototype.renderPage = function(){
    var html = new Array();
    html.push("<fieldset>");
        html.push("<legend>Tìm kiếm</legend>");
        html.push("<div style='overflow:hidden;'>");
            html.push("<div style='overflow:hidden;width:300px;float:left;'>");
                html.push("<div style='float: left;'>Mã hàng:</div>");
                html.push("<div style='float:right;'><input type='text' style='width:150px;' id='txt_PackageCode'/></div>");
            html.push("</div>");
            html.push("<div style='overflow:hidden;width:300px;float:left;margin-left:20px;'>");
                html.push("<div style='float: left;'>Trạng thái:</div>");
                html.push("<div style='float:right;'><input type='list' style='width:150px;' id='lst_PackageStatus'/></div>");
            html.push("</div>");
        html.push("</div>");
        html.push("<div style='overflow:hidden; margin-top: 20px;'>");
            html.push("<div style='overflow:hidden;width:300px;float:left;'>");
                html.push("<div style='float: left;'>Ngày nhập từ:</div>");
                html.push("<div style='float:right;'><input type='us-date' style='width:150px;' id='date_PackageDateF'/></div>");
            html.push("</div>");
            html.push("<div style='overflow:hidden;width:300px;float:left;margin-left:20px;'>");
                html.push("<div style='float: left;'>Ngày nhập tới:</div>");
                html.push("<div style='float:right;'><input type='us-date' style='width:150px;' id='date_PackageDateT'/></div>");
            html.push("</div>");
        html.push("</div>");
        html.push("<div style='overflow:hidden; margin-top: 20px;'>");
            html.push("<div style='float:left;margin-left: 150px;'><a href='#' class='button blue' id='searchPackage'>Tìm kiếm</a></div>");
            html.push("<div style='float:left;margin-left: 100px;'><a href='#' class='button blue' id='newPackage'>Thêm mới</a></div>");
            html.push("<div style='float:left;margin-left: 100px;'><a href='#' class='button blue' id='reportPackage'>Thống kê</a></div>");
        html.push("</div>");
    html.push("</fieldset>");
    html.push("<fieldset>");
        html.push("<legend>Danh sách sản phẩm</legend>");
        html.push("<div id='productlist'></div>");
    html.push("</fieldset>");
    $("#" + this.container).append(html.join(""));
    this.bindControl();
};

/**
 * Thêm các sự kiện và control cho các thành phần trên trang
 * @returns {undefined}
 */
ProductManage.prototype.bindControl = function(){
    var self = this;
    $('input[type=us-date]').w2field('date', { format: 'dd/mm/yyyy', start:  this.month + '/5/' + this.year, end: this.month + '/25/' + this.year });
    var statuslst = [
        {
            text: 'Tất cả',
            id: "9999"
        }, 
        { 
            text: 'Quá hạn 30 ngày',
            id: "1"
        }, 
        {
            text: 'Quá hạn 45 ngày',
            id: "2"
        }, {
            text: 'Đã bán',
            id: "3"
        }
    ];
    $('input[type=list]').w2field('list', { 
        items: statuslst,
        selected: 
            {
                text: 'Tất cả',
                id: "9999"
            }
    });
    if(typeof w2ui.grid !== "undefined"){
        w2ui.grid.destroy();
    }
    $('#productlist').w2grid({ 
        name: 'grid', 
        show:{
            header: true,
            footer: true,
            lineNumbers: true,
            toolbar: true
        },
        toolbar: {
            items: [
                {
                    type: "button",
                    id: "prevPage",
                    icon: "ico_prePage",
                    hint: "Trang trước"
                },
                {
                    type: "button",
                    id: "nextPage",
                    icon: "ico_nextPage",
                    hint: "Trang tiếp theo"
                }
            ],
            onClick: function(target, data){
                if(target === "nextPage"){
                    if(self.searchParam !== null){
                        //code, status, dateF, dateT, false
                        self.searchPackage(self.searchParam.code, self.searchParam.status, self.searchParam.fDate, self.searchParam.tDate, self.searchParam.isFirst, self.searchParam.pageSize, self.searchParam.pageIndex + 20);
                        self.searchParam.pageIndex += 20;
                    }
                }else{
                    if(self.searchParam !== null){
                        //code, status, dateF, dateT, false
                        self.searchPackage(self.searchParam.code, self.searchParam.status, self.searchParam.fDate, self.searchParam.tDate, self.searchParam.isFirst, self.searchParam.pageSize, (self.searchParam.pageIndex - 20) <= 0 ? 0 : self.searchParam.pageIndex - 20);
                        self.searchParam.pageIndex = (self.searchParam.pageIndex - 20) <= 0 ? 0 : self.searchParam.pageIndex - 20;
                    }
                }
                
            }
        },
        columns:[
            { field: 'productCode', caption: 'Mã gói hàng', size: '30%' },
            { field: 'productDesc', caption: 'Mô tả', size: '40%' },
            { field: 'productImportTime', caption: 'Ngày nhập', size: '120px' },
            { field: 'productStatus', caption: 'Trạng thái sản phẩm', size: '150px' },
            { field: 'productPrice', caption: 'Định giá', size: '120px' },
            { field: 'productEdit', caption: 'Sửa', size: '50px' },
            { field: 'productDel', caption: 'Xóa', size: '50px' }
        ],
        onClick: function(event){
             var recid = event.recid;
            var code = w2ui.grid.records[recid - 1].productCode;
            var request = {};
            request.code = code;
            if(event.column === 5){
                //Sửa thông tin gói hàng
                self.isEdit = true;
                $.ajax({
                    type: "POST",
                    url: self.service + baseLink + "controller/Admin/getPackage.php",
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
                        var record = data.result;
                        self.packageId = record.package.Id;
                        self.addProductDialog(record);
                    }
                });
            }else if(event.column === 6){
                //Xóa gói hàng
                $.ajax({
                    type: "POST",
                    url: self.service + baseLink + "controller/Admin/deletePackage.php",
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
                        //Gọi hàm tìm kiếm
                        $("#searchPackage").trigger("click");
                    }
                });
            }
        }
    });
    //Them su kien cho cac nut bam
    var self = this;
    $("#newPackage").click(function(){
        self.addProductDialog();
    });
    //Hien cua so xuat bao cao
    $("#reportPackage").click(function(){
        self.dailyReport();
    });
    $("#searchPackage").click(function(){
        var code = $("#txt_PackageCode").val();
        var status = $("#lst_PackageStatus").data("selected").id;
        var dateF = $('#date_PackageDateF').data("w2field").el.value;
        var dateT = $('#date_PackageDateT').data("w2field").el.value;
        self.searchParam = null;
        self.searchParam = {
            code : code,
            status : status,
            fDate : dateF,
            tDate : dateT,
            isFirst : false,
            pageSize : 20,
            pageIndex : 1
        };
        self.searchPackage(code, status, dateF, dateT, false, 20, 0);
    });
    //Tự hiển thị kết quả 1 tháng gần nhất, và chỉ hiển thị những gói hàng có trạng thái khác trạng thái "đã bán"
    //Lấy quãng thời gian 1 tháng tính từ thời điểm hiện tại
    var tm = (new Date()).getMonth() + 1;
    var ty  = (new Date()).getFullYear();
    var td = (new Date()).getDate();
    var fd = td;
    var fy;
    var fm;
    if(tm === 1){
        fm = 12;
        fy = ty - 1;
    }else{
        fm = tm - 1;
        fy = ty;
    }
    $("#date_PackageDateT").val(td + "/" + tm + "/" + ty);
    $("#date_PackageDateF").val(fd + "/" + fm + "/" + fy);
    //clear before add new
    this.searchParam = null;
    this.searchParam = {
        code : "",
        status : "",
        fDate : fd + "/" + fm + "/" + fy,
        tDate : td + "/" + tm + "/" + ty,
        isFirst : true,
        pageSize : 20,
        pageIndex : 1
    };
    this.searchPackage("", "", fd + "/" + fm + "/" + fy, td + "/" + tm + "/" + ty, true, 20, 0);
};

/*
 * Tim kiem goi san pham
 * @params code ma goi hang
 * @params status trang thai goi hang
 * @params dateF thoi gian bat dau
 * @params dateT thoi gian ket thuc
 * @params isFirst xác định đây là truy vấn cho lần khởi tạo trang hay cho thao tác tìm kiếm
 * @returns {undefined}
 */
ProductManage.prototype.searchPackage = function(code, status, dateF, dateT, isFirst, pageSize, pageIndex){
    var self = this;
    var request = {};
    request.code = code;
    request.status = status;
    request.fDate = dateF === "" ? "" : standardDate(dateF,1);
    request.tDate = dateT === "" ? "" : standardDate(dateT, 1);
    request.isFirst = isFirst;
    request.pageSize = pageSize;
    request.pageIndex = pageIndex;
    $.ajax({
        type: "POST",
        url: self.service + baseLink + "controller/Admin/SearchPackage.php",
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
            var records = data.result;
            var rs = new Array();
            for(var i = 0; i< records.length; i++){
                var record = records[i];
                rs.push({
                    recid: i + 1,
                    productCode: record.Code,
                    productDesc: record.Desc,
                    productImportTime: record.ImportDate,
                    productStatus: record.Status,
                    productPrice: typeof record.Cost === "" ?  "N/A" : record.Cost,
                    productEdit: "Sửa",
                    productDel: "Xóa"
                });
            }
            w2ui.grid.clear();
            w2ui["grid"].add(rs);
            w2ui.grid.refresh();
        }
    });
};

ProductManage.prototype.addCustomer = function(name, address, phonenumber, email, sex, birthday){
    var request = {};
    request.name = name;
    request.address = address;
    request.phonenumber = phonenumber;
    request.email = email;
    request.sex = sex;
    request.birthday = standardDate(birthday, 0);
    var self = this;
    $.ajax({
        type: "POST",
        url: self.service + baseLink + "controller/Admin/addNewCustomer.php",
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
            if(data !== "0"){
                //Luu thong tin goi hang
                var code = $("#popup_addPackage_packageCode").val();
                var customId = data;
                var desc = $("#popup_addPackage_packageDesc").val();
                var importDate = $('#dlg_importDate').data("w2field").el.value;
                if(!self.isEdit){
                    self.addPackage(code, customId, desc, importDate);  
                }else{
                    self.updatePackage(self.packageId, code, customId, desc, importDate);
                }
            }
        }
    });
};

ProductManage.prototype.updateCustomer = function(id, name, address, phonenumber, email, sex, birthday){
    var self = this;
    var request = {
        id: id,
        name: name,
        address: address,
        phonenumber: phonenumber,
        email: email,
        sex: sex,
        birthday: birthday
    };
    $.ajax({
        type: "POST",
        url: self.service + baseLink + "controller/Admin/updateCustomer.php",
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
            if(data !== "0"){
                //Luu thong tin goi hang
                var code = $("#popup_addPackage_packageCode").val();
                var customId = id;
                var desc = $("#popup_addPackage_packageDesc").val();
                var importDate = $('#dlg_importDate').data("w2field").el.value;
                if(!self.isEdit){
                    self.addPackage(code, customId, desc, importDate);  
                }else{
                    self.updatePackage(self.packageId, code, customId, desc, importDate);
                }
            }
        }
    });
};

ProductManage.prototype.updatePackage = function(id, code, customId, desc, importDate){
    var self = this;
    var request = {};
    request.package = {
        id: id,
        code: code,
        desc: desc,
        customId: customId,
        importDate: standardDate(importDate, 0)
    };
    request.product = this.products;
    $.ajax({
        type: "POST",
        url: self.service + baseLink + "controller/Admin/updatePackage.php",
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
        }
    });
};

/**
 * Thêm một gói hàng mới
 * @param {type} code
 * @param {type} customId
 * @param {type} desc
 * @param {type} importDate
 * @returns {undefined}
 */
ProductManage.prototype.addPackage = function(code, customId, desc, importDate){
    var self = this;
    var request = {};
    request.code = code;
    request.desc = desc;
    request.customId = customId;
    request.importDate = standardDate(importDate, 0);
    $.ajax({
        type: "POST",
        url: self.service + baseLink + "controller/Admin/addNewPackage.php",
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
            if(data.result !== -1){
                //Lưu thông tin sản phẩm đi kèm gói hàng
                request = {};
                for(var i = 0; i < self.products.length; i++){
                    self.products[i].packageId = data.result;
                }
                request.data = self.products;
                $.ajax({
                    type: "POST",
                    url: self.service + baseLink + "controller/Admin/addNewProduct.php",
                    data: JSON.stringify(request),
                    dataType: "json",
                    success: function(data, textStatus, jqXHR){
                        if(data.result.length > 0){
                            $.notify("Lưu thành công!",{
                                autohide: true,
                                autoHideDelay: 5000,
                                position: "top center"
                             });
                        }
                    }
                });
            }
        }
    });
};

/*
 * Hiển thị màn hình thống kê cho hệ thống
 */
ProductManage.prototype.dailyReport = function(){
    var self = this;
    destroyW2uiObject(w2ui.layout_report_product);
    $().w2layout({
        name: "layout_report_product",
        padding: 0,
        panels: [
            {
                type: "main",
                size: "500",
                content: "<div id='layout_main_report_product'></div>"
            }
        ]
    });
    w2popup.close();
    w2popup.open({
        title: "Thống kê sản phẩm",
        body: "<div id='popup_report_product'></div>",
        modal: true,
        width: 460,
        height: 250,
        showClose: true,
        onOpen: function(event){
            event.onComplete = function(event){
                $("#w2ui-popup #popup_report_product").w2render("layout_report_product");
                var html = new Array();
                html.push("<div style='width:430px;margin-top:10px;'>");
                    html.push("<div class='floatLayout' style='margin-left:14px;'>");
                        html.push("<div style='float:left;font-weight:bold;'><input type='radio' name='report' value='daily' checked>Báo cáo theo ngày:</div>");
                        html.push("<div style='float:right;'><input type='text' id='dailyReport'/></div>");
                    html.push("</div>");
                    html.push("<div class='floatLayout' style='margin-top:5px;margin-left:14px;'>");
                        html.push("<div style='float:left;font-weight:bold;'><input type='radio' name='report' value='daily'>Báo cáo theo tháng:</div>");
                        html.push("<div style='float:right;'><input type='text' id='monthlyReport'/></div>");
                    html.push("</div>");
                    html.push("<div class='floatLayout' style='margin-top:5px;margin-left:14px;'>");
                        html.push("<div style='float:left;font-weight:bold;'><input type='radio' name='report' value='daily'>Báo cáo theo năm:</div>");
                        html.push("<div style='float:right;'><input type='text' id='yearlyReport'/></div>");
                    html.push("</div>");
                    html.push("<div class='floatLayout' style='margin-top:5px;font-weight:bold;'>");
                        html.push("<fieldset>");
                            html.push("<legend><input type='radio' name='report' value='daily'>Báo cáo tùy chọn:</legend>");
                            html.push("<div class='floatLayout'>");
                                html.push("<div style='float:left;'>Từ:<input type='text' id='customReportFrom'/></div>");
                                html.push("<div style='float:right;'>Tới:<input type='text' id='customReportTo'/></div>");
                            html.push("</div>");
                        html.push("</fieldset>");
                    html.push("</div>");
                    html.push("<div class='floatLayout'>");
                        html.push("<div style='margin-top: 10px;float:right;'><a href='#' class='button blue' id='btn_excelExport'>Xuất Excel</a></div>");
                        html.push("<div style='margin-top: 10px;float:right;margin-right:10px;'><a href='#' class='button blue' id='btn_viewReport'>Xem báo cáo</a></div>");
                    html.push("</div>");
                html.push("</div>");
                $("#layout_main_report_product").append(html.join(""));
                $("#dailyReport").w2field('date', { format: 'dd/mm/yyyy', start:  self.month + '/5/' + self.year, end: self.month + '/25/' + self.year });
                $("#monthlyReport").w2field('date', { format: 'dd/mm/yyyy', start:  self.month + '/5/' + self.year, end: self.month + '/25/' + self.year });
                $("#yearlyReport").w2field('date', { format: 'dd/mm/yyyy', start:  self.month + '/5/' + self.year, end: self.month + '/25/' + self.year });
                $("#customReportFrom").w2field('date', { format: 'dd/mm/yyyy', start:  self.month + '/5/' + self.year, end: self.month + '/25/' + self.year });
                $("#customReportTo").w2field('date', { format: 'dd/mm/yyyy', start:  self.month + '/5/' + self.year, end: self.month + '/25/' + self.year });
                
            };
        }
    });
};

/**
 * Thêm một gói hàng mới
 * @param {json} package
 * @returns {undefined}
 */
ProductManage.prototype.addProductDialog = function(package){
    this.products.length = 0;//clear product cache
    this.isEdit = typeof package !== "undefined" ? true : false;
    //Không có thao tác xóa thông tin khách hàng.
    // Chỉ có: hoặc là update lại thông tin khách hàng, hoặc là thêm mới.
    var isUpdateCustomer = false;
    var idPrefix = "popup_addPackage";
    destroyW2uiObject(w2ui.layout_addProduct);
    var self = this;
    var isClickSaveButton = false; // Kiểm tra xem nút save có được bấm không, nếu không thì không cần phải cập nhật lại danh sách hàng hóa ở trang chính
    $().w2layout({
        name: "layout_addProduct",
        padding: 0,
        panels: [
            {
                type:"main",
                size: "1000",
                content: "<div id='layout_main_addProduct'><div id='dlg_productInfo'></div><div id='dlg_customerInfo'></div><div id='dlg_productList'></div></div>"
            }
        ]
    });
    w2popup.close();
    w2popup.open({
        title: "Thông tin gói hàng",
        body: "<div id='popup_addProduct'></div>",
        modal: false,
        width: 1110,
        height: 600,
        showClose: true,
        onOpen: function(event){
            event.onComplete = function(event){
                $("#w2ui-popup #popup_addProduct").w2render("layout_addProduct");
                var html1 = new Array();
                html1.push("<fieldset>");
                    html1.push("<legend>Thông tin sản phẩm</legend>");
                    
                    html1.push("<table>");
                        html1.push("<tr>");
                            html1.push("<td width='15%'></td>");
                            html1.push("<td width='30%'></td>");
                            html1.push("<td width='55%'></td>");
                        html1.push("</tr>");
                        html1.push("<tr>");
                            html1.push("<td>");
                                html1.push("Mã gói hàng:");
                            html1.push("</td>");
                            html1.push("<td>");
                                html1.push("<input type='text' id='" + idPrefix + "_packageCode'/>");
                            html1.push("</td>");
                            html1.push("<td rowspan='3'>");
                                html1.push("<div>");
                                    html1.push("<div>Mô tả gói hàng:</div>");
                                    html1.push("<div><textarea id='" + idPrefix + "_packageDesc' style='width:100%;height:100%;'></textarea></div>");
                                html1.push("</div>");
                            html1.push("</td>");
                        html1.push("</tr>");
                        html1.push("<tr>");
                            html1.push("<td>");
                                html1.push("Ngày nhập hàng:");
                            html1.push("</td>");
                            html1.push("<td>");
                                html1.push("<input type='us-date' id='dlg_importDate'/>");
                            html1.push("</td>");
                            html1.push("<td></td>");
                        html1.push("</tr>");
                        html1.push("<tr>");
                            html1.push("<td>");
                                html1.push("Định giá:");
                            html1.push("</td>");
                            html1.push("<td>");
                                html1.push("<input type='text' id='dlg_productPrice' readonly/>");
                            html1.push("</td>");
                            html1.push("<td></td>");
                        html1.push("</tr>");
                    html1.push("</table>");
                    html1.push("<div style='margin-top: 10px;'><a href='#' class='button blue' id='addNewItem'>Thêm mới sản phẩm</a></div>");
                html1.push("</fieldset>");
                $("#dlg_productInfo").append(html1.join(""));
                $("#dlg_importDate").w2field('date', { format: 'dd/mm/yyyy', start:  self.month + '/5/' + self.year, end: self.month + '/25/' + self.year });
                $('#dlg_productPrice').w2field('int', { autoFormat: false,placeholder: "VND" });
                
                
                var html2 = new Array();
                html2.push("<fieldset>");
                    html2.push("<legend>Thông tin khách hàng</legend>");
                    html2.push("<table>");
                        html2.push("<tr>");
                            html2.push("<td width='10%'></td>");
                            html2.push("<td width='30%'></td>");
                            html2.push("<td width='60%'></td>");
                        html2.push("</tr>");
                        html2.push("<tr>");
                            html2.push("<td>Họ tên:</td>");
                            html2.push("<td><input type='text' id='" + idPrefix + "_customerName'/></td>");
                            html2.push("<td rowspan='8'>");
                                html2.push("<fieldset>");
                                    html2.push("<legend>Tìm kiếm khách hàng</legend>");
                                    html2.push("<div>");
                                        html2.push("<table>");
                                            html2.push("<tr>");
                                                html2.push("<td width='25%'></td>");
                                                html2.push("<td width='35%'></td>");
                                                html2.push("<td width='40%'></td>");
                                            html2.push("</tr>");
                                            html2.push("<tr>");
                                                html2.push("<td>Họ tên:</td>");
                                                html2.push("<td><input type='text' id='" + idPrefix +  "txt_customerName'/></td>");
                                                html2.push("<td></td>");
                                            html2.push("</tr>");
                                            html2.push("<tr>");
                                                html2.push("<td>Số điện thoại:</td>");
                                                html2.push("<td><input type='text' id='" + idPrefix +  "txt_customerPhone'/></td>");
                                                html2.push("<td><a href='#' class='button blue' id='btn_searchCustomer'>Tìm kiếm</a></td>");
                                            html2.push("</tr>");
                                            html2.push("<tr>");
                                                html2.push("<td>Email:</td>");
                                                html2.push("<td><input type='text' id='" + idPrefix +  "txt_customerEmail'/></td>");
                                                html2.push("<td></td>");
                                            html2.push("</tr>");
                                        html2.push("</table>");
                                    html2.push("</div>");
                                    html2.push("<div>");
                                        //Đặt danh sách kết quả tìm kiếm tại đây
                                        html2.push("<div id='dlg_productManage_searchCustomer_result' style='width:100%;height:150px;'></div>");
                                    html2.push("</div>");
                                html2.push("</fieldset>");
                            html2.push("</td>");
                        html2.push("</tr>");
                        html2.push("<tr>");
                            html2.push("<td>Năm sinh:</td>");
                            html2.push("<td><input type='text' id='" + idPrefix + "_customerBirthday'/></td>");
                            html2.push("<td></td>");
                        html2.push("</tr>");
                        html2.push("<tr>");
                            html2.push("<td>Giới tính:</td>");
                            html2.push("<td><input type='radio' name='" + idPrefix + "_customerSex' value='0' checked/>Nam <input type='radio' name='" +idPrefix + "_customerSex' value='1' />Nữ</td>");
                            html2.push("<td></td>");
                        html2.push("</tr>");
                        html2.push("<tr>");
                        html2.push("<tr>");
                            html2.push("<td>Số điện thoại:</td>");
                            html2.push("<td><input type='text' id='" + idPrefix + "_customerPhone'/></td>");
                            html2.push("<td></td>");
                        html2.push("</tr>");
                        html2.push("<tr>");
                            html2.push("<td>Email:</td>");
                            html2.push("<td><input type='text' id='" + idPrefix + "_customerEmail'/></td>");
                            html2.push("<td></td>");
                        html2.push("</tr>");
                        html2.push("<tr>");
                            html2.push("<td>Địa chỉ:</td>");
                            html2.push("<td><input type='text' id='" + idPrefix + "_customerAddress'/></td>");
                            html2.push("<td></td>");
                        html2.push("</tr>");
                        html2.push("<tr>");
                            html2.push("<td>Ghi chú:</td>");
                            html2.push("<td><textarea name='comments' type='text' style='resize: none' id='" + idPrefix + "_customerNote'></textarea></td>");
                            html2.push("<td></td>");
                        html2.push("</tr>");
                        html2.push("<tr>");
                            html2.push("<td colspan='2'><a href='#' class='button blue' id='dlg_productmanager_sendMail'>Gửi thư cho khách hàng</a></td>");
                            html2.push("<td></td>");
                        html2.push("</tr>");
                    html2.push("</table>");
                html2.push("</fieldset>");
                $("#dlg_customerInfo").append(html2.join(""));
                $("#" + idPrefix + "_customerBirthday").w2field('date', { format: 'dd/mm/yyyy'});
                destroyW2uiObject(w2ui.searchCustomer_result);
                var customerId = 0;
                $("#dlg_productManage_searchCustomer_result").w2grid({
                    name: "searchCustomer_result",
                    columns:[
                        {field: "recid", caption: "STT", size: "7%"},
                        {field: "fname", caption: "Họ tên", size: "30%"},
                        {field: "fphone", caption: "Số điện thoại", size: "20%"},
                        {field: "femail", caption: "Email", size: "30%"},
                        {field: "faction", caption: "Thao tác", size: "13%"}
                    ],
                    onClick: function(event){
                        if(event.column === 4){
                            //Người dùng bấm vào nút thêm trong danh sách khách hàng
                            isUpdateCustomer = true;
                            var id = event.recid;
                            customerId = id;
                            var request = {};
                            request.id = id;
                            $.ajax({
                                type: "POST",
                                url: self.service + baseLink + "controller/Admin/getCustomer.php",
                                data: JSON.stringify(request),
                                dataType: "json",
                                success: function(data, textStatus, jqXHR){
                                    //Điền dữ liệu vào bảng bên cạnh
                                    var record = data.result;
                                    $("#" + idPrefix + "_customerName").val(record.name);
                                    $("#" + idPrefix + "_customerAddress").val(record.address);
                                    $("#" + idPrefix + "_customerPhone").val(record.phone);
                                    $("#" + idPrefix + "_customerEmail").val(record.email);
                                    $("#" + idPrefix + "_customerBirthday").val(convertDateToFormInput(record.birthday));
                                    $("input[name='" + idPrefix + "_customerSex'][value=" + record.sex + "]").prop('checked', true);
                                }
                            });
                        }
                    }
                });
                
                var html3 = new Array();
                html3.push("<fieldset>");
                    html3.push("<legend>Danh sách sản phẩm</legend>");
                    html3.push("<div id='dlg_grid_container_productList' style='height: 150px;'></div>");
                html3.push("</fieldset>");
                $("#dlg_productList").append(html3.join(""));
                destroyW2uiObject(w2ui.productInPackages);
                $("#dlg_grid_container_productList").w2grid({
                    name: "productInPackages",
                    columns: [
                        {field: "recid", caption: "STT", size: "7%"},
                        {field: "fcode", caption: "Mã sản phẩm", size: "20%"},
                        {field: "fdesc", caption: "Mô tả", size: "30%"},
                        {field: "fcost", caption: "Giá", size: "20%"},
                        {field: "fedit", caption: "Sửa", size: "15%"},
                        {field: "fdel", caption: "Xóa", size: "12%"}
                    ],
                    onClick: function(event){
                        var id = event.recid;
                        //Xóa sản phẩm
                        if(event.column === 5){
                            this.remove(id);
                            //loại bỏ sản phẩm ra khỏi cache
                            self.products.splice(id - 1, 1);
                        }else if(event.column === 4){
                            //Sửa sản phẩm
                            var product = self.products[id - 1];
                            self.addNewItem(product, id - 1);
                        }
                    }
                });
                
                //bind dữ liệu trong trường hợp sửa đổi gói hàng
                if(self.isEdit){
                    $("#" + idPrefix + "_packageCode").val(package.package.Code);
                    $("#dlg_importDate").val(convertDateToFormInput(package.package.ImportDate));
                    $("#" + idPrefix + "_customerName").val(package.customer.name);
                    $("#" + idPrefix + "_customerBirthday").val(convertDateToFormInput(package.customer.birthday));
                    $("#" + idPrefix + "_customerPhone").val(package.customer.phone);
                    $("#" + idPrefix + "_customerEmail").val(package.customer.email);
                    $("#" + idPrefix + "_customerAddress").val(package.customer.address);
                    $("#" + idPrefix + "_packageDesc").val(package.package.Desc);
                    $("input[name='" + idPrefix + "_customerSex'][value=" + package.customer.sex + "]").prop('checked', true);
                    var products = new Array();
                    var cost = 0;
                    for(var i = 0; i < package.products.length; i++){
                        products.push({
                            recid: i + 1,
                            fcode: package.products[i].productName,
                            fdesc: package.products[i].description,
                            fcost: package.products[i].importValue,
                            fedit: "Sửa",
                            fdel: "Xóa"
                        });
                        cost += parseInt(package.products[i].exportValue);
                        var request = {
                            packageId: package.package.id,
                            productName: package.products[i].productName,
                            productDesc: package.products[i].description,
                            importValue: package.products[i].importValue,
                            exportValue: package.products[i].exportValue,
                            exportDate: package.products[i].exportDate,
                            imagePath: package.products[i].imagePath,
                            isAccept: 0
                        };
                        self.products.push(request);
                    }
                    w2ui.productInPackages.clear();
                    w2ui.productInPackages.add(products);
                    $("#dlg_productPrice").val(cost);
                }
                //bind controls
                //Hien cua so them moi san pham vao goi hang
                $("#addNewItem").click(function(){
                    self.addNewItem();
                });
                
                //Hien cua so gui thu cho khach hang
                $("#dlg_productmanager_sendMail").click(function(){
                    var email = $("#" + idPrefix + "_customerEmail").val();
                    if(email !== ""){
                        if(w2utils.isEmail(email)){
                            self.sendMail(email);
                        }else{
                            $("#" + idPrefix + "_customerEmail").w2tag("Địa chỉ email không hợp lệ.");
                        }
                    }else{
                        $("#" + idPrefix + "_customerEmail").w2tag("Vui lòng nhập địa chỉ email.");
                    }
                });
                $("#popup_addPackage_cancelBtn").click(function(){
                    w2popup.close();
                });
                $("#popup_addPackage_saveBtn").click(function(){
                    //Lấy thông tin khách hàng
                    var name = $("#" + idPrefix + "_customerName").val();
                    var address = $("#" + idPrefix + "_customerAddress").val();
                    var phonenumber = $("#" + idPrefix + "_customerPhone").val();
                    var email = $("#" + idPrefix + "_customerEmail").val();
                    var sex = $("input:radio[name='" + idPrefix + "_customerSex']:checked").val();
                    var birthday = $("#" + idPrefix + "_customerBirthday").data("w2field").el.value;
                    isClickSaveButton = true;
                    //Chỉ khi bấm chọn một khách hàng bên cột tìm kiếm thì mới có hành động update, không thì luôn là thêm mới
                    if(!isUpdateCustomer){
                        self.addCustomer(name, address, phonenumber, email, sex, birthday);
                    }else{
                        self.updateCustomer(customerId, name, address, phonenumber, email, sex, birthday);
                    }
                });
                $("#btn_searchCustomer").click(function(){
                    var name = $("#" + idPrefix + "txt_customerName").val();
                    var phone = $("#" + idPrefix + "txt_customerPhone").val();
                    var email = $("#" + idPrefix + "txt_customerEmail").val();
                    self.searchCustomer(name, phone, email, 10, 0);
                });
            };
        },
        onClose: function(event){
            if(isClickSaveButton){
                $("#searchPackage").trigger("click");
            }
        },
        buttons: "<div id='dlg_buttons' style='overflow:hidden;'>"
                + "<div style='float: right;'><a href='#' class='button blue' id='popup_addPackage_cancelBtn'>Đóng</a></div>"
                + "<div style='float: right; margin-right: 10px;'><a href='#' class='button blue' id='popup_addPackage_sendCostBtn'>Gửi định giá</a></div>"
                + "<div style='float: right; margin-right: 10px;'><a href='#' class='button blue' id='popup_addPackage_saveBtn'>Lưu</a></div>"
                +"</div>"
    });
};

ProductManage.prototype.searchCustomer = function(name, phone, email, pageSize, pageIndex){
    var request = {};
    request.name = name;
    request.phone = phone;
    request.email = email;
    request.pageSize = pageSize;
    request.pageIndex = pageIndex;
    var self = this;
    $.ajax({
        type: "POST",
        url: self.service + baseLink + "controller/Admin/searchCustomer.php",
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
            //Hiển thị kết quả lên màn hình
            var records = data.result;
            var rs = new Array();
            for(var i = 0; i< records.length; i++){
                var record = records[i];
                rs.push({
                    recid: record.id,
                    fname: record.name,
                    fphone: record.phone,
                    femail: record.email,
                    faction: "Chọn"
                });
            }
            w2ui.searchCustomer_result.clear();
            w2ui.searchCustomer_result.add(rs);
            w2ui.searchCustomer_result.refresh();
        }
    });
};

/**
 * Hiển thị form gửi thư cho khách hàng
 * @param {string in email format} email email của khách hàng
 * @returns {undefined}
 */
ProductManage.prototype.sendMail = function(email){
    //render html code
    var html = new Array();
    html.push("<div>");
        html.push("<div id='dlg_productmanage_sendMail'>");
            html.push("<ul>");
                html.push("<li><a href='#dlg_productmanage_sendMail_template'>Gửi thư theo mẫu</a></li>");
                html.push("<li><a href='#dlg_productmanage_sendMail_notemplate'>Gửi thư tùy chọn</a></li>");
            html.push("</ul>");
            html.push("<div id='dlg_productmanage_sendMail_template'>");
                html.push("<table>");
                    html.push("<tr>");
                        html.push("<td width='10%'>");
                        html.push("</td>");
                        html.push("<td width='20%'>");
                        html.push("</td>");
                        html.push("<td width='10%'>");
                        html.push("</td>");
                        html.push("<td width='60%'>");
                        html.push("</td>");
                    html.push("</tr>");
                    html.push("<tr>");
                        html.push("<td>");
                            html.push("Gửi tới:");
                        html.push("</td>");
                        html.push("<td>");
                            html.push("<input type='text' id='txt_templateEmailAddress' value='" + email + "'/>");
                        html.push("</td>");
                        html.push("<td>");
                            html.push("Mẫu thư:");
                        html.push("</td>");
                        html.push("<td>");
                            html.push("<select id='mailTemplate'>");
                                html.push("<option value='99'>Chọn một mẫu thư</option>");
                                html.push("<option value='0'>Thư định giá</option>");
                                html.push("<option value='1'>Thư thông báo quá hạn 30 ngày</option>");
                                html.push("<option value='2'>Thư thông báo quá hạn 45 ngày</option>");
                                html.push("<option value='3'>Thư thông báo đã bán sản phẩm</option>");
                            html.push("</select>");
                        html.push("</td>");
                    html.push("</tr>");
                    html.push("<tr>");
                        html.push("<td>");
                            html.push("Tiêu đề:");
                        html.push("</td>");
                        html.push("<td colspan='3'>");
                            html.push("<input type='text' style='width:450px;'/>");
                        html.push("</td>");
                    html.push("</tr>");
                html.push("</table>");
                html.push("<div style='width:100%;'>");
                    html.push("<textarea id='sendMailContent' style='width:100%;height:360px;'></textarea>");
                html.push("</div>");
            html.push("</div>");
            html.push("<div id='dlg_productmanage_sendMail_notemplate'>");
                html.push("<table>");
                    html.push("<tr>");
                        html.push("<td width='10%'>");
                        html.push("</td>");
                        html.push("<td width='20%'>");
                        html.push("</td>");
                        html.push("<td width='10%'>");
                        html.push("</td>");
                        html.push("<td width='60%'>");
                        html.push("</td>");
                    html.push("</tr>");
                    html.push("<tr>");
                        html.push("<td>");
                            html.push("Gửi tới:");
                        html.push("</td>");
                        html.push("<td>");
                            html.push("<input type='text' />");
                        html.push("</td>");
                        html.push("<td>");
                        html.push("</td>");
                        html.push("<td>");
                        html.push("</td>");
                    html.push("</tr>");
                    html.push("<tr>");
                        html.push("<td>");
                            html.push("Tiêu đề:");
                        html.push("</td>");
                        html.push("<td colspan='3'>");
                            html.push("<input type='text' style='width:450px;'/>");
                        html.push("</td>");
                    html.push("</tr>");
                html.push("</table>");
                html.push("<div style='width:100%;'>");
                    html.push("<textarea id='sendMailContentNoTemplate' style='width:720px;height:300px;'></textarea>");
                html.push("</div>");
            html.push("</div>");
        html.push("</div>");
        html.push("</div>");
    html.push("</div>");
    $(html.join("")).dialog({
        title: "Gửi thư cho khách hàng",
        buttons: [
            {
                text: "Gửi"
            },
            {
                text: "Bỏ qua",
                click: function(){
                    $("#dlg_productmanage_sendMail").tabs("destroy");
                    $("#mailTemplate").selectmenu("destroy");
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        ],
        open: function(){
            $("#dlg_productmanage_sendMail" ).tabs();
            $("#mailTemplate").selectmenu({
                change: function(event, ui){
                    var selectedIndex = parseInt(ui.item.value);
                    switch(selectedIndex){
                        case 0:
                            $("#sendMailContent").loadTemplate("../../models/mail-templates/SetCostEmailTemplate.html?v=381405102015",
                            {
                                customerName: "Lê Anh Đức"
                            },
                            {
                                success: function(){
                                    console.log($("#SetCostEmailTemplate").html());
                                    $("#sendMailContent").sceditor('instance').wysiwygEditorInsertHtml($("#SetCostEmailTemplate").html());
                                }
                            }
                            );
                            break;
                        case 1:
                            
                            break;
                        case 2:
                            break;
                        case 3:
                            break; 
                        default:
                            break;
                    }
                }
            });
            $("#sendMailContent").sceditor({
                plugins: "bbcode",
                style:"../../css/defaulteditor.css"
            });
            $("#sendMailContentNoTemplate").sceditor({
                plugins: "bbcode",
                style:"../../css/defaulteditor.css"
            });
        },
        close: function(){
            $(this).dialog("destroy");
        },
        modal: true,
        dialogClass: "popup_sendmail"
    });
    
};

/*
 * Cua so them moi, chỉnh sửa san pham trong gói hàng
 * @param object product sản phẩm cần sửa
 * @param int vị trí của sản phẩm trong mảng sản phẩm
 */
ProductManage.prototype.addNewItem = function(product, index){
    var self = this;
    var isEditItem = false;
    if(typeof product !== "undefined"){
        isEditItem = true;
    }
    //render html code
    var html = new Array();
    html.push("<div>");
        html.push("<table>");
            html.push("<tr>");
                html.push("<td width='30%'></td><td width='50%'></td><td width='20%'></td>");
            html.push("</tr>");
            html.push("<tr>");
                html.push("<td>Tên sản phẩm:</td>");
                html.push("<td><input type='text' id='productName'/></td>");
                html.push("<td>");
                    html.push("Xem trước:");
                html.push("</td>");
            html.push("</tr>");
            html.push("<tr>");
                html.push("<td>Mô tả sản phẩm:</td>");
                html.push("<td><input type='text' id='productDesc'/></td>");
                html.push("<td rowspan='3'><img id='thumb_item_image' src='../../css/images/nophoto.png'></td>");
            html.push("</tr>");
            html.push("<tr>");
                html.push("<td>Giá nhập vào:</td>");
                html.push("<td><input type='text' id='importValue' placeholder='VND'/></td>");
                html.push("<td></td>");
            html.push("</tr>");
            html.push("<tr>");
                html.push("<td>Định giá:</td>");
                html.push("<td><input type='text' id='exportValue' placeholder='VND'/></td>");
                html.push("<td></td>");
            html.push("</tr>");
            html.push("<tr>");
                html.push("<td>Ảnh:</td>");
                var service = getService();
                html.push("<td><div><input id='dlg_addNewItem_upload' type='file' name='files[]' data-url='" 
                        + service 
                        + baseLink
                        + "controller/Admin/uploadPhoto.php'/></div>"
                        + "<div id='progress' style='width:240px;height:20px;'><div class='bar' style='width: 0%;'></div></div>"
                        +"</td>");
                html.push("<td></td>");
            html.push("</tr>");
        html.push("</table>");
    html.push("</div>");
    var imagePath = "";//Đường dẫn tới tập tin ảnh
    var imageName = "";// Tên tập tin ảnh
    $(html.join("")).dialog({
        title: typeof product === "undefined" ? "Thêm mới sản phẩm" : "Chỉnh sửa sản phẩm",
        buttons: [
            {
                text: "Lưu",
                id: "btn_addNewItem_save",
                click: function(){
                    var productName = $("#productName").val();
                    var productDesc = $("#productDesc").val();
                    var importValue = $("#importValue").val();
                    var exportValue = $("#exportValue").val();
                    var exportDate = "";
                    var packageId = 0;
                    var request = {
                        packageId: packageId,
                        productName: productName,
                        productDesc: productDesc,
                        importValue: importValue.replace(/,/g, ""),
                        exportValue: exportValue.replace(/,/g, ""),
                        exportDate: exportDate,
                        imagePath: imagePath,
                        imageName: imageName,
                        isAccept: 0
                    };
                    if(!isEditItem){
                        self.products.push(request);
                        var rlength = w2ui.productInPackages.records.length;
                         w2ui.productInPackages.add({
                             recid: rlength + 1,
                             fcode: productName,
                             fdesc: productDesc,
                             fcost: importValue.replace(/,/g, ""),
                             fedit: "Sửa",
                             fdel: "Xóa"
                         });
                    }else{
                        //Đây là thao tác sửa đổi
                        self.products[index] = request;
                        w2ui.productInPackages.remove(index + 1);
                        w2ui.productInPackages.add({
                            recid: index + 1,
                             fcode: productName,
                             fdesc: productDesc,
                             fcost: importValue.replace(/,/g, ""),
                             fedit: "Sửa",
                             fdel: "Xóa"
                        });
                    }
                    $.notify("Lưu thành công!",{
                        autohide: true,
                        autoHideDelay: 5000,
                        position: "top center"
                     });
                     //Tính lại tham số định giá cho gói hàng
                     var price = 0;
                     for(var i = 0; i < self.products.length; i++){
                         price += parseInt(self.products[i].exportValue);
                     }
                     $("#dlg_productPrice").val(price);
                     //Ẩn nút lưu
                     $("#btn_addNewItem_save").hide();
                }
            },
            {
                text: "Đóng",
                id: "btn_addNewItem_close",
                click: function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        ],
        open: function(event, ui){
            $("#dlg_addNewItem_upload").fileupload({
                dataType: "json",
                done: function(e, data){
                    $.each(data.result.files, function(index, file){
                        var thumbUrl = file.thumbnailUrl;
                        $("#thumb_item_image").attr("src",thumbUrl);
                        imagePath = file.url;
                        imageName = file.name;
                    });
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .bar').css(
                        'width',
                        progress + '%'
                    );
                }
            });
            $("#importValue").w2field("int",{placeholder: "VND"});
            $("#exportValue").w2field("int", {placeholder: "VND"});
            if(typeof product !== "undefined"){
                $("#productName").val(product.productName);
                $("#productDesc").val(product.productDesc);
                $("#importValue").val(product.importValue);
                $("#exportValue").val(product.exportValue);
                //load image's base64 string
                var request = {
                  id: product.imagePath  
                };
                $.ajax({
                    type: "POST",
                    url: self.service + baseLink + "controller/Admin/getImage.php",
                    data: JSON.stringify(request),
                    dataType: "json",
                    success: function(data, textStatus, jqXHR){
                        var image = data.result;
                        $("#thumb_item_image").attr("src",image);
                        $("#thumb_item_image").width(200);
                        $("#thumb_item_image").height(200);
                    }
                });
            }
        },
        close: function(){
            $(this).dialog("destroy");
        },
        modal: true,
        dialogClass: "popup_addNewItem"
    });
};

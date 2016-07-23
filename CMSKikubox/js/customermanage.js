/* 
 * Nhóm chức năng quản lý danh sách khách hàng
 */

function CustomerManage(container){
    this.container = container;
    this.service = getService();
};

CustomerManage.prototype.constructor = CustomerManage;

CustomerManage.prototype.container;
CustomerManage.prototype.service; //<--Lấy thông tin link service của hệ thống


//Phục vụ việc phân trang, nếu là tìm mới thì reset lại dữ liệu này
//Khi phân trang, luôn kiểm tra xem điều kiện tìm kiếm hiện tại là gì
CustomerManage.prototype.searchParam = {};

CustomerManage.prototype.renderPage = function(){
    var html = new Array();
    html.push("<fieldset>");
        html.push("<legend>Tìm kiếm</legend>");
        html.push("<div style='overflow:hidden;'>");
            html.push("<div style='overflow:hidden;width:300px;float:left;'>");
                html.push("<div style='float: left;'>Tên khách hàng:</div>");
                html.push("<div style='float:right;'><input type='text' style='width:150px;' id='txt_CustomerName'/></div>");
            html.push("</div>");
            html.push("<div style='overflow:hidden;width:300px;float:left;margin-left:20px;'>");
                html.push("<div style='float: left;'>Số điện thoại:</div>");
                html.push("<div style='float:right;'><input type='text' style='width:150px;' id='txt_CustomerPhone'/></div>");
            html.push("</div>");
        html.push("</div>");
        html.push("<div style='overflow:hidden; margin-top: 20px;'>");
            html.push("<div style='overflow:hidden;width:300px;float:left;'>");
                html.push("<div style='float: left;'>Email:</div>");
                html.push("<div style='float:right;'><input type='text' style='width:150px;' id='txt_CustomerEmail'/></div>");
            html.push("</div>");
            html.push("<div style='overflow:hidden;width:300px;float:left;margin-left:20px;'>");
            html.push("</div>");
        html.push("</div>");
        html.push("<div style='overflow:hidden; margin-top: 20px;'>");
            html.push("<div style='float:left;margin-left: 150px;'><a href='#' class='button blue' id='searchCustomer'>Tìm kiếm</a></div>");
        html.push("</div>");
    html.push("</fieldset>");
    html.push("<fieldset>");
        html.push("<legend>Danh sách khách hàng</legend>");
        html.push("<div id='customerlist'></div>");
    html.push("</fieldset>");
    $("#" + this.container).append(html.join(""));
    this.bindControl();
};

/**
 * Thêm các sự kiện và control cho các thành phần trên trang
 * @returns {undefined}
 */
CustomerManage.prototype.bindControl = function(){
    var self = this;
    if(typeof w2ui.customergrid !== "undefined"){
        w2ui.customergrid.destroy();
    }
    $('#customerlist').w2grid({ 
        name: 'customergrid', 
        show:{
            header: true,
            footer: true,
            lineNumbers: true,
            toolbar: true
        },
        toolbar:{
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
                    self.searchCustomer(self.searchParam.name, self.searchParam.phone, self.searchParam.email, self.searchParam.pageSize, self.searchParam.pageIndex + 20);
                    self.searchParam.pageIndex += 20;
                }else{
                    self.searchCustomer(self.searchParam.name, self.searchParam.phone, self.searchParam.email, self.searchParam.pageSize, self.searchParam.pageIndex + 20);
                    self.searchParam.pageIndex = (self.searchParam.pageIndex - 20) <= 0 ? 0 : self.searchParam.pageIndex - 20;
                }
            }
        },
        columns:[
            { field: 'customerName', caption: 'Tên khách hàng', size: '30%' },
            { field: 'customerEmail', caption: 'Email', size: '40%' },
            { field: 'customerPhone', caption: 'Số điện thoại', size: '120px' },
            { field: 'customerAddress', caption: 'Địa chỉ', size: '150px' },
            { field: 'customerLevel', caption: 'Cấp', size: '120px' },
            { field: 'customerEdit', caption: 'Sửa', size: '50px' }
        ]
    });
    
    var name = $("#txt_CustomerName").val();
    var phone = $("#txt_CustomerPhone").val();
    var email = $("#txt_CustomerEmail").val();
    this.searchParam = null;
    this.searchParam = {
        pageSize : 20,
        pageIndex : 1,
        name: name,
        phone: phone,
        email: email
    };
    this.searchCustomer(name, phone, email, 20, 0);
};

/**
 * Tìm kiếm, hiển thị danh sách khách hàng đang có trong hệ thống.
 * @param {string} name
 * @param {string} phone
 * @param {string} email
 * @param {int} pageSize
 * @param {int} pageIndex
 * @returns 
 */
CustomerManage.prototype.searchCustomer = function(name, phone, email, pageSize, pageIndex){
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
                    customerName: record.name,
                    customerPhone: record.phone,
                    customerEmail: record.email,
                    customerAddress: "Test",
                    customerLevel: "1",
                    customerEdit: "Sửa"
                });
            }
            w2ui.customergrid.clear();
            w2ui.customergrid.add(rs);
            w2ui.customergrid.refresh();
        }
    });
};
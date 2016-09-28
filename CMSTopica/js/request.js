function Request(){
//    this.service = "/CMSTopica";
    this.service = getService();
    this.init();
}

Request.prototype.constructor = Request;
Request.prototype.request_type = [];
Request.prototype.request_level = [];
Request.prototype.searchParam = {};

Request.prototype.init = function(){
    var self = this;
    //Search button's action
    $("#search-btn").click(function(){
        self.searchParam = {
            worker : $("#search-content").val(),
            request_type: "",
            request_important: "",
            request_status: "",
            f_request_at : "",
            t_request_at : "",
            pageSize : 50,
            pageIndex : 0
        };
        self.search(self.searchParam);
    });
    //Next button
    $("#next_btn").click(function(){
        if(self.searchParam !== null){
            self.searchParam.pageIndex += 50;
            self.search(self.searchParam);
        }
    });
    //Prev button
    $("#prev_btn").click(function(){
        if(self.searchParam !== null){
            self.searchParam.pageIndex = (self.searchParam.pageIndex - 50) <= 0 ? 0 : self.searchParam.pageIndex - 50;
            self.search(self.searchParam);
        }
    });
    $("#new-request").click(function(){
        self.createNewRequestWindow();
    });
    self.searchParam = {
        worker : "",
        request_type: "",
        request_important: "",
        request_status: "",
        f_request_at : "",
        t_request_at : "",
        pageSize : 50,
        pageIndex : 0
    };
    $.ajax({
        type: "POST",
        url: self.service + "/controller/SearchRequest.php",
        data: JSON.stringify(self.searchParam),
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
            self.generateRequestList(records, textStatus, jqXHR);
            
            //get init values
            var request = {};
            request.id = 1;
            //why using this case? because we need send sequently requests
            $.ajax({
                type: "POST",
                url: self.service + "/controller/GetRequestType.php",
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
                    self.getRequestType(records, textStatus, jqXHR);
                    var request = {};
                    request.id = 1;
                    $.ajax({
                        type: "POST",
                        url: self.service + "/controller/GetRequestLevel.php",
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
                            self.getRequestLevel(records, textStatus, jqXHR);
                        }
                    });
                }
            });
            
        }
    });
    $("#request_table").w2grid({
        show:{
            header: true,
            footer: true,
            lineNumbers: true
        },
        name: "request-table",
        columns:[
            { field: 'requestContent', caption: 'Nội dung', size: '35%' },
            { field: 'requestPetitioner', caption: 'Người gửi', size: '10%' },
            { field: 'requestType', caption: "Loại yêu cầu", size: "10%"},
            { field: "requestLevel", caption: "Độ ưu tiên", size: "10%"},
            { field: 'requestAt', caption: 'Thời gian gửi', size: '12%' },
            { field: 'requestStatus', caption: 'Trạng thái', size: '10%' }
        ]
    });
};

Request.prototype.getRequestType = function(records, textStatus, jqXHR){
    records = records[0];
    for(var i = 0; i < records.length; i++){
        this.request_type.push({
            text: records[i].request_type,
            id: records[i].id
        });
    }
};

Request.prototype.getRequestLevel = function(records, textStatus, jqXHR){
    records = records[0];
    for(var i = 0; i < records.length; i++){
        this.request_level.push({
            text: records[i].request_level,
            id: records[i].id
        });
    }
};

Request.prototype.createNewRequestWindow = function(){
    var self = this;
    this.destroyW2uiObject(w2ui.layout_new_request);
    $().w2layout({
        name: "layout_new_request",
        padding: 0,
        panels: [
            {
                type:"main",
                size: "700",
                content: "<div id='layout_new_request'><div id='popup_content'></div></div>"
            }
        ]
    });
    w2popup.close();
    w2popup.open({
        title: "Tạo mới yêu cầu",
        body: "<div id='popup_one_request'></div>",
        modal: false,
        width: 780,
        height: 600,
        overflow  : 'hidden',
        showClose: true,
        onOpen:function(event){
            event.onComplete = function(event){
                $("#w2ui-popup #popup_one_request").w2render("layout_new_request");
                var html = new Array();
                html.push("<div class='row' style='margin-top:5px;'>");
                    html.push("<div class='col-md-3 col-md-offset-1'>Người xử lý:</div>");
                    html.push("<div class='col-md-5'><input id='worker' style='width: 100%;'/></div>");
                html.push("</div>");
                html.push("<div class='row' style='margin-top:5px;'>");
                    html.push("<div class='col-md-3 col-md-offset-1'>Loại yêu cầu:</div>");
                    html.push("<div class='col-md-5'><input type='text' id='request_type' style='width: 100%;'/></div>");
                html.push("</div>");
                html.push("<div class='row' style='margin-top:5px;'>");
                    html.push("<div class='col-md-3 col-md-offset-1'>Mức ưu tiên:</div>");
                    html.push("<div class='col-md-5'><input type='text' id='request_level' style='width: 100%;'/></div>");
                html.push("</div>");
                html.push("<div class='row' style='margin-top:5px;'>");
                    html.push("<div class='col-md-3 col-md-offset-1'>Nội dung yêu cầu</div>");
                html.push("</div>");
                html.push("<div class='row' style='margin-top:5px;'>");
                    html.push("<div class='col-md-12'>");
                        html.push("<textarea name='request-content' id='request-content' rows='7' cols='80'></textarea>");
                    html.push("</div>");
                html.push("</div>");
                html.push("<div class='row' style='margin-top:5px;'>");
                    html.push("<div class='col-md-2 col-md-offset-8'><button id='request-cancel' type='button' class='btn btn-primary btn-sm'>Hủy yêu cầu</button></div>");
                    html.push("<div class='col-md-2'><button id='request-save' type='button' class='btn btn-primary btn-sm'>Gửi yêu cầu</button></div>");
                html.push("</div>");
                $("#popup_content").append(html.join(""));
                //bind actions
                CKEDITOR.replace('request-content');
                $('#request_type').w2field('list', { 
                    items: self.request_type
                });
                $('#request_level').w2field('list', { 
                    items: self.request_level
                });
                //Render user field
                $("#worker").autocomplete({
                    source: self.service + "/controller/AutocompletePetitioner.php",
                    minLength: 3,
                    classes: {
                        "ui-autocomplete" : "user-autocomplete"
                    }
                });
                $("#request-cancel").click(function(){
                     w2popup.close();
                });
                $("#request-save").click(function(){
                    var worker = $("#worker").val();
                    var request_type = $("#request_type").data("selected").id;
                    var request_level = $("#request_level").data("selected").id;
                    var content = CKEDITOR.instances["request-content"].getData();
                    var request = {};
                    request.worker = worker;
                    request.request_type = request_type;
                    request.request_important = request_level;
                    request.content = content;
                    $.ajax({
                        type: "POST",
                        url: self.service + "/controller/SaveRequest.php",
                        data: JSON.stringify(request),
                        dataType: "json",
                        beforeSend: function(){
                            $(".waiting_modal").show("slow");
                        },
                        error: function(){
                            $(".waiting_modal").hide("slow");
                            noty({
                                text: "Không thể lưu thông tin. Vui lòng liên hệ quản trị để kiểm tra.",
                                layout: 'center',
                                theme: 'defaultTheme',
                                modal: true,
                                type: "error"
                            });
                        },
                        success: function(data, textStatus, jqXHR){
                            $(".waiting_modal").hide("slow");
                            w2popup.close();
                            noty({
                            text: data.res,
                            layout: 'center',
                            theme: 'defaultTheme',
                            modal: true
                        });
                        }
                    });
                });
            };
        }
    });
};

Request.prototype.search = function(request){
    var self = this;
    sendRequest("POST", self.service + "/controller/SearchRequest.php", request, "json", self.generateRequestList);
};

Request.prototype.generateRequestList = function(records, textStatus, jqXHR){
    var records = records[0];
    console.log(records);
    var rs = new Array();
    for(var i = 0; i< records.length; i++){
        var record = records[i];
        rs.push({
            recid: i + 1,
            requestContent: record.request_content,
            requestPetitioner: record.petitioner,
            requestType: record.type,
            requestLevel: record.level,
            requestAt: record.request_at,
            requestStatus: record.status
        });
    }
    w2ui["request-table"].clear();
    w2ui["request-table"].add(rs);
    w2ui["request-table"].refresh();
};

Request.prototype.renderTable = function(){
    var self = this;
    $("#request_table").w2grid({
        show:{
            header: true,
            footer: true,
            lineNumbers: true
        },
        name: "request_table",
        columns:[
            { field: 'requestType', caption: 'Loại yêu cầu', size: '10%' },
            { field: 'requestWorker', caption: 'Người nhận', size: '15%' },
            { field: 'requestContent', caption: 'Nội dung yêu cầu', size: '15%' },
            { field: 'requestLevel', caption: 'Mức độ ưu tiên', size: '30%' },
            { field: 'requestStatus', caption: 'Trạng thái', size: '10%' },
            { field: 'requestAt', caption: 'Ngày yêu cầu', size: '5%' },
            { field: 'finishAt', caption: 'Ngày hoàn thành', size: '5%' }
        ]
    });
};

Request.prototype.destroyW2uiObject = function(ow2ui){
    if(typeof ow2ui !== "undefined"){
        ow2ui.destroy();
    }
};
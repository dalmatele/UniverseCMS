function Request(){
    this.service = "/CMSTopica";
//    this.service = getService();
    this.init();
}

Request.prototype.constructor = Request;

Request.prototype.init = function(){
    var self = this;
    //Search button's action
    $("#search-btn").click(function(){
        self.search();
    });
    self.search();
    $("#new-request").click(function(){
        self.createNewRequestWindow();
    });
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
                size: "600",
                content: "<div id='layout_new_request'><div id='popup_content'></div></div>"
            }
        ]
    });
    w2popup.close();
    w2popup.open({
        title: "Tạo mới yêu cầu",
        body: "<div id='popup_one_request'></div>",
        modal: false,
        width: 600,
        height: 350,
        showClose: true,
        onOpen:function(event){
            event.onComplete = function(event){
                $("#w2ui-popup #popup_one_request").w2render("layout_new_request");
                var html = new Array();
                html.push("<div class='row' style='margin-top:5px;'>");
                    html.push("<div class='col-md-3 col-md-offset-1'>Người xử lý:</div>");
                    html.push("<div class='col-md-5'><input type='text' id='from_address' style='width: 100%;'/></div>");
                html.push("</div>");
                $("#popup_content").append(html.join(""));
            };
        }
    });
};

Request.prototype.search = function(){
    var self = this;
    var request = {};
    request.worker = "";
    request.request_type = "";
    request.request_important = "";
    request.request_status = "";
    request.f_request_at = "";
    request.t_request_at = "";
    request.pageSize = 50;
    request.pageIndex = 0;
    sendRequest("POST", self.service + "/controller/SearchRequest.php", request, "json", self.generateRequestList);
};

Request.prototype.generateRequestList = function(records, textStatus, jqXHR){
    console.log(records);
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
        ],
    });
};

Request.prototype.destroyW2uiObject = function(ow2ui){
    if(typeof ow2ui !== "undefined"){
        ow2ui.destroy();
    }
};
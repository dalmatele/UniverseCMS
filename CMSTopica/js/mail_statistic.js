function EmailStatistic(){
//    this.service = "/CMSTopica";
    this.service = getService();
}

EmailStatistic.prototype.constructor = EmailStatistic;
EmailStatistic.prototype.service; //<--Lấy thông tin link service của hệ thống
//Phục vụ việc phân trang, nếu là tìm mới thì reset lại dữ liệu này
EmailStatistic.prototype.searchParam = {};

//static variable
EmailStatistic.haveNext = true;

EmailStatistic.prototype.init = function(){
    var self = this;
    $('input[type=us-date1]').w2field('date', { format: 'dd/mm/yyyy', end: $('input[type=us-date2]') });
    $('input[type=us-date2]').w2field('date', { format: 'dd/mm/yyyy', start: $('input[type=us-date1]') });
    $("#search-btn").click(function(){
        var dateF = $('#f_date').data("w2field").el.value;
        var dateT = $('#t_date').data("w2field").el.value;
        var subject = $("#email-subject").val();
        self.searchParam = null;
        self.searchParam = {
            subject : subject,
            f_date : self.standardDate(dateF, 1),
            t_date : self.standardDate(dateT, 1),
            pageSize : 50,
            pageIndex : 0
        };
        EmailStatistic.haveNext = true;
        self.search(self.searchParam);
    });
    $("#email-statistic-table").w2grid({
        show:{
            header: true,
            footer: true,
            lineNumbers: true
        },
        name: "email-static-table",
        columns:[
            { field: 'subject', caption: 'Tiêu đề thư', size: '35%' },
            { field: 'created_at', caption: 'Ngày tạo', size: '15%' },
            { field: 'status', caption: "Trạng thái", size: "10%"},
            { field: "send_at", caption: "Thời gian gửi", size: "15%"},
            { field: 'total', caption: 'Số lượng gửi', size: '10%' },
            { field: 'count', caption: 'Số lượt mở', size: '10%' }
        ]
    });
    //Next button
    $("#next_btn").click(function(){
        if(self.searchParam !== null && EmailStatistic.haveNext){
            self.searchParam.pageIndex += 50;
            self.search(self.searchParam);
        }
    });
    //Prev button
    $("#prev_btn").click(function(){
        if(self.searchParam !== null && self.searchParam.pageIndex !== 0){
            self.searchParam.pageIndex = (self.searchParam.pageIndex - 50) <= 0 ? 0 : self.searchParam.pageIndex - 50;
            self.search(self.searchParam);
        }
    });
};

EmailStatistic.prototype.search = function(searchParam){
    var self = this;
    var request = {};
    request.subject = searchParam.subject;
    request.f_date = searchParam.f_date;
    request.t_date = searchParam.t_date;
    request.pageSize = searchParam.pageSize;
    request.pageIndex = searchParam.pageIndex;
    sendRequest("POST", self.service + "/controller/GetMailStatistic.php", request, "json", self.generateEmailStatistic);
};

EmailStatistic.prototype.generateEmailStatistic = function(records, textStatus, jqXHR){
    var records = records[0];
    var rs = new Array();
    var count = 0;
    for(var i = 0; i< records.length; i++){
        var record = records[i];
        var created_date = EmailStatistic.convertToHumanDate(record.created_date);
        var send_at = EmailStatistic.convertToHumanDate(record.send_at);
        var percent_click = record.email_total > 0 ? record.email_count / record.email_total : 0;
        var style = "";
        percent_click = percent_click * 100;
        if(percent_click === 0){
            style = "background-color: red;color:white;";
        }else if(percent_click > 0 && percent_click <= 30){
            style = "background-color: #ff5733;color:white;";
        }else if(percent_click > 30 && percent_click <= 60){
            style = "background-color: yellow;color:white;";
        }else{
            style = "background-color: green;color:white;";
        }
        rs.push({
            recid: i + 1,
            subject: record.subject,
            created_at: created_date,
            status: record.status === 1 ? "Đã gửi" : "Chưa gửi",
            send_at: send_at,
            total: record.email_total,
            count: record.email_count,
            style: style
        });
        count++;
    }
    if(count < 50){
        EmailStatistic.haveNext = false;
    }
    w2ui["email-static-table"].clear();
    w2ui["email-static-table"].add(rs);
    w2ui["email-static-table"].refresh();
};

EmailStatistic.prototype.standardDate = function(date, format){
    var d = date.split("/");
    if(format === 1){
        return d[2] + "-" + d[1] + "-" + d[0];
    }
    if(format === 0){
        return d[2] + "/" + d[1] + "/" + d[0];
    }
};
EmailStatistic.convertToHumanDate = function(date){
    var date_tmp1 = date.split(" ");
    var date_tmp2 = date_tmp1[0];
    var date_tmp3 = date_tmp2.split("-");
    var date_tmp4 = date_tmp3[2] + "-" + date_tmp3[1] + "-" + date_tmp3[0];
    return date_tmp1[1] + " " + date_tmp4;
};


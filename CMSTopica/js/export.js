function Export(){
    this.service = "/CMSTopica";
//    this.service = getService();
    this.init();
};

Export.prototype.service; //<--Lấy thông tin link service của hệ thống

Export.prototype.constructor = Export;
Export.prototype.plot;

Export.prototype.init = function(){
    var self = this;
    var statuslst = [
       {
           text: 'Chưa gửi',
           id: "-1"
       }, 
       { 
           text: 'Đã gửi',
           id: "000"
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
    $('input[type=us-date1]').w2field('date', { format: 'dd/mm/yyyy', end: $('input[type=us-date2]') });
    $('input[type=us-date2]').w2field('date', { format: 'dd/mm/yyyy', start: $('input[type=us-date1]') });
    //for account report
    $('input[type=us-date3]').w2field('date', { format: 'dd/mm/yyyy', end: $('input[type=us-date4]') });
    $('input[type=us-date4]').w2field('date', { format: 'dd/mm/yyyy', start: $('input[type=us-date3]') });
    
     $("#search_btn").click(function(){
        self.exportExcel();
    });
    $("#search_account_report_btn").click(function(){
        self.exportAccountReport();
    });
    $("#account_monthly_report_btn").click(function(){
        self.AccountMonthlyReport();
    });
    self.generateGraph();
    //tabs manager
    $("#tabs").w2tabs({
        name: "tabs",
  	active: "kerry_report_tab",
  	tabs: [
            {id:"kerry_report_tab", caption: "Kerry Report"},
            {id: "account_report_tab", caption: "Accounting Report"},
            {id: "tm_report_tab", caption: "TM Report"},
            {id: "sale_report_tab", caption: "Sale Report"}
  	],
        onClick: function(event){
            $("#export_tabs_manager .tab").hide();//hide all tab's content before active one
            $("#export_tabs_manager #" + event.target).show();
        }
    });
    $("#export_tabs_manager .tab").hide();
    $("#kerry_report_tab").show();//show the content of tab
};

Export.prototype.requestGraphData = function(request, container){
    var self = this;
    $.ajax({
        type: "POST",
        url: self.service + "/controller/orderReport.php",
        data: JSON.stringify(request),
        dataType: "json",
        beforeSend: function(){
            $(".waiting_modal").show("slow");
        },
        error: function(){
            $(".waiting_modal").hide("slow");
        },
        success: function(data, textStatus, jqXHR){
            //generate graph
            $(".waiting_modal").hide("slow");
            var result= data.res;
            var successCount = 0;
            var unSuccessCount = 0;
            var otherReason = 0;
            for(var i = 0; i< result.length; i++){
                if(result[i].status_code === "POD"){
                    successCount = result[i].number;
                }else if(result[i].status_code.indexOf("060") !== -1){
                    unSuccessCount += result[i].number;
                }else{
                    otherReason += result[i].number;
                }
            }
            var result = new Array();
            result.push(["Thành công", parseInt(successCount) ]);
            result.push(["Không thành công", parseInt(unSuccessCount)]);
            result.push(["Khác", parseInt(unSuccessCount)]);
            self.plot = $.jqplot(container, [result], {
                gridPadding: {top:0, bottom:25, left:0, right:0},
                seriesDefaults:{
                    renderer:$.jqplot.PieRenderer, 
                    trendline:{ show:true }, 
                    rendererOptions: { 
                        padding: 8,
                        showDataLabels: true
                    }
                },
                legend:{
                    show:true, 
//                    placement: 'outside', 
                    rendererOptions: {
                        numberRows: 1
                    }, 
                    location:'s',
                    marginTop: '10px'
                },
                height: 200,
                width: 200
            });
        }
    });
};

/**
 * Lấy dữ liệu để tạo biểu đồ thống kê
 * @returns {undefined}
 */
Export.prototype.generateGraph = function(){
    var self = this;
//    if (typeof self.plot !== "undefined") {
//        self.plot.destroy();//huy do thi truoc do roi moi ve ban moi
//    }
    //get current date
    var today = new Date();
    var sday = this._generateDateString(today);
    var request = {};
    request.t_status_date = self.standardDate(sday, 1);
    var dayAgo = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 1);
    sday = this._generateDateString(dayAgo);
    request.f_status_date = self.standardDate(sday, 1);
    //lấy dữ liệu ngày
    self.requestGraphData(request, 'day_graph');
    //lấy dữ liệu tuần - 7 ngày gần nhất
     var lastWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);
     var wday = this._generateDateString(lastWeek);
     request.f_status_date = self.standardDate(wday, 1);
     self.requestGraphData(request, 'week_graph');
     //lấy dữ liệu 30 ngày gần nhất
     var lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
     var mday = this._generateDateString(lastMonth);
     request.f_status_date = self.standardDate(mday,1);
     self.requestGraphData(request , 'month_graph');
     //lấy dữ liệu 365 ngày gần nhất
     var lastYear = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate());
     var yday = this._generateDateString(lastYear);
     request.f_status_date = self.standardDate(yday,1);
     self.requestGraphData(request , 'year_graph');
};

Export.prototype._generateDateString = function(date){
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    if(day < 10){
        day = "0" + day;
    }
    if(month < 10){
        month = "0" + month;
    }
    var sday = day + "/" + month + "/" + year;
    return sday;
};

Export.prototype.exportExcel = function(){
    var self = this;
    var dateF = $('#f_date').data("w2field").el.value;
    var dateT = $('#t_date').data("w2field").el.value;
    var request = {};
    request.con_no = "";
    request.location = "";
    request.status_code = "";
    request.f_status_date = self.standardDate(dateF, 1);
    request.t_status_date = self.standardDate(dateT, 1);
    request.f_update_date = "";
    request.t_update_date = "";
    $.ajax({
        type: "POST",
        url: self.service + "/controller/excelExport.php",
        data: JSON.stringify(request),
        timeout: 60000,
        beforeSend: function(){
            $(".waiting_modal").show("slow");
        },
        error: function(error){
            $(".waiting_modal").hide("slow");
        },
        success: function(data, textStatus, jqXHR){
            $(".waiting_modal").hide("slow");
            if(data !== "none"){
                //Download file
                console.log(data);
                var iframe = document.createElement("iframe");
                iframe.setAttribute("src", self.service + "/controller/excelExport.php" + "?filename=" + data);
                iframe.setAttribute("style", "display: none");
                document.body.appendChild(iframe);
            }else{
                alert("No have data to export!");
            }
        }
    });
};

Export.prototype.exportAccountReport = function(){
    var self = this;
    var dateF = $('#f_date_account_report').data("w2field").el.value;
    var dateT = $('#t_date_account_report').data("w2field").el.value;
    
    var df = new Date(self.standardDate(dateF, 1));
    var dt = new Date(self.standardDate(dateT, 1));
    var daydiff = Math.floor(dt - df) / 86400000;
    var mustWait = 0;
    if(daydiff > 7){
        mustWait = 1;
        alert("Nội dung báo cáo có thể rất lớn. Vui lòng đợi ít nhất 15' trước khi quay lại và bấm vào nút xem báo cáo tháng để xem!");
    }
    var fdate = self.standardDate(dateF, 1);
    var tdate = self.standardDate(dateT, 1);
    if(mustWait === 1){
        console.log("run it!");
        $.get(self.service + "/controller/GenerateAccountMonthlyReport.php?now=1&fdate="+fdate+"&tdate="+tdate, function(){

        });
        return;
    }
    var request = {};
    request.f_status_date = self.standardDate(dateF, 1);
    request.t_status_date = self.standardDate(dateT, 1);
    $.ajax({
        type: "POST",
        url: self.service + "/controller/GetAccountReport.php",
        data: JSON.stringify(request),
        dataType: "json",
        async: true,
        timeout: 600000,
        beforeSend: function(){
            $(".waiting_modal").show("slow");
        },
        error: function(jqXHR, textStatus, errorThrown){
            $(".waiting_modal").hide("slow");
        },
        success: function(data, textStatus, jqXHR){
            //Download file
            $(".waiting_modal").hide("slow");
            if(data === "none"){
                alert("Không có dữ liệu tương ứng để báo cáo");
                return;
            }
            $(".waiting_modal").hide("slow");
            var iframe = document.createElement("iframe");
            iframe.setAttribute("src", self.service + "/controller/GetAccountReport.php" + "?filename=" + data);
            iframe.setAttribute("style", "display: none");
            document.body.appendChild(iframe);
        }
    });
};

/**
 * 
 * @param {type} date
 * @param {type} format 1 là cho định dạng y-m-d, 0 là cho định dạng y/m/d
 * @returns {String}
 */
Export.prototype.standardDate = function(date, format){
    var d = date.split("/");
    if(format === 1){
        return d[2] + "-" + d[1] + "-" + d[0];
    }
    if(format === 0){
        return d[2] + "/" + d[1] + "/" + d[0];
    }
};


Export.prototype.AccountMonthlyReport = function(){
    var self = this;
    this.destroyW2uiObject(w2ui.layout_monthly_report);
    this.destroyW2uiObject(w2ui.monthly_report_table);
    $().w2layout({
        name: "layout_monthly_report",
        padding: 0,
        panels: [
            {
                type:"main",
                size: "400",
                content: "<div id='layout_main_monthly_report'><div id='main_monthly_report'>"
                         +"</div></div>"
            },
            {
                type:"top",
                size: "70",
                content: "<div id='layout_top_monthly_report'><div class='row' style='margin-top:10px;'>"
                        + "<div class='col-md-3' style='line-height:29px;'>Năm cần tra cứu:</div>"
                        + "<div class='col-md-4'><input type='us-date5' id='date_account_report' /></div>"
                        + "<div class='col-md-2'><input id='account_monthly_report_search_btn' type='button' value='Tìm kiếm'/></div>"
                        +"</div></div>"
            }
        ]
    });
    w2popup.close();
    w2popup.open({
        title: "Account Reporting Management",
        body: "<div id='popup_monthly_report'></div>",
        modal: false,
        width: 770,
        height: 650,
        showClose: true,
        onOpen:function(event){
            event.onComplete = function(event){
                $("#w2ui-popup #popup_monthly_report").w2render("layout_monthly_report");
                $("#main_monthly_report").w2grid({
                    show:{
                        header: true,
                        footer: true,
                        lineNumbers: true
                    },
                    name: "monthly_report_table",
                    columns:[
                        { field: 'reportYear', caption: 'Năm', size: '5%' },
                        { field: 'reportMonth', caption: 'Tháng', size: '5%' },
                        { field: 'reportName', caption: 'Tên tập tin', size: '15%' },
                        { field: "reportAction", caption: "Tải về", size: "10%",attr: "align=center" }
                    ],
                    onExpand: function(event){
                        if (w2ui.hasOwnProperty('subgrid-' + event.recid)){
                            w2ui['subgrid-' + event.recid].destroy();
                        }
                        $('#'+ event.box_id)
                                .css({ margin: '0px', padding: '0px', width: '100%' })
                                .animate({ height: '150px' }, 100);
                        var record = w2ui.monthly_report_table.get(event.recid);
                        var year = record.reportYear;
                        var month = record.reportMonth;
                        var request = {};
                        request.year = year;
                        request.month = month;
                        setTimeout(function(){
                            $('#'+ event.box_id).w2grid({
                                name: 'subgrid-' + event.recid, 
                                show: { columnHeaders: false },
                                fixedBody: true,
                                columns: [                
                                    { field: 'reportYear', caption: 'Năm', size: '10%' },
                                    { field: 'reportMonth', caption: 'Tháng', size: '10%' },
                                    { field: 'reportTime', caption: 'Thời gian', size: '15%' },
                                    { field: 'reportFile', caption: 'Tên file', size: '10%' },
                                    { field: 'reportAction', caption: "Tải về", size: "10%", attr: "align=center"}
                                ]
                            });
                            $.ajax({
                                type: "POST",
                                url: self.service + "/controller/GetAccountMonthlyReport.php",
                                data: JSON.stringify(request),
                                dataType: "json",
                                async: true,
                                beforeSend: function(){
                                },
                                error: function(jqXHR, textStatus, errorThrown){
                                },
                                success: function(data, textStatus, jqXHR){
                                    var records = data.res;
                                    var rs = new Array();
                                    for(var i = 0; i< records.length; i++){
                                        var record = records[i];
                                        rs.push({
                                            recid: i + 1,
                                            reportYear: record.year_report,
                                            reportMonth: record.month_report,
                                            reportTime: record.time_now,
                                            reportFile: record.file_name,
                                            reportAction: "<a class='btn btn-primary download-file' arial-label='download' id='" +record.file_name + "'><i class='fa fa-download' arial-hidden='true'></i></a>",
                                            style: "background-color: #C2F5B4;"
                                        });
                                    }
                                    console.log('subgrid-' + event.recid);
                                    w2ui['subgrid-' + event.recid].add(rs);
                                    w2ui['subgrid-' + event.recid].refresh();
                                    w2ui['subgrid-' + event.recid].resize();
                                    $(".download-file").off();
                                    $(".download-file").click(function(event){
                                        var filename = $(this).attr("id");
                                        var iframe = document.createElement("iframe");
                                        iframe.setAttribute("src", self.service + "/controller/GetAccountMonthlyReport.php" + "?filename=" + filename);
                                        iframe.setAttribute("style", "display: none");
                                        document.body.appendChild(iframe);
                                    });
                                }
                            });
                        }, 300);
                        
                        
                    }
                });
                $('input[type=us-date5]').w2field('date');
                $("#account_monthly_report_search_btn").click(function(){
                    var date = $('#date_account_report').data("w2field").el.value;
                    var year = date.split("/")[2];
                    var request = {};
                    request.year = year;
                    request.month = "";
                    $.ajax({
                        type: "POST",
                        url: self.service + "/controller/GetAccountMonthlyReport.php",
                        data: JSON.stringify(request),
                        dataType: "json",
                        async: true,
                        beforeSend: function(){
                            $(".waiting_modal").show("slow");
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            $(".waiting_modal").hide("slow");
                        },
                        success: function(data, textStatus, jqXHR){
                            //Download file
                            $(".waiting_modal").hide("slow");
                            var records = data.res;
                            var rs = new Array();
                            for(var i = 0; i< records.length; i++){
                                var record = records[i];
                                rs.push({
                                    recid: i + 1,
                                    reportYear: record.year_report,
                                    reportMonth: record.month_report,
                                    reportName: record.file_name,
                                    reportAction: "<a class='btn btn-primary download-file' arial-label='download' id='" +record.file_name + "'><i class='fa fa-download' arial-hidden='true'></i></a>"
                                });
                            }
                            w2ui.monthly_report_table.clear();
                            w2ui.monthly_report_table.add(rs);
                            w2ui.monthly_report_table.refresh();
                            $(".download-file").off();
                            $(".download-file").click(function(event){
                                var filename = $(this).attr("id");
                                
                                var iframe = document.createElement("iframe");
                    //            console.log(self.service + "/controller/GetAccountReport.php" + "?filename=" + data);
                                iframe.setAttribute("src", self.service + "/controller/GetAccountMonthlyReport.php" + "?filename=" + filename);
                                iframe.setAttribute("style", "display: none");
                                document.body.appendChild(iframe);
                            });
                        }
                    });
                });
            };
        }
    });
};

Export.prototype.destroyW2uiObject = function(ow2ui){
    if(typeof ow2ui !== "undefined"){
        ow2ui.destroy();
    }
};
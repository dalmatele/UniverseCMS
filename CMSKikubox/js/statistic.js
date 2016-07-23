/* 
 * Quản lý chức năng thống kê
 */

function Statistic(container){
    this.container = container;
    this.month = (new Date()).getMonth() + 1;
    this.year  = (new Date()).getFullYear();
    this.service = getService();
};

Statistic.prototype.constructor = Statistic;

Statistic.prototype.service; //<--Lấy thông tin link service của hệ thống
Statistic.prototype.container;
Statistic.prototype.month;
Statistic.prototype.year;
Statistic.prototype.importDate;
Statistic.prototype.plot;

/**
 * Render các thành phần cơ bản của trang thống kê
 */
Statistic.prototype.renderPage = function(){
    var html = new Array();
    html.push("<fieldset>");
        html.push("<div style='overflow:hidden;'>");
            html.push("<div style='overflow:hidden;width:300px;float:left;'>");
                html.push("<div style='float: left;'>Thời gian bắt đầu:</div>");
                html.push("<div style='float:right;'><input type='us-date' style='width:150px;' id='txt_DateF'/></div>");
            html.push("</div>");
            html.push("<div style='overflow:hidden;width:300px;float:left;margin-left:20px;'>");
                html.push("<div style='float: left;'>Thời gian kết thúc:</div>");
                html.push("<div style='float:right;'><input type='us-date' style='width:150px;' id='txt_DateT'/></div>");
            html.push("</div>");
        html.push("</div>");
        html.push("<div style='overflow:hidden; margin-top: 20px;'>");
            html.push("<div style='overflow:hidden;width:300px;float:left;'>");
                html.push("<div style='float: left;'>Chọn loại biểu đồ:</div>");
                html.push("<div style='float:right;'><input type='list'' style='width:150px;' id='lst_graphs'/></div>");
            html.push("</div>");
        html.push("</div>");
        html.push("<div style='overflow:hidden; margin-top: 20px;'>");
            html.push("<div style='float:left;margin-left: 150px;'><a href='#' class='button blue' id='seeGraph'>Xem</a></div>");
        html.push("</div>");
    html.push("</fieldset>");
    html.push("<div id='graphContent' style='margin-top: 20px;'>");
    html.push("</div>");
    $("#" + this.container).append(html.join(""));
    this.bindControl();
};

/**
 * Thêm các sự kiện và control cho các thành phần trên trang
 * @returns {undefined}
 */
Statistic.prototype.bindControl = function(){
    var self = this;
    $('#txt_DateF').w2field('date', { format: 'dd/mm/yyyy'});
    $('#txt_DateT').w2field('date', { format: 'dd/mm/yyyy'});
    var graphlst = [
        {
            text: 'Biểu đồ cột',
            id: "gcol"
        }, 
        {
            text: 'Biều đồ hình tròn',
            id: "gcirl"
        }, 
        
        {
            text: 'Biểu đồ đường',
            id: "gline"
        }];
    $('#lst_graphs').w2field('list', { items: graphlst });
    $("#seeGraph").click(function(){
        var graph = $("#lst_graphs").data("selected").id;
        if(graph === "gcol"){
            self.drawColumnGraph();
        }else if(graph === "gcirl"){
            self.drawCircleGraph();
        }else{
            self.drawLineGraph();
        }
    });
};

Statistic.prototype.drawColumnGraph = function(){
    var s = new Array();
    var t = new Array();
    for(var i = 0; i < 10; i++){
        s.push((Math.random() * 100));
        t.push(i);
    }
    if (typeof this.plot !== "undefined") {
        this.plot.destroy();//huy do thi truoc do roi moi ve ban moi
    }
    this.plot = $.jqplot("graphContent", [s], {
        animate: !$.jqplot.use_excanvas,
        seriesDefaults: {
            renderer: $.jqplot.BarRenderer,
            pointLabels: { show: true }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks: t
            }
        },
        highlighter: { show: false }
    });
};

Statistic.prototype.drawCircleGraph = function(){
    var self = this;
    if (typeof this.plot !== "undefined") {
        this.plot.destroy();//huy do thi truoc do roi moi ve ban moi
    }
    
    var dateF = $('#txt_DateF').data("w2field").el.value;
    var dateT = $('#txt_DateT').data("w2field").el.value;
    var d = new Date();
    if(dateF === ""){
        dateF = getNowTime();
    }
    if(dateT === ""){
        dateT = getNowTime();
    }
    var request = {};
    request.fDate = standardDate(dateF,1);
    request.tDate = standardDate(dateT,1);
    $.ajax({
        type: "POST",
        url: self.service + baseLink + "controller/Admin/getPieChart.php",
        data: JSON.stringify(request),
        dataType: "json",
        success: function(data, textStatus, jqXHR){
            var records = data.result;
            var result = new Array();
            result.push(["Tiền mua", parseInt(records[0]) ]);
            result.push(["Tiền bán", parseInt(records[1])]);
            self.plot = $.jqplot('graphContent', [result], {
                gridPadding: {top:0, bottom:38, left:0, right:0},
                seriesDefaults:{
                    renderer:$.jqplot.PieRenderer, 
                    trendline:{ show:false }, 
                    rendererOptions: { padding: 8, showDataLabels: true }
                },
                legend:{
                    show:true, 
                    placement: 'outside', 
                    rendererOptions: {
                        numberRows: 1
                    }, 
                    location:'s',
                    marginTop: '25px'
                }       
            });
        }
    });
};

Statistic.prototype.drawLineGraph = function(){
    var line = new Array();
    for(var i = 0; i < 10; i++){
        line.push((Math.random() * 100));
    }
    if (typeof this.plot !== "undefined") {
        this.plot.destroy();//huy do thi truoc do roi moi ve ban moi
    }
    this.plot = $.jqplot("graphContent", [line],
    {
        series: [{ showMarker: false }],
        axes: {
            label: "Biểu đồ"
        },
        yaxis: {
            label: "ĐV"
        }
    });
};

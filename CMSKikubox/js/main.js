/* 
 * @created by: ducla@outlook.com
 * @created on: 10/09/2015
 * @Copyright Kikubox
 */

var baseLink = "/CMSKikubox/"; //on server is "/"

function initPage(account){
    
    // Thiet lap thong so cho layout
    var w = $(window).width();
    var h = $(window).height();
    $("#layout").css("width", w - 5);
    $("#layout").css("height",h);

    var pstyle = 'border: 1px solid #dfdfdf; padding: 5px;';
    $('#layout').w2layout({
        name: 'layout',
        panels: [
            { 
                type: "top", size: 65, style: pstyle,
                content: "<div style='overflow:hidden;'><div id='top_banner'></div><div class='accountBtn'>Xin chào, " + account + "<img id='account-icon' src='../../css/images/Actions-arrow-down-icon.png'></div></div>" 
            },
            { 
                type: "left", 
                size: 200, 
                style: pstyle, 
                content: "<div id='pageSidebar'></div>",
                title: "Quản trị"
            },
            { 
                type: "main",
                style: pstyle,
                content: "<div id='pageContent'></div>" ,
                tabs: {
                    active: "mainTab",
                    tabs: [
                        {
                            id: "mainTab",
                            caption: "Quản lý hàng hóa"
                        }
                    ]
                }
            },
            {
                type: "bottom",
                style: pstyle,
                content: "<div style='text-align: center;margin-top:10px;font-weight:bold;'>Bản quyền thuộc &copy;Kikubox.com 2015</div>",
                size: 50
            }
        ]
    });
    $("#pageSidebar").w2sidebar({
        name: "pageSidebar",
        nodes: [
            {
                id: "productManage",
                text: "Quản lý hàng hóa",
                img: "ico_manage_product"
            },
            {
                id: "customerManage",
                text: "Quản lý khách hàng",
                img: "ico_manage_customer"
            },
            {
                id: "statisticManage",
                text: "Thống kê",
                img: "ico_statistic",
                nodes:[
                    {
                        id: "userStatistic",
                        text: "Người dùng"
                    },
                    {
                        id: "moneyStatistic",
                        text: "Tài chính"
                    },
                    {
                        id: "productStatistic",
                        text: "Sản phẩm"
                    }
                ]
            }
        ],
        onClick: function(event){
            var tab = w2ui['layout_main_tabs'].tabs[0];
            if(event.target === "productManage"){
                tab.caption = "Quản lý sản phẩm";
                $("#pageContent").empty();
                var pm = new ProductManage("pageContent");
                pm.renderPage();
            }else if(event.target === "customerManage"){
                tab.caption = "Quản lý khách hàng";
                $("#pageContent").empty();
                var cm = new CustomerManage("pageContent");
                cm.renderPage();
            }else if(event.target === "userStatistic"){
                tab.caption = "Thống kê người dùng";
                $("#pageContent").empty();
                var s = new Statistic("pageContent");
                s.renderPage();
            }else if(event.target === "moneyStatistic"){
                tab.caption = "Thống kê tài chính";
                $("#pageContent").empty();
                var s = new Statistic("pageContent");
                s.renderPage();
            }else if(event.target === "productStatistic"){
                tab.caption = "Thống kê sản phẩm";
                $("#pageContent").empty();
                var s = new Statistic("pageContent");
                s.renderPage();
            }
            w2ui['layout_main_tabs'].refresh();
        }
    });
    var pm = new ProductManage("pageContent");
    pm.renderPage();
    bindEvent();
};

/**
 * Thêm sự kiên cho các thành phần trên trang
 * @returns {undefined}
 */
function bindEvent(){
    $.contextMenu({
        selector: "#account-icon",
        callback: function(key, options){
            if(key === "setting"){
                window.location.href = "../../user_settings.php";
            }else if(key === "logout"){
                window.location.href = "../../logout.php";
            }
        },
        trigger: 'left',
        items:{
            "setting":{
                name: "Cài đặt tài khoản",
                icon: "edit"
            },
            "logout":{
                name: "Thoát",
                icon: "quit"
            }
        }
    });
};

/*
 * Huy mot doi tuong w2ui neu no da ton tai
 */
function destroyW2uiObject(ow2ui){
    if(typeof ow2ui !== "undefined"){
        ow2ui.destroy();
    }
};

/**
 * Tim link cua service
 * @returns {String}
 */
function getService(){
    var protocol = location.protocol;
    var hostname = location.hostname;
    var port = location.port ? ":" + location.port : "";
    return protocol + "//" + hostname + port;
};

/**
 * 
 * @param {type} date
 * @param {type} format 1 là cho định dạng y-m-d, 0 là cho định dạng y/m/d
 * @returns {String}
 */
function standardDate(date, format){
    var d = date.split("/");
    if(format === 1){
        return d[2] + "-" + d[1] + "-" + d[0];
    }
    if(format === 0){
        return d[2] + "/" + d[1] + "/" + d[0];
    }
};

/**
 * Chuyển sang định dạng để đổ dữ liệu vào form
 * @param {type} date
 * @returns {undefined}
 */
function convertDateToFormInput(date){
    var d = date.split("-");
    return d[2] + "/" + d[1] + "/" + d[0];
};

function getNowTime(){
    var d = new Date();
    var m = d.getMonth()  + 1;
    return (d.getDate() < 10 ? "0" + d.getDate() : d.getDate()) + "/" 
            + (m < 10 ? "0" + m : m)  + "/" 
            + d.getFullYear();
};


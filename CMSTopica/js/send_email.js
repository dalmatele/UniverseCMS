function SendEmail(){
    this.service = "/CMSTopica";
//    this.service = getService();
}

SendEmail.prototype.constructor = SendEmail;
SendEmail.prototype.service; //<--Lấy thông tin link service của hệ thống

SendEmail.prototype.init = function(){
    $("#address_file_upload").attr("data-url", this.service + "/controller/uploadAddressFile.php");
    $('input[type=us-date]').w2field('date');
    $('input[type=us-time]').w2field('time',  { format: 'h12' });
    CKEDITOR.replace('email-content');
    var self = this;
    //Get list of emails
    var request = {};
    request.send_from = "";
    request.pageSize = 50;
    request.pageIndex = 0;
    sendRequest("POST", self.service + "/controller/GetEmailList.php", request, "json", self.generateEmailList)
    
};

SendEmail.prototype.generateEmailList = function(records, textStatus, jqXHR){
    var emailList = new Array();
    records = records[0];
    for(var i = 0; i < records.length; i++){
        emailList.push({
            text: records[i].send_from,
            id: records[i].id
        });
    }
    $('#from_address').w2field('list', { 
        items: emailList
    });
    $("#address_file_upload").fileupload({
        formData:{
            mail_id: $("#from_address").data("selected").id
        },
        dataType: "json",
        add: function(e, data){
            $("#send-mail").off('click').on('click', function () {
                data.submit();
            });
        },
        done: function(e, data){
            console.log($("#from_address").data("selected").id);
            $(".waiting_modal").hide("slow");
            var result = data._response.result.res.desc;
            $("#upload_address_result").empty();
            $("#upload_address_result").append(result);
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        },
        beforeSend: function(){
            $(".waiting_modal").show("slow");
        },
        error: function(){
            $(".waiting_modal").hide("slow");
            console.log($("#from_address").data("selected").id);
        }
    });
};




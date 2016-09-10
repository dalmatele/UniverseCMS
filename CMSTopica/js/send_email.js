function SendEmail(){
    this.service = "/CMSTopica";
//    this.service = getService();
}

SendEmail.prototype.constructor = SendEmail;
SendEmail.prototype.service; //<--Lấy thông tin link service của hệ thống

SendEmail.prototype.init = function(){
    $("#address_file_upload").attr("data-url", this.service + "/controller/uploadAddressFile.php");
    $('input[type=us-date]').w2field('date', {format: 'dd/mm/yyyy'});
    $('input[type=us-time]').w2field('time',  { format: 'h12' });
    CKEDITOR.replace('email-content');
    var self = this;
    //Get list of emails
    var request = {};
    request.send_from = "";
    request.pageSize = 50;
    request.pageIndex = 0;
    sendRequest("POST", self.service + "/controller/GetEmailList.php", request, "json", self.generateEmailList);
};

SendEmail.prototype.generateEmailList = function(records, textStatus, jqXHR){
    var self = this;
    var emailList = new Array();
    records = records[0];
    for(var i = 0; i < records.length; i++){
        emailList.push({
            text: records[i].send_from +  " (" + records[i].fullname + ")",
            id: records[i].id
        });
    }
    $('#from_address').w2field('list', { 
        items: emailList
    });
    $("#address_file_upload").fileupload({
        dataType: "json",
        add: function(e, data){
            $("#send-mail").off('click').on('click', function () {
                
//                console.log(CKEDITOR.instances["email-content"].getData());
//                http://stackoverflow.com/questions/21281770/jqueryfiledata-upload-using-blueimp-file-upload-plugin-on-form-submit
                //we get datetime for mail
                var time = $('#time-to-send').data("w2field").el.value;
                var date = $('#date-to-send').data("w2field").el.value;
                var d = date.split("/");
                date = d[2] + "-" + d[1] + "-" + d[0];                
                data.formData = {
                    mail_id: $("#from_address").data("selected").id,
                    mail_data: unicodeToAsscii(CKEDITOR.instances["email-content"].getData()),
                    mail_subject: unicodeToAsscii($("#email_subject").val()),
                    send_at: date + " " + time
                };
                data.submit();
            });
        },
        replaceFileInput:false,
        done: function(e, data){
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
        }
    });
};

/**
 * 
 * @param {type} date
 * @param {type} format 1 là cho định dạng y-m-d, 0 là cho định dạng y/m/d
 * @returns {String}
 */
SendEmail.prototype.standardDate = function(date, format){
    var d = date.split("/");
    if(format === 1){
        return d[2] + "-" + d[1] + "-" + d[0];
    }
    if(format === 0){
        return d[2] + "/" + d[1] + "/" + d[0];
    }
};
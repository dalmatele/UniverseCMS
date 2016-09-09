

//Global variable
var baseLink = "/CMSTopica/"; //on server is "/"
/**
 * init function when page is loaded
 * @returns {undefined}
 */
function init(){
    var service = getService();
    $("#file_upload").attr("data-url", service + baseLink + "controller/uploadFile.php");
//    $("#email_file_upload").attr("data-url", service + baseLink + "controller/uploadEmailFile.php");
    bindActions();
}

function bindActions(){
    
    $("#file_upload").fileupload({
        dataType: "json",
        done: function(e, data){
            $(".waiting_modal").hide("slow");
            var result = data._response.result.res.desc;
            $("#upload_result").empty();
            $("#upload_result").append(result);
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
    
}

/**
 * Tim link cua service
 * @returns {String}
 */
function getService(){
    var protocol = location.protocol;
    var hostname = location.hostname;
    var port = location.port ? ":" + location.port : "";
    return protocol + "//" + hostname + port;
//    return "/";
};

function sendRequest(type, url, request, dataType, successFunction){
    $.ajax({
        type: type,
        url: url,
        data: JSON.stringify(request),
        dataType: dataType,
        beforeSend: function(){
            $(".waiting_modal").show("slow");
        },
        error: function(){
            $(".waiting_modal").hide("slow");
        },
        success: function(data, textStatus, jqXHR){
             var records = data.res;
            successFunction(records, textStatus, jqXHR);
        }
    });
};

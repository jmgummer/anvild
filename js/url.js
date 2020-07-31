var rfapi = "http://beta.reelforge.com/anvileleczip.php";
$(function(){
    var url = window.location.pathname, 
    urlRegExp = new RegExp(url.replace(/\/$/,'') + "$");
    $('.nav a').each(function(){
        if(urlRegExp.test(this.href.replace(/\/$/,''))){
            $(this).parent('li').addClass('active');
        }
    });
});

// function opendiv(id){
// 	var div
// }

function opendiv(elementId) {
	var ele = document.getElementById(elementId);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
  	}
	else {
		ele.style.display = "block";
	}
}

function GenerateElectronicZip(filename){
    var json = document.getElementById('filejson').value;
    $('#filefuncs').html('Please wait, Generating File');
    jQuery.ajax({
        url:rfapi,
        type:'POST',
        data:{
            'json':json,'filename':filename
        },
        cache:false,
        success:function(data){
            $('#filefuncs').html('<a href="'+rfapi+'?download='+filename+'.zip" target="blank" >Download</a>');
        },
        error:function(){
            $('#filefuncs').html('Download Failed, Please Try Again Later');
        }
    });
}
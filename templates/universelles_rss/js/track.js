function setTrack(uid,page_id,kuid) {
	
	    $.ajax(
    {
        url : "/ACP/acp_track_data.php",
        type: "POST",
        data : "uid=" + uid + "&page_id=" + page_id + "&kuid=" + kuid,
        success:function(data, textStatus, jqXHR)
        {
				
				$("#acp_message").html(data);
	 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
           alert(data + ' ' + errorThrown);
        }
    });
	return true;
	

}
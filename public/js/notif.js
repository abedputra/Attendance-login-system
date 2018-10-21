$(function() {
       // this is ajax for get info, is it any update for the system 
       $.ajax({
       url: 'http://abedputra.com/attendance-system/checkUpdate.php',
       dataType: 'json',
       success: function(data) {
          // check is there any update
          $.each(data, function(key, val) {
            if(data[0]['status_code'] == 1 && data[0]['status'] == 'yes'){
              $('.info-update').show();
            }else{
              $('.info-update').hide();
            }
          });
       },
      statusCode: {
         404: function() {
           alert('There was a problem with the server.  Try again soon!');
         }
       }
    });
});
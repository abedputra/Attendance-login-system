    </div><!--row-->   
    
    <footer>
        <div class="col-md-12" style="text-align:center;">
            <hr>
            Copyright (c) - 2017 | <a href="http://abedputra.com">abedputra.com</a>
        </div>
    </footer>
    </div><!-- /container -->  

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Attendance Login System</h4>
          </div>
          <div class="modal-body">
            <h2>Version</h2>
            <p>V2.0a</p>
            <h2>About</h2>
            <p>Attendance login system is based on the <a href="https://github.com/bcit-ci/CodeIgniter">codeigniter</a>.
            <p>If you have question, please email me : <a href="mailto:abedputra@gmail.com">abedputra@gmail.com</a><br>
            Visit: <a href="http://abedputra.com" rel="nofollow">http://abedputra.com</a></p>
            <h2>License</h2>
            <p>The MIT License (MIT).</p>
            <p>Copyright (c) 2017, Abed Putra.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
        
    
    <!-- /Load Js -->
    <script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url().'public/js/main.js' ?>"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <script>
        $('#datefrom').datepicker({
    		format: 'yyyy/mm/dd',
    		todayHighlight: true,
    		autoclose: true,
	    });
        $('#dateto').datepicker({
    		format: 'yyyy/mm/dd',
    		todayHighlight: true,
    		autoclose: true,
	    });
	    
	    $(function(){
          new Clipboard('.copy-text');
        });
        
        $('#checkbox-pass').change(function() {
            if($(this).is(":checked")) {
                $('#hide-pass').css('display', 'block');
                $('#check-change-pass').val('yes');
            }else{
                $('#hide-pass').css('display', 'none');
                $('#check-change-pass').val('no');
            }   
        });

        $('input[type=radio][name=type]').change(function() {
            if (this.value == 0) {
                $('.with-user').hide();
                $('.without-user').show();
            }
            else if (this.value == 1) {
                $('.without-user').hide();
                $('.with-user').show();
            }
        });
    </script>
    </body>
</html>
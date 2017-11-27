    </div><!--row-->   
    
    <footer>
        <div class="col-md-12" style="text-align:center;">
            <hr>
            Copyright - 2017 | <a href="http://abedputra.com">abedputra.com</a>
        </div>
    </footer>
    </div><!-- /container -->  
    
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
    </script>
    </body>
</html>

        <script type="text/javascript">
			
		//     $( document ).ready(function() {
		// 		alert('tes');
		// 		$('.datatable').dataTable();
		// 	} );
			
		// 	$(document).ready(function() {   
		// 	  alert("Hey");
		// 	});
			
			var hasLoaded;
			$(document).ready(
			        function()
			        {
			                init();
			                hasLoaded = true;
			        }
			);
			$(window).load(
			        function()
			        {
			                if(typeof hasLoaded == "undefined")
			                {
			                        init();
			                }
			        }
			);
			
			function init()
			{
				
				loadTabelMK();
			}
		
			function loadTabelMK(){
				$('#progress-table-MK').show();
				var theUrl = 'course/ajaxLoadTabelMK/';
				
				var theParams = "p=test";
				
				$.ajax({
					type 	: "POST",
					timeout : this.TheTimeout,
					url		: theUrl,
					data 	: theParams,
					success : function(rv) {				
						try {				
							$('#div-table-MK').html(rv);
							$('#table-MK').dataTable();
							$('#progress-table-MK').hide();
						} catch (err) {	
							$('#progress-table-MK').hide();
							alert(err.message);				
						}									   				
					},
					error : function(x, t, m) {
						$('#progress-table-MK').hide();
						if (t === "timeout") {
							alert(this.TheMsgErr);
						} else {
							alert(t);
						}
					}
				});
			}
			
			</script>
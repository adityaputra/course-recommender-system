
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
// 				$('#progress-table-MK').show();
				loadTabel();
			}
		
			function loadTabel(){
				$('#progress-table').show();
				var theUrl = 'student/ajaxLoadTabel/';
				
				var theParams = "p=test";
				
				$.ajax({
					type 	: "POST",
					timeout : this.TheTimeout,
					url		: theUrl,
					data 	: theParams,
					success : function(rv) {				
						try {				
							$('#div-table').html(rv);
							$('#table').dataTable();
							$('#progress-table').hide();
						} catch (err) {	
							$('#progress-table').hide();
							alert(err.message);				
						}									   				
					},
					error : function(x, t, m) {
						$('#progress-table').hide();
						if (t === "timeout") {
							alert(this.TheMsgErr);
						} else {
							alert(t);
						}
					}
				});
			}
			
			</script>
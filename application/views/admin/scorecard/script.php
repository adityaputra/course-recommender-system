
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
				$('#progress-table').hide();
				$('#progress-search-result').hide();
// 				loadTabel();

				$("form#form-cari-primary").submit(function(event) {
			        event.preventDefault();
// 			        alert('ajax');
			        doSearch();
			        
			    });
			}

			function getDropdownSemesterKHS(){
				var angkatan = $('#res-ANGKATAN').html();
				var curYear = <?php echo date('Y');?>;
				var semester = ((curYear - angkatan) + 1) * 2;
				var html = 'KHS Semester: ';
				html += '<select name="in-krs-semester" id="in-krs-semester">';
				for(var i = 1; i <= semester; i++){
					html += '<option value="'+i+'">'+i+'</option>';
				}
				html += '</select>&nbsp;';
				
				html += '<button type="submit" class="btn btn-default" id="btn-cari-secondary" onclick="loadTabel()">Tampilkan</button>';
				$('#div-search-result').append(html);
				
			}

			function doSearch(){
				$('#progress-search-result').show();
				$('#div-search-result').html('');
				$('#div-table').html('');
				var theUrl = 'registration/search/';
				var theParams = "in-nim="+$('#in-nim').val();
				
				$.ajax({
					type 	: "POST",
					timeout : this.TheTimeout,
					url		: theUrl,
					data 	: theParams,
					success : function(rv) {				
						try {				
							$('#div-search-result').html(rv);
							$('#progress-search-result').hide();
							getDropdownSemesterKHS();
							
						} catch (err) {	
							$('#progress-search-result').hide();
							alert(err.message);				
						}									   				
					},
					error : function(x, t, m) {
						$('#progress-search-result').hide();
						if (t === "timeout") {
							alert(this.TheMsgErr);
						} else {
							alert(t);
						}
					}
				});
			}
		
			function loadTabel(){
				$('#progress-table').show();
				var theUrl = 'scorecard/ajaxLoadTabel/';
				
				var theParams = "in-nim="+$('#in-nim').val()+"&in-semester="+$('#in-krs-semester').val();
				
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
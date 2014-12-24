
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

				doSearch();
			    
			}

			function getDropdownSemesterKRS(){
				var angkatan = $('#res-ANGKATAN').html();
				var curYear = <?php echo date('Y');?>;
				var semester = ((curYear - angkatan) + 1) * 2;
				var html = 'KRS: ';
// 				html += '<select name="in-krs-semester" id="in-semester-krs">';
// 				for(var i = 1; i <= semester; i++){
// 					html += '<option value="'+i+'">'+i+'</option>';
// 				}
// 				html += '</select>&nbsp;';
				
				html += '<select name="in-krs-tahun" id="in-krs-tahun">';
				for(var i = angkatan; i <= curYear; i++){
					html += '<option value="'+i+'">'+i+'</option>';
				}
				html += '</select>&nbsp;';

				html += '<select name="in-krs-ganjil" id="in-krs-ganjil">';
					html += '<option value="'+1+'">Ganjil</option>';
					html += '<option value="'+0+'">Genap</option>';
				html += '</select>&nbsp;';

				html += '<select name="in-krs-pendek" id="in-krs-pendek">';
					html += '<option value="'+0+'">Reguler</option>';
					html += '<option value="'+1+'">Pendek</option>';
				html += '</select>&nbsp;';

				html += '<button type="submit" class="btn btn-default" id="btn-cari-secondary" onclick="loadTabel()">Tampilkan</button>';
				$('#div-search-result').append(html);
				
			}

			function doSearch(){
				$('#progress-search-result').show();
				$('#div-search-result').html('');
				$('#div-table').html('');
				var theUrl = 'registration/search/';
				var theParams = "";
				
				$.ajax({
					type 	: "POST",
					timeout : this.TheTimeout,
					url		: theUrl,
					data 	: theParams,
					success : function(rv) {				
						try {				
							$('#div-search-result').html(rv);
							$('#progress-search-result').hide();
							getDropdownSemesterKRS();
							
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
				var theUrl = 'registration/ajaxLoadTabel/';
				
				var theParams = "in-nim="+$('#in-nim').val()+"&in-tahun="+$('#in-krs-tahun').val()+"&in-ganjil="+$('#in-krs-ganjil').val()+"&in-pendek="+$('#in-krs-pendek').val();
				
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
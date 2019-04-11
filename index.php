<?
session_start();

$_SESSION["proyects"]=[];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include "php/base.php";

?>
<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
	 crossorigin="anonymous">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="style.css">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	 crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$( "#clear" ).hide();
		});
		
		var TotalPoints = 0;

		function Set_Sliders()
		{
			$(".sliderproyect").slider({
						create: function () {
							var item = $(this).attr('id');

							item = item.replace("slider_", "");
							// alert($("#extras_" + item).val());
							$(this).slider('value', $("#extras_" + item).val());
							$(this).slider("option", "max", parseInt($(this).attr("max_value")));
							$(this).slider("option", "min", parseInt($(this).attr("min_value")));
							$(this).slider("option", "step", parseInt($(this).attr("interval")));
							if ($(this).attr("novalues")==1)
							{
								$(this).find('#custom-handle').text("");
							}else{
								$(this).find('#custom-handle').text($(this).slider("value"));
							}
						},
						slide: function (event, ui) {
							//novalues
							console.log($(this).attr("novalues"));
							if ($(this).attr("novalues")==1)
							{
								$(this).find('#custom-handle').text("");
							}else{
								$(this).find('#custom-handle').text(ui.value);
							}
							

						},
						stop: function (event, ui) {
							var item = $(this).attr('id');
							item = item.replace("slider_", "");
							$.ajax({
								data: {
									cmd: "SetExtras",
									index: item,
									extras: ui.value
								},
								url: 'php/points.php'
							});

							//alert( ui.value);
							//var extra=$("#extrap_" + item).val();
							//alert(extra);
							//var inter=$("#interval_" + item).val();
							//alert(inter);
							//var cant = Math.ceil(ui.value / inter);
							//alert(cant);
							//var totalex =  cant * extra;
							//alert (totalex);
							Calc_Points(item);
						}

					});
		}
		
		function Calc_Points(Id) {
			var radioValue = $("input[name='type_" + Id + "']:checked").val();
			var SubTotal = radioValue;
			var max = $("#items").val();
			TotalPoints = 0;
			var extrapoints = 0;
			var i;
			for (i = 0; i < max; i++) {
				radioValue = $("input[name='type_" + i + "']:checked").val();
				extrapoints = 0;
				if ($("#slider_" + i).length) {
					var extra = $("#extrap_" + i).val();
					//alert(extra);
					var inter = $("#interval_" + i).val();
					var include = $("#include_" + i).val();
					//alert (include);
					//alert(inter);
					var cant = Math.ceil(($("#slider_" + i).slider("value") - include) / inter);
					//alert(cant);
					extrapoints = cant * extra;
				} else {
					extrapoints = 0;
				}
				if (extrapoints < 0) {
					extrapoints = 0;
				}				
				if (!isNaN(radioValue) && !isNaN(extrapoints)) {
					//console.log("Cacl Total " + "#total_" + Id);
					TotalPoints = parseFloat(TotalPoints) + parseFloat(radioValue) + parseFloat(extrapoints);
					TotalProyect= parseFloat(radioValue) + parseFloat(extrapoints);
					//console.log(TotalProyect);					
					$("#total_" + i).html(TotalProyect + " pts");
				}
			}

			//TotalPoints = parseFloat(TotalPoints) + parseFloat(extrapoints);			
			$("#totalpoints").html(TotalPoints + " pts");
		}

		function TypeChange(Id) {
			var radioValue = $("input[name='type_" + Id + "']:checked").val();
			$.ajax({
				data: {
					cmd: "ChangeType",
					index: Id,
					type: radioValue
				},
				url: 'php/points.php',
				success: function (respuesta) {
					//console.log("ChangeType");
					console.log(respuesta);
					$('#extrap_' + Id).val(respuesta.extraval);
					//$("#proyects").html(respuesta.html);
					Calc_Points(Id);
				}

			});
		}

		function ClearProyects() {
			$.ajax({
				data: {
					cmd: "ClearProyects"
				},
				url: 'php/points.php',
				success: function (respuesta) {
					console.log("return php");
					console.log(respuesta);
					$("#proyects").html("");
				}
			});
			$( "#clear" ).hide();
		}

		function DeleteProyect(pitem) {
			$.ajax({
				data: {
					cmd: "RemoveProyect",
					item: pitem
				},
				url: 'php/points.php',
				success: function (respuesta) {
					console.log(respuesta);
					$("#proyects").html(respuesta.html);
					if (respuesta.cant_items>0)
					{
						$("#div_clear").html('<a id="clear" class="button--outline" href="#" onClick=\'ClearProyects();\'>Clear All</a>');
					}else{
						$("#div_clear").html('');
					}

					Set_Sliders();
				}
			});
			// if $("#proyects").empty() {
			// 	$( "#clear" ).hide();
			// }
		}

		function AddProyecto(IdProyect) {
			$.ajax({
				data: {
					cmd: "AddProyect",
					id: IdProyect
				},
				url: 'php/points.php',
				success: function (respuesta) {
					//console.log("return php");
					//console.log(respuesta);
					$("#proyects").html(respuesta.html);
					if (respuesta.cant_items>0)
					{
						$("#div_clear").html('<a id="clear" class="button--outline" href="#" onClick=\'ClearProyects();\'>Clear All</a>');
					}else{
						$("#div_clear").html('');
					}
					

					//

					Set_Sliders();
					/*				
								      var listaUsuarios = $("#lista-usuarios");
								      $.each(respuesta.data, function(index, elemento) {
								        listaUsuarios.append(
								            '<div>'
								          +     '<p>' + elemento.first_name + ' ' + elemento.last_name + '</p>'
								          +     '<img src=' + elemento.avatar + '></img>'
								          + '</div>'
								        );    
								      });*/
				},
				error: function () {
					console.log("No se ha podido obtener la información");
				}
			});
			$( "#clear" ).show();
		}
	</script>
</head>

<body>
	<div class="container">
		<H1>Points <span>Calculator</span></H1>
		<h5><span>• • •</span> WELCOME TO YOUR BEXI POINTS CALCULATOR</h5>
		<br><br>
		<h2>Learn how much without the fuss! 
Start by adding a project.</h2>
		<a class="button" href="#" data-toggle="modal" data-target="#myModal">Add Project</a>
		<div id="proyects" class="container">
		</div>
		<div id="div_clear">
			<a id="clear" class="button--outline" href="#" onClick='ClearProyects();'>Clear All</a>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="display: block;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Project</h4>
					</div>
					<div class="modal-body">
						<ul style="columns: 2; -webkit-columns: 2; -moz-columns: 2;">
							<?php
		        		$tam = count($base);				        		
		        		for ($i =1; $i <= $tam; $i++) {
						    echo "<li><a href='#' onClick ='AddProyecto(".$i.");'  data-dismiss='modal'>".$base[$i]["name"]."</a></li>";
								}
		        	?>
						</ul>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>

			</div>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
	 crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
	 crossorigin="anonymous"></script>
</body>

</html>
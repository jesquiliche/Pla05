<?php
	require_once './utils/utils.php';
	

	//inicializar variables
	$errores=array();
	$fecha=date('d-m-Y');
	$nombre="";
	$email="";
	$telefono="";
	$comentario="";
	$cuerpoMail="";
	

	if(isset($_POST['enviar'])) {
		$nombre=$_POST['nombre'];
		if(isEmpty($nombre)) 
			array_push($errores,"El nombre es requerido");
		

		$email=$_POST['email'];
		if(!validateEmail($email)) 
			array_push($errores,"El email incorrecto o no informado.");

		$telefono=$_POST['telefono'];
		if(!validatePhone($telefono)) 
			array_push($errores,"formato teléfono incorrecto");

		$comentario=$_POST['comentario'];
		if(isEmpty($comentario)) 
			array_push($errores,"El comentario es requerido");

		$fileName=$_FILES['fichero']['name'];
		if(!isEmpty($fileName)){
			if(subirFicheros($_FILES['fichero'],102400,$errores)){
				echo "entro";
			} else
				$fileName="";

		}

		$codigo=obtenerCodigo();
	
		if($fileName==="")
			$linea="$fecha;$email;$nombre;$comentario;$codigo";
		else 
			$linea="$fecha;$email;$nombre;$comentario;$codigo;$fileName";
		
		$texto=leerCSV("./archivos/log.txt");
	}

	function resetearCampos(){
		$errores=array();
		$fecha=date('d-m-Y');
		$nombre="";
		$email="";
		$telefono="";
		$comentario="";
		$cuerpoMail="";	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>IEM</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/cerulean/bootstrap.min.css">
	<link rel="stylesheet" href="css/App.css" type="text/css" />
</head>
<body>
	<div class="container my-3">
		<div class="card mx-auto py-3 bg-ligth">
			<div class="slider mx-auto" >
				<img src="img/iem_3.jpg" /><img src="img/iem_4.jpg" />
		    </div>

		    <div class="container col-lg-10 bg-ligth mx-auto">
		    	<h1 style="text-align:center">LOCALIZACIÓN DEL CENTRO Y CONTACTO</h1><br><br>
		    	<div class="container col-lg-10 bg-ligth">
					<h2>CONTACTO</h2>
					<p>Los campos marcados con * son obligatorios.</p><br>
						<form name="form" method="post" action='#' enctype="multipart/form-data">
						<div class="form-group">
							<label for="nombre">Nombre: * </label>
							<input type="text" 
								name="nombre" id="nombre" 
								class="form-control"
								value=<?=$nombre?>>
						</div>
						<div class="form-group">
							<label for="email">Email: * </label>
							<input type="email" 
								name="email" 
								id="email" 
								placeholder="nom@mail.com" 
								class="form-control"
								value=<?=$email?>>
								
						</div>
						<div class="form-group">
							<label for="telefono">Teléfono: </label>
							<input type="tel" 
								name="telefono" 
								id="telefono" class="form-control"
								value=<?=$telefono?>>
						</div>
						<div class="form-group">
							<label>Mensaje: *</label><br><br>
							<textarea id="comentario" 
							name="comentario" 
							class="form-control"
							placeholder="Introduzca aquí su pregunta o comentario"><?=$comentario?></textarea>
						</div>
						<div>
						<input type="file" name="fichero" id="fichero">
						</div>
						<div>
						<input id="enviar"
							class="btn btn-primary mt-3" 
							type="submit" 
							name="enviar"
							id="enviar" 
							value="Enviar">

						<span id='mensajes'>
						<?php

						if ((count($errores)==0 && isset($_POST['enviar']))){
							try{
								$cuerpoMail="<h6>Fecha  $fecha</h6>";
								$cuerpoMail.="<h6>Remitente $nombre</h6>";
								$cuerpoMail.="<h6>Teléfono $telefono</h6>";
								$cuerpoMail.="<h6>Mensaje $comentario</h6>";
							
								$cuerpoMail.="<h6>Código consulta $codigo</h6>";
								if(isset($fileName))
									$cuerpoMail.="<h6>Nombre fichero $fileName</h6>";
								else
								$cuerpoMail.="<h6>Nombre fichero </h6>";	
								mail($email,"Correo de contacto","<html><body>".$cuerpoMail."</body></html>");
								escribirLog($linea,$errores);
								resetearCampos();
								echo "<b> Correo 
								enviado sad
								satisfactoriamente.</b>";
							} catch(Exception $e) {
								if(isset($fileName)) borrarFichero($fileName);
								
							}
						
						?>
						</span>
						
						</div>
					</form>
					<hr>
					
					<?php 
						echo "<div class='card correo px-3 py-2'>";
						echo $cuerpoMail;
						echo "</div>";
					 } elseif((count($errores)>0 && $fileName!=="")) {
						borrarFichero($fileName); 
						
					}
					if(count($errores)>0) showErrors($errores);
					

					?>




					<hr>
					<div class='log'>
											</div>
				</div>
				<?php
					dibujarTabla(leerCSV('./archivos/log.txt'));
				?>
		    </div>
			
				
			

		</div>
	</div>
</body>
</html> 

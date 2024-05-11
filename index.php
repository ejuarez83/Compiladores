

<?php
// Verificar si se ha enviado un archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    // Abrir el archivo en modo lectura de texto
    $archivo_temporal = fopen($_FILES['archivo']['tmp_name'], "r");

    // Verificar si se abriÃ³ correctamente el archivo
    if ($archivo_temporal) {
        // Inicializar una variable para almacenar el contenido del archivo
        $contenido = '';
	//$cadena = '';
        // Leer el contenido del archivo lÃ­nea por lÃ­nea
        while (($linea = fgets($archivo_temporal)) !== false) {
            // Agregar la lÃ­nea al contenido con un salto de lÃ­nea
            //$contenido .= $linea;
           $contenido .= rtrim($linea) . " \n"; 
        }

        // Cerrar el archivo
        fclose($archivo_temporal);

        // Mostrar el contenido del archivo
        //echo nl2br($contenido); // La funciÃ³n nl2br() convierte los saltos de lÃ­nea en <br> para que se muestren en HTML
    } else {
        echo "No se pudo abrir el archivo";
    }
} else {
    //echo "Falla al cargar el archivo";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MarGCode</title>
<?php
//include menu.php;
?>
<!-- Enlaces a los archivos CSS de Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .editor {
    width: calc(100% - 10px);
    margin-bottom: 10px;
  }
   body {
            background-color: #f0f0f0;
}
.form-control.editor {
    font-family: Arial, sans-serif; /* Tipo de fuente */
    font-size: 14px; /* TamaÃ±o de fuente */
    line-height: 15px; /* Altura de lÃ­nea */
    height: 400px; /* Altura del div en pÃ­xeles (20px * 10 lÃ­neas) */
    overflow-y: auto; /* Mostrar barras de desplazamiento vertical si el contenido excede el tamaÃ±o del div */
}

#myDiv {
    position: relative;
    padding-left: 30px; /* Espacio para los nÃºmeros de lÃ­nea */
}

#lineNumbers {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 20px; /* Ancho de la columna de nÃºmeros de lÃ­nea */
    padding-top: 20px; /* AlineaciÃ³n vertical de los nÃºmeros de lÃ­nea */
    color: gray; /* Color de los nÃºmeros de lÃ­nea */
}
.scrollable-table {
    max-height: 300px; /* Ajusta esto según la altura deseada */
    overflow-y: auto;  /* Habilita el desplazamiento vertical */
    border: 1px solid #ccc; /* Opcional: añade un borde al contenedor */
}

table {
    width: 100%; /* Asegura que la tabla ocupe todo el contenedor */
    border-collapse: collapse; /* Opcional: para que el diseño de la tabla sea más compacto */
}

th, td {
    border: 1px solid #ddd; /* Opcional: añade bordes a las celdas */
    padding: 8px; /* Opcional: añade padding para espacio en las celdas */
    text-align: left; /* Alineación del texto en las celdas */
}


</style>
<script>
function funcionNoHabilitada() {
    alert("Funcion No habilitada");
    aplicarEstilos();
    return "FunciÃ³n no habilitada";
}
</script>

</head>
<body>
<?php
include 'menu.php';
?>
<div class="container">

<!-- <blockquote class="blockquote text-center">
     <h1>MarGCode</h1>
</blockquote>
-->
<div>
<br>
<span>Para usar este cargador de archivos debe seleccionar el archivo deseado con extension umg y cargarlo, si desea modificar el archivo puede hacerlo y luego presionar el boton verifica para que pueda continuar.</span>
<br><br>
	<form method='post' enctype='multipart/form-data'>
        	<div class="form-group">
            	Selecciona un archivo de texto:<input type='file' name='archivo' accept='.umg'>
            	<input type='submit' class="btn btn-info" value='Cargar'>
       		</div>
    	</form>
    	<br>

<div class="container">
  <div class="row">
              
    <div class="col">
      <div></div>
      <div id="myDiv" class="form-control editor" contenteditable="true" style="background-color: #080808; color: white;"><?php echo nl2br($contenido) ?></div>
    </div>
<!--   <div class="col">
      <div id="myDiv2" class="form-control editor" contenteditable="true" style="background-color: #080808; color: white;"><?php echo nl2br($cadena) ?></div>

   </div>
    <div class="col">
      <textarea class="form-control editor" rows="20" readonly>Errores encontrados</textarea>
    </div> -->
    <div class="col col-lg-2">
      <button type="button" class="btn btn-outline-primary" onclick="manual()">Analizador Lexico</button>
      <br><br>
      <button type="button" class="btn btn-outline-secondary" onclick="funcionNoHabilitada()">Analizador Sintactico</button>
    </div>
  </div>
</div>
<div class="container">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<h3 class="text-center">Errores Encontrados:</h3>
		<div class="scrollable-table">
		<div class="table-responsive table-hover" contenteditable="true" id="TablaErrores">
		</div>
		</div>
		<h3 class="text-center">Indicadores Lenguaje:</h3>
		<div class="scrollable-table">
		<div class="table-responsive table-hover" contenteditable="true" id="TablaTLP">
		</div>
		</div>
	</div>
</div>
<!-- Enlaces a los archivos JavaScript de Bootstrap (opcional, pero necesarios para algunos componentes de Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>

function insertData(token4, lexema4, linea4) {
    // Crear el objeto que queremos enviar
  //const data = { token4, linea4 };
  //console.log('insertar.php?token='+token4+'&lexema='+lexema4+'&linea='+linea4);
  //console.log("datos en json: "+JSON.stringify(data));
  // Hacer la petición POST
  fetch('insertar.php?token='+token4+'&lexema='+lexema4+'&linea='+linea4)
  .then(response => response.text()) // Convertir la respuesta a texto
  .then(data => {
    //console.log('Success:', data); // Mostrar la respuesta del servidor
  })
  .catch((error) => {
    console.error('Error:', error);
  });
}
function manual(){
	const div = document.getElementById('myDiv');
	let contenido = div.innerHTML;
	// Remover los elementos <p> completamente
	contenido = contenido.replace(/<p[^>]*>(.*?)<\/p>/g, '');

	// Reemplazar los <span> con su contenido de texto solamente
	contenido = contenido.replace(/<span[^>]*>(.*?)<\/span>/g, '$1');
	contenido = contenido.replace(/(?<!['"])\s{2,}(?!['"])/g, ' ');
	 contenido = contenido.replace(/<div[^>]*>(.*?)<\/div>/gis, '$1');

	//console.log(contenido)
	//alert("ready?");
	div.innerHTML=contenido;
	cargadatos();
	muestraTokens();
	muestraErrores();
}

function inserterror(error2, linea2) {
    // Crear el objeto que queremos enviar
  //const data = { error2, linea2 };
  //console.log('inserta_error.php?error='+error2+'&linea='+linea2);
  //console.log("datos en json: "+JSON.stringify(data));
  // Hacer la petición POST
  fetch('inserta_error.php?error='+error2+'&linea='+linea2)
  .then(response => response.text()) // Convertir la respuesta a texto
  .then(data => {
    //console.log('Success:', data); // Mostrar la respuesta del servidor
  })
  .catch((error) => {
    console.error('Error:', error);
  });
}
function muestraTokens() {
	//Crea Tabla
	var TablaTLP = document.getElementById('TablaTLP');
	var Tabla = "<table class='table'>";
	Tabla += "<thead class='text-muted'>";
	Tabla += "<th>#</th>";
	Tabla += "<th>Token</th>";
	Tabla += "<th>Lexema</th>";
	//Tabla += "<th>Patron</th>";
	Tabla += "<th>No.Linea</th>";
	Tabla += "</thead>";
	Tabla += "<tbody>";
	var index =0;
    fetch('obtenertokens.php')
    .then(response => response.json())
    .then(data => {
        //console.log("Datos de tokens"+data); // Aquí manejas los datos recibidos
        // Por ejemplo, mostrar datos en la consola o en la página web
        data.forEach(token5 => {
		    //console.log("token: "+token5.token+" - lineas: "+token5.lineas);
			index = index +1;
			//token = data.map(item => item.token);
			//patron = data.map(item => item.patron);
            Tabla += "<tr>";
			Tabla += "<td>"+index+"</td>";
			Tabla += "<td>"+token5.token+"</td>";
			Tabla += "<td>"+token5.lexemas+"</td>";
			//Tabla += "<td>"+tmpPatron+"</td>";
			Tabla += "<td>"+token5.lineas+"</td>";
			Tabla += "</tr>";
			//console.log("tabla en proceso"+Tabla);
			//console.log(`Token: ${token.token}, Linea: ${token.linea}`);
        });
		Tabla += "</tbody>";
		Tabla += "</table>";
	//document.getElementById('data-table').innerHTML = Tabla;
		TablaTLP.innerHTML = Tabla;
    })
    .catch(error => console.error('Error:', error));
	//console.log(Tabla);
	
}
function muestraErrores() {
	//Crea Tabla
	//var TablaTLP = document.getElementById('TablaTLP');
	var TablaErrores = document.getElementById('TablaErrores');
	var TablaError = "<table class='table'>";
	var TablaError = "<table class='table'>";
	TablaError += "<thead class='text-muted'>";
	TablaError += "<th>#</th>";
	TablaError += "<th>Error</th>";
	//TablaError += "<th>Detalle</th>";
	TablaError += "<th>No.Linea</th>";
	TablaError += "</thead>";
	TablaError += "<tbody>";
	var index =0;
    fetch('obtenererror.php')
    .then(response => response.json())
    .then(data => {
        //console.log("Datos de tokens"+data); // Aquí manejas los datos recibidos
        // Por ejemplo, mostrar datos en la consola o en la página web
        data.forEach(token5 => {
		    //console.log("token: "+token5.token+" - lineas: "+token5.lineas);
			index = index +1;
			//token = data.map(item => item.token);
			//patron = data.map(item => item.patron);
            TablaError += "<tr>";
			TablaError += "<td>"+index+"</td>";
			TablaError += "<td>"+token5.error+"</td>";
			//TablaError += "<td>"+token5.lexemas+"</td>";
			//Tabla += "<td>"+tmpPatron+"</td>";
			TablaError += "<td>"+token5.linea+"</td>";
			TablaError += "</tr>";
			//console.log("tabla en proceso"+Tabla);
			//console.log(`Token: ${token.token}, Linea: ${token.linea}`);
        });
		TablaError += "</tbody>";
		TablaError += "</table>";
	//document.getElementById('data-table').innerHTML = Tabla;
		TablaErrores.innerHTML = TablaError;
    })
    .catch(error => console.error('Error:', error));
	//console.log(Tabla);
	
}
function limpia_datos() {
  fetch('limpia.php')
  .then(response => response.text()) // Convertir la respuesta a texto
  .then(data => {
    //console.log('Success:', data); // Mostrar la respuesta del servidor
  })
  .catch((error) => {
    console.error('Error:', error);
  });//xhr.send(`token=${encodeURIComponent(token)}&linea=${encodeURIComponent(linea)}`);
  //alert("verifica");
}
function cargadatos(){

fetch('consulta_color.php')
    .then(response => response.json())
    .then(data => {
        //console.log("ingreso a fetch");
        palabrasReservadas = data.map(item => item.identificador);
        colores = data.map(item => item.color);
        token = data.map(item => item.token);
        patron = data.map(item => item.patron);
		//limpia la tabla de tokens
		limpia_datos();
        aplicarEstilos(palabrasReservadas,colores,token, patron);
})
    .catch(error => console.error('Error al obtener los datos:', error));
 }
	let timerId; // Variable para almacenar el identificador del temporizador
	const tiempoEspera = 500; // Tiempo de espera en milisegundos
        // FunciÃ³n para aplicar estilos a las palabras reservadas
	
	
	TablaTLP.innerHTML = "";
	TablaErrores.innerHTML = "";
	
	var PalabraAnterior = "";
        // Prepara las cadenas
        //agregarEspaciosAlDiv('myDiv');

        function aplicarEstilos(palabrasReservadas, colores, token, patron) {
            
            const div = document.getElementById('myDiv');
           // Guardar la posiciÃ³n actual del cursor
			const startPos = div.selectionStart;
			const endPos = div.selectionEnd;
            let contenido = div.innerHTML;
			//console.log(contenido);
            let cadena = contenido;
            let cadenaErrores = contenido;
			let errores = [];
			let tokens = [];
            const lineas=0
	        let palabras = contenido.match(/[^\s]+|\n?/g);
			var index=1;
			var flag_found=0;
			var indices='';
			var token_actual='';
			var retorno='';
			var inicio=true;
		console.log(contenido);
	    contenido = palabras.map(palabraEnTexto => {
			retorno='';
			if(inicio){
				if(index<10) {retorno='<p style="display: inline;">[ '+index+']  </p>  ';}
				else {retorno='<p style="display: inline;">[ '+index+']</p>';}
				inicio = false;
			}
			if(palabraEnTexto === '<br>'){
				index = index +1;
				inicio=true;
			}
			
			
			//console.log("ciclo de palabras: **"+palabraEnTexto+"**");
        	if (palabraEnTexto.length > 0) {
				flag_found=0;
			//busca palabra en arreglo de regex
				for (let i = 0; i < patron.length; i++) {
					let extp = patron[i];
					let expr = extp.slice(1, -1);
					let temp=new RegExp(expr);
					//palabraEnTexto.replace("&gt;",">");
					//palabraEnTexto.replace("&lt;","<");
					if (palabraEnTexto === "-&gt;=!"){
						console.log("palabra: "+palabraEnTexto+" regex: "+temp);
						palabraEnTexto ='->=!';
					}
					if ((palabraEnTexto === "&gt;") ||  (palabraEnTexto === "&gt")){
						palabraEnTexto = ">"
					}
					if ((palabraEnTexto === "&lt;") || (palabraEnTexto === "&lt")){
						palabraEnTexto = "<"
					}
					
					if (temp.test(palabraEnTexto)) {
						//console.log("La palabra hace match con la expresión regular " + (i+1) + " regex: "+temp+" color: "+colores[i]);
						//busca si el token ya esta:
						token_actual = token[i];
						//console.log("token a buscar: "+token_actual);
						//console.log("lista actual: "+tokens);
						tokens.push([token_actual, index]);
						insertData(token_actual, palabraEnTexto,index);
						flag_found=1;
						retorno = retorno + "<span style='color: "+colores[i]+";'>"+palabraEnTexto+"</span>";
						return retorno
						// Hacer algo si la palabra hace match
						break; // Opcional: si solo quieres que haga match con la primera regex que coincida
					}
					
				}
				
				
				// fin de lista de tokens
				if (flag_found ===0){
					if((palabraEnTexto !== '\n') && (palabraEnTexto !== '<br>') && (palabraEnTexto !== '&lt;') && (palabraEnTexto.length>0 ) && (palabraEnTexto !== ' ') && (palabraEnTexto !== '&gt;') && (palabraEnTexto !== '&gt;_') && (palabraEnTexto !== "'&")&& (palabraEnTexto !== ';&')){
						errores.push([palabraEnTexto, index]);
						inserterror(palabraEnTexto, index);
						//return palabraEnTexto;
					}
					retorno = retorno+ palabraEnTexto;
					return retorno;
				}
				
		} else {
		return retorno;
		}
	}).join(" ");
			
			
			palabras = cadenaErrores.split(" ");
		    	cadenaErrores = palabras.map(palabraEnTexto => {
		        	// Comparar la palabra en el texto con la palabra reservada
				if (palabraEnTexto.length > 0) {
					if (palabraEnTexto.charAt(0) == "~"){
						return "";
					}
		        	}
				if (palabraEnTexto.toUpperCase() === palabraEnTexto.toUpperCase()) {
		           		 return "";
		       		 } else {
		            		return palabraEnTexto;
		       		 }
		    	}).join(" ");

 		// Logica para determinar palabras reservadas del lenguaje.
			palabras = cadena.split(" ");
			var Conteo = 1;
			var CC = 0;
			var tmpIndex = 0;
			var tmpToken = "";
			var tmpPalabra = "";
			var tmpPatron = "";
			var tmpConteo = "";
			var tmpBandera = 0;
		    	cadena = palabras.map(palabraEnTexto => {
				if(palabraEnTexto.trim() == "<br>")
				{
					Conteo = Conteo + 1;
				}
		        	// Comparar la palabra en el texto con la palabra reservada
				if (palabraEnTexto.length > 0) {
					if (palabraEnTexto.charAt(0) == "~"){
 						tmpBandera = 1;
						tmpIndex = index + 1;
						tmpToken = token[index];
						tmpPalabra = tmpPalabra;
						tmpPatron = patron[index];
						tmpConteo += Conteo.toString() + ",";
						return token[index]+"|"+patron[index];

					}
				}
		        	if (palabraEnTexto.toUpperCase() === palabraEnTexto.toUpperCase()) {

 					tmpBandera = 1;
					if  (PalabraAnterior != tmpPalabra){
						PalabraAnterior = tmpPalabra;
						tmpIndex = index + 1;
						tmpToken = token[index];
						tmpPalabra = tmpPalabra;
						tmpPatron = patron[index];
				 	}
					tmpConteo += Conteo.toString() + ",";
					return token[index]+"|"+patron[index];
		       		 } else {
		            		return palabraEnTexto;
		       		 }
		    	}).join(" ");

			if (tmpBandera > 0){
				
			}
            //});
		var palabrasError = cadenaErrores.split("<br>");
		//alert(cadenaErrores);
		var conteoErrores = 0;
		var lineaError = 0;
		var strlineaError = "";
		var ErrorOrdenado = cadenaErrores.split("<br>");
		var ccx = 0;
		var ccy = 0;
		var ccz = 0;
		//var arrayError = [];
		cadenaErrores = palabrasError.map(palabraEnTexto => {
			ccx += 1;
			lineaError = 0;
			ccz = 0;
			strlineaError = "";
			if(palabraEnTexto.trim() != ""){
		       		cadenaErrores = palabrasError.map(palabraEnTexto2 => {
					lineaError += 1;
					if(palabraEnTexto.trim() === palabraEnTexto2.trim()){
						ccy = lineaError;
						ccz += 1;
						strlineaError += lineaError.toString() + ",";
					}
				}).join(" ");
				//alert(palabraEnTexto+"** ccz:"+ccz.toString()+" ccx:"+ccx.toString()+" ccy:"+ccy.toString());
				if(ccx == ccy){
						conteoErrores = conteoErrores + 1;
						//TablaError += "<tr>";
						//TablaError += "<td>"+conteoErrores+"</td>";
						////TablaError += "<td>"+palabraEnTexto+"</td>";
						//TablaError += "<td>Validar error lexico</td>";
						//TablaError += "<td>"+strlineaError.toString()+"</td>";
						//TablaError += "</tr>";

				}
			}

		}).join(" ");

	    //TablaError += "</tbody>";
	    //TablaError += "</table>";
	    //alert(caden);
            
            //TablaErrores.innerHTML = TablaError;
            // Actualiza el contenido del div con los estilos aplicados
	    //console.log("contenido final:" + contenido);
            div.innerHTML = contenido;
			//crear listado de Tokens
				
				// Convertir el array en un objeto de tokens con un array de líneas
				let tokenMap = {};

				for (let i = 0; i < tokens.length; i += 2) {
				  const token = tokens[i];
				  const line = tokens[i + 1];
				  
				  if (!tokenMap[token]) {
					tokenMap[token] = [];
				  }
				  
				  tokenMap[token].push(line);
				}

				// Crear un array resultante con el formato deseado
				let resultArray = [];

				Object.keys(tokenMap).forEach(token => {
				  // Concatenamos las líneas asociadas a cada token y lo añadimos al array final
				  resultArray.push(token, tokenMap[token].join(", "));
				});

				//console.log("array resultante: "+resultArray);
				////fin de tokens
        };
        // Aplica estilos inicialmente al cargar la pÃ¡gina
  	//alert("aplicara los estilos");
        //aplicarEstilos();
	cargadatos();
	muestraTokens();
	muestraErrores();
   //})
   // .catch(error => console.error('Error al obtener los datos:', error));
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const editor = document.getElementById('myDiv');
    const lineNumbers = document.getElementById('lineNumbers');

    function updateLineNumbers() {
        const lines = editor.textContent.split('\n');
        lineNumbers.innerHTML = lines.map((line, index) => `<div>${index + 1}</div>`).join('');
    }

    //editor.addEventListener('input', updateLineNumbers);

    //updateLineNumbers();
});

</script>
</body>
</html>

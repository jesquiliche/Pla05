<?php

//Función que valida el Email develovera True o false, dependiendo si el valor
// es correcto o no
function validateEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // echo "{$email}: A valid email"."<br>";
        return true;
    } else {
        return false;
    }
}

//Función para determinar si el valor es nulo o esta vacio.
function isEmpty($valor)
{
    if (empty(trim($valor)) || $valor == "") {
        return true;
    }

    return false;
}

//Funcion para deterninar si $value esta comprendio en un determinado rango
// de valores.
function isNumber($value, $minValue, $maxValue)
{
    $value = floatval($value);
    if ($value > $maxValue || $value < $minValue) {
        echo "${value} no esta comprendido entre ${minValue} y ${maxValue}";
    } else {
        echo null;
    }

}

//Muestre un Array de errores en un DIV dentro del formulario
function showErrors($errores)
{

    try {
        // Solo dibujar el Div si existen errores
        if (count($errores) > 0) {
            echo "<div class='card col-lg-10 mx-auto py-3 mt-2'>";
            echo "  <div class='card-title mx-auto'>";
            echo "      <b><b>Errores de validación</b>";
            echo "  </div>";
            for ($x = 0; $x < count($errores); $x++) {
                echo "      <div>";
                echo "          <i class='fa fa-times-circle px-2' style='color: red' ></i> " . $errores[$x] . "<br>";
                echo "      </div>";
            }
            echo "</div>";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
//Sube el fichero al servidor
// $file $_FILES['fichero'] pasado por parametro
// $masFileSize tamaño maximo de fichero en byte
// &$errores array que contiene los errores
function subirFicheros($file, $maxFileSize, &$errors)
{

    try {
        $fileTmpPath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileSize = $file['size'];

        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = $fileName;
        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'svg');
        //Comprobamos la extensión
        if (in_array($fileExtension, $allowedfileExtensions)) {
            //directorio de destino
            if ($fileSize > $maxFileSize) {
                $message = 'El fichero no puede sobrepasar las 100k';
                throw new Exception($message, 10);

            }
            $uploadFileDir = './archivos/';
            //fichero de destino
            $dest_path = $uploadFileDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                $message = 'Hubo algun problema al cargar el fichero.';
                throw new Exception($message, 10);

            }

        } else {

            {
                $message = 'El fichero debe tener una de estas extensiones (jpg,jpeg,png,svg)';
                throw new Exception($message, 10);
            }
        }
        //la subida ha tenido exito
        return true;
    } catch (Exception $e) {
        array_push($errors, $e->getMessage());
        //la subida ha fallado
        return false;
    }

}

///Función encargada de escribir en el Log
///Parametros
///$linea -> Linea del fichero en formato csv a grabar
///$erroes -> Array de errores que se mostrara con la función showErrors
function escribirLog($linea, &$errors)
{
    try {
        $fecha = new DateTime();

        $fileContent = file_get_contents("./archivos/log.txt");

        $file = fopen("./archivos/log.txt", "w+");
        flock($file, LOCK_EX);
        fwrite($file, $linea . "\n");
        fwrite($file, $fileContent);
    } catch (Exception $e) {
        array_push($errores, $e->getMessage());
    } finally {
        flock($file, LOCK_UN);
        fclose($file);
    }
}

function obtenerCodigo()
{
    $textKey = "A1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6Q7R8S9T0U1V2W3X4Z5";

    $clave = "";
    for ($x = 0; $x < 6; $x++) {
        $pos = mt_rand(0, strlen($textKey) - 1);
        $clave .= substr($textKey, $pos, 1);
    }
    return $clave;
}

function leerCSV($archivoCSV, $delimitador = ";"): array
{
    $file_handle = fopen($archivoCSV, 'r');

    while (!feof($file_handle)) {
        $linea_de_texto[] = fgetcsv($file_handle, 1024, $delimitador);
    }
    fclose($file_handle);
    return $linea_de_texto;

}

function dibujarTabla($arrayCSV)
{
    error_reporting(0);
    echo "<table class='table table-stripe col-lg-10 mx-auto'>";
    foreach ($arrayCSV as $fila) {
        echo "<tr>";
        $fila = (array) $fila;

        echo "<td width='30%'>" . $fila[4] . "</td>";
        echo "<td width='30%'>" . $fila[0] . "</td>";
        echo "<td width='40%'>" . $fila[1] . "</td>";

        echo "</tr>";
    }
    error_reporting(E_ALL);
}

function borrarFichero($fileName)
{
    unlink($fileName);
}

function validatePhone($number)
{
    if (!isEmpty($number)) {
        if (strlen($number) == 9 && intval($number) != 0) {

            return true;
        }
        return false;
    } else {
        return true;
    }
}

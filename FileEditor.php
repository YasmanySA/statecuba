<?php
/*
 * Copyright (c) 2024. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

class FileEditor
{

    /**
     * @param $file_path
     * @param $line_to_find
     * @param $new_line
     * @return void
     */
    public function insertLines($file_path, $line_to_find, $new_line)
    {
        // Leer el contenido del archivo
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES);

        // Variable para almacenar el nuevo contenido del archivo
        $new_content = [];

        // Recorrer cada línea del archivo
        foreach ($file_content as $line) {
            // Añadir la línea actual al nuevo contenido
            $new_content[] = $line;

            // Si la línea actual es la que estamos buscando, añadir la nueva línea después de ella
            if (strpos($line, $line_to_find) !== false) {
                $new_content[] = $new_line;
            }
        }

        // Escribir el nuevo contenido de vuelta al archivo
        file_put_contents($file_path, implode("\n", $new_content));


    }

    /**
     * Reemplaza una línea específica en un archivo.
     *
     * @param string $file_path La ruta al archivo.
     * @param string $line_to_find La línea que se debe encontrar.
     * @param string $new_line La nueva línea que reemplazará la línea encontrada.
     * @return void
     */
    public function replaceLine($file_path, $line_to_find, $new_line)
    {
        // Leer el contenido del archivo
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES);

        // Verificar que el archivo fue leído correctamente
        if ($file_content === false) {
            echo "Error al leer el archivo.\n";
            return;
        }

        // Variable para almacenar el nuevo contenido del archivo
        $new_content = [];

        // Recorrer cada línea del archivo
        foreach ($file_content as $line) {
            // Si la línea actual es la que estamos buscando, reemplazarla con la nueva línea
            if (strpos($line, $line_to_find) !== false) {
                $new_content[] = $new_line;
            } else {
                // Añadir la línea actual al nuevo contenido
                $new_content[] = $line;
            }
        }

        // Escribir el nuevo contenido de vuelta al archivo
        $result = file_put_contents($file_path, implode("\n", $new_content));

        // Verificar que el archivo fue escrito correctamente
        if ($result === false) {
            echo "Error al escribir en el archivo.\n";
        } else {
            echo "Archivo actualizado correctamente.\n";
        }
    }

    /**
     * Inserta una línea específica en una posición específica del archivo.
     *
     * @param string $file_path La ruta al archivo.
     * @param string $new_line La nueva línea que se desea insertar.
     * @param int $position La posición en la que se debe insertar la nueva línea (0-indexada).
     * @return void
     */
    public function insertLineAtPosition($file_path, $new_line, $position)
    {
        // Leer el contenido del archivo
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES);

        // Verificar que el archivo fue leído correctamente
        if ($file_content === false) {
            echo "Error al leer el archivo.\n";
            return;
        }

        // Insertar la nueva línea en la posición especificada
        array_splice($file_content, $position, 0, $new_line);

        // Escribir el nuevo contenido de vuelta al archivo
        $result = file_put_contents($file_path, implode("\n", $file_content));

        // Verificar que el archivo fue escrito correctamente
        if ($result === false) {
            echo "Error al escribir en el archivo.\n";
        } else {
            echo "Archivo actualizado correctamente.\n";
        }
    }


    /**
     * Elimina múltiples líneas específicas en un archivo.
     *
     * @param string $file_path La ruta al archivo.
     * @param array $lines_to_find Las líneas que se deben eliminar.
     * @return void
     */
    public function deleteLines($file_path, $lines_to_find)
    {
        // Leer el contenido del archivo
        $file_content = file($file_path, FILE_IGNORE_NEW_LINES);

        // Verificar que el archivo fue leído correctamente
        if ($file_content === false) {
            echo "Error al leer el archivo.\n";
            return;
        }

        // Variable para almacenar el nuevo contenido del archivo
        $new_content = [];

        // Recorrer cada línea del archivo
        foreach ($file_content as $line) {
            // Si la línea actual no es una de las líneas que estamos buscando (después de eliminar espacios en blanco), añadirla al nuevo contenido
            if (!in_array(trim($line), array_map('trim', $lines_to_find))) {
                $new_content[] = $line;
            }
        }

        // Escribir el nuevo contenido de vuelta al archivo
        $result = file_put_contents($file_path, implode("\n", $new_content));

        // Verificar que el archivo fue escrito correctamente
        if ($result === false) {
            echo "Error al escribir en el archivo.\n";
        } else {
            echo "Archivo actualizado correctamente.\n";
        }
    }


}

//class FileScanner
//{
//    public function scanDirectory($directory, $file_to_find)
//    {
//        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
//        foreach ($iterator as $file) {
//            if ($file->getFilename() === $file_to_find) {
//                return $file->getPathname();
//            }
//        }
//        return null;
//    }
//}

// Ejemplo de uso

?>

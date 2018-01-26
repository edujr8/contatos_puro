<?php

include '../class/conexao.php';
include '../class/PHPExcel/IOFactory.php';

if (empty($_FILES['arquivo_contato']['name'])) {
    header('Location: ../view/contato/cadastro_contato.php');
} else {

    $inputFileName = $_FILES['arquivo_contato']['tmp_name'];
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

    $query = '';

    $con = new Conexao();

    foreach ($sheetData as $key => $row) {
        if ($key > 1) {

            $values = '';

            foreach ($row as $key => $colums) {
                if (!empty($colums)) {
                    switch (strtolower($key)) {
                        case 'c':
                            $values[] = "'" . trim(str_replace('-', '', str_replace('.', '', $colums))) . "'";
                            break;

                        case 'g':
                            $data = new DateTime(str_replace('/', '-', $colums));
                            $values[] = "'" . $data->format('Y-m-d') . "'";
                            break;

                        default :
                            $values[] = "'" . $colums . "'";
                    }
                }
            }

            if (!empty($values)) {
                $query = "INSERT INTO contato ("
                        . "nome,"
                        . "email,"
                        . "celular,"
                        . "operadora_cel,"
                        . "cidade,"
                        . "estado,"
                        . "data_nascimento"
                        . ") VALUE ("
                        . implode(',', $values)
                        . ")";

                $con->query($query);
            }
        }
    }

    header('Location: ../view/contato/arquivo_load.php');
}

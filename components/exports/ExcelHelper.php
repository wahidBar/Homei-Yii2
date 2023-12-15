<?php

namespace app\components\exports;

use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Font;

class ExcelHelper
{
    public $sheet;
    public $objPHPExcel;

    protected function border($position, $style, $color)
    {
        // BORDER_NONE - 'none'
        // BORDER_DASHDOT - 'dashDot'
        // BORDER_DASHDOTDOT - 'dashDotDot'
        // BORDER_DASHED - 'dashed'
        // BORDER_DOTTED - 'dotted'
        // BORDER_DOUBLE - 'double'
        // BORDER_HAIR - 'hair'
        // BORDER_MEDIUM - 'medium'
        // BORDER_MEDIUMDASHDOT - 'mediumDashDot'
        // BORDER_MEDIUMDASHDOTDOT - 'mediumDashDotDot'
        // BORDER_MEDIUMDASHED - 'mediumDashed'
        // BORDER_SLANTDASHDOT - 'slantDashDot'
        // BORDER_THICK - 'thick'
        // BORDER_THIN - 'thin'
        $border = array('borders' => array($position => array('style' =>
        $style, 'color' => array('argb' => $color),)));

        return $border;
    }

    protected function font($bold, $size, $font)
    {
        $styleArray = [
            'font' => [
                'bold'  =>  $bold,
                'size'  =>  $size,
                'name'  =>  $font
            ],
        ];

        return $styleArray;
    }

    protected function alignmentCenter()
    {
        $styleArray = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
        ];

        return $styleArray;
    }

    function download($name = "download")
    {
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}

<?php

namespace app\components\exports;

use PHPExcel;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

class BarangKeluarExport extends ExcelHelper
{
    public $models;
    public function __construct($models)
    {
        $this->objPHPExcel = new PHPExcel();
        $this->model = $models;

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("HOMEI")
            ->setLastModifiedBy("HOMEI")
            ->setTitle("HomeI Report")
            ->setSubject("HomeI Report")
            ->setDescription("Homei Report")
            ->setKeywords("homei report")
            ->setCategory("home report");

        $this->objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'HOMEi');
        $this->sheet = $this->objPHPExcel->getActiveSheet();
        $this->styleHeader($this->sheet);
        $this->generateData($this->model, $this->objPHPExcel, $this->sheet);
    }

    function styleHeader(&$sheet)
    {
        $sheet->getStyle('A1')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('A1')->setValue('No');
        $sheet->getStyle("A1")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("A1")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        $sheet->getStyle('B1')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('B1')->setValue('Nama Barang');
        $sheet->getStyle("B1")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("B1")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        $sheet->getStyle('C1')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('C1')->setValue('Jumlah');
        $sheet->getStyle("C1")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("C1")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        $sheet->getStyle('D1')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('D1')->setValue('Pembeli');
        $sheet->getStyle("D1")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("D1")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        $sheet->getStyle('E1')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('E1')->setValue('Tanggal');
        $sheet->getStyle("E1")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("E1")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
    }

    function generateData($models, &$objPHPExcel, &$sheet, &$row = 2, &$no = 1, &$abjad = "A")
    {
        foreach ($models as $key => $model) {
            $sheet->getStyle('A' . $row)->applyFromArray($this->font(true, '11', 'Times New Roman'));
                $sheet->getCell('A' . $row)->setValue($no);
                $sheet->getStyle('A' . $row)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                $sheet->getStyle('A' . $row)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                
                $sheet->getCell('B' . $row)->setValue($model->supplierBarang->nama_barang);
                $sheet->getStyle('B' . $row)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                $sheet->getStyle('B' . $row)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));

                $sheet->getCell('C' . $row)->setValue($model->jumlah);
                $sheet->getStyle('C' . $row)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                $sheet->getStyle('C' . $row)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));

                $sheet->getCell('D' . $row)->setValue($model->supplierOrder->user->name);
                $sheet->getStyle('D' . $row)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                $sheet->getStyle('D' . $row)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));

                $sheet->getCell('E' . $row)->setValue($model->created_at);
                $sheet->getStyle('E' . $row)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                $sheet->getStyle('E' . $row)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));

                $no++;
                $row++;
        }

        // Auto size columns for each worksheet
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        // Rename worksheet
        $sheet->setTitle('Laporan Barang Keluar');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
    }
}

<?php

namespace app\components\exports;

use app\components\Tanggal;
use PHPExcel;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

class ProyekKemajuanExport extends ExcelHelper
{
    public $project;
    public $model;

    /**
     * To Splice per Week
     */
    public $start_date;
    public $end_date;

    public function __construct($project, $model)
    {
        $this->objPHPExcel = new PHPExcel();
        $this->model = $model;
        $this->project = $project;

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("HOMEI")
            ->setLastModifiedBy("HOMEI")
            ->setTitle("HomeI Report")
            ->setSubject("HomeI Report")
            ->setDescription("Homei Report")
            ->setKeywords("homei report")
            ->setCategory("home report");

        $week_count = Tanggal::numberOfWeekBetween($this->project->tanggal_awal_kontrak, $this->project->tanggal_akhir_kontrak);

        // sheet 1
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->sheet = $this->objPHPExcel->getActiveSheet();
        $this->sheet->setTitle("Report Minggu Ke-1");
        $this->start_date = $this->project->tanggal_awal_kontrak;
        $this->end_date = date("Y-m-d", strtotime($this->project->tanggal_awal_kontrak . " 7 days"));
        $this->styleHeader($this->sheet, $this->project);
        $this->generateData($this->model, $this->objPHPExcel, $this->sheet);

        for ($i = 1; $i < $week_count; $i++) {
            $this->sheet = $this->objPHPExcel->createSheet($i);
            $this->sheet->setTitle("Report Minggu Ke-" . ($i + 1));

            $this->start_date = $this->end_date;
            $this->end_date = date("Y-m-d", strtotime($this->start_date . " 7 days"));
            $this->styleHeader($this->sheet, $this->project);
            $this->sheet->getCell('G1')->setValue('LAPORAN PROGRESS MINGGUAN (MINGGU ' . ($i + 1) . ')');
            $this->generateData($this->model, $this->objPHPExcel, $this->sheet);
        }

        foreach ($this->objPHPExcel->getWorksheetIterator() as $worksheet) {

            $this->objPHPExcel->setActiveSheetIndex($this->objPHPExcel->getIndex($worksheet));

            $sheet = $this->objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }
    }

    function styleHeader(&$sheet, $project)
    {
        $this->project = $project;

        //Section Homei
        $sheet->setCellValue('A2', 'HOMEi');
        $sheet->getStyle('A2')->applyFromArray(array_merge($this->font(false, '28', 'Usuzi Condensed'), $this->alignmentCenter()));
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle("A1:F3")->applyFromArray($this->border('top', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("A1:F3")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("A1:A3")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("F1:F3")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Subtitle
        $sheet->mergeCells('A3:F3');
        $sheet->getStyle('A3')->applyFromArray(array_merge($this->font(false, '12', 'Calibri'), $this->alignmentCenter()));
        $sheet->getCell('A3')->setValue('"Hunian Dalam Genggaman"');

        //Section Nama Laporan
        $sheet->mergeCells('G1:N3');
        $sheet->getStyle('G1')->applyFromArray(array_merge($this->font(true, '18', 'Arial Rounded MT Bold'), $this->alignmentCenter()));
        $sheet->getCell('G1')->setValue('LAPORAN PROGRESS MINGGUAN (MINGGU 1)');
        $sheet->getStyle("G1:N3")->applyFromArray($this->border('top', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("G1:N3")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("N1:N3")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Contractor name
        $sheet->mergeCells('A4:C5');
        $sheet->getStyle('A4')->applyFromArray(array_merge($this->font(false, '12', 'AR YuanGB Bold'), $this->alignmentCenter()));
        $sheet->getCell('A4')->setValue('CONTRACTOR NAME');
        $sheet->getStyle("A5:C5")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("C4:C5")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("A4:A5")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Project name
        $sheet->mergeCells('D4:H5');
        $sheet->getStyle('D4')->applyFromArray(array_merge($this->font(false, '12', 'AR YuanGB Bold'), $this->alignmentCenter()));
        $sheet->getCell('D4')->setValue('PROJECT NAME');
        $sheet->getStyle("D4:H5")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("H4:H5")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("D4:D5")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Location
        $sheet->mergeCells('I4:N5');
        $sheet->getStyle('I4')->applyFromArray(array_merge($this->font(false, '12', 'AR YuanGB Bold'), $this->alignmentCenter()));
        $sheet->getCell('I4')->setValue('LOCATION');
        $sheet->getStyle("I4:N5")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("N4:N5")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("I4:I5")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Nama Kontraktor
        $sheet->mergeCells('A6:C7');
        $sheet->getStyle('A6')->applyFromArray(array_merge($this->font(true, '18', 'Calibri'), $this->alignmentCenter()));
        $sheet->getCell('A6')->setValue('');
        $sheet->getStyle("A7:C7")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("C7:C7")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("A6:A7")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Nama Proyek
        $sheet->mergeCells('D6:H7');
        $sheet->getStyle('D6')->applyFromArray(array_merge($this->font(false, '12', 'Calibri'), $this->alignmentCenter()));
        $sheet->getCell('D6')->setValue($this->project->nama_proyek);
        $sheet->getStyle("D6:H7")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("H6:H7")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("D6:D7")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Alamat Proyek
        $sheet->mergeCells('I6:N7');
        $sheet->getStyle('I6')->applyFromArray(array_merge($this->font(false, '12', 'Calibri'), $this->alignmentCenter()));
        $sheet->getCell('I6')->setValue($this->project->deskripsi_proyek);
        $sheet->getStyle("I6:N7")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("N6:N7")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("I6:I7")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section No.
        $sheet->mergeCells('A8:A10');
        $sheet->getStyle('A8')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('A8')->setValue('No.');
        $sheet->getStyle("A10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("A8:A10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("A8:A10")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle('A8:A10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');

        //Section Uraian Pekerjaan
        $sheet->mergeCells('B8:B10');
        $sheet->getStyle('B8')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('B8')->setValue('Uraian Pekerjaan');
        $sheet->getStyle("B10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("B8:B10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("B8:B10")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle('B8:B10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');

        //Section Kontrak
        $sheet->mergeCells('C8:E8');
        $sheet->getStyle('C8')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('C8')->setValue('Kontrak');
        $sheet->getStyle('C8:E8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("C8:E8")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("E8")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("C8")->applyFromArray($this->border('left', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Volume
        $sheet->mergeCells('C9:C10');
        $sheet->getStyle('C9')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('C9')->setValue('Volume');
        $sheet->getStyle('C9:C10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("C9:C10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("C9:C10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Satuan
        $sheet->mergeCells('D9:D10');
        $sheet->getStyle('D9')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('D9')->setValue('Sat');
        $sheet->getStyle('D9:D10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("D9:D10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("D9:D10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Bobot
        $sheet->mergeCells('E9:E10');
        $sheet->getStyle('E9')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('E9')->setValue('Bobot (%)');
        $sheet->getStyle('E9:E10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("E9:E10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("E9:E10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Progress
        $sheet->mergeCells('F8:N8');
        $sheet->getStyle('F8')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('F8')->setValue('Progress');
        $sheet->getStyle('F8:N8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("F8:N8")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("N8")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Minggu Lalu
        $sheet->mergeCells('F9:H9');
        $sheet->getStyle('F9')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('F9')->setValue('Minggu Lalu');
        $sheet->getStyle('F9:H9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("F9:H9")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("H9")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Minggu Lalu : Volume
        $sheet->getStyle('F10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('F10')->setValue('Volume');
        $sheet->getStyle('F10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("F10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("F10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Minggu Lalu : Progress
        $sheet->getStyle('G10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('G10')->setValue('Progress (%)');
        $sheet->getStyle('G10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("G10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("G10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Minggu Lalu : Bobot
        $sheet->getStyle('H10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('H10')->setValue('Bobot (%)');
        $sheet->getStyle('H10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("H10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("H10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Minggu Ini
        $sheet->mergeCells('I9:K9');
        $sheet->getStyle('I9')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('I9')->setValue('Minggu Ini');
        $sheet->getStyle('I9:K9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("I9:K9")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("K9")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Minggu Ini : Volume
        $sheet->getStyle('I10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('I10')->setValue('Volume');
        $sheet->getStyle('I10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("I10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("I10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Minggu Ini : Progress
        $sheet->getStyle('J10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('J10')->setValue('Progress (%)');
        $sheet->getStyle('J10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("J10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("J10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section Minggu Ini : Bobot
        $sheet->getStyle('K10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('K10')->setValue('Bobot (%)');
        $sheet->getStyle('K10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("K10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("K10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section S/D Minggu Ini
        $sheet->mergeCells('L9:N9');
        $sheet->getStyle('L9')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('L9')->setValue('S/D Minggu Ini');
        $sheet->getStyle('L9:N9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("L9:N9")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("N9")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section S/D Minggu Ini : Volume
        $sheet->getStyle('L10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('L10')->setValue('Volume');
        $sheet->getStyle('L10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("L10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("L10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section S/D Minggu Ini : Progress
        $sheet->getStyle('M10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('M10')->setValue('Progress (%)');
        $sheet->getStyle('M10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("M10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("M10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section S/D Minggu Ini : Bobot
        $sheet->getStyle('N10')->applyFromArray(array_merge($this->font(true, '11', 'Times New Roman'), $this->alignmentCenter()));
        $sheet->getCell('N10')->setValue('Bobot (%)');
        $sheet->getStyle('N10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ACB9CA');
        $sheet->getStyle("N10")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));
        $sheet->getStyle("N10")->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_MEDIUM, '000000'));

        //Section space kosong
        $sheet->getStyle("A11:N11")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
        $huruf_awal = 'a';
        $huruf_akhir = 'o';
        $baris = '11';
        while ($huruf_awal != $huruf_akhir) {
            $sheet->getStyle($huruf_awal . $baris)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));
            $huruf_awal = chr(ord($huruf_awal) + 1);
        }
    }

    function generateData($model, &$objPHPExcel, &$sheet, &$row = 12, &$no = 1, $abjad = "A", $tingkat = 1)
    {
        $new = true;

        foreach ($model as $key => $parent) {
            if ($parent->getChildren()->andWhere(['flag' => 1])->count() != 0) {

                if ($new && $key == 0 && $tingkat != 1) {
                    $abjad .= ".1";
                    $new = false;
                }

                $abjad = explode(".", $abjad);
                if ($tingkat != 1 && $key != 0) {
                    $abjad[count($abjad) - 1] = ++$abjad[count($abjad) - 1];
                }

                $abjad = implode(".", $abjad);
                $no = 1;

                //section urutan parent pekerjaan
                $sheet->getStyle('A' . $row)->applyFromArray($this->font(true, '11', 'Times New Roman'));
                $sheet->getCell('A' . $row)->setValue($abjad);
                $sheet->getStyle('A' . $row)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                $sheet->getStyle('A' . $row)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));

                //section nama parent pekerjaan
                $sheet->getStyle('B' . $row)->applyFromArray($this->font(true, '11', 'Times New Roman'));
                $sheet->getCell('B' . $row)->setValue($parent->item);
                $sheet->getStyle('B' . $row)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                $sheet->getStyle('B' . $row)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));

                //Garis
                $sheet->getStyle("A11:N11")->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                $huruf_awal = 'C';
                $huruf_akhir = 'O';
                $baris = $row;
                while ($huruf_awal != $huruf_akhir) {
                    $sheet->getStyle($huruf_awal . $baris)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                    $sheet->getStyle($huruf_awal . $baris)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                    $sheet->getStyle($huruf_awal . $baris)->applyFromArray($this->border('top', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                    $huruf_awal = chr(ord($huruf_awal) + 1);
                }

                $row += 1;

                $this->generateData($parent->getChildren()->andWhere(['flag' => 1])->all(), $objPHPExcel, $sheet, $row, $no, $abjad, $tingkat + 1);
                $no = 1;
                if ($tingkat == 1) {
                    $abjad = explode(".", $abjad);
                    $abjad = ++$abjad[0];
                }
            } else {
                //Garis
                $huruf_awal = 'A';
                $huruf_akhir = 'O';
                $baris = $row;
                while ($huruf_awal != $huruf_akhir) {
                    $sheet->getStyle($huruf_awal . $baris)->applyFromArray($this->border('right', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                    $sheet->getStyle($huruf_awal . $baris)->applyFromArray($this->border('bottom', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                    $sheet->getStyle($huruf_awal . $baris)->applyFromArray($this->border('top', PHPExcel_Style_Border::BORDER_THIN, '000000'));
                    $huruf_awal = chr(ord($huruf_awal) + 1);
                }
                //section no
                $sheet->getStyle('A' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('A' . $row)->setValue($no);

                //section sub nama pekerjaan
                $sheet->getStyle('B' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('B' . $row)->setValue($parent->item);

                //section volume
                $sheet->getStyle('C' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('C' . $row)->setValue($parent->volume);

                //section satuan
                $sheet->getStyle('D' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('D' . $row)->setValue($parent->satuan->nama);

                //section bobot
                $sheet->getStyle('E' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('E' . $row)->setValue($parent->bobot);

                $stat_sd_minggu_lalu = $parent->sdMingguLalu($this->date);
                $stat_minggu_ini = $parent->mingguIni($this->date);
                $stat_sd_minggu_ini = $parent->sdMingguIni($this->date);


                //section volume
                $sheet->getStyle('F' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('F' . $row)->setValue($stat_sd_minggu_lalu[0]); # nilai dari data per satuan waktu

                //section satuan
                $sheet->getStyle('G' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('G' . $row)->setValue($stat_sd_minggu_lalu[1] . "%"); # nilai dari data per satuan waktu

                //section bobot
                $sheet->getStyle('H' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('H' . $row)->setValue($stat_sd_minggu_lalu[2] . "%"); # nilai dari data per satuan waktu


                //section volume
                $sheet->getStyle('I' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('I' . $row)->setValue($stat_minggu_ini[0]); # nilai dari data per satuan waktu

                //section satuan
                $sheet->getStyle('J' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('J' . $row)->setValue($stat_minggu_ini[1] . "%"); # nilai dari data per satuan waktu

                //section bobot
                $sheet->getStyle('K' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('K' . $row)->setValue($stat_minggu_ini[2] . "%"); # nilai dari data per satuan waktu


                //section volume
                $sheet->getStyle('L' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('L' . $row)->setValue($stat_sd_minggu_ini[0]); # nilai dari data per satuan waktu

                //section satuan
                $sheet->getStyle('M' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('M' . $row)->setValue($stat_sd_minggu_ini[1] . "%"); # nilai dari data per satuan waktu

                //section bobot
                $sheet->getStyle('N' . $row)->applyFromArray($this->font(false, '11', 'Times New Roman'));
                $sheet->getCell('N' . $row)->setValue($stat_sd_minggu_ini[2] . "%"); # nilai dari data per satuan waktu


                $no++;
            }
            $row++;
        }
    }
}

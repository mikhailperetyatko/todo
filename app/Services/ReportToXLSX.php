<?php

namespace App\Services;
use PHPExcel;
use Illuminate\Support\Facades\Storage;

class ReportToXLSX
{
    protected $document;
    protected $sheet;
    protected $currentX = 0;
    protected $currentY = 1;
    protected $currentCellStyle = [];
        
    public function __construct(array $autoSizeColumn = ['a', 'b'])
    {
        $this->document = new PHPExcel();
        $this->sheet = $this->document->setActiveSheetIndex(0);
        foreach ($autoSizeColumn as $column) {
            $this->sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    protected function putToCell($value)
    {
        $this->setStyle()->setCellValueByColumnAndRow($this->currentX, $this->currentY, $value);
        return $this;
    }
    
    protected function setStyle()
    {
        $this->sheet->getStyleByColumnAndRow($this->currentX, $this->currentY)->applyFromArray($this->currentCellStyle);
        return $this->sheet;
    }
    
    protected function resetStyle()
    {
        $this->currentCellStyle = [];
        return $this;
    }
    
    protected function increaseX()
    {
        $this->currentX++;
        return $this;
    }
    
    protected function increaseY()
    {
        $this->currentY++;
        return $this;
    }
    
    protected function resetX()
    {
        $this->currentX = 0;
        return $this;
    }
    
    protected function resetY()
    {
        $this->currentY = 1;
        return $this;
    }
    
    protected function withHorizontalCenterAligment()
    {   
        $this->currentCellStyle['alignment']['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
        return $this;
    }
    
    protected function withVerticalCenterAligment()
    {   
        $this->currentCellStyle['alignment']['vertical'] = \PHPExcel_Style_Alignment::VERTICAL_CENTER;
        return $this;
    }
    
    protected function withWrap()
    {   
        $this->currentCellStyle['alignment']['wrap'] = true;
        return $this;
    }
    
    protected function withBoldFont()
    {
        $this->currentCellStyle['font']['bold'] = true;
        return $this;
    }
    
    protected function withUnderline()
    {
        $this->currentCellStyle['font']['underline'] = \PHPExcel_Style_Font::UNDERLINE_DOUBLE;
        return $this;
    }
    
    protected function withAllBorder()
    {
        $this->currentCellStyle['borders'] = [
            'bottom' => [
        		'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],
        	'top' => [
        		'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],
            'left' => [
        		'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],
            'right' => [
        		'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],   
        ];
        
        return $this;
    }
    
    protected function withAutoSize()
    {
        $this->sheet->getColumnDimension('abcdefghijklmnopqrstuvwxyz'[$this->currentX])->setAutoSize(true);
        return $this;
    }
    
    protected function withMergeCells($increaseX, $increaseY)
    {
        $this->sheet->mergeCellsByColumnAndRow($this->currentX, $this->currentY, $this->currentX + $increaseX, $this->currentY + $increaseY);
        return $this;
    }
    
    public function putTitle(string $title)
    {
        $this
            ->withMergeCells(1, 0)
            ->withHorizontalCenterAligment()
            ->withVerticalCenterAligment()
            ->withBoldFont()
            ->withUnderline()
            ->putToCell($title)
        ;
        
        return $this->resetStyle()->increaseY();
    }
    
    public function putHeader(array $columns)
    {
        $this
            ->withHorizontalCenterAligment()
            ->withBoldFont()
            ->withAllBorder()
            ->putRow($columns)
        ;
        return $this->resetStyle();
    }
    
    public function putRowWithStandartStyle(array $columns)
    {
        $this
            ->withAllBorder()
            ->putRow($columns)
        ;
    }
    
    public function putRow(array $columns)
    {
        $this->withWrap();
        foreach ($columns as $column) {
            $this
                ->putToCell($column)
                ->increaseX()
            ;
        }
        
        $this
            ->resetX()
            ->increaseY()
        ;
            
        return $this;
    }
    
    public function save($file)
    {
        $objWriter = \PHPExcel_IOFactory::createWriter($this->document, 'Excel2007');
        if (! in_array(config('filesystems.disks.reportsStorage.dirname'), Storage::directories())) Storage::makeDirectory(config('filesystems.filesystems.disks.reportsStorage.dirname'));
        $objWriter->save(config('filesystems.disks.reportsStorage.root') . '/' . $file);
    }
}
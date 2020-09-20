<?php
namespace LeaseWeb\ExcelBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ExcelParserService extends Controller
{
	

	public function fetchLocationList($spreadsheet)
	{
		$data = $this->createDataFromSpreadsheet($spreadsheet);
	}

	protected function createDataFromSpreadsheet($spreadsheet)
	{
	    $data = [];
	    foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
	        $worksheetTitle = $worksheet->getTitle();
	        $data[$worksheetTitle] = [
	            'columnNames' => [],
	            'columnValues' => [],
	        ];
	        foreach ($worksheet->getRowIterator() as $row) {
	            $rowIndex = $row->getRowIndex();
	            if ($rowIndex > 2) {
	                $data[$worksheetTitle]['columnValues'][$rowIndex] = [];
	            }
	            $cellIterator = $row->getCellIterator();
	            $cellIterator->setIterateOnlyExistingCells(false); // Loop over all cells, even if it is not set
	            foreach ($cellIterator as $cell) {
	                if ($rowIndex === 2) {
	                    $data[$worksheetTitle]['columnNames'][] = $cell->getCalculatedValue();
	                }
	                if ($rowIndex > 2) {
	                    $data[$worksheetTitle]['columnValues'][$rowIndex][] = $cell->getCalculatedValue();
	                }
	            }
	        }
	    }

	    return $data;
	}
}

?>
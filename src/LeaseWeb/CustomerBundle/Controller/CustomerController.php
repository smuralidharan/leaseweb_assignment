<?php

namespace LeaseWeb\CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use LeaseWeb\ExcelBundle\Services\ExcelParserService;


class CustomerController extends Controller
{
	private $excelParserService;

	public function __construct(ExcelParserService $excelParserService)
	{
		$this->excelParserService = $excelParserService;
	}

    public function indexAction(Request $request)
    {
    	$reader = $this->get('phpspreadsheet')->createReader('Xlsx');
    	$spreadsheet = $reader->load("LeaseWeb.xlsx");
    	$locationList = $this->excelParserService->fetchLocationList($spreadsheet);
    	$form = $this->createFormBuilder()
	        ->add('range', ChoiceType::class, [
	        	'required' => false,
	        	'choices'  => [
			        'Select' => "",
			        '250GB' => "250GB",
			        '500GB' => "500GB",
			        '1TB' => "1TB",
			        '2TB' => "2TB",
			        '3TB' => "3TB",
			        '4TB' => "4TB",
			        '8TB' => "8TB",
			        '12TB' => "12TB",
			        '24TB' => "24TB",
			        '48TB' => "48TB",
			        '72TB' => "72TB",
			    ],
	        ])
	        ->add('ram', ChoiceType::class, [
	        	'required' => false,
	        	'choices'  => [
	        		'Select' => "",
			        '2GB' => "2GB",
			        '4GB' => "4GB",
			        '8GB' => "8GB",
			        '12GB' => "12GB",
			        '16GB' => "16GB",
			        '24GB' => "24GB",
			        '32GB' => "32GB",
			        '48GB' => "48GB",
			        '64GB' => "64GB",
			        '96GB' => "96GB",
			    ],
	        ])
	        ->add('harddisk', ChoiceType::class, [
	        	'required' => false,
	        	'choices'  => [
	        		'Select' => "",
			        'SAS' => "SAS",
			        'SATA' => "SATA",
			        'SSD' => "SSD",
			    ],
	        ])
	        ->add('location', ChoiceType::class, [
	        	'required' => false,
	        	'choices'  => [
	        		'Select' => "",
			        'AMSTERDAM' => "AMSTERDAM",
			    ],
	        ])
	        ->add('Search', SubmitType::class, [
    			'attr' => ['class' => 'save'],
			])
	        ->getForm();
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        // data is an array with "name", "email", and "message" keys
	        $data = $form->getData();
	        echo "<pre>";
	        print_r($data);
    	}
        return $this->render('@LeaseWebCustomerBundle/Default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

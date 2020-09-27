<?php

namespace LeaseWeb\CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use LeaseWeb\ExcelBundle\Services\ExcelParserService;
use GuzzleHttp\Client;



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
    	$excelData = $this->excelParserService->fetchLocationList($spreadsheet);
    	$locations = [];
    	if(!empty($excelData['Sheet2']))
    	{
    		foreach ($excelData['Sheet2'] as $key => $value) {
    			if(trim($key) == 'columnValues')
    			{
    				foreach ($value as $colKey => $colValue) {
    					$locations[] = $colValue[3];
    				}
    			}
    		}
    	}
    	$locations = array_combine($locations, $locations);
    	$locations = ['Select' => ""] + $locations;
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
	        	'choices'  => $locations,
	        ])
	        ->add('Search', SubmitType::class, [
    			'attr' => ['class' => 'btn btn-primary btn-search'],
			])
	        ->getForm();
	    $form->handleRequest($request);
	    
        return $this->render('@LeaseWebCustomerBundle/Default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function storeInfoAction(Request $request)
    {
    	$searchData = $request->request->all();
    	if(!empty($searchData))
    	{
    		$url = $request->getSchemeAndHttpHost()."/register";
    		$client = new \GuzzleHttp\Client();
    		$requestData = ["username" => "LeaseWeb", "password" => "123456"];
    		$response = $client->post($url, [ 'body' => json_encode($requestData) ]);

			echo $response->getContent(); // 200
			die;
    	}
    }
}

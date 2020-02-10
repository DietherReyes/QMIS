<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\CustomerSatisfactionMeasurementSummary;

class SpreadsheetsController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');        
    }



    private function overall_summary_report(&$spreadsheet, $year){

        //General Style
            $spreadsheet->setActiveSheetIndex(0);
            $overall_summary = $spreadsheet->getActiveSheet();
            
            $overall_summary->getStyle('A1:G999')->getAlignment()->setWrapText(true);
            $overall_summary->getStyle('A1:G999')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); 
            $overall_summary->getStyle('A1:G999')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $overall_summary->getColumnDimension('A')->setWidth(45);
            $overall_summary->getColumnDimension('B')->setWidth(12);
            $overall_summary->getColumnDimension('C')->setWidth(12);
            $overall_summary->getColumnDimension('D')->setWidth(12);
            $overall_summary->getColumnDimension('E')->setWidth(12);
            $overall_summary->getColumnDimension('G')->setWidth(25);
        
        //End General Style


                                //TABLE1

        //header

            $overall_summary->mergeCells('A1:G1');
            $overall_summary->mergeCells('A2:G2');
            $overall_summary->mergeCells('A3:G3');

            $header = [
                ['Department of Science and Technology - CALABARZON Region '],
                ['OVERALL SUMMARY OF CUSTOMER SATISFACTION MEASUREMENT'],
                ['January to December ' . $year]
            ];
            $overall_summary->fromArray($header, NULL, 'A1');

            //Header Style
                $overall_summary->getStyle('A1:A2')->getFont()->setBold(TRUE);
        //End Header

                               

        //Column Names
            $overall_summary->getRowDimension('5')->setRowHeight(20);
            $overall_summary->getRowDimension('6')->setRowHeight(40);
            $overall_summary->mergeCells('C5:F5');
            $overall_summary->mergeCells('A5:A6');
            $overall_summary->mergeCells('B5:B6');
            $overall_summary->mergeCells('G5:G6');

            $column_names = [
                [
                    'Service/Center/ Provincial Office/ Division/Unit', 
                    'No. of Customers/ Responses',
                    'Average Rating',
                    NULL,
                    NULL,
                    NULL,
                    'Adjectival Rating'
                ],
                [
                    NULL,
                    NULL,
                    'Response Delivery', 
                    'Work Quality',
                    'Personnels Quality',
                    'Overall Rating'
                ]
            ];
            $overall_summary->fromArray($column_names, NULL, 'A5');
            

            //Column Names Style
            $column_style = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'abadb0',
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ],
                ]

            ];
            $overall_summary->getStyle('A5:G6')->applyFromArray($column_style);
            

        //End Column Names

        //Data
            $start_data = 7; //Top left coordinate of the worksheet range where we want to set these values at Column A.
            $data = [];
            $total_customer = 0;
            $response_delivery = 0;
            $work_quality = 0;
            $personnels_quality = 0;
            $overall_rating = 0;
            $count = 0;
            $csm_summaries = CustomerSatisfactionMeasurementSummary::where('year', $year)->orderBy('functional_unit', 'ASC')->get();

            foreach ($csm_summaries as $csm) {
                $temp = [];
                array_push($temp, $csm->functional_unit);
                array_push($temp, $csm->total_customer);
                array_push($temp, $csm->response_delivery);
                array_push($temp, $csm->work_quality);
                array_push($temp, $csm->personnels_quality);
                array_push($temp, $csm->overall_rating);
                array_push($temp, $csm->adjectival_rating);

                $total_customer += $csm->total_customer;
                $response_delivery += $csm->response_delivery;
                $work_quality += $csm->work_quality;
                $personnels_quality += $csm->personnels_quality;
                $overall_rating += $csm->overall_rating;

                array_push($data, $temp);
                $count++;
            }

            $temp = [
                'Total Customers / Average Rating',
                $total_customer,
                number_format($response_delivery / $count, 2, '.', ''),
                number_format($work_quality / $count, 2, '.', ''),
                number_format($personnels_quality / $count, 2, '.', ''),
                number_format($overall_rating / $count, 2, '.', ''),
                NULL //adjectival rating
            ];
            
            array_push($data, $temp);
            $overall_summary->fromArray($data, NULL, 'A' . $start_data);

            //Data Style
            $end_row_style = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'abadb0',
                    ]
                ]
            ];

            $table_border = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ],
                ]
            ];

            $end_data = $start_data + $count;
            $overall_summary->getStyle('A'.$end_data.':G'.$end_data)->applyFromArray($end_row_style);
            $overall_summary->getStyle('A5'.':G'.$end_data)->applyFromArray($table_border);
            $overall_summary->getStyle('A'.$start_data.':A'. ($end_data - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $overall_summary->getStyle('A'. $end_data)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        //End Data

                                    //TABLE 2

        $start_header = $end_data + 2;
        //header
            $overall_summary->mergeCells('A'. $start_header . ':F' . $start_header);
            $header = [
                ['QUARTERLY CUSTOMER PERCEPTION OF DOST 4A SERVICES FOR ' . $year]
            ];
            $overall_summary->fromArray($header, NULL, 'A' . $start_header);

            //Header Style
                $overall_summary->getStyle('A' . $start_header)->getFont()->setBold(TRUE);
        //End Header

        $start_column = $start_header + 1;
        //Column Names

            $column_names = [
                [
                    'Service/Center/ Provincial Office/ Division/Unit',
                    '1st QTR',
                    '2nd QTR',
                    '3rd QTR',
                    '4th QTR',
                    'Average',
                ]
            ];
            $overall_summary->fromArray($column_names, NULL, 'A' . $start_column);
            

            //Column Names Style
            $column_style = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'abadb0',
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ],
                ]

            ];
            $overall_summary->getStyle('A' . $start_column . ':F' . $start_column)->applyFromArray($column_style);
            
        //End Column Names


        //Data
        $start_data = $start_column + 1; //Top left coordinate of the worksheet range where we want to set these values at Column A.
        $data = [];
        $q1 = 0;
        $q2 = 0;
        $q3 = 0;
        $q4 = 0;
        $overall_rating = 0;
        $count = 0;
        $csm_summaries = CustomerSatisfactionMeasurementSummary::where('year', $year)->orderBy('functional_unit', 'ASC')->get();

        foreach ($csm_summaries as $csm) {
            $temp = [];
            array_push($temp, $csm->functional_unit);
            array_push($temp, $csm->q1_overall_rating);
            array_push($temp, $csm->q2_overall_rating);
            array_push($temp, $csm->q3_overall_rating);
            array_push($temp, $csm->q4_overall_rating);
            array_push($temp, $csm->overall_rating);
            
            $q1 += $csm->q1_overall_rating;
            $q2 += $csm->q2_overall_rating;
            $q3 += $csm->q3_overall_rating;
            $q4 += $csm->q4_overall_rating;
            $overall_rating += $csm->overall_rating;
            array_push($data, $temp);
            $count++;
        }

        $temp = [
            'Average Rating Per Quarter',
            number_format($q1 / $count, 2, '.', ''),
            number_format($q2 / $count, 2, '.', ''),
            number_format($q3 / $count, 2, '.', ''),
            number_format($q4 / $count, 2, '.', ''),
            number_format($overall_rating / $count, 2, '.', '')
        ];
        
        array_push($data, $temp);
        $overall_summary->fromArray($data, NULL, 'A' . $start_data);

        //Data Style
        $end_row_style = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'abadb0',
                ]
            ]
        ];

        $table_border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ]
        ];

        $end_data = $start_data + $count;
        $overall_summary->getStyle('A'.$end_data.':F'.$end_data)->applyFromArray($end_row_style);
        $overall_summary->getStyle('A'.$start_column.':F'.$end_data)->applyFromArray($table_border);
        $overall_summary->getStyle('A'.$start_data.':A'. ($end_data - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $overall_summary->getStyle('A'. $end_data)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    //End Data
            
    }

    /**
     * Generate CSM Report of a given year
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {

        $storage_path = public_path('storage/downloads/');
        $spreadsheet = new Spreadsheet();
        

        $overall_summary = new Worksheet($spreadsheet, 'Overall Summary' . $request->year);
        $comparison = new Worksheet($spreadsheet, 'Comparison with ' . ($request->year - 1));
        $trends = new Worksheet($spreadsheet, 'Trends');


        $spreadsheet->addSheet($overall_summary, 0);
        $spreadsheet->addSheet($comparison, 1);
        $spreadsheet->addSheet($trends, 2);
        $spreadsheet->removeSheetByIndex(3);
        
        $this->overall_summary_report($spreadsheet, $request->year);

       
       


        $writer = new Xlsx($spreadsheet);
        $writer->save($storage_path.'CSM Report' . $request->year . '.xlsx');
        return Storage::download('public/downloads/'.'CSM Report' . $request->year . '.xlsx');
    }

}

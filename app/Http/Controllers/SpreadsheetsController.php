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
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\CustomerSatisfactionMeasurementSummary;
use App\Signatory;

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
            $overall_summary->getColumnDimension('F')->setWidth(12);
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

    //Bar Chart

        $start_header = $end_data + 2;

        //Header
        $overall_summary->mergeCells('A'.$start_header.':G'.$start_header);
        $header = [
            ['Customer Satisfaction Measurement January to December ' . $year]
        ];
        $overall_summary->fromArray($header, NULL, 'A' . $start_header);
        $overall_summary->getStyle('A' . $start_header)->getFont()->setBold(TRUE);

        
        // Set the Labels for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $cell_ref = 'Worksheet!$F$6';
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $cell_ref , null, 1),
        ];
        // Set the X-Axis Labels
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $cell_ref = 'Worksheet!$A$' . $start_data . ':$A$' . ($end_data -1 ); 
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $cell_ref, null, 4), 
        ];
        // Set the Data values for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $cell_ref = 'Worksheet!$F$' . $start_data . ':$F$' . ($end_data - 1); 
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $cell_ref, null, 4), 
        ];
        // $dataSeriesValues[2]->setLineWidth(60000);

        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            NULL, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );
        $layout = new Layout();
        $layout->setShowVal(true);
        // Set the series in the plot area
        $plotArea = new PlotArea($layout, [$series]);
        // Set the chart legend
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

        $title = new Title('Customer Satisfaction Measurement January to December ' . $year);
        $xAxisLabel = new Title('FUNCTIONAL UNIT / SERVICE');
        $yAxisLabel = new Title('CUSTOMER SATISFACTION RATING');

        // Create the chart
        $chart = new Chart(
            'CSM Chart', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            $xAxisLabel, // xAxisLabel
            $yAxisLabel  // yAxisLabel
        );

        $start_bar = $start_header + 1;
        $end_bar = $start_bar + 20;

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A' . $start_bar);
        $chart->setBottomRightPosition('H' . $end_bar);

        // Add the chart to the worksheet
        $overall_summary->addChart($chart);

    //End Bar Chart
    
    //Signatories
        $storage_path = public_path('storage/signature_photos/');
        $start_sig = $end_bar + 3;

        // $positions = [
        //     'ARD for Technical Operations',              
        //     'ARD Finance and Administrative Services',  
        //     'Quality Core Team Leader',                  
        //     'Regional Director'                         
        // ];
            
        //quality core team leader
        try {
            
            $signatory = Signatory::where('position', 'Quality Core Team Leader' )->get()[0];
            error_log($signatory->name);
            $overall_summary->setCellValue('A' . $start_sig, 'Evaluated By:');
            $overall_summary->setCellValue('A' . ($start_sig + 2), $signatory->name);
            $overall_summary->setCellValue('A' . ($start_sig + 3), $signatory->position);

            $drawing = new Drawing();
            $drawing->setName('Signature');
            $drawing->setDescription('Signature');
            $drawing->setPath($storage_path . $signatory->signature_photo);
            $drawing->setWidthAndHeight(300, 300);
            $drawing->setOffsetX(45);
            $drawing->setCoordinates('A' . ($start_sig - 2));
            $drawing->setWorksheet($overall_summary);

            //style
            $overall_summary->getStyle('A' . ($start_sig + 2))->getFont()->setBold(TRUE);
            $overall_summary->getStyle('A' . $start_sig)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        } catch (\Throwable $th) {
            error_log('Signatory for Quality Core Team Leader not found.');
        }


        //ARD for Technical Operation
        try {
            
            $overall_summary->mergeCells('B' . ($start_sig + 2) . ':D' . ($start_sig + 2));
            $overall_summary->mergeCells('B' . ($start_sig + 3) . ':D' . ($start_sig + 3));

            $signatory = Signatory::where('position', 'ARD for Technical Operations' )->get()[0];
            error_log($signatory->name);
            $overall_summary->setCellValue('B' . $start_sig, 'Noted By:');
            $overall_summary->setCellValue('B' . ($start_sig + 2), $signatory->name);
            $overall_summary->setCellValue('B' . ($start_sig + 3), $signatory->position);

            $drawing = new Drawing();
            $drawing->setName('Signature');
            $drawing->setDescription('Signature');
            $drawing->setPath($storage_path . $signatory->signature_photo);
            $drawing->setWidthAndHeight(300, 300);
            $drawing->setOffsetX(45);
            $drawing->setCoordinates('B' . ($start_sig - 2));
            $drawing->setWorksheet($overall_summary);

            //style
            $overall_summary->getStyle('B' . ($start_sig + 2))->getFont()->setBold(TRUE);
            $overall_summary->getStyle('B' . $start_sig)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        } catch (\Throwable $th) {
            error_log('Signatory for ARD for Technical Operations not found.');
        }


         //Regional Director
         try {
            
            $overall_summary->mergeCells('F' . ($start_sig + 2) . ':H' . ($start_sig + 2));
            $overall_summary->mergeCells('F' . ($start_sig + 3) . ':H' . ($start_sig + 3));

            $signatory = Signatory::where('position', 'Regional Director' )->get()[0];
            error_log($signatory->name);
            $overall_summary->setCellValue('F' . $start_sig, 'Evaluated By:');
            $overall_summary->setCellValue('F' . ($start_sig + 2), $signatory->name);
            $overall_summary->setCellValue('F' . ($start_sig + 3), $signatory->position);

            $drawing = new Drawing();
            $drawing->setName('Signature');
            $drawing->setDescription('Signature');
            $drawing->setPath($storage_path . $signatory->signature_photo);
            $drawing->setWidthAndHeight(300, 300);
            $drawing->setOffsetX(45);
            $drawing->setCoordinates('F' . ($start_sig - 2));
            $drawing->setWorksheet($overall_summary);

            //style
            $overall_summary->getStyle('F' . ($start_sig + 2))->getFont()->setBold(TRUE);
            $overall_summary->getStyle('F' . $start_sig)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        } catch (\Throwable $th) {
            error_log('Signatory for ARD for Technical Operations not found.');
        }

        
         //ARD for FAS
         try {
            
            $overall_summary->mergeCells('B' . ($start_sig + 6) . ':D' . ($start_sig + 6));
            $overall_summary->mergeCells('B' . ($start_sig + 7) . ':D' . ($start_sig + 7));

            $signatory = Signatory::where('position', 'ARD Finance and Administrative Services' )->get()[0];
            error_log($signatory->name);
            $overall_summary->setCellValue('B' . ($start_sig + 6), $signatory->name);
            $overall_summary->setCellValue('B' . ($start_sig + 7), $signatory->position);

            $drawing = new Drawing();
            $drawing->setName('Signature');
            $drawing->setDescription('Signature');
            $drawing->setPath($storage_path . $signatory->signature_photo);
            $drawing->setWidthAndHeight(300, 300);
            $drawing->setOffsetX(45);
            $drawing->setCoordinates('B' . ($start_sig + 2 ));
            $drawing->setWorksheet($overall_summary);

            //style
            $overall_summary->getStyle('B' . ($start_sig + 6))->getFont()->setBold(TRUE);
            $overall_summary->getStyle('B' . $start_sig)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        } catch (\Throwable $th) {
            error_log('Signatory for ARD for Technical Operations not found.');
        }
        

    //End Signatories

    }


    private function comparison_report(&$spreadsheet, $year){
        
        

    }

    /**
     * Generate CSM Report of a given year
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Note:
     *      Code for chart generation referenced from https://github.com/PHPOffice/PhpSpreadsheet/tree/master/samples/Chart
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
        $this->comparison_report($spreadsheet, $request->year);
       
       


        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->save($storage_path.'CSM Report' . $request->year . '.xlsx');
        return Storage::download('public/downloads/'.'CSM Report' . $request->year . '.xlsx');
    }

}

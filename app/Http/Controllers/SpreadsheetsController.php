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
use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\CustomerSatisfactionMeasurementSummary;
use App\Signatory;

class SpreadsheetsController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');        
    }

    //Add signatories at the end of the sheet
    private function add_signatory($end, $active_sheet){
        //Signatories
            $storage_path = public_path('storage/signature_photos/');
            $start_sig = $end + 3;

            // $positions = [
            //     'ARD for Technical Operations',              
            //     'ARD Finance and Administrative Services',  
            //     'Quality Core Team Leader',                  
            //     'Regional Director'                         
            // ];
                
            //quality core team leader
            try {
                
                $signatory = Signatory::where('position', 'Quality Core Team Leader' )->get()[0];
                
                $active_sheet->setCellValue('A' . $start_sig, 'Evaluated By:');
                $active_sheet->setCellValue('A' . ($start_sig + 2), $signatory->name);
                $active_sheet->setCellValue('A' . ($start_sig + 3), $signatory->position);

                $drawing = new Drawing();
                $drawing->setName('Signature');
                $drawing->setDescription('Signature');
                $drawing->setPath($storage_path . $signatory->signature_photo);
                $drawing->setWidthAndHeight(300, 300);
                $drawing->setOffsetX(45);
                $drawing->setCoordinates('A' . ($start_sig - 2));
                $drawing->setWorksheet($active_sheet);

                //style
                $active_sheet->getStyle('A' . ($start_sig + 2))->getFont()->setBold(TRUE);
                $active_sheet->getStyle('A' . $start_sig)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            } catch (\Throwable $th) {
                error_log('Signatory for Quality Core Team Leader not found.');
            }


            //ARD for Technical Operation
            try {
                
                $active_sheet->mergeCells('B' . ($start_sig + 2) . ':D' . ($start_sig + 2));
                $active_sheet->mergeCells('B' . ($start_sig + 3) . ':D' . ($start_sig + 3));

                $signatory = Signatory::where('position', 'ARD for Technical Operations' )->get()[0];
                
                $active_sheet->setCellValue('B' . $start_sig, 'Noted By:');
                $active_sheet->setCellValue('B' . ($start_sig + 2), $signatory->name);
                $active_sheet->setCellValue('B' . ($start_sig + 3), $signatory->position);

                $drawing = new Drawing();
                $drawing->setName('Signature');
                $drawing->setDescription('Signature');
                $drawing->setPath($storage_path . $signatory->signature_photo);
                $drawing->setWidthAndHeight(300, 300);
                $drawing->setOffsetX(45);
                $drawing->setCoordinates('B' . ($start_sig - 2));
                $drawing->setWorksheet($active_sheet);

                //style
                $active_sheet->getStyle('B' . ($start_sig + 2))->getFont()->setBold(TRUE);
                $active_sheet->getStyle('B' . $start_sig)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            } catch (\Throwable $th) {
                error_log('Signatory for ARD for Technical Operations not found.');
            }


            //Regional Director
            try {
                
                $active_sheet->mergeCells('E' . ($start_sig + 2) . ':G' . ($start_sig + 2));
                $active_sheet->mergeCells('E' . ($start_sig + 3) . ':G' . ($start_sig + 3));

                $signatory = Signatory::where('position', 'Regional Director' )->get()[0];
                
                $active_sheet->setCellValue('E' . $start_sig, 'Evaluated By:');
                $active_sheet->setCellValue('E' . ($start_sig + 2), $signatory->name);
                $active_sheet->setCellValue('E' . ($start_sig + 3), $signatory->position);

                $drawing = new Drawing();
                $drawing->setName('Signature');
                $drawing->setDescription('Signature');
                $drawing->setPath($storage_path . $signatory->signature_photo);
                $drawing->setWidthAndHeight(300, 300);
                $drawing->setOffsetX(45);
                $drawing->setCoordinates('E' . ($start_sig - 2));
                $drawing->setWorksheet($active_sheet);

                //style
                $active_sheet->getStyle('E' . ($start_sig + 2))->getFont()->setBold(TRUE);
                $active_sheet->getStyle('E' . $start_sig)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            } catch (\Throwable $th) {
                error_log('Signatory for ARD for Technical Operations not found.');
            }

            
            //ARD for FAS
            try {
                
                $active_sheet->mergeCells('B' . ($start_sig + 6) . ':D' . ($start_sig + 6));
                $active_sheet->mergeCells('B' . ($start_sig + 7) . ':D' . ($start_sig + 7));

                $signatory = Signatory::where('position', 'ARD Finance and Administrative Services' )->get()[0];
                
                $active_sheet->setCellValue('B' . ($start_sig + 6), $signatory->name);
                $active_sheet->setCellValue('B' . ($start_sig + 7), $signatory->position);

                $drawing = new Drawing();
                $drawing->setName('Signature');
                $drawing->setDescription('Signature');
                $drawing->setPath($storage_path . $signatory->signature_photo);
                $drawing->setWidthAndHeight(300, 300);
                $drawing->setOffsetX(45);
                $drawing->setCoordinates('B' . ($start_sig + 2 ));
                $drawing->setWorksheet($active_sheet);

                //style
                $active_sheet->getStyle('B' . ($start_sig + 6))->getFont()->setBold(TRUE);
                $active_sheet->getStyle('B' . $start_sig)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            } catch (\Throwable $th) {
                error_log('Signatory for ARD for Technical Operations not found.');
            }
        

        //End Signatories
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

                array_push($data, $temp);
                $count++;
            }

            $end_data = $start_data + $count;

            $temp = [
                'Total Customers / Average Rating',
                '=SUM(B'. $start_data . ':B' . ($end_data - 1 ) .')',
                '=AVERAGE(C'. $start_data . ':C' . ($end_data - 1 ) .')',
                '=AVERAGE(D'. $start_data . ':D' . ($end_data - 1 ) .')',
                '=AVERAGE(E'. $start_data . ':E' . ($end_data - 1 ) .')',
                '=AVERAGE(F'. $start_data . ':F' . ($end_data - 1 ) .')',
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
            $overall_summary->getStyle('C' . $start_data . ':F' . $end_data)->getNumberFormat()->setFormatCode('#.00');

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

        $count = 0;
        $csm_summaries = CustomerSatisfactionMeasurementSummary::where('year', $year)->orderBy('functional_unit', 'ASC')->get();

        foreach ($csm_summaries as $csm) {
            $temp = [];
            array_push($temp, $csm->functional_unit);
            array_push($temp, $csm->q1_overall_rating);
            array_push($temp, $csm->q2_overall_rating);
            array_push($temp, $csm->q3_overall_rating);
            array_push($temp, $csm->q4_overall_rating);
            array_push($temp, '=AVERAGE(B'. ($start_data + $count) . ':E' . ($start_data + $count) .')' );
            array_push($data, $temp);
            $count++;
        }

        $end_data = $start_data + $count;
        $temp = [
            'Average Rating Per Quarter',
            '=AVERAGE(B'. $start_data . ':B' . ($end_data - 1 ) .')',
            '=AVERAGE(C'. $start_data . ':C' . ($end_data - 1 ) .')',
            '=AVERAGE(D'. $start_data . ':D' . ($end_data - 1 ) .')',
            '=AVERAGE(E'. $start_data . ':E' . ($end_data - 1 ) .')',
            '=AVERAGE(F'. $start_data . ':F' . ($end_data - 1 ) .')', 
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
        $overall_summary->getStyle('B' . $start_data . ':F' . $end_data)->getNumberFormat()->setFormatCode('#.00');

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

        // Set the series in the plot area
        $layout = new Layout();
        $layout->setShowVal(true);
        $plotArea = new PlotArea($layout, [$series]);

        // Set the chart legend
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

        //Set tilte
        $layout = new Layout();
        $layout->setHeight(30);
        $layout->setWidth(30);
        $title = new Title('Customer Satisfaction Measurement January to December ' . $year, $layout);
        
        //Set Axis Label
        $xAxisLabel = new Title('FUNCTIONAL UNIT / SERVICE');
        $yAxisLabel = new Title('CUSTOMER SATISFACTION RATING');

       
        $yAxis = new Axis();
        $yAxis->setAxisOptionsProperties('CUSTOMER SATISFACTION RATING', null, null, null, 1 , 0 , 0, 5, null, null);

        // Create the chart
        $chart = new Chart(
            'CSM Chart', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            $xAxisLabel,
            $yAxisLabel,
            $yAxis
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
        $this->add_signatory($end_bar, $overall_summary);
        

    }


    private function comparison_report(&$spreadsheet, $year){
        
        //General Style
        $spreadsheet->setActiveSheetIndex(1);
        $comparison_report = $spreadsheet->getActiveSheet();
        
        $comparison_report->getStyle('A1:G999')->getAlignment()->setWrapText(true);
        $comparison_report->getStyle('A1:G999')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); 
        $comparison_report->getStyle('A1:G999')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $comparison_report->getColumnDimension('A')->setWidth(45);
        $comparison_report->getColumnDimension('B')->setWidth(12);
        $comparison_report->getColumnDimension('C')->setWidth(25);
        $comparison_report->getColumnDimension('D')->setWidth(12);
        $comparison_report->getColumnDimension('E')->setWidth(25);
        $comparison_report->getColumnDimension('F')->setWidth(12);
        $comparison_report->getColumnDimension('G')->setWidth(25);
    
    //End General Style


                            //TABLE1

    //header

        $comparison_report->mergeCells('A1:F1');
        $comparison_report->mergeCells('A2:F2');

        $header = [
            ['Department of Science and Technology - CALABARZON Region '],
            ['Comparison of Customer Satisfaction Measurement ' . ($year - 1) . ' & ' . $year],
        ];
        $comparison_report->fromArray($header, NULL, 'A1');

        //Header Style
            $comparison_report->getStyle('A1:A2')->getFont()->setBold(TRUE);
    //End Header

                           

    //Column Names
        $comparison_report->getRowDimension('4')->setRowHeight(20);
        $comparison_report->getRowDimension('5')->setRowHeight(40);
        $comparison_report->mergeCells('A4:A5');
        $comparison_report->mergeCells('F4:F5');
        $comparison_report->mergeCells('D4:E4');
        $comparison_report->mergeCells('B4:C4');

        $column_names = [
            [
                'Service/Center/ Provincial Office/ Division/Unit', 
                $year,
                NULL,
                $year - 1,
                NULL,
                'Standing'
            ],
            [
                NULL,
                'Overall Rating',
                'Adjectival Rating',
                'Overall Rating',
                'Adjectival Rating',
                NULL
            ]
        ];
        $comparison_report->fromArray($column_names, NULL, 'A4');
        

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
        $comparison_report->getStyle('A4:F5')->applyFromArray($column_style);
        

    //End Column Names

    //Data
        $start_data = 6;
        $data = [];
        $temp_data = [];
        $temp_keys = [];
        $curr_rating = 0;
        $prev_rating = 0;
        $count = 0;

        $curr_csms = CustomerSatisfactionMeasurementSummary::where('year', $year)->orderBy('functional_unit')->get();
        foreach($curr_csms as $csm){
            $temp = [];
            array_push($temp, $csm->functional_unit);
            array_push($temp, $csm->overall_rating);
            array_push($temp, $csm->adjectival_rating);

            $curr_rating += $csm->overall_rating;
            
            array_push($temp_keys, $csm->functional_unit);
            $temp_data[$csm->functional_unit] = $temp;

            $count++;

        }

        $prev_csms = CustomerSatisfactionMeasurementSummary::where('year', ($year - 1))->orderBy('functional_unit')->get();
        
        if(count($prev_csms) === 0){
            foreach($temp_keys as $key){
                array_push($temp_data[$key], NULL);
                array_push($temp_data[$key], NULL);
                array_push($temp_data[$key], NULL);
            }
        }else{
            foreach($prev_csms as $csm){
                array_push($temp_data[$csm->functional_unit], $csm->overall_rating);
                array_push($temp_data[$csm->functional_unit], $csm->adjectival_rating);

                $prev_rating += $csm->overall_rating;

                if($temp_data[$csm->functional_unit][1] !== NULL && $temp_data[$csm->functional_unit][3] !== NULL ){

                    if($temp_data[$csm->functional_unit][1] >= $temp_data[$csm->functional_unit][3]){
                        array_push($temp_data[$csm->functional_unit], '+');
                    }else{
                        array_push($temp_data[$csm->functional_unit], '-');
                    }

                }else{
                    array_push($temp_data[$csm->functional_unit], NULL);
                }
                
            }
        }

        

        $standing = '';
        if($curr_rating !== 0 && $prev_rating !== 0 ){

            if($curr_rating >= $prev_rating){
                $standing = '+';
            }else{
                $standing = '-';
            }

        }else{
            $standing = NULL;
        }

       
        $end_data = $start_data + $count;
        $temp = [
            'Mean Overall Rating',
            '=AVERAGE(B'. $start_data . ':B' . ($end_data - 1 ) .')',
            NULL,
            '=AVERAGE(D'. $start_data . ':D' . ($end_data - 1 ) .')',
            NULL,
            $standing
        ];
        $temp_data['mean'] = $temp;
        array_push($temp_keys, 'mean');

        foreach($temp_keys as $key){
            array_push($data, $temp_data[$key]);
        }

        $comparison_report->fromArray($data, NULL, 'A' . $start_data);

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
        $comparison_report->getStyle('A'.$end_data.':F'.$end_data)->applyFromArray($end_row_style);
        $comparison_report->getStyle('A4:F'.$end_data)->applyFromArray($table_border);
        $comparison_report->getStyle('A'.$start_data.':A'. ($end_data - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $comparison_report->getStyle('A'. $end_data)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $comparison_report->getStyle('B' . $start_data . ':D' . $end_data)->getNumberFormat()->setFormatCode('#.00');
        $ptr = $start_data;
        foreach ($temp_keys as $key) {
            if($temp_data[$key][5] === '+'){
                $comparison_report->getStyle('F' . $ptr )->getFont()->getColor()->setARGB(Color::COLOR_BLUE);
            }else{
                $comparison_report->getStyle('F' . $ptr )->getFont()->getColor()->setARGB(Color::COLOR_RED);
            }
            $ptr++;
        }

        
        
        $this->add_signatory($end_data, $comparison_report);

    }

                                                                                   
    private function trends_functional_unit($spreadsheet, $functional_unit, $year, $data_coordinates , $chart_coordinates){

        $trends_report = $spreadsheet->getActiveSheet();

        $overall_rating_arr = [];
        $overall_rating_keys = [];

        $csm_years = [$year - 4, $year - 3, $year - 2, $year - 1, $year];
        foreach($csm_years as $csm_year){
            $csm = CustomerSatisfactionMeasurementSummary::where([
                ['functional_unit', $functional_unit],
                ['year', $csm_year]
                ])->get();
            
            if(count($csm) === 1 && $csm[0]->overall_rating !== NULL){
                $overall_rating_arr[$csm_year] = number_format($csm[0]->overall_rating, 2, '.', NULL);
                array_push($overall_rating_keys, $csm_year);
            } 
        }
        
        
        //add data to worksheet;
        $start_chart_data = $data_coordinates['row'];
        $chart_data = [];
        foreach($overall_rating_keys as $key){
            $temp = [];
            array_push($temp, $key);
            array_push($temp, $overall_rating_arr[$key]);
            array_push($chart_data, $temp);
        }
        $chart_data_count = count($chart_data);
        $trends_report->fromArray($chart_data, NULL, $data_coordinates['left'] . $start_chart_data);
        $end_chart_data = $start_chart_data + $chart_data_count;
        

        // Set the Labels for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $cell_ref = 'Worksheet!$B$5';
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
        $cell_ref = 'Worksheet!$' . $data_coordinates['left'] . '$' . $start_chart_data . ':$' . $data_coordinates['left'] . '$' . ($end_chart_data - 1); 
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $cell_ref, null, $chart_data_count), 
        ];
        // Set the Data values for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $cell_ref = 'Worksheet!$' . $data_coordinates['right'] . '$' . $start_chart_data . ':$' . $data_coordinates['right'] . '$' . ($end_chart_data - 1); 
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $cell_ref, null, $chart_data_count), 
        ];
        // $dataSeriesValues[2]->setLineWidth(60000);

        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            NULL, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

         // Set the series in the plot area
        $layout = new Layout();
        $layout->setShowVal(true);
        $plotArea = new PlotArea($layout, [$series]);

        // Set the chart legend
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

        $title = new Title($functional_unit);
        // $xAxisLabel = new Title('YEAR');
        // $yAxisLabel = new Title('RATING');

        $yAxis = new Axis();
        $yAxis->setAxisOptionsProperties('CUSTOMER SATISFACTION RATING', null, null, null, 1 , 0 , 0, 5, null, null);

        // Create the chart
        $chart = new Chart(
            'CSM Chart', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null,
            null,
            $yAxis
        );

        $start_chart = $start_chart_data;
        $end_chart = $start_chart + 15;

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition($chart_coordinates['start'] . $start_chart);
        $chart->setBottomRightPosition($chart_coordinates['end'] . $end_chart);

        // Add the chart to the worksheet
        $trends_report->addChart($chart);
    }

    private function trends_report($spreadsheet, $year){
        //General Style
            $spreadsheet->setActiveSheetIndex(2);
            $trends_report = $spreadsheet->getActiveSheet();
            
            $trends_report->getStyle('A1:K999')->getAlignment()->setWrapText(true);
            $trends_report->getStyle('A1:K999')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); 
            $trends_report->getStyle('A1:K999')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $trends_report->getColumnDimension('A')->setWidth(40);
            $trends_report->getColumnDimension('B')->setWidth(14);
            $trends_report->getColumnDimension('C')->setWidth(14);
            $trends_report->getColumnDimension('D')->setWidth(14);
            $trends_report->getColumnDimension('E')->setWidth(14);
            $trends_report->getColumnDimension('F')->setWidth(14);
            $trends_report->getColumnDimension('G')->setWidth(14);
            $trends_report->getColumnDimension('H')->setWidth(14);
            $trends_report->getColumnDimension('I')->setWidth(14);
            $trends_report->getColumnDimension('J')->setWidth(14);
            $trends_report->getColumnDimension('K')->setWidth(14);

        //End General Style
                //TABLE1

        //header

            $trends_report->mergeCells('A1:K1');
            $trends_report->mergeCells('A2:K2');

            $header = [
                ['Department of Science and Technology - CALABARZON Region '],
                ['Trends of Customer Satisfaction Measurement ' . ($year - 4) . '-' . $year],
            ];
            $trends_report->fromArray($header, NULL, 'A1');

            //Header Style
                $trends_report->getStyle('A1:A2')->getFont()->setBold(TRUE);
        //End Header

                            

        //Column Names
            $trends_report->getRowDimension('4')->setRowHeight(20);
            $trends_report->getRowDimension('5')->setRowHeight(40);
            $trends_report->mergeCells('A4:A5');
            $trends_report->mergeCells('B4:C4');
            $trends_report->mergeCells('D4:E4');
            $trends_report->mergeCells('F4:G4');
            $trends_report->mergeCells('H4:I4');
            $trends_report->mergeCells('J4:K4');
            

            $column_names = [
                [
                    'Service/Center/ Provincial Office/ Division/Unit', 
                    $year - 4,
                    NULL,
                    $year - 3,
                    NULL,
                    $year - 2,
                    NULL,
                    $year - 1,
                    NULL,
                    $year
                ],
                [
                    NULL,
                    'Overall Rating',
                    'No. of Customers',
                    'Overall Rating',
                    'No. of Customers',
                    'Overall Rating',
                    'No. of Customers',
                    'Overall Rating',
                    'No. of Customers',
                    'Overall Rating',
                    'No. of Customers',
                ]
            ];
            $trends_report->fromArray($column_names, NULL, 'A4');
            

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
            $trends_report->getStyle('A4:K5')->applyFromArray($column_style);
            

        //End Column Names
        

        //Data
            $start_data = 6;
            $data = [];
            $temp_data = [];
            $temp_keys = [];
            $count = 0;
            $overall_rating_arr = [];
            $overall_rating_keys = [];

            //getting all functional units
            $curr_csms = CustomerSatisfactionMeasurementSummary::where('year', $year)->orderBy('functional_unit')->get();
            foreach($curr_csms as $csm){
                $temp = [];
                array_push($temp, $csm->functional_unit);
                array_push($temp_keys, $csm->functional_unit);
                $temp_data[$csm->functional_unit] = $temp;
                $count++;
            }


            $csm_years = [$year - 4, $year - 3, $year - 2, $year - 1, $year];

            foreach($csm_years as $csm_year){

                $average = CustomerSatisfactionMeasurementSummary::where('year', $csm_year)->avg('overall_rating');
                if($average !== NULL){
                    $overall_rating_arr[$csm_year] = number_format($average, 2, '.', NULL);
                    array_push($overall_rating_keys, $csm_year);
                } 
                

                $csms = CustomerSatisfactionMeasurementSummary::where('year', $csm_year)->orderBy('functional_unit')->get();
                if(count($csms) === 0){
                    foreach($temp_keys as $key){
                        array_push($temp_data[$key], NULL);
                        array_push($temp_data[$key], NULL);
                    }
                }else{
                    foreach($csms as $csm){
                        array_push($temp_data[$csm->functional_unit], $csm->overall_rating);
                        array_push($temp_data[$csm->functional_unit], $csm->total_customer);
                    }
                }
            }

            $end_data = $start_data + $count;
            $temp = [
                'Total Customers / Average Rating',
                '=AVERAGE(B'. $start_data . ':B' . ($end_data - 1 ) .')',
                '=SUM(C'. $start_data . ':C' . ($end_data - 1 ) .')',
                '=AVERAGE(D'. $start_data . ':D' . ($end_data - 1 ) .')',
                '=SUM(E'. $start_data . ':E' . ($end_data - 1 ) .')',
                '=AVERAGE(F'. $start_data . ':F' . ($end_data - 1 ) .')',
                '=SUM(G'. $start_data . ':G' . ($end_data - 1 ) .')',
                '=AVERAGE(H'. $start_data . ':H' . ($end_data - 1 ) .')',
                '=SUM(I'. $start_data . ':I' . ($end_data - 1 ) .')',
                '=AVERAGE(J'. $start_data . ':J' . ($end_data - 1 ) .')',
                '=SUM(K'. $start_data . ':K' . ($end_data - 1 ) .')'
            ];
            $temp_data['mean'] = $temp;
            array_push($temp_keys, 'mean');

            foreach($temp_keys as $key){
                array_push($data, $temp_data[$key]);
            }

            $trends_report->fromArray($data, NULL, 'A' . $start_data);
            
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
            $trends_report->getStyle('A'.$end_data.':K'.$end_data)->applyFromArray($end_row_style);
            $trends_report->getStyle('A4:K'.$end_data)->applyFromArray($table_border);
            $trends_report->getStyle('A'.$start_data.':A'. ($end_data - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $trends_report->getStyle('A'. $end_data)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $trends_report->getStyle('B' . $start_data . ':K' . $end_data)->getNumberFormat()->setFormatCode('#.00');


        //End Data
        

        //Overall rating chart
            
            //add data to worksheet;
            $start_chart_data = $end_data + 2;
            $chart_data = [];
            foreach($overall_rating_keys as $key){
                $temp = [];
                array_push($temp, $key);
                array_push($temp, $overall_rating_arr[$key]);
                array_push($chart_data, $temp);
            }
            $chart_data_count = count($chart_data);
            $trends_report->fromArray($chart_data, NULL, 'A' . $start_chart_data);
            $end_chart_data = $start_chart_data + $chart_data_count;
            

            // Set the Labels for each data series we want to plot
            //     Datatype
            //     Cell reference for data
            //     Format Code
            //     Number of datapoints in series
            //     Data values
            //     Data Marker
            $cell_ref = 'Worksheet!$B$5';
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
            $cell_ref = 'Worksheet!$A$' . $start_chart_data . ':$A$' . ($end_chart_data - 1); 
            $xAxisTickValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $cell_ref, null, $chart_data_count), 
            ];
            // Set the Data values for each data series we want to plot
            //     Datatype
            //     Cell reference for data
            //     Format Code
            //     Number of datapoints in series
            //     Data values
            //     Data Marker
            $cell_ref = 'Worksheet!$B$' . $start_chart_data . ':$B$' . ($end_chart_data - 1); 
            $dataSeriesValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $cell_ref, null, $chart_data_count), 
            ];
            // $dataSeriesValues[2]->setLineWidth(60000);

            // Build the dataseries
            $series = new DataSeries(
                DataSeries::TYPE_LINECHART, // plotType
                NULL, // plotGrouping
                range(0, count($dataSeriesValues) - 1), // plotOrder
                $dataSeriesLabels, // plotLabel
                $xAxisTickValues, // plotCategory
                $dataSeriesValues        // plotValues
            );

            // Set the series in the plot area
            $layout = new Layout();
            $layout->setShowVal(true);
            $plotArea = new PlotArea($layout, [$series]);

            // Set the chart legend
            $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

            $title = new Title('Overall Rating');
            // $xAxisLabel = new Title('YEAR');
            // $yAxisLabel = new Title('RATING');

            $yAxis = new Axis();
            $yAxis->setAxisOptionsProperties('CUSTOMER SATISFACTION RATING', null, null, null, 1 , 0 , 0, 5, null, null);

            // Create the chart
            $chart = new Chart(
                'CSM Chart', // name
                $title, // title
                $legend, // legend
                $plotArea, // plotArea
                true, // plotVisibleOnly
                0, // displayBlanksAs
                null,
                null,
                $yAxis
            );

            $start_chart = $start_chart_data;
            $end_chart = $start_chart + 15;

            // Set the position where the chart should appear in the worksheet
            $chart->setTopLeftPosition('A' . $start_chart);
            $chart->setBottomRightPosition('G' . $end_chart);

            // Add the chart to the worksheet
            $trends_report->addChart($chart);

        //End Chart


        //Chart for Each Functional Unit / Services
            $row = $end_chart + 2;
            $counter = 0;
            $coordinates = [
                    'data' => [
                        'left'  => ['A', 'D', 'I'],
                        'right' => ['B', 'E', 'J']
                    ],

                    'chart' => [
                        'start' => ['A', 'D', 'I'],
                        'end'   => ['C', 'H', 'M']
                    ] 
            ];

            array_pop($temp_keys);
            foreach($temp_keys as $key){
                
                $data_coordinates = [];
                $data_coordinates['row']    = $row;
                $data_coordinates['left']   = $coordinates['data']['left'][$counter];
                $data_coordinates['right']  = $coordinates['data']['right'][$counter];

                $chart_coordinates = [];
                $chart_coordinates['start'] = $coordinates['chart']['start'][$counter];
                $chart_coordinates['end']   = $coordinates['chart']['end'][$counter];

                $this->trends_functional_unit($spreadsheet, $key, $year, $data_coordinates , $chart_coordinates);

                $counter++;

                if($counter > 2){
                    $counter = 0;
                    $row += 17;
                }
            }

        //End Chart for Each Functional Unit / Services
        
        //signatories
            $end_data = $row + 17;
            $this->add_signatory($end_data, $trends_report);
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
        
        //Storage Path
        $storage_path = public_path('storage/downloads/');
        $spreadsheet = new Spreadsheet();
        
        //Create sheets
        $overall_summary = new Worksheet($spreadsheet, 'Overall Summary' . $request->year);
        $comparison = new Worksheet($spreadsheet, 'Comparison with ' . ($request->year - 1));
        $trends = new Worksheet($spreadsheet, 'Trends');

        //Add Sheets
        $spreadsheet->addSheet($overall_summary, 0);
        $spreadsheet->addSheet($comparison, 1);
        $spreadsheet->addSheet($trends, 2);
        $spreadsheet->removeSheetByIndex(3);
        
        //Create report
        $this->overall_summary_report($spreadsheet, $request->year);
        $this->comparison_report($spreadsheet, $request->year);
        $this->trends_report($spreadsheet, $request->year);
       
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->save($storage_path.'CSM Report' . $request->year . '.xlsx');
        return Storage::download('public/downloads/'.'CSM Report' . $request->year . '.xlsx');
    }

}

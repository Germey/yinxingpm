<?php
class Excel{
    /**
     * 生成excel
     * @param array $data 数据源
     * @param array $col 列标题
     * @param array $row 行标题
     * @param boolean $show 是否输出（否为保存文件）
     */
       public static function createExcel($data,$sheetname='sheet1',$filename='out',$col=array(),$row=array(),$show=true,$filedir='', $extra_links){
            vendor('PHPExcel.PHPExcel');
            vendor('PHPExcel.PHPExcel.Writer.Excel5');

            $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
            $cacheSettings = array( ' memoryCacheSize '  => '64MB' );
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $Excel=new PHPExcel();
            $ExcelWriter=new PHPExcel_Writer_Excel5($Excel);

            $objActSheet = $Excel->getActiveSheet();
            $objActSheet->setTitle($sheetname);
            $colcount=count($col);
            $rowcount=count($row);
            $style_header = array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb'=>'f5f5f5'),
                ),
                'font' => array(
                    'bold' => true
                )
            );
            //设置列标题
            if($col){
                foreach($col as $k=>$v){
                    $column_str = PHPExcel_Cell::stringFromColumnIndex($k); // 从o开始
                    $objActSheet->setCellValue($column_str . '1', $v);
                    $objActSheet->getStyle($column_str . '1')->applyFromArray($style_header);
                }
            }
            //设置行标题
            if($row){
                foreach($row as $k=>$v){
                    $column_str = PHPExcel_Cell::stringFromColumnIndex($k+2);
                    $objActSheet->setCellValue($column_str, $v);
                }
            }
            $initrow=1;
            //填充内容
            // $style_body = array(
            //     'fill' => array(
            //         'type' => PHPExcel_Style_Fill::FILL_SOLID,
            //         'color' => array('rgb'=>'f5f5f5'),
            //     ),
            // );
            foreach($data as $k=>$v){
                $initrow+=1;
                for($i=0;$i<$colcount;$i++){
                    $val=isset($v[$i])?$v[$i]:'-';
                    $column_str = PHPExcel_Cell::stringFromColumnIndex($i); // 从o开始

                    if($val && $extra_links && strpos($val, '_lingxilink_')>0) {//只有在表单导出的时候会用到link
                        $tmp = explode('_lingxilink_', $val);
                        $val = $tmp[0];
                        $extra_link = $extra_links[$tmp[1]];
                        $objActSheet->setCellValue($column_str.($initrow),$val);
                        if($extra_link) {
                            //设置link颜色，下划线
                            $objActSheet->getCell($column_str.($initrow))->getHyperlink()->setUrl($extra_link);
                            $objActSheet->getStyle($column_str.($initrow))->getFont()->getColor()->setRGB('428bca');
                            $objActSheet->getStyle($column_str.($initrow))->getFont()->setUnderline( PHPExcel_Style_Font::UNDERLINE_SINGLE);
                        }
                    } else {
                        //设置以文本格式显示，长数字也能以文本格式显示啦
                        $objActSheet->getStyle($column_str.($initrow))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $objActSheet->setCellValueExplicit($column_str.($initrow), $val, PHPExcel_Cell_DataType::TYPE_STRING);
                        // $objActSheet->setCellValue($column_str.($initrow),$val);
                    }

                    // if($initrow%2){
                    //     $objActSheet->getStyle(chr(65+$i).($initrow))->applyFromArray($style_body);
                    // }
                }
            }
            $outputFileName = $filename.".xls";
            if($show){
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header('Content-Disposition:inline;filename="'.$outputFileName.'"');
                header("Content-Transfer-Encoding: binary");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Pragma: no-cache");
                $ExcelWriter->save('php://output');
            }
            else{
            $ExcelWriter->save($filedir.$outputFileName);
            }
        }

//调用方法：

// $col[0]='日期\时间';
//  for($i=1;$i<25;$i++){
//  $col[]=$i-1;
//  }
//  $row=array_keys($data);
//  $Excel=new ExcelAction();
//  $Excel->createExcel($data,'registerdata','register'.time(),$col,$row);
}
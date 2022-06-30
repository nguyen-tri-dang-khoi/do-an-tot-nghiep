<?php
    include_once("../lib/database.php");
    require '../lib/vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Chart\Chart;
    use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
    use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
    use PhpOffice\PhpSpreadsheet\Chart\Legend as ChartLegend;
    use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
    use PhpOffice\PhpSpreadsheet\Chart\Title;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Style\Alignment as StyleAlignment;
    use PhpOffice\PhpSpreadsheet\Style\NumberFormat as StyleNumberFormat;
    use PhpOffice\PhpSpreadsheet\Style\Color as StyleColor;
    use PhpOffice\PhpSpreadsheet\Style\Border as StyleBorder;
    use PhpOffice\PhpSpreadsheet\Style\Fill as StyleFill;
    $is_download = false;
    $case = isset($_REQUEST["case"]) ? $_REQUEST["case"] : null;
    $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : null;
    if($status == "statistic_excel_download") {
        $is_download = true;
    }
    $year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : null;
    $month = isset($_REQUEST["month"]) ? $_REQUEST["month"] : null;
    $day = isset($_REQUEST["day"]) ? $_REQUEST["day"] : null;
    $k_word = "";
    $where = "where 1 = 1";
    $select = "";
    $gb = "GROUP BY";
    $_data = [];
    $_dat['stt'] = [];
    $_dat['time'] = [];
    $_dat['countProduct'] = [];
    $_dat['revenue'] = [];
    $_dat['profit'] = [];
    $_dat['countOrder'] = [];
    $max_label = -1;
    $max_data = -1;
    // Thống kê doanh thu
    if($case == "1") {
        if($year) {
            $where .= " and YEAR(ord.created_at)='$year'";
            $select = "MONTH(ord.created_at) as 'month'";
            $max_label = 12;
            $gb = "GROUP BY MONTH(ord.created_at)";
            $k_word = "month";
        }
        if($month) {
            $where .= " and MONTH(ord.created_at)='$month'";
            $select = "DAY(ord.created_at) as 'day'";
            if(in_array($month,[1,3,5,7,8,10,12])) {
                $max_label = 31;
            } else if(in_array($month,[4,6,9,11])){
                $max_label = 30;
            } else if($month == 2) {
                if(($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0){
                    $max_label = 29;
                }
                $max_label = 28;
            }
            $gb = "GROUP BY MONTH(ord.created_at),DAY(ord.created_at)";
            $k_word = "day";
        }
        if($day) {
            $where .= " and DAY(ord.created_at)='$day'";
            $select = "HOUR(ord.created_at) as 'hour'";
            $max_label = 24;
            $gb = "GROUP BY MONTH(ord.created_at),DAY(ord.created_at),HOUR(ord.created_at)";
            $k_word = "hour";
        }
        for($i = 1 ; $i <= $max_label ; $i++) {
            array_push($_data,0);
            array_push($_dat['stt'],$i);
            array_push($_dat['time'],$i);
        }
        $sql_case_1 = "select $select,sum(ord.total) as 'sum' from orders ord $where $gb";
        //print_r($sql_case_1);
        $pdo_statistic = sql_query($sql_case_1);
        foreach(fetch_all($pdo_statistic) as $row) {
            $_data[$row[$k_word] - 1] = $row['sum'];
        }
        $_data['label'] = $max_label;
        if($status == "statistic_excel_download") {
            $is_download = true;
        } else {
            print_r(json_encode(array_merge(["msg" => "ok"],$_data)));
        }
    } else if($case == "2") {
        if($year) {
            $where .= " and YEAR(ord.created_at)='$year'";
            $select = "MONTH(ord.created_at) as 'month'";
            $max_label = 12;
            $gb = "GROUP BY MONTH(ord.created_at)";
            $k_word = "month";
        }
        if($month) {
            $where .= " and MONTH(ord.created_at)='$month'";
            $select = "DAY(ord.created_at) as 'day'";
            if(in_array($month,[1,3,5,7,8,10,12])) {
                $max_label = 31;
            } else if(in_array($month,[4,6,9,11])){
                $max_label = 30;
            } else if($month == 2) {
                if(($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0){
                    $max_label = 29;
                }
                $max_label = 28;
            }
            $gb = "GROUP BY MONTH(ord.created_at),DAY(ord.created_at)";
            $k_word = "day";
        }
        if($day) {
            $where .= " and DAY(ord.created_at)='$day'";
            $select = "HOUR(ord.created_at) as 'hour'";
            $max_label = 24;
            $gb = "GROUP BY MONTH(ord.created_at),DAY(ord.created_at),HOUR(ord.created_at)";
            $k_word = "hour";
        }
        for($i = 1 ; $i <= $max_label ; $i++) {
            array_push($_data,0);
        }
        $sql_case_1 = "select $select,count(ord.id) as 'cnt',sum(ord.total) as 'total' from orders ord $where $gb";
        $pdo_statistic = sql_query($sql_case_1);
        foreach(fetch_all($pdo_statistic) as $row) {
            $_data[$row[$k_word] - 1] = $row['cnt'];
        }
        
        $_data['label'] = $max_label;
        if($status == "statistic_excel_download") {
            $is_download = true;
        } else {
            print_r(json_encode(array_merge(["msg" => "ok"],$_data)));
        }
    } else if($case == "3") {
        if($year) {
            $where .= " and YEAR(ord.created_at)='$year'";
            $select = "MONTH(ord.created_at) as 'month'";
            $max_label = 12;
            $gb = "GROUP BY MONTH(ord.created_at)";
            $k_word = "month";
        }
        if($month) {
            $where .= " and MONTH(ord.created_at)='$month'";
            $select = "DAY(ord.created_at) as 'day'";
            if(in_array($month,[1,3,5,7,8,10,12])) {
                $max_label = 31;
            } else if(in_array($month,[4,6,9,11])){
                $max_label = 30;
            } else if($month == 2) {
                if(($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0){
                    $max_label = 29;
                }
                $max_label = 28;
            }
            $gb = "GROUP BY MONTH(ord.created_at),DAY(ord.created_at)";
            $k_word = "day";
        }
        if($day) {
            $where .= " and DAY(ord.created_at)='$day'";
            $select = "HOUR(ord.created_at) as 'hour'";
            $max_label = 24;
            $gb = "GROUP BY MONTH(ord.created_at),DAY(ord.created_at),HOUR(ord.created_at)";
            $k_word = "hour";
        }
        for($i = 1 ; $i <= $max_label ; $i++) {
            array_push($_dat['stt'],0);
            array_push($_dat['time'],0);
            array_push($_dat['countOrder'],0);
            array_push($_dat['revenue'],0);
            array_push($_dat['countProduct'],0);
            array_push($_dat['profit'],0);
        }
        $sql_case_1 = "select $select,count(ord.id) as 'cnt',sum(ord.total) as 'total' from orders ord $where $gb";
        $sql_case_3 = "select $select, sum(od.count) as 'cntProduct' ,sum(od.cost) as 'sumPriceRoot' from orders ord inner join order_detail od on ord.id = od.order_id $where $gb";
        $pdo_statistic = sql_query($sql_case_1);
        foreach(fetch_all($pdo_statistic) as $row) {
            $_dat['countOrder'][$row[$k_word] - 1] = $row['cnt'];
            $_dat['revenue'][$row[$k_word] - 1] = $row['total'];
        }
        $pdo_statistic = sql_query($sql_case_3);
        foreach(fetch_all($pdo_statistic) as $row) {
            $_dat['countProduct'][$row[$k_word] - 1] = $row['cntProduct'];
            $_dat['profit'][$row[$k_word] - 1] = $_dat['revenue'][$row[$k_word] - 1] - $row['sumPriceRoot'];
        }
        $_data = $_dat;
    }
    if($is_download) {
        $dataColumnInfo = [
            "Number" => "Số thứ tự",
            "Time" => "Tháng",
            "Sales" => "Doanh số",
            "countProductSale" => "Số lượng sản phẩm bán ra",
            "Revenue" => "Doanh thu",
            "Profit" => "Lợi nhuận",
            "countBill" => "Số lượng đơn hàng",
        ];
        $arrDataInfoKey = [
            "countUser" => "Số lượng nhân viên",
            "countProduct" => "Số lượng sản phẩm tồn kho",
            "countCustomer" => "Số lượng khách hàng",
        ];
        $arrDataInfoValue= [
            "countUser" => "22",
            "countProduct" => "14",
            "countCustomer" => "6",
        ];
        $arrDataInfoValue['countUser'] = fetch(sql_query("select count(*) as 'cnt' from user where is_delete = 0"))['cnt'];
        $arrDataInfoValue['countProduct'] = fetch(sql_query("select count(*) as 'cnt' from product_info where is_delete = 0"))['cnt'];
        $arrDataInfoValue['countCustomer'] = fetch(sql_query("select count(*) as 'cnt' from user where is_delete = 0 and type = 'customer'"))['cnt'];
        // mảng thông tin nhân viên
        $arrUserInfoKey = [
            "fullName" => "Họ tên nhân viên báo cáo:",
            "Phone" => "Số điện thoại liên hệ:",
            "Email" => "Email liên hệ:",
            "Address" => "Địa chỉ liên hệ:",
            "dateReport" => "Ngày tạo tệp báo cáo:"
        ];
        
        $arrUserInfoValue = [
            "fullName" => "Nguyễn Trí Đăng Khôi",
            "Phone" => "0707327857",
            "Email" => "nguyentridangkhoi@gmail.com",
            "Address" => "37 đường 102, Phường Thạnh Mỹ Lợi,\nTP.Thủ Đức, TP.HCM",
            "dateReport" => "29-05-2022",
        ];
        $userInfo = fetch(sql_query("select * from user where is_delete = 0 and type = 'admin' and id = ? limit 1",[$_SESSION['id']]));
        $arrUserInfoValue['fullName'] = $userInfo['full_name'];
        $arrUserInfoValue['Phone'] = $userInfo['phone'];
        $arrUserInfoValue['Email'] = $userInfo['email'];
        $arrUserInfoValue['Address'] = $userInfo['address'];
        $arrUserInfoValue['dateReport'] = Date("d-m-Y");

        // mảng thông tin trang web công ty
        $arrCompanyInfoKey = [
            "fullName" => "Tên công ty:",
            "companyTaxCode" => "Mã số thuế:",
            "Phone" => "Số điện thoại:",
            "Email" => "Email:",
            "Address" => "Địa chỉ:",
        ];
        $arrCompanyInfoValue = [
            "fullName" => "Web bán hàng ephone",
            "companyTaxCode" => "0301215249",
            "Phone" => "0707327857",
            "Email" => "ephone@gmail.com",
            "Address" => "46-48 Hậu giang,Phường 4,Tân Bình,TP.Hồ Chí Minh",
        ];

        $companyInfo = fetch(sql_query("select * from company_info limit 1"));
        $arrCompanyInfoValue['fullName'] = $companyInfo['company_name'];
        $arrCompanyInfoValue['companyTaxCode'] = $companyInfo['company_tax_code'];
        $arrCompanyInfoValue['Phone'] = $companyInfo['company_phone'];
        $arrCompanyInfoValue['Email'] = $companyInfo['company_email'];
        $arrCompanyInfoValue['Address'] = $companyInfo['company_address'];
        if(in_array(count($_data['stt']),[30,31,28])) {
            for($i = 0 ; $i < count($_data['stt']) ; $i++) {
                $dataColumnInfo['Time'] = 'Ngày';
                $title_root = "Thông kê doanh thu cửa hàng tháng " . $month . " năm " . $year; 
                $_data['time'][$i] = "Ngày " . Date("d-m-Y",strtotime(($i + 1) . "-" . $month . "-" . $year));
                $_data['stt'][$i] = $i + 1;
                $arr_2[$i] = [$_data['stt'][$i],$_data['time'][$i],$_data['revenue'][$i],$_data['countProduct'][$i],$_data['revenue'][$i],$_data['profit'][$i],$_data['countOrder'][$i]];
            }
        } else if(in_array(count($_data['stt']),[24])) {
            $dataColumnInfo['Time'] = 'Giờ';
            $title_root = "Thông kê doanh thu cửa hàng trong ngày " .$day.'/'.$month.'/'.$year;
            for($i = 0 ; $i < count($_data['stt']) ; $i++) {
                $_data['time'][$i] = ($i + 1) . ":00";
                $_data['stt'][$i] = $i + 1;
                $arr_2[$i] = [$_data['stt'][$i],$_data['time'][$i],$_data['revenue'][$i],$_data['countProduct'][$i],$_data['revenue'][$i],$_data['profit'][$i],$_data['countOrder'][$i]];
            }
        } else if(count($_data['stt']) == 12){
            $dataColumnInfo['Time'] = 'Tháng';
            $title_root = "Thông kê doanh thu cửa hàng trong năm " . $year;
            for($i = 0 ; $i < count($_data['stt']) ; $i++) {
                $_data['time'][$i] = ($i + 1);
                //stt,
                $_data['stt'][$i] = $i + 1;
                $arr_2[$i] = [$_data['stt'][$i],$_data['time'][$i],$_data['revenue'][$i],$_data['countProduct'][$i],$_data['revenue'][$i],$_data['profit'][$i],$_data['countOrder'][$i]];
            }
        }
        $timeNewRoman = array(
            'font' => array(
                'name'  => 'Times New Roman',
            ),
        );
        //log_a($arr_2);
        $styleArray = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '#ddd'),
                'size'  => 12,
                'name'  => 'Times New Roman'
            )
        );
        // style cho tiêu đề
        $styleTitle = [
            'font'  => [
                'bold'  => true,
                'color' => ['argb' => '963634'],
                'size'  => 14,
                'name'  => 'Times New Roman'
            ],
            'alignment' => [
                'horizontal' => StyleAlignment::HORIZONTAL_CENTER
            ]
        ];  
        $colorTbl = [
            'fill' => [
                'fillType' => StyleFill::FILL_SOLID,
                'startColor' => ['argb' => 'E7B8B7'],
            ],'font' => [
                'color' => ['rgb' => '#ddd'],
                'size'  => 12,
                'bold'  => true,
                'underline' => true,
            ],
        ];
        $fontTbl = [
            
        ];
        // style cho thông tin nhân viên lập báo cáo
        $styleUserInfo = [
            'font' => [
                'color' => ['rgb' => '#ddd'],
                'size'  => 11,
            ],
            'alignment' => [
                'vertical' => StyleAlignment::VERTICAL_CENTER,
                'horizontal' => StyleAlignment::HORIZONTAL_LEFT
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];
        $styleUserInfo2 = [
            'font' => [
                'color' => ['rgb' => '#ddd'],
                'size'  => 11,
            ],
            'alignment' => [
                'vertical' => StyleAlignment::VERTICAL_CENTER,
                'horizontal' => StyleAlignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];
        $styleUserInfo3 = [
            'font' => [
                'color' => ['rgb' => '#ddd'],
                'size'  => 11,
            ],
            'alignment' => [
                'vertical' => StyleAlignment::VERTICAL_CENTER,
                'horizontal' => StyleAlignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];
        // style cho thông tin trang web công ty 
        $styleCompanyInfo = [

        ];
        // style cho thông tin tổng quan : số sản phẩm hiện có, số bài viết hiện có, số nhân viên hiện có, số khách hàng hiện có,
        $styleInfo = [

        ];
        // style chung cho bảng báo cáo :
        $styleTableReport = [

        ];
        // style cho bảng báo cáo cột : số thứ tự, giờ|ngày|tháng, doanh số, doanh thu,lợi nhuận, số lượng đơn hàng 
        $styleColumnInfo = [
            "Number" => "Số thứ tự",
            "Time" => "Tháng",
            "Revenue" => "Doanh thu",
            "Profit" => "Lợi nhuận",
            "countBill" => "Số lượng đơn hàng",
            "countProductSale" => "Số lượng sản phẩm bán ra"
        ];
        // style cho dòng data: xen kẽ màu sắc xanh dương - trắng,..
        $styleRowInfo = [

        ];
        $styleSumInfo = [
            "Sum" => "Tổng",
        ];
        $styleAverageInfo = [
            "Average" => "Trung bình",
        ];
        $dataColumnSumAverage = [
            "Sales" => "Doanh số",
            "Revenue" => "Doanh thu",
            "countProductSale" => "Số lượng sản phẩm bán ra",
            "Profit" => "Lợi nhuận",
            "countBill" => "Số lượng đơn hàng",
        ];
        $dataSumInfo = [
            "Sum" => "Tổng",
        ];
        $dataAverageInfo = [
            "Average" => "Trung bình",
        ];
        //$dataColumnInfoValue = array_chunk($dataColumnInfoValue,1);
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $worksheet->getColumnDimension('A')->setWidth(100,"pt");
        $worksheet->getColumnDimension('B')->setWidth(100,"pt");
        $worksheet->getColumnDimension('C')->setWidth(100,"pt");
        $worksheet->getColumnDimension('D')->setWidth(100,"pt");
        $worksheet->getColumnDimension('E')->setWidth(130,"pt");
        $worksheet->getColumnDimension('F')->setWidth(160,"pt");
        $worksheet->getColumnDimension('G')->setWidth(130,"pt");
        $worksheet->getColumnDimension('H')->setWidth(100,"pt");
        $worksheet->getColumnDimension('I')->setWidth(100,"pt");
        $worksheet->getColumnDimension('J')->setWidth(100,"pt");
        $worksheet->getColumnDimension('K')->setWidth(100,"pt");
        $worksheet->getStyle('A1:K1')->applyFromArray($styleTitle);
        $worksheet->getStyle('A2:E6')->applyFromArray($styleUserInfo);
        $worksheet->getStyle('G2:K6')->applyFromArray($styleUserInfo);
        $worksheet->getStyle('E9:G10')->applyFromArray($styleUserInfo);
        
        
        $worksheet->mergeCells('A1:K1');
        $worksheet->setCellValue('A1', $title_root);
        $worksheet->setCellValue('A2', $arrUserInfoKey['fullName']);
        $worksheet->mergeCells('A2:B2');
        $worksheet->setCellValue('A3', $arrUserInfoKey['Phone']);
        $worksheet->mergeCells('A3:B3');
        $worksheet->setCellValue('A4', $arrUserInfoKey['Email']);
        $worksheet->mergeCells('A4:B4');
        $worksheet->setCellValue('A5', $arrUserInfoKey['Address']);
        $worksheet->mergeCells('A5:B5');
        $worksheet->setCellValue('A6', $arrUserInfoKey['dateReport']);
        $worksheet->mergeCells('A6:B6');
        //
        $worksheet->setCellValue('C2', $arrUserInfoValue['fullName']);
        $worksheet->mergeCells('C2:E2');
        $worksheet->setCellValue('C3', $arrUserInfoValue['Phone']);
        $worksheet->mergeCells('C3:E3');
        $worksheet->setCellValue('C4', $arrUserInfoValue['Email']);
        $worksheet->mergeCells('C4:E4');
        $worksheet->setCellValue('C5', $arrUserInfoValue['Address']);
        $worksheet->mergeCells('C5:E5');
        $worksheet->setCellValue('C6', $arrUserInfoValue['dateReport']);
        $worksheet->mergeCells('C6:E6');
        //
        $worksheet->setCellValue('G2', $arrCompanyInfoKey['fullName']);
        $worksheet->mergeCells('G2:H2');
        $worksheet->setCellValue('G3', $arrCompanyInfoKey['companyTaxCode']);
        $worksheet->mergeCells('G3:H3');
        $worksheet->setCellValue('G4', $arrCompanyInfoKey['Phone']);
        $worksheet->mergeCells('G4:H4');
        $worksheet->setCellValue('G5', $arrCompanyInfoKey['Email']);
        $worksheet->mergeCells('G5:H5');
        $worksheet->setCellValue('G6', $arrCompanyInfoKey['Address']);
        $worksheet->mergeCells('G6:H6');
        // 
        $worksheet->setCellValue('I2', $arrCompanyInfoValue['fullName']);
        $worksheet->mergeCells('I2:K2');
        $worksheet->setCellValue('I3', $arrCompanyInfoValue['companyTaxCode']);
        $worksheet->mergeCells('I3:K3');
        $worksheet->setCellValue('I4', $arrCompanyInfoValue['Phone']);
        $worksheet->mergeCells('I4:K4');
        $worksheet->setCellValue('I5', $arrCompanyInfoValue['Email']);
        $worksheet->mergeCells('I5:K5');
        $worksheet->setCellValue('I6', $arrCompanyInfoValue['Address']);
        $worksheet->mergeCells('I6:K6');
        for($i = 0 ; $i < 50 ; $i++) {
            $worksheet->getRowDimension($i)->setRowHeight(25,'pt');
        }
        $worksheet->fromArray([$arrDataInfoKey],NULL,'E9');
        $worksheet->fromArray([$arrDataInfoValue],NULL,'E10');
        $worksheet->fromArray([$dataColumnInfo],NULL,'C12');
        $worksheet->getStyle('C12:I12')->applyFromArray(array_merge($colorTbl,$fontTbl));
        $worksheet->fromArray($arr_2,NULL,'C13',true);
        $cellNext = 15 + count($_data['stt']);
        $worksheet->getStyle('C12:I' . ($cellNext - 3))->applyFromArray($styleUserInfo2);
        $worksheet->getStyle('E13:E' . ($cellNext - 3))->getNumberFormat()->setFormatCode('#,##0"đ"');
        $worksheet->getStyle('G13:G' . ($cellNext - 3))->getNumberFormat()->setFormatCode('#,##0"đ"');
        $worksheet->getStyle('H13:H' . ($cellNext - 3))->getNumberFormat()->setFormatCode('#,##0"đ"');
        $worksheet->fromArray([$dataColumnSumAverage],NULL,'D' . $cellNext);
        $worksheet->setCellValue('C' . ($cellNext + 1),$dataSumInfo['Sum']);
        $worksheet->setCellValue('C'. ($cellNext + 2),$dataAverageInfo['Average']);
        // sum
        $worksheet->setCellValue('D' . ($cellNext + 1),"=SUM(" . 'E13:E' . ($cellNext - 3) .")");
        $worksheet->setCellValue('E' . ($cellNext + 1),"=SUM(" . 'G13:G' . ($cellNext - 3) .")");
        $worksheet->setCellValue('F' . ($cellNext + 1),"=SUM(" . 'F13:F' . ($cellNext - 3) .")");
        $worksheet->setCellValue('G' . ($cellNext + 1),"=SUM(" . 'H13:H' . ($cellNext - 3) .")");
        $worksheet->setCellValue('H' . ($cellNext + 1),"=SUM(" . 'I13:I' . ($cellNext - 3) .")");
        //
        $worksheet->getStyle('C12:I12')->applyFromArray($styleUserInfo3);
        $worksheet->getStyle('E9:G9')->applyFromArray($styleUserInfo3);
        $worksheet->getStyle('E10:G10')->applyFromArray($styleUserInfo2);
        //
        $worksheet->getStyle('D' . ($cellNext + 1))->getNumberFormat()->setFormatCode('#,##0"đ"');
        $worksheet->getStyle('E' . ($cellNext + 1))->getNumberFormat()->setFormatCode('#,##0"đ"');
        //$worksheet->getStyle('F' . ($cellNext + 1))->getNumberFormat()->setFormatCode('#,##0"đ"');
        $worksheet->getStyle('G' . ($cellNext + 1))->getNumberFormat()->setFormatCode('#,##0"đ"');
        //$worksheet->getStyle('H' . ($cellNext + 1))->getNumberFormat()->setFormatCode('#,##0"đ"');
        //
        // average
        $worksheet->setCellValue('D' . ($cellNext + 2),"=AVERAGE(" . 'E13:E' . ($cellNext - 3) .")");
        $worksheet->setCellValue('E' . ($cellNext + 2),"=AVERAGE(" . 'G13:G' . ($cellNext - 3) .")");
        $worksheet->setCellValue('F' . ($cellNext + 2),"=AVERAGE(" . 'F13:F' . ($cellNext - 3) .")");
        $worksheet->setCellValue('G' . ($cellNext + 2),"=AVERAGE(" . 'H13:H' . ($cellNext - 3) .")");
        $worksheet->setCellValue('H' . ($cellNext + 2),"=AVERAGE(" . 'I13:I' . ($cellNext - 3) .")");
        //
        //
        $worksheet->getStyle('D' . ($cellNext + 2))->getNumberFormat()->setFormatCode('#,##0"đ"');
        $worksheet->getStyle('E' . ($cellNext + 2))->getNumberFormat()->setFormatCode('#,##0"đ"');
        //$worksheet->getStyle('F' . ($cellNext + 2))->getNumberFormat()->setFormatCode('#,##0"đ"');
        $worksheet->getStyle('G' . ($cellNext + 2))->getNumberFormat()->setFormatCode('#,##0"đ"');
        //$worksheet->getStyle('H' . ($cellNext + 2))->getNumberFormat()->setFormatCode('#,##0"đ"');
        $worksheet->getStyle('C' . $cellNext . ":" . 'H'. ($cellNext + 2))->applyFromArray($styleUserInfo2);
        $worksheet->getStyle('C' . $cellNext . ":" . 'H'. $cellNext)->applyFromArray($styleUserInfo3);
        $worksheet->getStyle('C' . $cellNext . ":" . 'H'. $cellNext)->applyFromArray($colorTbl);
        $worksheet->getStyle('C' . $cellNext . ":" . 'C'. ($cellNext + 2))->applyFromArray($styleUserInfo);
        $worksheet->getStyle('E9:G9')->applyFromArray($colorTbl);
        $worksheet->getStyle('A2:A6')->applyFromArray($colorTbl);
        $worksheet->getStyle('G2:G6')->applyFromArray($colorTbl);

        
        $filename = 'thong-ke-' . Date("d-m-Y",time());
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename . '.xlsx').'"');
        $writer->save('php://output');
        exit();
    }
?>

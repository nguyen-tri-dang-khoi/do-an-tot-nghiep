<?php
    include_once("../lib/database.php");
    logout_session_timeout();
    check_access_token();
    redirect_if_login_status_false();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/left_menu.php");
        // code to be executed get method
?>
<!--html & css section start-->
<div class="container-wrapper" style="margin-left:253px;">
    <div class="container-fluid">
        <section class="content mt-10">
            <div class="row ml-5">
                <div class="col-6 area-filter" style="flex-direction:column">
                    <p style="font-weight:bold;margin:0">Chức năng thống kê: </p>
                    <div class="row mb-20">
                        <select onchange="activeSelect()" class="list-statistic form-control col-6" name="type_statistic">
                            <option value="">Chọn chức năng thống kê</option>
                            <option value="1">Thống kê doanh thu</option>
                            <option value="2">Thống kê số lượng đơn hàng</option>
                        </select>
                    </div>
                    <p style="font-weight:bold;margin:0">Mốc thời gian: </p>
                    <div class="row">
                        <select style="cursor:not-allowed;" onchange="showDataStatistic2()" class="form-control form-group col-3" name="year" disabled>
                            <option value="">Chọn năm</option>
                            <?php
                                for($i = 2022 ; $i <= Date('Y') ; $i++) {
                            ?>
                            <option value="<?=$i;?>"><?=$i;?></option>
                            <?php } ?>
                        </select>
                        <select style="cursor:not-allowed;" onchange="showDay()" class="form-control form-group col-3 mr-10 ml-10" name="month" disabled>
                            <option value="">Chọn tháng</option>
                            <?php
                                for($i = 1 ; $i <= 12 ; $i++) {
                            ?>
                            <option value="<?=$i;?>"><?=$i;?></option>
                            <?php } ?>
                        </select>
                        <select style="cursor:not-allowed;" onchange="showDataStatistic2()" class="form-control form-group col-3" name="day" disabled>
                            <option value="">Chọn ngày</option>
                        </select>
                    </div>
                    <div class="row">
                        <button onclick="showDataStatistic('statistic_excel_download')" class="dt-button button-blue">Xuất file excel báo cáo</button>
                    </div>
                </div>
                <div class="col-6">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </section>
    </div>
</div>

<!--html & css section end-->
<?php
    include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script src="js/chart.min.js"></script>
<script>
    var ctx = document.getElementById('myChart');
    var labels = [];
    var data2 = {
        labels: labels,
        datasets: [{
            label: 'Thống kê',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
        }]
    };
    var config = {
        type: 'line',
        data: data2,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };
    var myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
    function activeSelect(){
        let case2 = $("select[name='type_statistic'] > option:selected").val();
        if(case2 != "") {
            $('.area-filter select').prop("disabled",false);
            $('.area-filter select').css({"cursor":"default"});
        } else {
            $('.area-filter select:not(.list-statistic)').prop("disabled",true);
            $('.area-filter select > option[value=""]').prop("selected",true);
            $('.area-filter select:not(.list-statistic)').css({"cursor":"not-allowed"});
        }
        showDataStatistic2();
    }
    function showDataStatistic(status=""){
        let day = $("select[name='day'] > option:selected").val();
        let month = $("select[name='month'] > option:selected").val();
        let year = $("select[name='year'] > option:selected").val();
        let case2 = "3";
        if(status == "statistic_excel_download") {
            if(year == "" ) {
                return;
            }
            location.href=`ajax_statistic.php?year=${year}&month=${month}&day=${day}&case=${case2}&status=${status}`;
        } else {
            $.ajax({
                url:"ajax_statistic.php",
                type:"POST",
                data: {
                    year: year,
                    month: month,
                    day: day,
                    case: case2,
                },success:function(data2){
                    console.log(data2);
                    data2 = JSON.parse(data2);
                    console.log(data2);
                    if(data2.msg == "ok") {
                        //console.log(data);
                        let _label = [];
                        let _data = [];
                        delete data2['msg'];
                        for(let i2 = 0 ; i2 < data2.label ; i2++) 
                        {
                            _label.push(parseInt(i2) + 1);
                            _data.push(data2[i2]);
                        }
                        delete data2['label'];

                        console.log(_data);
                        if(case2 == 1) {
                            myChart.data.datasets[0].label = "Thống kê doanh thu";
                        } else if(case2 == 2) {
                            myChart.data.datasets[0].label = "Thống kê số lượng đơn hàng";
                        }
                        myChart.data.labels = _label;
                        myChart.data.datasets[0].data = _data;
                        myChart.update();
                    }
                },error:function(data){
                    console.log("Error: " + data);
                }
            })
        }
        
    }
    function showDataStatistic2(){
        let day = $("select[name='day'] > option:selected").val();
        let month = $("select[name='month'] > option:selected").val();
        let year = $("select[name='year'] > option:selected").val();
        let case2 = $("select[name='type_statistic'] > option:selected").val();
        if(year != "") {
            console.log(case2);
            $.ajax({
                url:"ajax_statistic.php",
                type:"POST",
                data: {
                    year: year,
                    month: month,
                    day: day,
                    case: case2,
                },success:function(data2){
                    console.log(data2);
                    data2 = JSON.parse(data2);
                    if(data2.msg == "ok") {
                        let _label = [];
                        let _data = [];
                        delete data2['msg'];
                        for(let i2 = 0 ; i2 < data2.label ; i2++) 
                        {
                            _label.push(parseInt(i2) + 1);
                            _data.push(data2[i2]);
                        }
                        delete data2['label'];

                        console.log(_data);
                        if(case2 == 1) {
                            myChart.data.datasets[0].label = "Thống kê doanh thu";
                        } else if(case2 == 2) {
                            myChart.data.datasets[0].label = "Thống kê số lượng đơn hàng";
                        }
                        myChart.data.labels = _label;
                        myChart.data.datasets[0].data = _data;
                        myChart.update();
                    }
                },error:function(data){
                    console.log("Error: " + data);
                }
            })
        }
    } 
    function showDay() {
        let months_31 = [1,3,5,7,8,10,12]; // tháng 31 ngày
        let months_30 = [4,6,9,11]; // tháng 30 ngày
        let max_day = -1;
        let month = parseInt($("select[name='month'] > option:selected").val());
        let year = $("select[name='year'] > option:selected").val();
        if(months_31.indexOf(month) > -1) {
            max_day = 31;
        } else if(months_30.indexOf(month) > -1){
            max_day = 30;
        } else if(month == 2){
            if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){
                max_day = 29;
            }
            max_day = 28;
        }
        let html = "<option value=''>Chọn ngày</option>";
        for(let i = 1 ; i <= max_day ; i++) {
            html += `<option value="${i}">${i}</option>`;
        }
        $("select[name='day']").empty();
        $(html).appendTo("select[name='day']");
        showDataStatistic2();
    }
</script>

<!--js section end-->
<?php
        include_once("include/footer.php"); 
?>
<?php
    } else if (is_post_method()) {
        
        // code to be executed post method
    }
?>
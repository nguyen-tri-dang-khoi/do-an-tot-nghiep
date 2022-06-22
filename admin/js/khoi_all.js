//tab filter ajax procession
toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
var count_row_z_index = 1000000;
var file_name_config = window.location.href.split('/').pop();
file_name_config = file_name_config.split(".")[0];
var html_config = {
    "category_manage":{
        "load":{
            'tbody_read':'.list-product-type',
        },
        "ins_fast":{
            "thead":
            `
            <table class='table table-bordered' style="height:auto;">
                <thead>
                    <tr>
                        <th>Số thứ tự</th>
                        <th>Tên danh mục</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
            `,
            "tbody":
            `
                <td><input class='kh-inp-ctrl' name='name2' type='text' value=''><p class='text-danger'></p></td>
                <td><button onclick="insMore2('category_manage')" class='dt-button button-blue'>Thêm</button></td>
            `,
            "ins_more":{
                "name2":"Tên danh mục không được để trống",
            },
            "ins_all":{

            }
        },
        "upt_fast":{
            'upt_more':{
                'pt_name':'Tên danh mục không được để trống'
            },
            'upt_more_id':'pt_id',
            'upt_all':'.list-product',
        },
        "read_fast":{
            'modal_read':'#form-product-type3',
            'modal_xl':'#modal-xl3',
            'tbody_read':'.list-product-type',
            'link_read':'ajax_category_manage',
        },
        "del_fast":{
            'tbody_read':'.list-product-type',
        },
        "sort_table":{
            'tbody_read':'.list-product-type',
            'thead_read':{
                '.danh-muc':'.th-danh-muc|string',
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.ngay-tao':'.th-ngay-tao|date',
            }
        }
    },
    "product_manage":{
        "load":{
            'tbody_read':'.list-product',
        },
        "ins_fast":{
            "thead":`
                <table class='table table-bordered' style="height:auto;">
                    <thead>
                    <tr>
                        <th>Số thứ tự</th>
                        <th>Tên sp</th>
                        <th class="w-300">Danh mục</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Mô tả sp</th>
                        <th>Ảnh đại diện</th>
                        <th>Thao tác</th>
                    </tr>
                    </thead> 
            `,
            "tbody":`
                <td><input class='kh-inp-ctrl' name='name_p2' type='text' value=''><p class='text-danger'></p></td>
                <td>
                <div style="display:flex;flex-direction:column;outline:none !important;">
                    <ul tabindex="1" class="col-md-12 ul_menu" style="padding-left:0px;height: 65px;outline:none !important;" id="menu">
                        <li onmouseover='load_menu()' class="parent" style="border: 1px solid #dce1e5;position:relative;">
                            <a href="#">Chọn danh mục</a>
                            <ul class="child aaab">
                            </ul>
                            <input type="hidden" name="category_id">
                        </li>
                    </ul>
                    <nav style='padding-left:0px;' class="col-md-12" aria-label="breadcrumb"></nav>
                    <p class='text-danger'></p>
                </div>
                </td>  
                <td><input class='kh-inp-ctrl' name='count_p2' type='text'  onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='price_p2' type='text'  onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" value=''><p class='text-danger'></p></td>
                <td><textarea class='kh-inp-ctrl' name='desc_p2' value=''></textarea><p class='text-danger'></p></td>
                <td>
                <div data-id="1" class="kh-custom-file" style="background-position:50%;background-size:cover;background-image:url();">
                    <input class="nl-form-control" name="img2[]" type="file" onchange="readURL(this,'1')">
                </div>
                <p class='text-danger'></p>
                </td>
                <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
            `,
        },"read_fast":{
            'modal_read':'#form-product',
            'modal_xl':'#modal-xl',
            'tbody_read':'.list-product',
            'link_read':'ajax_product_info',
        },
        "upt_fast":{
            'tbody_read':'.list-product',
        },
        "del_fast":{
            'tbody_read':'.list-product',
        },
        "sort_table":{
            'tbody_read':'.list-product',
            'thead_read':{
                '.danh-muc':'.th-danh-muc|string',
                '.ten-san-pham':'.th-ten-san-pham|string',
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.so-luong':'.th-so-luong|number',
                '.don-gia':'.th-don-gia|number',
                '.ngay-dang':'.th-ngay-dang|date',
            }
        }
    },
    "coupon_manage": {
        "load":{
            'tbody_read':'.list-coupon',
        },
        "ins_fast":{
            "thead":`
                <table class='table table-bordered' style="height:auto;">
                    <thead>
                    <tr>
                        <th>Số thứ tự</th>
                        <th>Mã khuyến mãi</th>
                        <th>Khuyến mãi (%)</th>
                        <th>Giá trị tối thiểu</th>
                        <th>Giá trị tối đa</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày hết hạn</th>
                        <th>Thao tác</th>
                    </tr>
                    </thead>
            `,
            "tbody":`
                <td><input class='kh-inp-ctrl' name='c_code2' type='text' value='' placeholder='Nhập mã khuyến mãi...'><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='c_discount_percent2' type='text' value='' placeholder='Nhập phần trăm khuyến mãi...'><p class='text-danger'></p></td>
                <td><input onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" class='kh-inp-ctrl' name='c_if_subtotal_min2' type='text' value='' placeholder="Nhập số tiền tối thiểu..."><p class='text-danger'></p></td>
                <td><input onpaste="pasteAutoFormat(event)" onkeyup="allow_zero_to_nine(event)" onkeypress="allow_zero_to_nine(event)" class='kh-inp-ctrl' name='c_if_subtotal_max2' type='text' value='' placeholder="Nhập số tiền tối đa..."><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='c_date_start2' type='text' value='' placeholder="Nhập thời gian bắt đầu..."><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='c_date_end2' type='text' value='' placeholder="Nhập thời gian kết thúc..."><p class='text-danger'></p></td>
                <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
            `,
        },
        "upt_fast":{
            'upt_more':{
                'pt_name':'Tên danh mục không được để trống'
            },
            'upt_more_id':'pt_id',
            'upt_all':'.list-coupon',
        },
        "read_fast":{
            'modal_read':'#form-khuyen-mai',
            'modal_xl':'#modal-xl',
            'tbody_read':'.list-coupon',
            'link_read':'ajax_coupon_manage',
        },
        "del_fast":{
            'tbody_read':'.list-coupon',
        },
        "sort_table":{
            'tbody_read':'.list-coupon',
            'thead_read':{
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.ma-khuyen-mai':'.th-ma-khuyen-mai|string',
                '.khuyen-mai':'.th-khuyen-mai|number',
                '.gia-tri-toi-thieu':'.th-gia-tri-toi-thieu|number',
                '.gia-tri-toi-da':'.th-gia-tri-toi-da|number',
                '.ngay-bat-dau':'.th-ngay-bat-dau|date',
                '.ngay-het-han':'.th-ngay-het-han|date',
                '.ngay-tao':'.th-ngay-tao|date',
            }
        }
    },
    "notification_manage":{
        "load":{
            'tbody_read':'.list-notification',
        },
        "ins_fast":{
            "thead":`
                <table class='table table-bordered' style="min-height:100px;height:auto;">
                    <thead>
                    <tr>
                        <th>Số thứ tự</th>
                        <th>Tiêu đề</th>
                        <th>Nội dung</th>
                        <th>Ảnh đại diện</th>
                        <th>Thao tác</th>
                    </tr>
                    </thead>
            `,
            "tbody":`
                <td><input class='kh-inp-ctrl' name='n_title2' type='text' value=''><p class='text-danger'></p></td>
                <td><textarea class='kh-inp-ctrl' name='n_content2' value=''></textarea><p class='text-danger'></p></td>
                <td>
                <div data-id="1" class="kh-custom-file " style="background-position:50%;background-size:cover;background-image:url();">
                    <input class="nl-form-control" name="img3[]" type="file" onchange="readURL(this)">
                </div>
                <p class='text-danger'></p>
                </td>
                <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
            `,
        },
        "read_fast":{
            'modal_read':'#form-bang-tin',
            'modal_xl':'#modal-xl',
            'tbody_read':'.list-notification',
            'link_read':'ajax_notification',
        },
        "del_fast":{
            'tbody_read':'.list-notification',
        },
        "sort_table":{
            'tbody_read':'.list-notification',
            'thead_read':{
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.tieu-de':'.th-tieu-de|string',
                '.luot-xem':'.th-luot-xem|number',
                '.ngay-tao':'.th-ngay-tao|date',
            }
        }
    },
    "user_manage":{
        "load":{
            'tbody_read':'.list-user',
        },
        "ins_fast":{
            "thead":`
                <table class='table table-bordered' style="height:auto;">
                <thead>
                <tr>
                    <th class='w-150'>Số thứ tự</th>
                    <th>Tên đầy đủ</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Số cmnd</th>
                    <th>Địa chỉ</th>
                    <th>Ngày sinh</th>
                    <th>Chức vụ</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
            `,
            "tbody":`
                <td><input class='kh-inp-ctrl' name='u_fullname2' type='text' value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='u_email2' type='text' value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='u_phone2' type='text' value=''><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' name='u_cmnd2' type='text' value=''><p class='text-danger'></p></td>
                <td><textarea class='kh-inp-ctrl' name='u_address2' value=''></textarea><p class='text-danger'></p></td>
                <td><input class='kh-inp-ctrl' data-date='' name='u_birthday2' type='text' value=''><p class='text-danger'></p></td>
                <td>
                    <select class='form-control'>
                        <option value=''>Chọn chức vụ</option>
                        <option value='officer'>Nhân viên văn phòng</option>
                        <option value='shipper'>Nhân viên giao hàng</option>
                    </select>
                    <p class='text-danger'></p>
                </td>
                <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
            `,
        },
        "read_fast":{
            'modal_read':'#manage_user',
            'modal_xl':'#modal-xl',
            'tbody_read':'.list-user',
            'link_read':'ajax_user',
        },
        "del_fast":{
            'tbody_read':'.list-user',
        },
        "sort_table":{
            'tbody_read':'.list-user',
            'thead_read':{
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.ten-day-du':'.th-ten-day-du|string',
                '.email':'.th-email|string',
                '.so-dien-thoai':'.th-so-dien-thoai|string',
                '.so-cmnd':'.th-so-cmnd|string',
                '.dia-chi':'.th-dia-chi|string',
                '.ngay-sinh':'.th-ngay-sinh|date',
                '.ngay-tao':'.th-ngay-tao|date',
            }
        }
    },
    "category_discount_manage":{
        "load":{
            'tbody_read':'.list-category-discount',
        },
        "ins_fast":{
            "thead":``,
            "tbody":``,
        },
        "read_fast":{
            'modal_read':'#form-danh-muc-khuyen-mai',
            'modal_xl':'#modal-xl',
            'tbody_read':'.list-category-discount',
            'link_read':'ajax_category_discount_manage',
        },
        "del_fast":{
            'tbody_read':'.list-category-discount',
        },
        "sort_table":{
            'tbody_read':'.list-category-discount',
            'thead_read':{
                '.khuyen-mai':'.th-khuyen-mai|number',
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.danh-muc-khuyen-mai':'.th-danh-muc-khuyen-mai|string',
                '.ngay-bat-dau':'.th-ngay-bat-dau|date',
                '.ngay-het-han':'.th-ngay-het-han|date',
                '.ngay-tao':'.th-ngay-tao|date',
            }
        }
    },
    "customer_manage":{
        "load":{
            'tbody_read':'.list-customer',
        },
        "read_fast":{
            'modal_read':'#manage_customer',
            'modal_xl':'#modal-xl',
            'tbody_read':'.list-customer',
            'link_read':'ajax_customer',
        },
        "del_fast":{
            'tbody_read':'.list-customer',
        },
        "sort_table":{
            'tbody_read':'.list-customer',
            'thead_read':{
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.ten-day-du':'.th-ten-day-du|string',
                '.email':'.th-email|string',
                '.so-dien-thoai':'.th-so-dien-thoai|string',
                '.dia-chi':'.th-dia-chi|string',
                '.ngay-sinh':'.th-ngay-sinh|date',
                '.ngay-tao':'.th-ngay-tao|date',
            }
        }
    },
    "order_manage":{
        "load":{
            'tbody_read':'.list-order',
        },
        "read_fast":{
            'modal_read':'#form-order',
            'modal_xl':'#modal-xl',
            'tbody_read':'.list-order',
            'link_read':'ajax_order_manage',
        },
        "sort_table":{
            'tbody_read':'.list-order',
            'thead_read':{
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.ma-hoa-don':'.th-ma-hoa-don|string',
                '.ten-khach-hang':'.th-ten-khach-hang|string',
                '.dia-chi-nhan-hang':'.th-dia-chi-nhan-hang|string',
                '.tong-tien':'.th-tong-tien|number',
                '.tinh-trang-thanh-toan':'.th-tinh-trang-thanh-toan|string',
                '.ngay-tao':'.th-ngay-tao|date',
            }
        }
    },
    "delivery_fee_manage":{
        "load":{
            'tbody_read':'.list-customer',
        },
        "read_fast":{
            'modal_read':'#manage_customer',
            'modal_xl':'#modal-xl',
            'tbody_read':'.list-customer',
            'link_read':'ajax_customer',
        },
        "sort_table":{
            'tbody_read':'.list-delivery-fee',
            'thead_read':{
                '.so-thu-tu':'.th-so-thu-tu|number',
                '.district':'.th-district|string',
                '.ward':'.th-ward|string',
                '.province':'.th-province|string',
                '.phi-van-chuyen':'.th-phi-van-chuyen|number',
                '.ngay-tao':'.th-ngay-tao|date',
            }
        }
    },
};
window.addEventListener('popstate', () => {
    loadDataInTab(document.location,false);
});
function loadDataInTab(url,pushState = true){
    $(".img-load").show();
    $("#is-load").hide();
    if(pushState) {
        window.history.pushState('ok', 'hi', `${url}`);
    }
    setTimeout(() => {
        $('.ok-game-start').load(`${url} #load-all`,() => {
          $('#select-type2').select2();
          $('#pagination').pagination({
              items: $('[dt-items]').attr('dt-items'),
              itemsOnPage:  $('[dt-limit]').attr('dt-limit'),
              currentPage: $('[dt-page]').attr('dt-page'),
              hrefTextPrefix: "?page=",
              hrefTextSuffix: `&`+ $('[dt-url]').attr('dt-url'),
              prevText: "<",
              nextText: ">",
              onPageClick: function(pageNumber,event){
                event.preventDefault();
                loadDataInTab(window.location.protocol + window.location.pathname + `?page=${pageNumber}&` +  $('[dt-url]').attr('dt-url'));
              },
              cssStyle: 'light-theme'
          });
          $("#is-load").show();
          $(".img-load").hide();
          setSortTable(file_name_config);
          showPicker();
          $('[class*=select-type]').select2();
        })
    },500);
}
function loadDataComplete(status = ""){
    let parameters = new URLSearchParams(location.search);
    let page = parameters.get('page');
    let tab_unique = parameters.get('tab_unique');
    let parent_id = parameters.get('parent_id');
    let location_ok = window.location.href;
    if($('input[name=check_all]').is(':checked')) {
        if(page == 1 || page == null) {
            page = parseInt(page) + 1;
        } else if(page > 1){
            page = page - 1;
        }
        location_ok = `${file_name_config}.php?page=${page}&tab_unique=${tab_unique}`;
    } 
    if(status == "Insert") {
        if(parent_id != null) {
            location_ok = `${file_name_config}.php?page=1&tab_unique=${tab_unique}&parent_id=${parent_id}`;
        } else {
            location_ok = `${file_name_config}.php?page=1&tab_unique=${tab_unique}`;
        }
        
    }
    $(`.table-game-start`).load(`${location_ok} #table-${file_name_config}`,() => {
        $('#select-type2').select2();
        $('#pagination').pagination({
            items: $('[dt-items]').attr('dt-items'),
            itemsOnPage:  $('[dt-limit]').attr('dt-limit'),
            currentPage: $('[dt-page]').attr('dt-page'),
            hrefTextPrefix: "?page=",
            hrefTextSuffix: `&`+ $('[dt-url]').attr('dt-url'),
            prevText: "<",
            nextText: ">",
            onPageClick: function(pageNumber,event){
                event.preventDefault();
                loadDataInTab(window.location.protocol + window.location.pathname + `?page=${pageNumber}&` +  $('[dt-url]').attr('dt-url'));
            },
            cssStyle: 'light-theme'
        });
        setSortTable(file_name_config);
        showPicker();
        $('[class*=select-type]').select2();
        window.history.pushState('ok', 'ok', `${location_ok}`);
    })
}
function searchTabLoad(form_id){
    event.preventDefault();
    loadDataInTab(window.location.protocol + window.location.pathname + "?" + $(`${form_id}`).serialize());
}
function saveTabFilter(){
    let tab_urlencode = `http://localhost/project/admin/${file_name_config}.php?1=1`;
    $.ajax({
        url:window.location.href,
        type:"POST",
        data: {
          status:"saveTabFilter",
          tab_urlencode : tab_urlencode,
        },success:function(data) {
          data = JSON.parse(data);
          if(data.msg == "ok") {
            $('.tab-delete').remove();
            let html = `
            <li data-index="${data.tab_index}"  oncontextmenu="focusInputTabName(this)" class="li-tab ">
              <button onclick="loadDataInTab('${data.tab_urlencode}')" class="tab">
                ${data.tab_name}
              </button>
              <span onclick="delTabFilter('')" class="k-tab-delete"></span>
            </li>`
            $(html).appendTo('.ul-tab');
          }
        }
    })
}
function delTabFilter(is_active){
    let evt = $(event.currentTarget);
    let index = evt.closest('.li-tab').attr('data-index');
    $.ajax({
        url: window.location.href,
        type: "POST",
        data: {
          status: "deleteTabFilter",
          index: index,
          is_active_2: is_active,
        },success:function(data){
          data = JSON.parse(data);
          if(data.msg == "ok") {
            if(is_active.trim() == '') {
              let next = evt.closest('li').nextAll();
              evt.closest('li').css({"visibility":"hidden"});
              evt.closest('li').addClass('tab-delete');
              next.animate({'right':'220px'},"fast",() => {
                evt.closest('.li-tab').remove();
                ik = 0;
                $('.k-tab-delete').each(function(){
                  if($(this).closest('.li-tab').hasClass('tab-active')) {
                      $(this).attr('onclick',`delTabFilter('1')`);
                  } else {
                      $(this).attr('onclick',`delTabFilter('')`);
                  }
                  $(this).closest('.li-tab').attr('data-index',ik);
                  ik++;
                })
                next.css({'right':'0px'});
              });
              
            } else if(is_active == 1) {
              loadDataInTab(data.tab_urlencode);
            }
          }
        }
    })
}
function focusInputTabName(evt) {
    event.preventDefault();
    let text = $(evt).find('button').text();
    if(!$(evt).hasClass('tab-active')) {
        $(evt).find('button').replaceWith(`<input onblur="changeTabName(this)" type='text' value='${text}' class='form-control' style='border-radius:0px;height:100%;width:100%;border:none;border-top:5px solid #007bff;'>`)
    } else {
        $(evt).find('button').replaceWith(`<input onblur="changeTabName(this)" type='text' value='${text}' class='form-control' style='border-radius:0px;height:100%;width:100%;border:none;border-top:5px solid #007bff;border-right:1px solid #ddd !important;'>`)
    }
    $(evt).find('input').focus();
    $(evt).find('input').select();
    $(evt).find('span').hide();
}
function changeTabName(evt) {
    let new_tab_name = $(evt).val();
    //console.log(new_tab_name);
    let index = $(event.currentTarget).closest('.li-tab').attr('data-index');
    if(new_tab_name == "") {
        toastr["error"]("Vui lòng không để trống tên tab");
        return;
    } else {
        $.ajax({
            url:window.location.href,
            type:"POST",
            data: {
                status:"changeTabNameFilter",
                new_tab_name : new_tab_name,
                index : index,
            },success:function(data) {
                console.log(data);
                data = JSON.parse(data);
                if(data.msg == "ok") {
                    $(evt).siblings('span').show();
                    $(evt).replaceWith(`<button onclick="location.href='${data.tab_urlencode}'" class="tab">${new_tab_name}</button>`);
                }
            }
        })
    }
}

// checkbox procession
function checkedAll(){
    if($(event.currentTarget).is(':checked')) {
        $('input[name*="check_id"]').prop('checked',true);
        $('input[name*="check_id"]').closest('tr').addClass("selected");
        $('input[name="check_all"]').prop('checked',true);
    } else {
        $('input[name*="check_id"]').prop('checked',false);
        $('input[name*="check_id"]').closest('tr').removeClass("selected");
        $('input[name="check_all"]').prop('checked',false);
    }
}
let begin_shift_click = "";
let end_shift_click = "";
let is_checked = "";
function shiftCheckedRange(parent){
    if(!event.shiftKey) {
        begin_shift_click = $(event.currentTarget).attr('data-shift');
        $(event.currentTarget).closest('tr').toggleClass('selected');
        is_checked = $(event.currentTarget).prop("checked");
    } else if(event.shiftKey) {
        if(end_shift_click == "" && begin_shift_click != "") {
        end_shift_click = $(event.currentTarget).attr('data-shift');
        } else if(begin_shift_click == "") {
        begin_shift_click = $(event.currentTarget).attr('data-shift');
        $(event.currentTarget).closest('tr').toggleClass('selected');
        is_checked = $(event.currentTarget).prop("checked");
        }
        // set property for checkbox
        if(begin_shift_click != "" && end_shift_click != "") {
        if(end_shift_click > begin_shift_click) {
            for(let i = parseInt(begin_shift_click) ; i < parseInt(end_shift_click) + 1 ; i++) {
            $(`[data-shift='${i}']`).prop('checked',is_checked);
            if(is_checked) {
                $(`[data-shift='${i}']`).closest('tr').addClass('selected');
            } else {
                $(`[data-shift='${i}']`).closest('tr').removeClass('selected');
            }
            }
        } else {
            for(let i = parseInt(end_shift_click) ; i < parseInt(begin_shift_click) + 1 ; i++) {
            $(`[data-shift='${i}']`).prop('checked',is_checked);
            if(is_checked) {
                $(`[data-shift='${i}']`).closest('tr').addClass('selected');
            } else {
                $(`[data-shift='${i}']`).closest('tr').removeClass('selected');
            }
            }
        }
        begin_shift_click = end_shift_click = is_checked = "";
        }
    }
    if($('input[name*="check_id"]:checked').length == $(`${parent} tr`).length){
        $('input[name="check_all"]').prop('checked',true);
    } else {
        $('input[name="check_all"]').prop('checked',false);
    }
    console.log(($('input[name*="check_id"]:checked')).length);
}
// table sorting
function setSortTable(){
    let thead_read = html_config[file_name_config]['sort_table']['thead_read'];
    Object.keys(thead_read).forEach(function(ele){
        ele_ = thead_read[ele].split('|');
        $(ele_[0]).attr('onclick',`sortTable(this,'${ele_[1]}','${ele}','asc')`);
    });
}
function sortTable(event,type,class_td,asc_desc = "asc"){
    let tbody_read = html_config[file_name_config]['sort_table']['tbody_read'];
    console.log(tbody_read);
    $('.sort-asc').hide();
    $('.sort-desc').hide();
    let target = $(event);
    let obj_html_td = {};
    $(class_td).each(function(){
      if(obj_html_td[$(this).text().trim()] === undefined) {
        obj_html_td[$(this).text().trim()] = ["<tr>" + $(this).parent().html().trim() + "</tr>"];
      } else {
        obj_html_td[$(this).text().trim()].push("<tr>" + $(this).parent().html().trim() + "</tr>");
      }
    })
    console.log(obj_html_td);
    let html_result = "";
    let result = [];
    if(type == "number") {
      if(asc_desc == "asc") {
        result = Object.keys(obj_html_td).sort(function(a,b){
            a = a.replace(/\.|đ/g,"");
            b = b.replace(/\.|đ/g,"");
            return a-b;
        });
      } else {
        result = Object.keys(obj_html_td).sort(function(a,b){
            a = a.replace(/\.|đ/g,"");
            b = b.replace(/\.|đ/g,"");
            return b-a;
        });
      }
    } else if(type == "string") {
      if(asc_desc == "asc") {
        result = Object.keys(obj_html_td).sort();
      } else {
        result = Object.keys(obj_html_td).sort().reverse();
      }
    } else if(type == "date") {
      if(asc_desc == "asc") {
        result = Object.keys(obj_html_td).sort(function(a,b){
          a = a.split("-");
          a = `${a[2]}-${a[1]}-${a[0]}`;
          b = b.split("-");
          b = `${b[2]}-${b[1]}-${b[0]}`;
          return Date.parse(a)-Date.parse(b);
        });
      } else {
        result = Object.keys(obj_html_td).sort(function(a,b){
          a = a.split("-");
          a = `${a[2]}-${a[1]}-${a[0]}`;
          b = b.split("-");
          b = `${b[2]}-${b[1]}-${b[0]}`;
          return Date.parse(b)-Date.parse(a);
        });
      }
    }
    result.forEach(function(ele,ind){
      html_result += obj_html_td[`${ele}`].join("");
    })
    $(`tbody${tbody_read}`).empty();
    $(html_result).appendTo(`${tbody_read}`);
    if(asc_desc == "asc"){
      target.attr('onclick',`sortTable(this,'${type}','${class_td}','desc')`);
      target.find('.sort-asc').show();
      target.find('.sort-desc').hide();
    } else {
      target.attr('onclick',`sortTable(this,'${type}','${class_td}','asc')`);
      target.find('.sort-desc').show();
      target.find('.sort-asc').hide();
    }
}
function showPicker(){
    $('input[name*="date"]').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
    });
    $('input[name*="birthday"]').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
    });
}
showPicker();
// file procession
function xlsx2input(input,arr_column,arr_name_input) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });
            var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[workbook.SheetNames[0]]);
            console.log(XL_row_object);
            setDataFromXLSX(XL_row_object,arr_column,arr_name_input);
        };
        reader.onerror = function(ex) {
            console.log(ex);
        };
        reader.readAsBinaryString(input.files[0]);
    }
}
function csv2input(input,arr_column,arr_name_input) {
    let arr = [];
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload=function(e){
        arr = reader.result.split(/\r\n|\n/);
        console.log(arr);
        // step 1
        let columns = arr[0].split(/\,/);
        let arr_csv = [];
        arr.shift();
        for(i = 0 ; i < arr.length ; i++) {
            let new_arr = arr[i].split(/\,/);
            //console.log(new_arr);
            let new_obj = {};
            for(j = 0 ; j < columns.length ; j++) {
                new_obj[columns[j]] = new_arr[j];
                //console.log(new_obj);
            }
            arr_csv.push(new_obj);
            }
            console.log(arr_csv);
            setDataFromCSV(arr_csv,arr_column,arr_name_input);
        }
        reader.readAsText(input.files[0]);
    }
}
function setDataFromCSV(arr_csv,arr_csv_columns,arr_input_names) {
    if(arr_csv_columns.every(key => Object.keys(arr_csv[0]).includes(key))) {
        $("[data-plus]").attr("data-plus",arr_csv.length);
        showRow(file_name_config,1);
        let i = 0;
        arr_csv_columns.forEach(function(ele,ind){
            $(`td [name='${arr_input_names[ind]}'].kh-inp-ctrl`).each(function(){
                if(!isNaN(arr_csv[i][ele])) {
                    $(this).val(parseInt(arr_csv[i][ele]).toLocaleString().replace(/\,/g, "."));
                } else {
                    $(this).val(arr_csv[i][ele]);
                }
                i++;
            });
            i = 0; 
        });
    } else {
        $.alert({
            title:"Thông báo",
            content: "Vui lòng nhập đúng tên cột khi đổ dữ liệu"
        });
    }
    $("input[name='read_csv']").val("");
}
function setDataFromXLSX(arr_xlsx,arr_excel_columns,arr_input_names){
    if(arr_excel_columns.every(key => Object.keys(arr_xlsx[0]).includes(key))) {
        $("[data-plus]").attr("data-plus",arr_xlsx.length);
        showRow(1);
        let i = 0;
        arr_excel_columns.forEach(function(ele,ind){
            $(`td [name='${arr_input_names[ind]}'].kh-inp-ctrl`).each(function(){
            if(!isNaN(arr_xlsx[i][ele])) {
                $(this).val(parseInt(arr_xlsx[i][ele]).toLocaleString().replace(/\,/g, "."));
            } else {
                $(this).val(arr_xlsx[i][ele]);
            }
            i++;
            });
            i = 0; 
        });
    } else {
        $.alert({
            title:"Thông báo",
            content: "Vui lòng nhập đúng tên cột khi đổ dữ liệu"
        });
    }
    $("input[name='read_excel']").val("");
}

// crud
function insMore(number = 2){
    $(`#modal-xl${number}`).modal({backdrop: 'static', keyboard: false});
}
function insMore2(){
    let ins_more =  html_config[file_name_config]['ins_fast']['ins_more'];
    let obj_ins_more = {
        'status':'ins_more',
    };
    if(file_name_config == "category_manage") {
        obj_ins_more['parent_id'] = $('[dt-parent-id]').attr('dt-parent-id');
        if(obj_ins_more['parent_id'] == "") {
            obj_ins_more['parent_id'] = null;
        }
    }
    let test = true;
    Object.keys(ins_more).forEach(function(ele){
        obj_ins_more[ele] = $(event.currentTarget).closest('tr').find(`td [name="${ele}"]`).val();
        if(obj_ins_more[ele].trim() == "") {
            $(event.currentTarget).closest('tr').find(`td [name="${ele}"]`).next().text(ins_more[ele]);
            test = false;
            return test;
        }
    });
    if(test) {
        let this2 = $(event.currentTarget);
        $.ajax({
        url: window.location.href,
        type: "POST",
        data: obj_ins_more,
        success: function(data){
            console.log(data);
            data = JSON.parse(data);
            if(data.msg == "ok") {
            $.alert({
                title: "Thông báo",
                content: "Bạn đã thêm dữ liệu thành công",
                buttons: {
                "Ok": function(){
                    this2.text("Đã thêm");
                    this2.prop("disabled",true);
                    this2.css({
                    "border": "1px solid #cac0c0",
                    "color": "#cac0c0",
                    "pointer-events": "none",
                    });
                }
                }
            })
            }
        },error: function(data){
            console.log("Error: " + data);
        }
        })
    }  
}
function uptMore(parent_id = "",tab_unique=""){
    let arr_del = [];
    let tbody_read = html_config[file_name_config]['load']['tbody_read'];
    $(`${tbody_read} td input[name*="check_id"]:checked`).each(function(){
      arr_del.push($(this).val());
    });
    let str_arr_upt = arr_del.join(",");
    if(parent_id != "") {
        loadDataInTab(`${file_name_config}.php?upt_more=1&parent_id=${parent_id}&str=${str_arr_upt}&tab_unique=${tab_unique}`);
    } else {
        loadDataInTab(`${file_name_config}.php?upt_more=1&str=${str_arr_upt}&tab_unique=${tab_unique}`);
    }
}
function uptMore2(){
    let upt_more =  html_config[file_name_config]['upt_fast']['upt_more'];
    let upt_more_id = html_config[file_name_config]['upt_fast']['upt_more_id'];
    let obj_upt_more = {
        status:'upt_more',
    };
    obj_upt_more[upt_more_id] = $(event.currentTarget).attr('data-id');
    let test = true;
    Object.keys(upt_more).forEach(function(ele){
        obj_upt_more[ele] = $(event.currentTarget).closest('tr').find(`td [name="${ele}"]`).val();
        if(obj_upt_more[ele].trim() == "") {
            $(event.currentTarget).closest('tr').find(`td [name="${ele}"]`).next().text(upt_more[ele]);
            test = false;
            return test;
        }
    });
    if(test) {
      let this2 = $(event.currentTarget);
      $.ajax({
        url: window.location.href,
        type: "POST",
        data: obj_upt_more
        ,success: function(data){
          data = JSON.parse(data);
          if(data.msg == "ok"){
            $.alert({
              title: "Thông báo",
              content: "Bạn đã sửa dữ liệu thành công",
              buttons: {
                "Ok": function(){
                    this2.closest('tr').find('.text-danger').text("");
                }
              }
            });
          }
        },error:function(data){
            console.log("Error: " + data);
        }
      });
    }
}
function showRow(page,apply_dom = true){
    let count = $('[data-plus]').attr('data-plus');
    limit = 7;
    if(apply_dom) {
        $('[data-plus]').attr('data-plus',$('input[name=count2]').val());
        $('#form-insert table').remove();
        $('#form-insert #paging').remove();
        html = html_config[file_name_config]['ins_fast']['thead'];
        count2 = parseInt(count / 7);
        g = 1;
        for(i = 0 ; i < count2 ; i++) {
            html += `<tbody style='display:none;' class='t-bd t-bd-${parseInt(i) + 1}'>`;
            for(j = 0 ; j < 7 ; j++) {
            html += `
                <tr data-row-id="${parseInt(g)}">
                    <td>${parseInt(g)}</td>
                    ${html_config[file_name_config]['ins_fast']['tbody']}
                </tr>
            `;
            g++;
            }
            html += "</tbody>";
            
        }
        if(count % 7 != 0) {
            count3 = count % 7;
            html += `<tbody style='display:none;' class='t-bd t-bd-${parseInt(i) + 1}'>`;
            for(k = i ; k < parseInt(count3) + parseInt(i) ; k++) {
                html += `
                    <tr data-row-id="${parseInt(g)}">
                        <td>${parseInt(g)}</td>
                        ${html_config[file_name_config]['ins_fast']['tbody']}
                    </tr>
                `;
                g++;
            }
            html += "</tbody>";
        }
        html += `
            </table>
        `;
        html += `
            <div id="paging" style="justify-content:center;" class="row">
                <nav id="pagination2">
                </nav>
            </div>
        `;
        $(html).appendTo('#form-insert');
        apply_dom = false;
        $('.t-bd-1').css({"display":"contents"});
    } else {
        $('.t-bd').css({"display":"none"});
        $('.t-bd-' + page).css({"display":"contents"});
    }
    $('#pagination2').pagination({
        items: count,
        itemsOnPage: limit,
        currentPage: page,
        prevText: "<",
        nextText: ">",
        onPageClick: function(pageNumber,event){
            event.preventDefault();
            showRow(pageNumber,false);
        },
        cssStyle: 'light-theme',
    });
    count_row_z_index = 1000000;
    if(file_name_config == "product_manage" || file_name_config == "category_discount_manage") {
        for(let h = 0 ; h < count ; h++) {
            $(`[data-row-id='${parseInt(h) + 1}']`).find('.ul_menu').css({'z-index':count_row_z_index});
            count_row_z_index--;
        }
    }
    showPicker();
}
function delEmpty(){
    $.confirm({
      title:"Thông báo",
      content:"Bạn có chắc chắn muốn xoá toàn bộ dòng ?",
      buttons: {
        "Có": function(){
          $('#form-insert table > tbody').remove();
          $('#form-insert #paging').remove();
          $('[data-plus]').attr('data-plus',0);
        },"Không":function(){

        }
      }
    });
   
}
function insRow(){
    num_of_row_insert = $('input[name="count3"]').val();
    let k_first_z_index =  $('[data-plus]').attr('data-plus');
    if(num_of_row_insert == "") {
      $.alert({
        title: "Thông báo",
        content: "Vui lòng không để trống số dòng cần thêm",
      })
      return;
    } 
    for(i = 0 ; i < num_of_row_insert ; i++) {
        let page = $('[data-plus]').attr('data-plus');
        let html = "";
        let count2 = parseInt(page / 7) + 1;
        //count_row_z_index--;
        html = `
            <tr data-row-id='${parseInt(page) + 1}'>
            <td>${parseInt(page) + 1}</td>
            ${html_config[file_name_config]['ins_fast']['tbody']}
            </tr>
        `;
        console.log(html_config[file_name_config]['ins_fast']);
        if(page % 7 != 0) {
            $('.t-bd').css({"display":"none"});
            $(`.t-bd-${parseInt(count2)}`).css({"display":"contents"});
            $(html).appendTo(`.t-bd-${count2}`);
        } else {
            $('.t-bd').css({"display":"none"});
            html = `<tbody style='display:contents;' class='t-bd t-bd-${parseInt(count2)}'>${html}</tbody>`;
            $(html).appendTo('#form-insert table');
        }
        if(page == 0) {
            let html2 = `<div id="paging" style="justify-content:center;" class="row">
                <nav id="pagination2">
                </nav>
            </div>`;
            $(html2).appendTo('#form-insert');
        }
        
        
        $('[data-plus]').attr('data-plus',parseInt(page) + 1);
        $('input[name="count2"]').val(parseInt(page) + 1);
        $('#pagination2').pagination({
            items: parseInt(page) + 1,
            itemsOnPage: 7,
            currentPage: count2,
            prevText: "<",
            nextText: ">",
            onPageClick: function(pageNumber,event){
                event.preventDefault();
                showRow(pageNumber,false);
            },
            cssStyle: 'light-theme',
        });
        showPicker();
    }
    if(file_name_config == "product_manage" || file_name_config == "category_discount_manage") {
        for(let h = parseInt(k_first_z_index) ; h < parseInt(k_first_z_index) + parseInt(num_of_row_insert) ; h++) {
            $(`[data-row-id='${parseInt(h) + 1}']`).find('.ul_menu').css({'z-index':count_row_z_index});
            count_row_z_index--;
        }
    }
}
function delRow(){
    let count_del = $("input[name=count3]").val();
    if(count_del == "") {
      $.alert({
        title: "Thông báo",
        content: "Vui lòng không để trống số dòng cần xoá",
      })
      return;
    } 
    for(i = 0 ; i < count_del ; i++) {
      let page = $('[data-plus]').attr('data-plus');
      if(page < 0) {
        $('[data-plus]').attr('data-plus',0);
        return;
      }
      let currentPage1 = page / 7;
      if(page % 7 != 0) currentPage1 = parseInt(currentPage1) + 1;
      $(`[data-row-id="${page}"]`).remove();
      page--;
      $('[data-plus]').attr('data-plus',page);
      $('input[name="count2"]').val(page);
      currentPage1 = page / 7;
      if(page % 7 != 0) currentPage1 = parseInt(currentPage1) + 1;
      else $(`.t-bd-${parseInt(currentPage1) + 1}`).remove();
      if(page == 0) {
        $('#paging').remove();
      }
      $('.t-bd').css({"display":"none"});
      $(`.t-bd-${parseInt(currentPage1)}`).css({"display":"contents"});
      $('#pagination2').pagination({
        items: parseInt(page),
        itemsOnPage: 7,
        currentPage: currentPage1,
        prevText: "<",
        nextText: ">",
        onPageClick: function(pageNumber,event){
          event.preventDefault();
          showRow(pageNumber,false);
        },
        cssStyle: 'light-theme',
      });
    }
}
function readMore(){
    let arr_del = [];
    console.log(`${html_config[file_name_config]['read_fast']['tbody_read']} td input[name*="check_id"]:checked`);
    let count4 = $(`${html_config[file_name_config]['read_fast']['tbody_read']} td input[name*="check_id"]:checked`).length;
    $(`${html_config[file_name_config]['read_fast']['tbody_read']} td input[name*="check_id"]:checked`).each(function(){
      arr_del.push($(this).val());
    });
    let str_arr_upt = arr_del.join(",");
    if(arr_del.length == 0) {
      $.alert({
        title: "Thông báo",
        content: "Bạn vui lòng chọn dòng cần xem",
      });
      return;
    }
    $(`${html_config[file_name_config]['read_fast']['modal_read']}`).load(`${html_config[file_name_config]['read_fast']['link_read']}.php?status=read_more&str_arr_upt=${str_arr_upt}`,() => {
      let html2 = `
        <div id="paging" style="justify-content:center;" class="row">
          <nav id="pagination3">
          </nav>
        </div>
      `;
      $(html2).appendTo(`${html_config[file_name_config]['read_fast']['modal_read']}`);
      console.log(`${html_config[file_name_config]['read_fast']['modal_xl']}`);
      $(`${html_config[file_name_config]['read_fast']['modal_xl']}`).modal({backdrop: 'static', keyboard: false});
      $('.t-bd-read').css({
        "display":"none",
      });
      $('.t-bd-read-1').css({
        "display":"contents",
      });
      $('#pagination3').pagination({
        items: count4,
        itemsOnPage: 1,
        currentPage: 1,
        prevText: "<",
        nextText: ">",
        onPageClick: function(pageNumber,event){
          event.preventDefault();
          $(`.t-bd-read`).css({"display":"none"});
          $(`.t-bd-read-${pageNumber}`).css({"display":"contents"});
        },
        cssStyle: 'light-theme',
      });
    });
}
function delMore(){
    let arr_del = [];
    let tbody_read = html_config[file_name_config]['del_fast']['tbody_read'];
    let count4 = $(`${tbody_read} td input[name*="check_id"]:checked`).length;
    $(`${tbody_read} td input[name*="check_id"]:checked`).each(function(){
      arr_del.push($(this).val());
    });
    if(count4 > 0) {
      $.confirm({
          title: "Thông báo",
          content: "Bạn có chắc chắn muốn xoá " + count4 + " dòng này",
          buttons: {
            "Có": function(){
                $.ajax({
                  url: window.location.href,
                  type: "POST",
                  data: {
                    status: "del_more",
                    rows: arr_del.join(","),
                  },
                  success: function(data){
                      data = JSON.parse(data);
                      if(data.msg == "ok"){
                        $.alert({
                            title: "Thông báo",
                            content: "Bạn đã xoá dữ liệu thành công",
                        });
                        loadDataComplete();
                      }
                  },error: function(data){
                      console.log("Error:" + data);
                  }
                });
            },"Không": function(){
                
            }
          }
      });
    } else {
      $.alert({
          title: "Thông báo",
          content: "Bạn chưa chọn dòng cần xoá",
      });
    }
}
// modal
$("#modal-xl2").on("hidden.bs.modal",function(){
    $("#form-insert table tbody").remove();
    $("input[name='count2']").val("");
    $("input[name='count3']").val("");
    $("input[name='count2']").attr("data-plus",0);
    $("input[name='count3']").attr("data-plus",0);
    $('#form-insert #paging').remove();
})
$("#modal-xl").on("hidden.bs.modal",function(){
    $("tr").removeClass("bg-color-selected");
})
  

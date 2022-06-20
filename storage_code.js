/*function showRow(page,apply_dom = true){
      let count = $('[data-plus]').attr('data-plus');
      limit = 7;
      if(apply_dom) {
        $('[data-plus]').attr('data-plus',$('input[name=count2]').val());
        $('#form-insert table').remove();
        $('#form-insert #paging').remove();
        let html = `
        <table class='table table-bordered' style="height:auto;">
          <thead>
            <tr>
              <th>Số thứ tự</th>
              <th>Tên danh mục</th>
              <th>Thao tác</th>
            </tr>
          </thead>
        `;
        count2 = parseInt(count / 7);
        g = 1;
        for(i = 0 ; i < count2 ; i++) {
          html += `<tbody style='display:none;' class='t-bd t-bd-${parseInt(i) + 1}'>`;
          for(j = 0 ; j < 7 ; j++) {
            html += `
              <tr data-row-id="${parseInt(g)}">
                <td>${parseInt(g)}</td>
                <td><input class='kh-inp-ctrl' name='name2' type='text' value=''><p class='text-danger'></p></td>
                <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
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
                <td><input class='kh-inp-ctrl' name='name2' type='text' value=''><p class='text-danger'></p></td>
                <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
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
        //console.log(html);
      } else {
        //$('[data-plus]').attr('data-plus',$('input[name=count2]').val());
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
          showRow(pageNumber,false);
        },
        cssStyle: 'light-theme',
      });
    }*/
    
    /*function insRow(){
      num_of_row_insert = $('input[name="count3"]').val();
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
        html = `
          <tr data-row-id='${parseInt(page) + 1}'>
            <td>${parseInt(page) + 1}</td>
            <td><input class='kh-inp-ctrl' name='name2' type='text' value=''><p class='text-danger'></p></td>
            <td><button onclick='insMore2()' class='dt-button button-blue'>Thêm</button></td>
          </tr>
        `;
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
            showRow(pageNumber,false);
          },
          cssStyle: 'light-theme',
        });
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
            showRow(pageNumber,false);
          },
          cssStyle: 'light-theme',
        });
      }
      
    }*/
    /*$(document).ready(function (e) {
      $.fn.dataTable.moment('DD-MM-YYYY');
      $('#first_tab').on('focus', function() {
        $('input[tabindex="1"]').focus();
      });
      $('#btn-ins-fast').on('focus',function(){
        $('input[tabindex="<?=$cnt;?>"]').focus();
      });
      dt_pt = $("#m-product-type").DataTable({
        "sDom": 'RBlfrtip',
        columnDefs: [
          { 
              "name":"pi-checkbox",
              "orderable": false,
              "className": 'select-checkbox',
              "targets": 0
          },{ 
              "name":"manipulate",
              "orderable": false,
              "className": 'manipulate',
              "targets": 5,
              'searchable': false,
          }, 
        ],
        "select": {
          style: 'multi+shift',
          selector: 'td:first-child'
        },
        "order": [
          [1, 'desc']
        ],
        "language": {
            "emptyTable": "Không có dữ liệu",
            "sZeroRecords": 'Không tìm thấy kết quả',
            "infoEmpty": "",
            "infoFiltered":"Lọc dữ liệu từ _MAX_ dòng",
            "search":"Tìm kiếm trong bảng này:",   
            "info":"Hiển thị từ dòng _START_ đến dòng _END_ trên tổng số _TOTAL_ dòng",
            "select": {
              "rows": "Đã chọn %d dòng",
            },
            "buttons": {
              "copy": 'Copy',
              "copySuccess": {
                  1: "Bạn đã sao chép một dòng thành công",
                  _: "Bạn đã sao chép %d dòng thành công"
              },
              "copyTitle": 'Thông báo',
            }
         },
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "searchHighlight": true,
        "paging":false,
        "oColReorder": {
            "bAddFixed":false
        },
        "buttons": [
          {
            "extend": "excel",
            "text": "Excel (2)",
            "key": {
              "key": '2',
            },
            "autoFilter": true,
            "filename": "danh_sach_danh_muc_ngay_<?=Date("d-m-Y",time());?>",
            "title": "Dữ liệu danh mục sản phẩm trích xuất ngày <?=Date("d-m-Y",time());?>",
            "exportOptions":{
              columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "pdf",
            "text": "PDF (3)",
            "key": {
              "key": '3',
            },
            "filename": "danh_sach_danh_muc_ngay_<?=Date("d-m-Y",time());?>",
            "title": "Dữ liệu danh mục sản phẩm trích xuất ngày <?=Date("d-m-Y",time());?>",
            "exportOptions":{
              columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "csv",
            "text": "CSV (4)",
            "charset": 'UTF-8',
            "filename": "danh_sach_danh_muc_ngay_<?=Date("d-m-Y",time());?>",
            "bom": true,
            "key": {
              "key": '4',
            },
            "exportOptions":{
              columns: ':visible:not(.select-checkbox):not(.manipulate)'
            },
          },{
            "extend": "colvis",
            "text": "Ẩn / Hiện cột (7)",
            "columns": ':not(.select-checkbox)',
            "key": {
              "key": '7',
            },
          }
        ]
      });
      dt_pt.buttons().container().appendTo('#m-product-type_wrapper .col-md-6:eq(0)');
      //
      dt_pt.on("click", "th.select-checkbox", function() {
        if ($("th.select-checkbox").hasClass("selected")) {
          dt_pt.rows().deselect();
          $("th.select-checkbox").removeClass("selected");
        } else {
          dt_pt.rows().select();
          $("th.select-checkbox").addClass("selected");
        }
      }).on("select deselect", function() {
        if (dt_pt.rows({
                selected: true
          }).count() !== dt_pt.rows().count()) {
          $("th.select-checkbox").removeClass("selected");
        } else {
          $("th.select-checkbox").addClass("selected");
        }
      });
      // php auto select all rows when focus update all function execute
      <?=$upt_more == 1 ? 'dt_pt.rows().select();' . PHP_EOL . '$("th.select-checkbox").addClass("selected");'.PHP_EOL  : "";?>
    });*/
    /*$("#modal-xl2").on("hidden.bs.modal",function(){
      $("#form-insert table tbody").remove();
      $("input[name='count2']").val("");
      $("input[name='count2']").attr("data-plus",0);
    })
    $("#modal-xl").on("hidden.bs.modal",function(){
      $("tr").removeClass("bg-color-selected");
    })
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
     
    }*/
    /*function loadDataInTab(url){
    //location.href='category_manage.php?tab_unique=all';
    let target = $(event.currentTarget);
    $(".img-load").show();
    $("#is-load").hide();
    setTimeout(() => {
      $('.card-body').load(`${url} #load-all`,() => {
        $('#select-type2').select2();
        $('#pagination').pagination({
          items: $('[dt-items]').attr('dt-items'),
          itemsOnPage:  $('[dt-limit]').attr('dt-limit'),
          currentPage: $('[dt-page]').attr('dt-page'),
          hrefTextPrefix: "<?='?page='; ?>",
          hrefTextSuffix: `&`+ $('[dt-url]').attr('dt-url'),
          prevText: "<",
          nextText: ">",
          onPageClick: function(){

          },
          cssStyle: 'light-theme'
        });
        $("#is-load").show();
        $(".img-load").hide();
      })
    },500);
  }
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
  function shiftCheckedRange(){
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
    if($('input[name*="check_id"]:checked').length == $('.list-product-type tr').length){
      $('input[name="check_all"]').prop('checked',true);
    } else {
      $('input[name="check_all"]').prop('checked',false);
    }
    console.log(($('input[name*="check_id"]:checked')).length);
  }*/
  /*function focusInputTabName(evt) {
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
  function saveTabFilter(){
    let tab_urlencode = "http://localhost/project/admin/category_manage.php?tab_unique=all";
    $.ajax({
        url:window.location.href,
        type:"POST",
        data: {
          status:"saveTabFilter",
          tab_urlencode : tab_urlencode,
        },success:function(data) {
          data = JSON.parse(data);
          if(data.msg == "ok") {
            //location.href=data.tab_urlencode;
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
              location.href=data.tab_urlencode;
            }
          }
        }
    })
  }
  */
     /*function readMore(){
      let arr_del = [];
      let count4 = $('.list-product-type td input[name*="check_id"]:checked').length;
      $('.list-product-type td input[name*="check_id"]:checked').each(function(){
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
      $('#form-product-type3').load(`ajax_category_manage.php?status=read_more&str_arr_upt=${str_arr_upt}`,() => {
        let html2 = `
          <div id="paging" style="justify-content:center;" class="row">
            <nav id="pagination3">
            </nav>
          </div>
        `;
        $(html2).appendTo('#form-product-type3');
        $('#modal-xl3').modal({backdrop: 'static', keyboard: false});
        $('.t-bd-read').css({
          "display":"none",
        });
        $('.tb-bd-read-1').css({
          "display":"contents",
        });
        $('#pagination3').pagination({
          items: count4,
          itemsOnPage: 1,
          currentPage: 1,
          prevText: "<",
          nextText: ">",
          onPageClick: function(pageNumber,event){
            $(`.t-bd-read`).css({"display":"none"});
            $(`.tb-bd-read-${pageNumber}`).css({"display":"contents"});
          },
          cssStyle: 'light-theme',
        });
      });
    }*/
    /*function uptMore(){
      let arr_del = [];
      let count4 = $('.list-product-type td input[name*="check_id"]:checked').length;
      $('.list-product-type td input[name*="check_id"]:checked').each(function(){
        arr_del.push($(this).val());
      });
      let str_arr_upt = arr_del.join(",");
      location.href="category_manage.php?upt_more=1&parent_id=<?=$parent_id;?>&str=" + str_arr_upt;
  }*/
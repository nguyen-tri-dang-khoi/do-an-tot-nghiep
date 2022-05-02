<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/datepicker-vi.js"></script>
<script src="js/jquery.simplePagination.js"></script>
<script src="js/boostrap.bundle.min.js"></script>
<script src="js/adminlte.js"></script>
<script src="js/jquery-confirm.min.js"></script>
<script>
    $('.ui-icon.ui-icon-circle-triangle-w').removeClass('.ui-icon.ui-icon-circle-triangle-w');
    $("input[type='number']").attr("min",0);
    function pasteAutoFormat(e){
        e = e || window.event;
        let pastedData = e.clipboardData.getData('text');
        if (pastedData.match(/^\d+(\.\d+)*$/)){
            let n = parseInt(pastedData.replace(/\./g,''),10);
            if(!isNaN(n)){
                $(e.currentTarget).val(n.toLocaleString().replace(/\,/g, "."));
            } else {
                $(e.currentTarget).val("");
            }
        }
        e.preventDefault();
    }
    function allow_zero_to_nine(e){
        e = e || window.event;
        let charCode = (typeof e.which == "undefined") ? e.keyCode : e.which;
        if(e.keyCode == '8') {
            if (e.which != 110 ){
                let n = parseInt($(e.currentTarget).val().replace(/\./g, ",").replace(/\,/g,''),10);
                if(!isNaN(n)){
                    $(e.currentTarget).val(n.toLocaleString().replace(/\,/g, "."));
                }   
            }
            return;
        }
        let charStr = String.fromCharCode(charCode);
        if (charStr.match(/^[0-9]+$/)){
            if (e.which != 110 ){
                let n = parseInt($(e.currentTarget).val().replace(/\./g, ",").replace(/\,/g,''),10);
                if(!isNaN(n)){
                    $(e.currentTarget).val(n.toLocaleString().replace(/\,/g, "."));
                }   
            }
        } else {
            e.preventDefault();
        }
    }
</script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script>
function ExcelToJSON() {
  this.parseExcel = function(file) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var data = e.target.result;
      var workbook = XLSX.read(data, {
        type: 'binary'
      });
      workbook.SheetNames.forEach(function(sheetName) {
        // Here is your object
        var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
        var json_object = JSON.stringify(XL_row_object);
        console.log(json_object);
      })
    };
    reader.onerror = function(ex) {
      console.log(ex);
    };
    reader.readAsBinaryString(file);
  };
};
</script>-->

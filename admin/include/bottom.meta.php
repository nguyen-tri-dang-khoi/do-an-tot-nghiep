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
    function pasteAutoFormat(){
        event.preventDefault();
        let clipboard_data = event.clipboardData.getData('text');
        if (clipboard_data.match(/^\d+(\.\d+)*$/)){
            let n = parseInt(clipboard_data.replace(/\./g,''),10);
            $(event.currentTarget).val(n.toLocaleString().replace(/\,/g, "."));
        }
    }
    function allow_zero_to_nine(){
        let phim_unicode = event.keyCode;
        let convert_phim_unicode = String.fromCharCode(phim_unicode);
        if(convert_phim_unicode == ".") event.preventDefault();
        if(convert_phim_unicode == "," || $(event.currentTarget).val().indexOf(",") > -1) {
            if($(event.currentTarget).val().indexOf(",") != $(event.currentTarget).val().lastIndexOf(",") || $(event.currentTarget).val() == ",") {
                $(event.currentTarget).val($(event.currentTarget).val().replace(/\,$/, ""));
            }
        } else {
            if(convert_phim_unicode.match(/^\d+$/) || phim_unicode == "8" || phim_unicode == "46") {
                if($(event.currentTarget).val().indexOf(",") > -1) {
                    $(event.currentTarget).val($(event.currentTarget).val().replace(/\,$/,""));
                }
                let n = parseInt($(event.currentTarget).val().replace(/\./g, ""),10);
                if(!isNaN(n)){
                    $(event.currentTarget).val(n.toLocaleString().replace(/\,/g, "."));
                } 
            } else {
                event.preventDefault();
            }
        }
    }
    function blur_number_format(){
        let aka = 6;
        let number2 = $(event.currentTarget).val();
        if(number2 != "") {
            if(number2.indexOf(",") > -1) {
                number2 = number2.replace(/\,/, ".");
            } else {
                number2 = number2.replace(/\./g, "");
            }
            let length_aaa = number2.length;
            let length_bbb = aka - length_aaa;
            if(length_bbb > 0) {
                number2 = number2 * Math.pow(10,aka);
            }
            number2 = parseInt(number2,10);
            $(event.currentTarget).val(number2.toLocaleString().replace(/\,/g, "."));
        }
    }
</script>

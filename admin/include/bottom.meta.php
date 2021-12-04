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

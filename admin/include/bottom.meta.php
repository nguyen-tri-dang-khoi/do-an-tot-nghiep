<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/datepicker-vi.js"></script>
<script src="js/jquery.simplePagination.js"></script>
<script src="js/boostrap.bundle.min.js"></script>
<script src="js/adminlte.js"></script>
<script src="js/jquery-confirm.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
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
        let phim_unicode = event.which == "undefined" ? event.keyCode : event.which;
        let convert_phim_unicode = String.fromCharCode(phim_unicode);
        if(convert_phim_unicode.match(/^[0-9]+$/) || phim_unicode == "8" || phim_unicode == "46") {
            let n = parseInt($(event.currentTarget).val().replace(/\./g, ",").replace(/\,/g,''),10);
            if(!isNaN(n)){
                $(event.currentTarget).val(n.toLocaleString().replace(/\,/g, "."));
            }
        } 
    }
</script>

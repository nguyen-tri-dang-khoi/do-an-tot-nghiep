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
    $("input[type='number']").on('keypress',function(e){
        e = e || window.event;
        var charCode = (typeof e.which == "undefined") ? e.keyCode : e.which;
        var charStr = String.fromCharCode(charCode);
        if (!charStr.match(/^[0-9]+$/)){
            e.preventDefault();
        }
    })
    $("input[type='number']").bind('paste',function(e){
        var pastedData = e.originalEvent.clipboardData.getData('text');
        if (!pastedData.match(/^[0-9]+$/)){
            e.preventDefault();
        }
    })
    function allow_zero_to_nine(e){
        e = e || window.event;
        var charCode = (typeof e.which == "undefined") ? e.keyCode : e.which;
        var charStr = String.fromCharCode(charCode);
        if (!charStr.match(/^[0-9]+$/)){
            e.preventDefault();
        }
    }
</script>

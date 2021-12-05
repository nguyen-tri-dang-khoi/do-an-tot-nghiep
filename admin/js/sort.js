jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "formatted-num-pre": function ( a ) {
        a = (a === "-" || a === "") ? 0 : a.replace( /[^\d\-\.]/g, "" );
        a = a.replace(/[\.]/g,"");
        return parseFloat( a );
    },
 
    "formatted-num-asc": function ( a, b ) {
        a = a.replace(/[\.]/g,"");
        b = b.replace(/[\.]/g,"");
        return a - b;
    },
 
    "formatted-num-desc": function ( a, b ) {
        a = a.replace(/[\.]/g,"");
        b = b.replace(/[\.]/g,"");
        return b - a;
    }
} );
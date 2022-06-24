<script>
    $(function() {
        $('#pagination').pagination({
            items: <?=$total;?>,
            itemsOnPage: <?=$limit;?>,
            currentPage: <?=$page;?>,
            hrefTextPrefix: "",
            hrefTextSuffix: "",
            prevText: "<",
            nextText: ">",
            onPageClick: function(pageNumber,event){
                event.preventDefault();
                let url = new URLSearchParams(window.location.search);
                if(url.has('page')) url.delete('page');
                url.set('page',pageNumber);
                loadDataInTab(`${file_name_config}.php?${url.toString()}`);
            },
            cssStyle: 'light-theme'
        });
    });
</script>
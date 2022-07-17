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
                if(file_name_config == 'product_comment_manage') {
                    location.href = `product_comment_manage.php?${url.toString()}`;
                } else {
                    loadDataInTab(`${file_name_config}.php?${url.toString()}`);
                }
                
            },
            cssStyle: 'light-theme'
        });
    });
</script>
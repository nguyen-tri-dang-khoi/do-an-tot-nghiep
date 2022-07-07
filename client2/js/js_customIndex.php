<script>
    $('.title_producer').click(function() {
        let temp = $(this).attr('class');
        $(this).siblings().toggleClass('hidden_class');
        $(this).find('i').toggleClass('activeClassI');
    });
    $('.title_price').click(function() {
        let temp = $(this).attr('class');
        $(this).siblings().toggleClass('hidden_class');
        $(this).find('i').toggleClass('activeClassI');
    });

    // toastr.options = {
    //     "closeButton": false,
    //     "debug": false,
    //     "newestOnTop": false,
    //     "progressBar": false,
    //     "positionClass": "toast-bottom-right",
    //     "preventDuplicates": false,
    //     "onclick": null,
    //     "showDuration": "300",
    //     "hideDuration": "1000",
    //     "timeOut": "5000",
    //     "extendedTimeOut": "1000",
    //     "showEasing": "swing",
    //     "hideEasing": "linear",
    //     "showMethod": "fadeIn",
    //     "hideMethod": "fadeOut"
    // }



    $('#exampleModal').on('shown.bs.modal',
        function() {
            $('#view_cart').empty();
            $.ajax({
                url: `cart_ok.php`,
                type: "POST",
                data: {
                    'thao_tac': 'loadCart',
                },
                success: function(data) {
                    console.log(data);
                    let result = JSON.parse(data);
                    // console.log(result);
                    let html = "";
                    let totalProduct = 0;
                    let totalPrice = 0;
                    if (result.msg == "ok") {
                        let arr = Object.keys(result['cart']);
                        for (let i = 0; i < arr.length; i++) {
                            let value = result['cart'][arr[i]];
                            //let price2 = value['price']+"đ";
                            html += `<div data-id = "${arr[i]}" class="items_cart">
                                        <div class="img_products"><img src=" ../admin/${value['img']}" alt="#"></div>
                                        <div class="info_products">
                                            <div class="name_products"><p>${value['name']}</p></div>
                                            <div class="Price_products"><p>${parseInt(value['price']).toLocaleString().replace(/\,/g,".")}đ</p></div>
                                        </div>
                                        <div class="change_product">
                                            <div><span onclick="updateInfoCart('-')">-</span><input name='count' readonly type="text" value="${value['count']}"> <span onclick="updateInfoCart('+')">+</span></div>
                                            <div class="i-product">
                                                <i onclick = "deleteCart()" class="fa-solid fa-trash-can"></i>
                                            </div>
                                        </div>
                                    </div>`;
                            totalProduct += parseInt(value['count']);
                            console.log(totalProduct);
                            totalPrice += parseInt(value['price'] * value['count']);
                        }
                        let totalId = arr.length;
                        $(".modal-title > span").text(totalId);
                        $(".totalProduct > span").text(totalProduct);
                        totalPrice = totalPrice.toLocaleString().replace(/\,/g, ".") + "đ";
                        $(".totalPrice > span").text(totalPrice);
                        $(html).appendTo('#view_cart'); // them html vao view_cart
                        $('#exampleModal').modal('show');
                        $('.cart--amount').text(totalProduct);
                    }
                }
            });
        })
    $('#exampleModal').on('hidden.bs.modal', function() {
        $('#view_cart').empty();
    })

    function activeclass() {

    }

    function updateInfoCart(thaotac) {
        let count = $(event.currentTarget).siblings('input[name="count"]').val();
        let totalProduct = $('.totalProduct > span').text();
        let price = $(event.currentTarget).closest('.change_product').siblings('.info_products').find('.Price_products p').text();
        let totalPrice = $('.totalPrice > span').text();
        price = price.replace(/\.|đ/g, "");
        totalPrice = totalPrice.replace(/\.|đ/g, "");

        let id = $(event.currentTarget).closest(".items_cart").attr("data-id");
        let cartamount = $('.cart--amount').text();

        // console.log(cartamount);
        // console.log("id:"+id);
        // console.log(count);
        // console.log(price);
        // console.log(totalProduct);
        // console.log(totalPrice)

        if (thaotac == "-") {
            count--;
            if (count < 0) {
                return;
            } else if (count == 0) {
                let temp = $(event.currentTarget);
                let id = $(event.currentTarget).closest(".items_cart").attr('data-id');
                let count = $(event.currentTarget).closest(".change_product").find("div input[name='count']").val();
                let price = $(event.currentTarget).closest('.change_product').siblings('.info_products').find('.Price_products p').text();
                price = price.replace(/\./g, "");
                let cartamount = $('.cart--amount').text();
                let totalProduct = cartamount;
                let totalPrice = $('.totalPrice > span').text();
                price = price.replace(/\.|đ/g, "");
                totalPrice = totalPrice.replace(/\.|đ/g, "");
                let totalId = $('#exampleModalLabel span').text();

                $.ajax({
                    url: `cart_ok.php`,
                    type: "POST",
                    data: {
                        "id": id,
                        "thao_tac": "deleteCart",
                    },
                    success: function(data) {
                        temp.closest(".items_cart").remove();
                        cartamount = parseInt(cartamount) - parseInt(count);
                        console.log(count);
                        console.log(price);
                        totalPrice = parseInt(totalPrice) - parseInt(count * price);
                        $('.cart--amount').text(cartamount);
                        $('.totalProduct span').text(cartamount);
                        $('.totalPrice span').text(totalPrice.toLocaleString().replace(/\,/g, ".") + "đ");
                        totalId--;
                        $('#exampleModalLabel span').text(totalId);

                    }
                });
                return;
            } else {
                $('.totalProduct > span').text(parseInt(totalProduct) - 1);
                $(event.currentTarget).siblings('input[name="count"]').val(count);
                $('.totalPrice > span').text((parseInt(totalPrice) - parseInt(price)).toLocaleString().replace(/\,/g, ".") + "đ");
                //totalprice = $('.totalPrice > span').text();
                cartamount--;
                $('.cart--amount').text(cartamount);
            }

        } else if (thaotac == "+") {
            count++;
            $('.totalProduct > span').text(parseInt(totalProduct) + 1);
            $(event.currentTarget).siblings('input[name="count"]').val(count);
            $('.totalPrice span').text((parseInt(totalPrice) + parseInt(price)).toLocaleString().replace(/\,/g, ".") + "đ");
            cartamount++;
            $('.cart--amount').text(cartamount);
        }
        $.ajax({
            url: `cart_ok.php`,
            type: "POST",
            data: {
                "id": id,
                "count": count,
                "price": price,
                "thao_tac": "updateInfoCart",
            },
            success: function(data) {
                $('#exampleModal').modal('show');
            }
        });


    }

    function deleteCart() {
        let temp = $(event.currentTarget);
        let id = $(event.currentTarget).closest(".items_cart").attr('data-id');
        let count = $(event.currentTarget).closest(".change_product").find("div input[name='count']").val();
        let price = $(event.currentTarget).closest('.change_product').siblings('.info_products').find('.Price_products p').text();
        let cartamount = $('.cart--amount').text();
        let totalProduct = cartamount;
        let totalPrice = $('.totalPrice > span').text();
        totalPrice = totalPrice.replace(/\.|đ/g, "");
        price = price.replace(/\.|đ/g, "");
        let totalId = $('#exampleModalLabel span').text();

        $.ajax({
            url: `cart_ok.php`,
            type: "POST",
            data: {
                "id": id,
                "thao_tac": "deleteCart",
            },
            success: function(data) {
                temp.closest(".items_cart").remove();
                cartamount = parseInt(cartamount) - parseInt(count);
                console.log(count);
                console.log(price);
                totalPrice = parseInt(totalPrice) - parseInt(count * price);
                $('.cart--amount').text(cartamount);
                $('.totalProduct span').text(cartamount);
                $('.totalPrice span').text(totalPrice.toLocaleString().replace(/\,/g, ".") + "đ");
                totalId--;
                $('#exampleModalLabel span').text(totalId);

            }
        });
    }

    function deleteAllCart() {
        let temp = $(event.currentTarget);
        let cartamount = $('.cart--amount ').text();
        $.ajax({
            url: `cart_ok.php`,
            type: "POST",
            data: {
                'thao_tac': 'deleteAllCart',
            },
            success: function(data) {
                $('.cart--amount').text(0);
                $("#view_cart  div").remove();
                $("#exampleModalLabel > span").text(0);
                $(".totalProduct  > span").text(0);
                $(".totalPrice  > span").text(0 + "đ");
            }
        });
    }

    function addToCart() {
        //console.log('khoideptrai');
        console.log($(event.currentTarget));
        let id = $(event.currentTarget).attr('data-id'); //lay du lieu sau khi bat su kien (click) bang currentTarget
        let name = $(event.currentTarget).attr('data-name');
        let price = $(event.currentTarget).attr('data-price');
        let img = $(event.currentTarget).attr('data-img');
        //let page = $(event.currentTarget).attr('data-page');
        console.log(name);
        console.log(id);
        console.log(price);
        $.ajax({
            url: `cart_ok.php`,
            type: "POST",
            data: {
                "id": id,
                'count': 1,
                'name': name,
                'price': price,
                'img': img,
                'thao_tac': 'addCart',
            },
            success: function(data) {
                $('#exampleModal').modal('show');
            }
        });

    }
</script>
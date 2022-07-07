<?php
                        $sql_load_img = "select * from product_image where product_info_id = '$id' order by img_order asc";
                        $image_result = mysqli_query($conn, $sql_load_img);
                        $number_slide = 0;
                    ?>
                    <div class="carousel-indicators">
                        <?php
                            while($row_image = mysqli_fetch_assoc($image_result)) {
                        ?>
                            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="<?php echo $number_slide;?>" class="<?=$row_image['img_order'] == 1 ? "active" : "";?>" <?=$row_image['img_order'] == 1 ? 'aria-current="true"' : "";?> aria-label="Slide <?php echo ($number_slide + 1);?>"></button>
                        <?php
                                $number_slide++;
                            }
                        ?>
                    </div>  
                    
                    <div class="carousel-inner">
                        <?php
                            $sql_load_img = "select * from product_image where product_info_id = '$id' order by img_order asc";
                            $image_result = mysqli_query($conn, $sql_load_img);
                            while($row_image = mysqli_fetch_assoc($image_result)) {
                                //echo "aaaaaaaaav";
                        ?>
                                <div class="carousel-item <?=$row_image['img_order'] == 1? "active" : "";?>" data-bs-interval="10000">
                                    <img src="<?php echo $row_image['img_id'];?>" class="d-block w-80 m-auto" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                        <!-- <div class="carousel-item" data-bs-interval="2000">
                            <img src="Img/product/giado01b.jpg" class="d-block w-80 m-auto" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="Img/product/giado01c.jpg" class="d-block w-80 m-auto" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="Img/product/giado01d.jpg" class="d-block w-80 m-auto" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                            </div>
                        </div> -->
                    </div>
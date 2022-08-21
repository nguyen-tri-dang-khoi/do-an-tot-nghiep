<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            box-sizing:border-box;
        }
        body {
            position: relative;
            height: 100vh;
        }
        .aaa {
            height:200px;
            width:200px;
            background-color:#ffeb3b;
            position: absolute;
            top:200px;
            left:400px;
            border:5px dashed red;
        }
    </style>
</head>
<body onmousedown="showToaDo2(event)" onmouseup="showToaDo3(event)" onmousemove="showToaDo(event)">
    <div id="bbb" class="aaa"></div>
    <script>
        let bbb = document.getElementById('bbb');
        let aaa = document.getElementsByTagName('body')[0];
        let test = false;
        let count = 0;
        let x_ = "", y_ = "";
        let current_x = 400,current_y = 200;
        function showToaDo(e){
            if(test) {
                if(count == 0) {
                    count++;
                    x_ = parseInt(e.clientX) - current_x;
                    y_ = parseInt(e.clientY) - current_y;
                } else {
                    aaa.style.cursor = "move";
                    let x = parseInt(e.clientX) - x_;
                    let y = parseInt(e.clientY) - y_;
                    bbb.style.left = x + "px";
                    bbb.style.top = y + "px";
                    current_x = x;
                    current_y = y;
                }
            }
        }
        function showToaDo2(e){
            if(e.target.id == "bbb") {
                bbb.style.cursor = "move";
                test = true;
                // let x = parseInt(e.clientX) - current_x;
                // let y = parseInt(e.clientY) - current_y;
                // console.log(`x: ${x}, y: ${y}`);
            }
        }
        function showToaDo3(e) {
            aaa.style.cursor = "default";
            bbb.style.cursor = "default";
            test = false;
            count = 0;
        }
        // // bbb.onmouse
        // bbb.onmousedown = function(e){
        //     bbb.style.cursor = "move";
        //     test = true;
        //     showToaDo(e);
        // }
        // bbb.onmouseup = function(e){
        //     bbb.style.cursor = "default";
        //     test = false;
        //     showToaDo(e);
        // }
        // bbb.onmouseleave = function(e) {
        //     bbb.style.cursor = "default";
        //     test = false;
        //     showToaDo(e);
        // }
        // bbb.onmousemove = function(e){
        //     bbb.style.cursor = "move";
        //     let setGameInterval = setInterval(() => {
        //         if(test) {
        //             showToaDo(e);
        //         } else {
        //             clearInterval(setGameInterval);
        //         }
        //     },100);
        // }
    </script>
</body>
</html>
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
            padding:0;
            margin:0;
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
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 200px;
            position: absolute;
            top:700px;
            left:500px;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>
<body onmousedown="showToaDo2(event,'bbb')" onmouseup="showToaDo3(event,'bbb')" onmousemove="showToaDo(event,'bbb')">
    <div id="bbb" class="aaa"></div>
    <table>
        <tr>
            <th style="width:200px;">a</th>
        </tr>
        <tr>
            <td>a</td>
            
        </tr>
        <tr>
            <td>a</td>
            
        </tr>
        <tr>
            <td>a</td>
            
        </tr>
    </table>
    <script>
        //let bbb = document.getElementById('bbb');
        let aaa = document.getElementsByTagName('body')[0];
        let test = false;
        let count = 0;
        let x_ = "", y_ = "";
        function showToaDo(e,target_id){
            let id = document.getElementById(target_id);
            if(test) {
                if(count == 0) {
                    count++;
                    x_ = parseInt(e.clientX) - Math.round(id.getBoundingClientRect().left);
                    y_ = parseInt(e.clientY) - Math.round(id.getBoundingClientRect().top);
                } else {
                    aaa.style.cursor = "move";
                    let x = parseInt(e.clientX) - x_;
                    let y = parseInt(e.clientY) - y_;
                    id.style.left = x + "px";
                    id.style.top = y + "px";
                }
            }
        }
        function showToaDo2(e,target_id){
            let id = document.getElementById(target_id);
            if(e.target.id == target_id) {
                id.style.cursor = "move";
                test = true;
            }
        }
        function showToaDo3(e,target_id) {
            let id = document.getElementById(target_id);
            aaa.style.cursor = "default";
            id.style.cursor = "default";
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
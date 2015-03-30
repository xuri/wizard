// 消息提醒部分

var nav_message = document.getElementById('nav_message'); // 消息提醒盒子
var nav_msg_title = nav_message.getElementsByClassName('nav_message_title')[0]; // 消息提醒标题
var nav_msg_list = nav_message.getElementsByClassName('nav_message_list')[0]; // 消息列表ul

// 点击消息提醒标题
nav_msg_title.onclick = function(event) {

        // 如果有未查看消息才显示
        for (var i = 0; i < nav_msg_list.children.length; i++) {

            if (nav_msg_list.children[i].style.display == 'block') {
                nav_msg_list.style.display = 'block';

                var event = event || window.event;
                if (event.stopPropagation) {
                    event.stopPropagation();
                } else {
                    event.cancelBubble = true;
                }
            }

        }
    }
    // 消息列表ul也要阻止事件冒泡
nav_msg_list.onclick = function(event) {
    var event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
}

var oNavMain = document.getElementById('nav_main'),
    oNav = document.getElementById('nav_left').getElementsByTagName('ul')[0],
    nav = document.getElementById('nav_left'),
    navNum = false;
oNavMain.onmouseover = function() {
    if (navNum == true) {
        return false;
    } else {
        navS();
    }
}

function navS() {
        if (navNum == false) {
            nav.style.left = '0px';
            navNum = true;
        } else {
            nav.style.left = '-60px';
            navNum = false;
        }
    }
    // function stopBubble(e){
    // 	var e=e||windw.event;
    //     if(e&&e.stopPropagation){
    //         e.stopPropagation();
    //     }else{
    //         e.cancelBubble = true;
    //     }
    // }
oNavMain.onclick = function(e) {
    if (navNum == true) {
        var arr = oNav.getElementsByTagName('li');
        for (var i = 0; i < arr.length; i++) {
            arr[i].style.width = '60px';
        }
    }
    navS();
    var e = e || event;
    e.cancelBubble = true;
};
oNav.onmouseover = function() {
    var arr = oNav.getElementsByTagName('li');
    for (var i = 0; i < arr.length; i++) {
        arr[i].style.width = '150px';
    }
}
oNav.onclick = function(e) {
    var e = e || windw.event;
    e.cancelBubble = true;
}
document.onclick = function() {
    var arr = oNav.getElementsByTagName('li');
    for (var i = 0; i < arr.length; i++) {
        arr[i].style.width = '60px';
    }
    if (navNum == false) {

    } else {
        navS();
    }
}
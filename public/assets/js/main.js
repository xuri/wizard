window.onload = function() {
    //alert(document.body.scrollTop);
    var oBoy = document.getElementById('boy'),
        oGirl = document.getElementById('girl'),
        body = document.getElementsByTagName('body')[0],
        arr = new Array(),
        oH = document.documentElement.clientHeight,
        oW = document.documentElement.clientWidth,
        oTimer = null,
        a = 1,
        data = [363, 294, 180],
        paperNum = null, //5个Div的对应数
        moveNum = 0; //触发滚动事件时候使用，用于记录向上滚动还是向下滚动。
    boxSize(); //初始化div高度
    /**
     *getPaper();用于获得当前可视区的div是哪个。
     *stop();阻止鼠标滚动事件。
     *
     */
    function getPaper() {
        if (document.body.scrollTop == document.documentElement.scrollTop) {
            var paper = 0;
        } else {
            if (document.body.scrollTop > document.documentElement.scrollTop) {
                var paper = document.body.scrollTop;
            } else {
                var paper = document.documentElement.scrollTop;
            }
        }
        paperNum = Math.ceil(paper / oH) + 1;
    }


    /**
     *改变浏览器窗口大小的时候给div设置对应的高度。
     *
     */

    window.onresize = function() { //当浏览器窗口尺寸发生变化的时候给div高度重新复制
            boxSize();
        }
        /**
         *设置5个div的高度。
         *
         */
    function boxSize() {
        document.documentElement.clientHeight < 500 ? oH = 640 : oH = document.documentElement.clientHeight;
        if (document.documentElement.clientWidth < 1000) {
            body.style.width = '1000px';
        } else {
            body.style.width = '100%';
        }
        for (i = 1; document.getElementById(i) != null; i++) {
            arr.push(document.getElementById(i));
            var oDiv = document.getElementById(i);
            oDiv.style.height = oH + 'px';
        }
    }

    /**
     *heartMove();boy();girl();是三个动画效果。
     */
    var girl = function() {
        setTimeout(function() {
            oGirl.style.height = 220 + 'px';
            oGirl.style.width = 159 + 'px';
        }, 800);
    };
    var boy = function() {
        setTimeout(function() {
            oBoy.style.webkitTransform = 'rotate(0deg)';
            oBoy.style.oTransform = 'rotate(0deg)';
            oBoy.style.msTransform = 'rotate(0deg)';
            oBoy.style.mozTransform = 'rotate(0deg)';
            oBoy.style.transform = 'rotate(0deg)';
        }, 1000);
    };
    var heartMove = function() {
        oTimer = setInterval(function() { //三个心出现的效果
            if (a == 4) {
                clearInterval(oTimer);
            } else {
                document.getElementById('xin' + a).style.opacity = 1;
                document.getElementById('xin' + a).style.marginTop = data[a - 1] + 'px';
                a++;
            }
        }, 500);
    }

    var aFn = [heartMove, boy, girl]; //三个动画特效数组。

    /**
     *scrollFunc();鼠标滚动时，div滚动并且出现动画效果。其中用toStop是针对jquery.scrolltop.js中滚动事件的监听，防止触发滚动事件。
     *
     */

    function scrollFunc(e) {
        var direct = 0;
        e = e || window.event;
        getPaper();
        if (e.wheelDelta) { //IE/Opera/Chrome
            moveNum = e.wheelDelta;
            if (moveNum > 0) {
                if (paperNum != 6) {
                    scroll(paperNum - 1);
                    if (paperNum - 2 < 3) {
                        if (paperNum < 2) {
                            paperNum = 2;
                        }
                        aFn[paperNum - 2]();
                    } else {}
                    toStop(paperNum - 1);
                    return false;
                }
            } else {
                if (paperNum != 5) {
                    scroll(paperNum + 1);
                    if (paperNum < 3) {
                        aFn[paperNum]();
                    } else {}
                    toStop(paperNum + 1);
                    return false;
                }
            }
        } else if (e.detail) { //Firefox
            moveNum = e.detail;
            if (moveNum < 0) {
                if (paperNum != 6) {
                    scroll(paperNum - 1);
                    if (paperNum - 2 < 3) {
                        aFn[paperNum - 2]();
                    }
                    toStop(paperNum - 1);
                    e.preventDefault();
                }
            } else {
                if (paperNum != 5) {
                    scroll(paperNum + 1);
                    if (paperNum < 3) {
                        aFn[paperNum]();
                    }
                    toStop(paperNum + 1);
                    e.preventDefault();
                }
            }
        }
    }




    /*注册事件*/
    if (document.addEventListener) {
        document.addEventListener('DOMMouseScroll', scrollFunc, false);
    }
    document.onmousewheel = scrollFunc; //IE/Opera/Chrome/Safari


    getPaper();
    if (paperNum < 4) {
        aFn[paperNum - 1]();
    }


    function stopDefault(e) {
        var event = e || window.event;
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }
    }

    /**
     *阻止鼠标滚动事件。
     */
    var stop = function(e) {
        e = e || window.event;
        e.preventDefault();
        return false;
    }

    function toStop(num) {
        var oTimer = null;
        var obj = document.getElementById(num);
        var oTop = null;
        if (num < 7) {
            oTimer = setInterval(function() {
                if (document.documentElement.scrollTop == document.body.scrollTop) {
                    oTop = 0;
                } else if (document.documentElement.scrollTop > 0) {
                    oTop = document.documentElement.scrollTop;
                } else {
                    oTop = document.body.scrollTop;
                }
                if (oTop == obj.offsetTop) {
                    if (document.addEventListener) {
                        document.removeEventListener('DOMMouseScroll', stop, false);
                        document.addEventListener('DOMMouseScroll', scrollFunc, false);
                    }
                    document.onmousewheel = scrollFunc;
                    clearInterval(oTimer);
                } else {
                    if (document.addEventListener) {
                        document.removeEventListener('DOMMouseScroll', scrollFunc, false);
                        document.addEventListener('DOMMouseScroll', stop, false);
                    }
                    document.onmousewheel = stop;
                }
            }, 1)
        }
    }
}
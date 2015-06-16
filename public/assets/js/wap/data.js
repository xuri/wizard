$('.select-birth').click(function(){
    var hse = new Hselect({
        selectTplId : 'birth-box',
        defaultSelect : 0,  // 默认初始选项
        completeFn : function($elem){
            $('.select-birth').html($elem.html());
        }
    });
});



$('.select-constellation').click(function(){
    var hse = new Hselect({
        selectTplId : 'constellation-box',
        defaultSelect : 0,  // 默认初始选项
        completeFn : function($elem){
            $('.select-constellation').html($elem.html());
        }
    });
});


$('.select-school-year').click(function(){
    var hse = new Hselect({
        selectTplId : 'schoolyear-box',
        defaultSelect : 0,  // 默认初始选项
        completeFn : function($elem){
            $('.select-school-year').html($elem.html());
        }
    });
});

$('.submit-data').on('click', function(){
    var born_year         = $('.select-birth').html();
    var constellation_str = $('.select-constellation').html();
    var grade             = $('.select-school-year').html();
    var bio_str           = $('textarea.peo-intr').val();

    switch (constellation_str) {
        case '水瓶座':
            var constellation = 1;
            break;

        case '双鱼座':
            var constellation = 2;
            break;

        case '白羊座':
            var constellation = 3;
            break;

        case '金牛座':
            var constellation = 4;
            break;

        case '双子座':
            var constellation = 5;
            break;

        case '巨蟹座':
            var constellation = 6;
            break;

        case '狮子座':
            var constellation = 7;
            break;

        case '处女座':
            var constellation = 8;
            break;

        case '天秤座':
            var constellation = 9;
            break;

        case '天蝎座':
            var constellation = 10;
            break;

        case '射手座':
            var constellation = 11;
            break;

        case '摩羯座':
            var constellation = 12;
            break;

        default:
            var constellation = 12;
            break;
    }

    if (bio_str == '') {
        var bio = '未填写';
    } else {
        var bio = bio_str;
    }
    window.location.href = set_data_url + '?born_year=' + born_year + '&constellation=' + constellation + '&grade=' + grade + '&bio=' + bio;
})
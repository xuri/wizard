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

$('.select-salary').click(function(){
    var hse = new Hselect({
        selectTplId : 'salary-box',
        defaultSelect : 0,  // 默认初始选项
        completeFn : function($elem){
            $('.select-salary').html($elem.html());
        }
    });
});

$('.submit-data').on('click', function(){
    var born_year         = $('.select-birth').html();
    var constellation_str = $('.select-constellation').html();
    var grade             = $('.select-school-year').html();
    var salary_str        = $('.select-salary').html();

    var hobbies_str       = $('textarea.hobbies-intr').val();
    var bio_str           = $('textarea.peo-intr').val();

    switch (salary_str) {
        case '在校学生':
            var salary = 0;
            break;

        case '0-2000':
            var salary = 1;
            break;

        case '2000-5000':
            var salary = 2;
            break;

        case '5000-9000':
            var salary = 3;
            break;

        case '9000以上':
            var salary = 4;
            break;

        default:
            var salary = 0;
            break;
    }

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

    if (bio_str == '' || hobbies_str == '') {

        if (bio_str == '') {
            alert('介绍一下你自己吧~');
        }

        if (hobbies_str == '') {
            alert('不要忘记填写你的爱好哦');
        }

    } else {
        var bio     = bio_str;
        var hobbies = hobbies_str;
        window.location.href = set_data_url + '?born_year=' + born_year + '&constellation=' + constellation + '&grade=' + grade + '&bio=' + bio + '&salary='  + salary + '&hobbies='  + hobbies;
    }

})
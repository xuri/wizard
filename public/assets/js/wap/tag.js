// 标签配置json
var tagConfig = {
	1 : '高冷',
	2 : '颜控',
	3 : '女神',
	4 : '萌萌哒',
	5 : '治愈系',
	6 : '小清新',
	7 : '女王范',
	8 : '天然呆',
	9 : '萝莉',
	10 : '静待缘分',
	11 : '减肥ing',
	12 : '戒烟ing',
	13 : '缺爱ing',
	14 : '暖男',
	15 : '创业者',
	16 : '直率',
	17 : '懒',
	18: '感性',
	19: '理性',
	20: '温柔细心',
	21: '暴脾气',
	22: '技术宅',
	23: '文艺病',
	24: '旅行爱好者',
	25: '健身狂魔',
	26: '考研ing',
	27: '吃货',
	28: '长腿欧巴',
	29: '街舞solo',
	30: '爱音乐',
	31: '幽默',
	32: '乐观',
	33: '事业型',
	34: '完美主义',
	35: '情商略高',
	36: '阳光',
	37: '学霸',
	38: '执着',
	39: '自信',
	40: '独立型',
	41: '无标签',
};

var tagTpl = [];
// 创建标签
for(k in tagConfig){
	tagTpl.push('<li class="per-tag" data-id="' + k + '">' + tagConfig[k] + '</li>');
}
var tagTplStr = tagTpl.join('');
$('.tag-list').html(tagTplStr);

// 点击标签变色
var summitArr = [];
$('.per-tag').on('click', function(){
	$(this).addClass('selectting');
	// 将标签的id存到数组中
	if(summitArr.indexOf($(this).data('id')) == -1){
		summitArr.push($(this).data('id'));
		//alert(3)
	}
});

// 点击下一步按钮
$('.submit-data').on('click', function(){
	var tagData =JSON.stringify(""+summitArr);
	// alert(tagData.replace(/\"/g, ""));
	// 将tagData传给后端就可以了
	window.location.href = set_tag_url + '?tag_str=,' + tagData.replace(/\"/g, "");
})
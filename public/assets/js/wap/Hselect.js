/**
 * @desc 移动端模拟浏览器滚动/下拉刷新/上拉加载更多 组件
 * @copyright (c) 2015 anxin Inc
 * @author 霍春阳 <huochunyang@anxin365.com>
 * @since 2015-04-22
 */
(function($){
	var Hselect = function(opations){
		// 配置参数
		this.settings = {
			// 下拉刷新的回调函数
			refreshCallback : function(){},
			// 上拉加载更多的回调函数
			loadMoreCallback : function(){},
			// 选项【只要下拉刷新 - onlyTop】【只要上拉加载更多 - onlyBottom】【两个都要 - double】【都不要 - none】
			opationType : 'none',
			// 默认选择第几项
			defaultSelect : 0,
			// 点击完成按钮的回调函数
			completeFn : function(){},

			selectTplId : ''
		};

		$.extend(this.settings, opations);

		// 计算时间变量定义
		this.startTime = 0;
		this.endTime = 0;
		this.timeconsuming = 0;
		// 计算距离变量定义
		this.startlocation = 0;
		this.endlocation = 0;
		this.distance = 0;
		// 速度
		this.speed = 0;
		// touchstart时记录pageY
		this.prevY = 0;
		// disY为初始滑动时手指相对Hscroll的位置
		this.disY = 0;

		// 当前Hscroll的Top值
		this.curTop = 0;

		// 向上拉还是向下拉的标志【大于0下拉】【小于0上拉】
		this.upOrDown = 0;

		// tpl
		this.tpl = '<div id="H-wrap-box">'
	+		'<header class="H-head"><a href="javascript:;" class="H-complete">完成</a></header>'
	+		'<div id="H-box">'
	+			'<div id="Hwrap">'
	+				'<div id="Hscroll">'
	+					'<header class="Hs-header"><span class="pullDownIcon"></span><em>下拉刷新</em></header>'
	+						'<ul id="H-list"><li></li><li></li>'
	+							$('#' + this.settings.selectTplId).html()
	+						'<li></li><li></li></ul>'
	+					'<footer class="Hs-footer"><span class="pullUpIcon"></span><em>上拉加载更多</em></footer>'
	+				'</div>'
	+				'<section class="H-box-mask H-top-mask"></section>'
	+				'<section class="H-center-mask"></section>'
	+				'<section class="H-box-mask H-bottom-mask"></section>'
	+			'</div>'
	+		'</div>'
	+	'</div>';

		this.init();
		
	};

	var proto = Hselect.prototype;

	$.extend(proto, {

		constructor : Hselect,

		// 是否刷新的标志
		refreshMark : false,
		// 是否加载更多的标志
		loadMark : false,

		/**
		 * @desc 初始化方法
		 *
		 */
		init : function(){
			var self = this;
			// 创建之前 删除掉之前的组件
			if($('#H-wrap-box').get(0)){
				$('#H-wrap-box').detach();
			}
			

			$('body').append($(this.tpl));

			// 包裹层
			this.Hwrap = document.getElementById('Hwrap');
			// 滚动层
			this.Hscroll = document.getElementById('Hscroll');
			this.Hsheader = this.Hscroll.getElementsByClassName('Hs-header')[0];
			this.Hsfooter = this.Hscroll.getElementsByClassName('Hs-footer')[0];
			// 模板元素
			this.tplElem = $('#' + this.settings.selectTplId);
			// li选项元素
			this.activeLi = $('#H-list .H-effective');

			// 记录上一次的选项
			this.prevSelect = this.tplElem.data('hprevselect');


			if(this.settings.opationType == 'onlyTop'){
				this.Hsfooter.style.display = 'none';
			}else if(this.settings.opationType == 'onlyBottom'){
				this.Hsheader.style.display = 'none';
			}else if(this.settings.opationType == 'none'){
				this.Hsfooter.style.display = 'none';
				this.Hsheader.style.display = 'none';
			}
			
			// 调整参数
			if(this.settings.defaultSelect >= this.activeLi.length){
				this.settings.defaultSelect = this.activeLi.length - 1;
			}else if(this.settings.defaultSelect < 0){
				this.settings.defaultSelect = 0;
			}
			// 初始化选中位置
			if(this.prevSelect > -1){
				$(this.Hscroll).css({top : -this.prevSelect * 40 + 'px'});
				// 选择的元素
				this.SelectElement = $('#H-list .H-effective').eq(this.prevSelect);
			}else{
				// 根据设置的初始选项设置位置
				$(this.Hscroll).css({top : -this.settings.defaultSelect * 40 + 'px'});
				// 选择的元素
				this.SelectElement = $('#H-list .H-effective').eq(this.settings.defaultSelect);
			}
			
			if(!this.Hscroll){
				throw new Error('未定义Hscroll元素');
			}
			
			if(!this.Hsheader || !this.Hsfooter){
				throw new Error('未定义Hs-header 或 Hs-footer 元素');
			}

			// Hscroll最大可滚动的高度
			this.maxScrollHeight = Math.abs(this.Hscroll.offsetHeight - this.Hwrap.offsetHeight);

			
			// 是否可获取 已选择元素 的标志
			this.getSelectEleMark = true;
			// 完成按钮
			this.completeBtn = $('.H-complete');

			this.toBind();

			this.completeBtn.tap(function(){
				var $elem = self.getSelectEle();
				if($elem){
					self.settings.completeFn(self.SelectElement);
					self.tplElem.data('hprevselect', $elem.index() - 2);
					self.hide();
				}
			});

		},
		/**
		 * @desc 移除组件
		 *
		 */
		hide : function(){
			$('#H-wrap-box').detach();
		},
		
		/**
		 * @desc 绑定事件的方法
		 *
		 */
		toBind : function(){
			var self = this;
			this._addHandler(this.Hwrap, 'touchstart', function(ev){
				var ev = ev || window.event;

				self._touchstartFn(ev);
			});
			this._addHandler(this.Hwrap, 'touchmove', function(ev){
				var ev = ev || window.event;
				self._preventDefault(ev);

				self._touchmoveFn(ev);
			});

			this._addHandler(this.Hwrap, 'touchend', function(ev){
				var ev = ev || window.event;

				self._touchendFn(ev);
			});

			// 当input[type="text"] 或者 textarea元素获得焦点的时候，也要删除组件
			$('input[type="text"]').on('focus', this.hide);
			$('textarea').on('focus', this.hide);
		},
		/**
		 * @desc touchstart事件函数
		 *
		 */
		_touchstartFn : function(ev){
			this.addTransionStyle(this.Hscroll, '0s');
			// 记录初始位置和时间
			this.startlocation = ev.touches[0].pageY;
			this.startTime = new Date().getTime();
			// 计算初始时手指相对Hscroll的位置
			this.prevY =	ev.touches[0].pageY;
			this.disY = this.prevY - this.Hscroll.offsetTop;
		},
		/**
		 * @desc touchmove事件函数
		 *
		 */
		_touchmoveFn : function(ev){
			// move的时候更新当前的top值
			this.curTop = ev.touches[0].pageY - this.disY;
			this.upOrDown = ev.touches[0].pageY - this.prevY;
			var config = this.settings;
			
			if(this.curTop > 0){
				// 拖动使top大于0的时候，要减缓运动，（摩擦感）----------------> 下拉
				this.curTop = this.curTop * 0.3;

				if(this.curTop >= 50){
					// 放开刷新
					$('.pullDownIcon').addClass('flip');
					this.Hsheader.getElementsByTagName('em')[0].innerHTML = '放开刷新';
					if(config.opationType == 'onlyTop' || config.opationType == 'double'){
						this.refreshMark = true;
					}
					
				}else{
					$('.pullDownIcon').removeClass('flip');
				}
				
			}else if(Math.abs(this.curTop) > this.maxScrollHeight){
				// 拖动使top大于最大可拖动的距离的时候，要减缓运动，（摩擦感）-------------------> 上拉
				this.curTop = this.curTop + (this.prevY - ev.touches[0].pageY) * 0.7;

				// 如果只要下拉刷新
				if(Math.abs(this.curTop) - this.maxScrollHeight >= 50){
					// 放开加载更多
					$('.pullUpIcon').addClass('flip');
					this.Hsfooter.getElementsByTagName('em')[0].innerHTML = '放开加载更多';

					if(config.opationType == 'onlyBottom' || config.opationType == 'double'){
						this.loadMark = true;
					}

				}else{
					$('.pullUpIcon').removeClass('flip');
				}
				
			}


			this.Hscroll.style.top = this.curTop + 'px';


		},
		/**
		 * @desc touchend事件函数
		 *
		 */
		_touchendFn : function(ev){

			var self = this;

			// 获取结束位置及时间
			this.endlocation = ev.changedTouches[0].pageY;
			this.endTime = new Date().getTime();

			// 如果 Hscroll的top值在0~50之间 ,刷新并return
			if(this.curTop > 0 && this.curTop < 50){
				this.setSelectEle('first');
				this.refreshEnd();
				return false;
			}
			// 如果  Hscroll的top值在 最大值~最大值+50 之间 ,加载更多并return
			if(Math.abs(this.curTop) > this.maxScrollHeight && (Math.abs(this.curTop) < this.maxScrollHeight + 50)){
				this.setSelectEle('last');
				this.loadMoreEnd();
				return false;
			}

			if(this.refreshMark){
				this.refresh();
				return false;
			}
			if(this.loadMark){
				this.loadMore();
				return false;
			}

			// 禁用下拉条件
			if(this.upOrDown > 0 && this.curTop > 0 && (this.settings.opationType == 'onlyBottom' || this.settings.opationType == 'none')){
				this.setSelectEle('first');
				this.refreshEnd();
				return false;
			}
			// 禁用上拉条件
			if(this.upOrDown < 0 && (Math.abs(this.curTop) > this.maxScrollHeight) && (this.settings.opationType == 'onlyTop' || this.settings.opationType == 'none')){
				this.setSelectEle('last');
				this.loadMoreEnd();
				return false;
			}

			// 计算速度
			this.speed = parseInt( (this.endlocation - this.startlocation) / (this.endTime - this.startTime) * 150 );
			
			// end的时候更新当前的top值
			this.curTop = this.Hscroll.offsetTop + this.speed;

			// 下拉后滚动过界
			if(this.curTop > 0){
				this.setSelectEle('first');

				this.curTop = this.curTop * 0.1;
				this.Hscroll.style.top = this.curTop + 'px';
				this.addTransionStyle(this.Hscroll, '0.6s');
				setTimeout(function(){
					self.refreshEnd();
				}, 600);

				// 速度清0 ，必须
				this.speed = 0;
				return false;
			}else if(Math.abs(this.curTop) > this.maxScrollHeight){
				this.setSelectEle('last');

				// 上拉后滚动过界
				this.curTop = -this.maxScrollHeight - (Math.abs(this.curTop) - this.maxScrollHeight) * 0.2;
				this.Hscroll.style.top = this.curTop + 'px';
				this.addTransionStyle(this.Hscroll, '0.6s');
				setTimeout(function(){
					self.loadMoreEnd();
				}, 600);

				// 速度清0 ，必须
				this.speed = 0;
				return false;
			}

			var minTop = 900000;
			$.each($('#H-list .H-effective'), function(i, obj){
				var licenterTop = parseInt(obj.offsetTop) + self.curTop + 20;
				$(obj).data('ct', licenterTop);
				$(obj).data('t', obj.offsetTop - 80);
			});

			$.each($('#H-list .H-effective'), function(i, obj){
				if(Math.abs(Math.abs($(obj).data('ct')) - 100) <= minTop){
					minTop = Math.abs(Math.abs($(obj).data('ct')) - 100);
					self.curTop = -($(obj).data('t'));
				}

				if($('#H-list .H-effective').length == i + 1){
					minTop = 900000;
				}
			});
			
			this.setSelectEle(this.curTop);

			this.Hscroll.style.top = this.curTop + 'px';
			this.addTransionStyle(this.Hscroll, '0.6s');
			// 速度清0 ，必须
			this.speed = 0;
			return false;
		},
		/**
		 * @desc 放开刷新
		 *
		 */
		refresh : function(){
			$('.pullDownIcon').addClass('loading');
			this.Hscroll.style.top = '50px';
			this.addTransionStyle(this.Hscroll, '0.2s');
			if(this.settings.refreshCallback){
				this.settings.refreshCallback(this);
			}
		},
		/**
		 * @desc 加载更多
		 *
		 */
		loadMore : function(){
			$('.pullUpIcon').addClass('loading');
			this.Hscroll.style.top = -(this.maxScrollHeight + 50) + 'px';
			this.addTransionStyle(this.Hscroll, '0.2s');
			if(this.settings.loadMoreCallback){
				this.settings.loadMoreCallback(this);
			}
		},
		/**
		 * @desc 刷新完毕，归位
		 *
		 */
		refreshEnd : function(){
			$('.pullDownIcon').removeClass('flip');
			$('.pullDownIcon').removeClass('loading');
			this.Hscroll.style.top = 0;
			this.addTransionStyle(this.Hscroll, '0.2s');
			this.refreshMark = false;

			// 更新Hscroll最大可滚动的高度
			this.maxScrollHeight = Math.abs(this.Hscroll.offsetHeight - this.Hwrap.offsetHeight);
		},
		/**
		 * @desc 加载更多完毕，归位
		 *
		 */
		loadMoreEnd : function(){
			$('.pullUpIcon').removeClass('flip');
			$('.pullUpIcon').removeClass('loading');
			this.Hscroll.style.top = -this.maxScrollHeight + 'px';
			this.addTransionStyle(this.Hscroll, '0.2s');
			this.loadMark = false;

			// 更新Hscroll最大可滚动的高度
			this.maxScrollHeight = Math.abs(this.Hscroll.offsetHeight - this.Hwrap.offsetHeight);
		},
		/**
		 * @desc 禁用下拉刷新、上拉加载更多、全部禁用、全部释放的方法
		 * @param (str) types ->【只要下拉刷新 - onlyTop 】【只要上拉加载更多 - onlyBottom 】【两个都要 - double 】【都不要 - none 】
		 */
		changeTypeTo : function(types){
			this.settings.opationType = types;

			this.loadDom();
		},
		/**
		 * @desc 添加transition样式
		 * @param (DOM OBJ) obj : dom元素
		 * @param (string) str : transition css 字符串
		 */
		addTransionStyle : function(obj, str){
			obj.style.transition = str;
			obj.style.WebkitTransition = str;
			obj.style.MozTransition = str;
			obj.style.OTransition = str;
			obj.style.msTransition = str;
		},
		/**
		 * @desc 给元素绑定事件
		 *
		 */
		_addHandler: function(element, type, handler){
			if(element.addEventListener){
				if(type == 'transitionend'){
					element.addEventListener('transitionend', handler, false);
					element.addEventListener('webkitTransitionEnd', handler, false);
				}else{
					element.addEventListener(type, handler, false);
				}
				
			}else if(element.attachEvent){
				element.attachEvent("on" + type, handler);
			}else{
				element["on" + type] = handler;
			}
		},
		/**
		 * @desc 给元素移除事件
		 *
		 */
		_removeHandler : function(element, type, handler){
			if(element.removeEventListener){
				element.removeEventListener(type, handler, false);
			}else if(element.detachEvent){
				element.detachEvent("on" + type, handler);
			}else{
				element["on" + type] = null;
			}
		},
		/**
		 * @desc 阻止默认事件
		 *
		 */
		_preventDefault: function(event){
			if(event.preventDefault){
				event.preventDefault();
			}else{
				event.returnValue = false;
			}
			
		},
		/**
		 * @desc 设置选中的选项
		 * @param cond : 当前top值/first/last
		 *
		 */
		setSelectEle : function (cond){
			var index;
			if(typeof cond == 'number'){
				index = Math.abs(cond / 40);
				this.SelectElement = $('#H-list .H-effective').eq(index);
				this.getSelectEleMark = true;
			}
			if(typeof cond == 'string'){
				if(cond == 'first'){
					this.SelectElement = $('#H-list .H-effective').eq(0);
					this.getSelectEleMark = true;
				}else if(cond == 'last'){
					this.SelectElement = $('#H-list .H-effective').eq($('#H-list .H-effective').length - 1);
					this.getSelectEleMark = true;
				}
			}

			return null;
		},
		/**
		 * @desc 获取选中的选项
		 *
		 */
		getSelectEle : function(){
			if(this.getSelectEleMark){

				this.getSelectEleMark = false;
				return this.SelectElement;
			}
			return $();
		}

	});

	// 暴露接口
	if (typeof module !== 'undefined' && module.exports) {
	    module.exports = Hselect;
	} else {
	    window.Hselect = Hselect;
	}
})($);

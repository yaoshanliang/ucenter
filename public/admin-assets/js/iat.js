/**
 * 显示操作成功信息
 *
 * @param  string 提示信息, float 显示时间
 * @return null
 */
function showSuccessTip(tip, time){
	var tip = arguments[0] || '操作成功';
	var time = arguments[1] || 1.5;
	var background = '#5cb85c';
	var bordercolor = '#4cae4c';

	showTip(tip, time, background, bordercolor);
}

/**
 * 显示操作失败信息
 * @param string 提示信息, float 显示时间
 * @return null
 */
function showFailTip(tip, time){
	var tip = arguments[0] || '操作失败';
	var time = arguments[1] || 1.5;
	var background = '#c9302c';
	var bordercolor = '#ac2925';

	showTip(tip, time, background, bordercolor);
}

/**
 * 显示信息，供成功、失败调用
 * @param string 提示信息, float 显示时间, string 背景颜色, string 边框颜色
 * @return null
 */
function showTip(tip, time, background, bordercolor) {
	var windowWidth = document.documentElement.clientWidth;
	var height = 10;
	var width = 200;
	var tipsDiv = '<div class="tipsClass">' + tip + '</div>div>';

	$('body').append(tipsDiv);
	$('div.tipsClass').css({
		'z-index': 9999,
		'top': height + 'px',
		'width': width + 'px',
		'height': '30px',
		'left': (windowWidth / 2) - (width / 2) + 'px',
		'position': 'fixed',
		'padding': '3px 5px',
		'background': background,
		'border': '1px solid transparent',
		'border-color': bordercolor,
		'border-radius':'4px',
		'font-size': 14 + 'px',
		'margin': '0 auto',
		'text-align': 'center',
		'color': '#fff',
		'opacity': '0.8'
	}).show();
	setTimeout(function(){$('div.tipsClass').fadeOut();}, (time * 1000));
}

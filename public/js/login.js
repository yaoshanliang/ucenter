$(function() {
    changebg();
    sectionFixedWidth();
	$(window).resize(function() {
		imgWH();
        sectionFixedWidth();
	}).resize();
	function imgWH() {
		var i = 0,
			imgpnglength = $('.imgpng').length;
		for(i=0; i < imgpnglength; i++) {
			var imgpng = $('.imgpng').eq(i),
				imgpngW = imgpng.width(),
				maximgpngW = imgpng.attr("width");
			if(imgpngW >=　maximgpngW){
				imgpng.attr('width', maximgpngW);
			} else {
				imgpng.attr('width', imgpngW);
			}
		}
		var imgtopW = $('.imgtop').width(),
			imgtopH = $('.imgtop').height();
		$('.gem-back').css({width:imgtopW*0.375,height:imgtopH*0.19,marginTop:imgtopH*0.259});
	}
function changebg(){
var random_bg=Math.floor(Math.random()*10+1);
var bg='url(/images/background'+random_bg+'.jpg) no-repeat center';
    $("body").css("background",bg);
}

$(".flash4 ul").find('li').eq(0).css({'border':'.1px solid #8dcfe9','box-shadow':'0px 0px 3.5px #8dcfe9', 'margin-top':'-25px'});
$(".flash4 ul").find('li').eq(0).find('.imgBot').css({background:'#8dcfe9',height:'125px'});
$(".flash4 ul").find('li').eq(0).find('.bt_1').css({color:"white",'line-height':'95px'});
//手风琴动画效果
$(".flash4 ul li").mouseover(function(){
    var _index7=$(this).index();
    $(".flash4 ul li").css({'border':'.1px solid #dbdbdd', 'box-shadow':'0px 0px 0px #00c1de','margin-top':'0px'});
    $('.imgBot').css({'background':'#e5e6e6','height':'100px'});
    $('.bt_1').css({color:"#232323",'line-height':'75px'});
    $(this).css({'border':'.1px solid #8dcfe9','box-shadow':'0px 0px 3.5px #8dcfe9', 'margin-top':'-25px'});
    $(this).find('.imgBot').css({background:'#8dcfe9',height:'125px'});
    $(this).find('.bt_1').css({color:"white",'line-height':'95px'});
    $(this).stop().animate({width:280, height:405},300).siblings("li").stop().animate({width:260, height:350},300);
    });
});

//判断窗口大小
function sectionFixedWidth() {
    var sectionFixed = $('.sectionFixed'),
        navFixed = $('.navFixed'),
        sectionFixedw = 300;
        sectionWidth = $(window).width(),
        sectionFixedleft = (sectionWidth - 780)/2;
    if(sectionFixedleft > sectionFixedw){
        sectionFixed.removeClass('sectionFixedmin');
        sectionFixed.css("left", sectionFixedleft - sectionFixedw);
    } else {
        if(sectionFixedleft < 200){
            sectionFixed.css("left", '0px');
        } else {
            sectionFixed.css("left", sectionFixedleft-200);
        }
        sectionFixed.addClass('sectionFixedmin');
    }
    if((sectionFixedleft - 150) > 20) {
        navFixed.css("right", sectionFixedleft - 150);
    } else {
        navFixed.css("right", '20px');
    }
}
//珍品域名页面socroll事件
$(function() {
    // price
      scrollPage();
      scrollWindow();
    // section_list
     $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
     $('.section_list li').each(function(i){
      $(this).click(function(){
        sectionBtn(i);
        $body.animate({
          scrollTop: $('#section' + (i)).offset().top
        }, 600);
        return false;
      });
     });
    $(window).scroll(function() {
        scrollWindow();
    });
    $('.TopBtn').click(function() {
        backTop();
    });
    $('.NextBtn').click(function() {
        $body.animate({
          scrollTop: $('.section').eq(navList-1).offset().top
        }, 600);
        $(this).fadeOut();
        return false;
    });
});
// backtop 
function backTop() {
    $("html,body").animate({
        scrollTop : 0
    }, 900);
}

// backtop 隐藏判断事件
function scrollWindow() {
    var navH = $('.nav').height();
    if ($(window).scrollTop() > navH) {
        $(".TopBtn").fadeIn();
    } else {
        $(".TopBtn").fadeOut();
    }
}

//提交资料 事件
function sectionBtn(Btn){
    //if(Btn >= 2 && Btn<=4){
      $('.sectionFixed').fadeIn();
  //} else {
    //$('.sectionFixed').hide();
  //}
}
//cur当前位置
function scrollPagecur(cur){
    $('.section_list li').removeClass('cur');
    $('.section_list li').eq(cur).addClass('cur');
}

function scrollPage(){
  var currentSectionIndex = 0;
  var isScrolling = false;
  var sectionList = $('.section');
  var sectionCount = sectionList.length;
  var highlightedSection = 0;
  //全局section个数
  navList = sectionCount;
  function highlightSection(i){
    $('.sectionWrapper').removeClass('fadeInDown');
    $(sectionList[i]).find('.sectionWrapper').addClass('fadeInDown');
  }
  function adjustCurrentSectionIndex() {
        var $w = $(window);
        var winScrollTop = $w.scrollTop();
        var winHeight = $w.height();        
        var viewTop = $w.scrollTop(),
            viewBottom = viewTop + $w.height();
        for(var i = 0; i < sectionCount; i++) {
          var section = $(sectionList[i]);
          var sectionTop = section.offset().top;
          var sectionHeight = section.height();
          var sectionBottom = sectionTop + sectionHeight;
          if(winScrollTop <=  (sectionTop + sectionHeight) && winScrollTop > (sectionTop + sectionHeight*9/10)) {
            highlightedSection=i+1;
            highlightSection(highlightedSection);
            currentSectionIndex = highlightedSection;
            $('.NextBtn').show();
          }else{
            highlightedSection = i;
          }
          if((sectionBottom+1) == viewBottom){
            $('.NextBtn').fadeOut();
          }
          if((sectionBottom <= viewBottom) && (sectionTop >= viewTop)) {
            currentSectionIndex = i;
            break;
          }
        }
      }
  adjustCurrentSectionIndex();
  var ele;
  $(window).on('scroll', function(){
    adjustCurrentSectionIndex();
      sectionBtn(currentSectionIndex);
    scrollPagecur(currentSectionIndex);
    if (currentSectionIndex == 0) {
      currentSectionIndex = 1;
    };
    ele=sectionList[currentSectionIndex];
    $(ele).find('.sectionWrapper').addClass('fadeInDown');
    $(this).resize(function(){
      if(ele){
          scrollTo(ele);
      }
    });
  })

  function scrollTo(ele) {
    $('.sectionWrapper').removeClass('fadeInDown');
    isScrolling = true;
    $('html,body').stop().animate({
      scrollTop: $(ele).offset().top
    }, 500, function(){
      isScrolling = false;
      $(ele).find('.sectionWrapper').addClass('fadeInDown');
    });
  }

  $('body').on('mousewheel', function(e){
    e.preventDefault();
    if(!isScrolling) {
      var deltaY = e.deltaY;
      switch(deltaY){
        case 1:
        currentSectionIndex -= 1;
        if(currentSectionIndex < 0) {
          currentSectionIndex = 0;
        } else {
          scrollTo(sectionList[currentSectionIndex]);
        }
        break;
        case -1:
        currentSectionIndex += 1;
        if(currentSectionIndex > sectionCount - 1) {
          currentSectionIndex = sectionCount - 1;
        } else { 
          scrollTo(sectionList[currentSectionIndex]);
        }
        break;
        default:
        break;
      }
    }
  });
}



$(document).ready(function() {
	$(".btn-yuding").click(function(){
		var url = $(this).attr('data-href');
		var time = $('input[name="time"]').val();
		if(time == ''){
			layer.msg('请选择住院时间');
		}else{
			window.location.href = url +'&time='+time;
		}
	});

	$(".saveOrder").click(function(){
		var data = $('form').serialize();
		$.post('/frame/index.php?m=pc&c=Hospital&a=save_order&'+data,function(result){
			if(result.status){
				console.log('/frame/index.php?m=pc&c=pay&a=index&order_sn='+result.data.order_sn);
				window.location.href = '/frame/index.php?m=pc&c=pay&a=index&order_sn='+result.data.order_sn+'&catid='+result.data.catid;
			}else{
				layer.msg(result.msg);
			}
		},'json');
		console.log(data);
	});

	$('.btn-guahao').click(function(){
		var stock = $(this).attr('data-stock'); //剩余预约号数
		layer.confirm('当前剩余预约号数：'+stock, {
		  offset:'auto',
		  title:'预约挂号',
		  btn: ['预约','取消'] //按钮
		}, function(){
		  layer.msg('的确很重要', {icon: 1});
		}, function(){
		  layer.msg('也可以这样', {
		    time: 20000, //20s后自动关闭
		    btn: ['明白了', '知道了']
		  });
		});
	});
})


function ScrollImgLeft(){
	var speed=20
	var scroll_begin = document.getElementById("scroll_begin");
	var scroll_end = document.getElementById("scroll_end");
	var scroll_div = document.getElementById("scroll_div");
	scroll_end.innerHTML=scroll_begin.innerHTML

	function Marquee(){
	    if(scroll_end.offsetWidth-scroll_div.scrollLeft<=0)
	      scroll_div.scrollLeft-=scroll_begin.offsetWidth
	    else
	      scroll_div.scrollLeft++
	}

	var MyMar=setInterval(Marquee,speed)
	scroll_div.onmouseover=function() {clearInterval(MyMar)}
	scroll_div.onmouseout=function() {MyMar=setInterval(Marquee,speed)}
}


function ScrollImgLeft2(){

	var speed=20
	var scroll_begin = document.getElementById("scroll_begin2");
	var scroll_end = document.getElementById("scroll_end2");
	var scroll_div = document.getElementById("scroll_div2");
	scroll_end.innerHTML=scroll_begin.innerHTML

	function Marquee(){
	    if(scroll_end.offsetWidth-scroll_div.scrollLeft<=0)
	      scroll_div.scrollLeft-=scroll_begin.offsetWidth
	    else
	      scroll_div.scrollLeft++
	}

	var MyMar=setInterval(Marquee,speed)
	scroll_div.onmouseover=function() {clearInterval(MyMar)}
	scroll_div.onmouseout=function() {MyMar=setInterval(Marquee,speed)}

}

function ScrollImgLeft3(){

	var speed=20
	var scroll_begin = document.getElementById("scroll_begin3");
	var scroll_end = document.getElementById("scroll_end3");
	var scroll_div = document.getElementById("scroll_div3");
	scroll_end.innerHTML=scroll_begin.innerHTML

	function Marquee(){
	    if(scroll_end.offsetWidth-scroll_div.scrollLeft<=0)
	      scroll_div.scrollLeft-=scroll_begin.offsetWidth
	    else
	      scroll_div.scrollLeft++
	}

	var MyMar=setInterval(Marquee,speed)
	scroll_div.onmouseover=function() {clearInterval(MyMar)}
	scroll_div.onmouseout=function() {MyMar=setInterval(Marquee,speed)}

}
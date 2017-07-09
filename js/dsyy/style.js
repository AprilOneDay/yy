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

	//保存病房信息
	$(".saveYuyueOrder").click(function(){
		var data = $('form').serialize();
		layer.load();
		$.post('/frame/index.php?m=pc&c=hospital&a=save_order&'+data,function(result){
			layer.closeAll('loading');
			if(result.status){
				window.location.href = '/frame/index.php?m=pc&c=pay&a=index&order_sn='+result.data.order_sn+'&catid='+result.data.catid;
			}else{
				layer.msg(result.msg);
			}
		},'json');
		console.log(data);
	});

	//保存挂号信息
	$(".saveGuahaoOrder").click(function(){
		var data = $('form').serialize();
		layer.load();
		$.post('/frame/index.php?m=pc&c=doctor&a=save_order&'+data,function(result){
			layer.closeAll('loading');
			if(result.status){
				window.location.href = '/frame/index.php?m=pc&c=pay&a=index&order_sn='+result.data.order_sn+'&catid='+result.data.catid;
			}else{
				layer.msg(result.msg);
			}
		},'json');
	});

	//跳转挂号信息
	$('.btn-guahao').click(function(){
		var stock = $(this).attr('data-stock'); //剩余预约号数
		var href  = $(this).attr('data-href'); //跳转地址
		console.log(href);
		layer.confirm('当前剩余预约号数：'+stock, {
		  offset:'auto',
		  title:'预约挂号',
		  btn: ['预约','取消'] //按钮
		}, function(){
			window.location.href = href;
		});
	});

	//查询挂号
	$('.btn-chaxun').click(function(){
		var data = $('form').serialize();
		layer.load();
		$.get('/frame/index.php?m=pc&c=order&a=get_detail_doctor&'+data,function(result){
			layer.closeAll('loading');
			if(result.status){
				window.location.href = '/frame/index.php?m=pc&c=order&a=list_doctor&'+data;
			}else{
				layer.msg(result.msg);
			}
		},'json');
	});

	//发送验证码
	$('.send-verification').click(function(){
		var mobile = $("input[name='mobile']").val();
		layer.load();
		$.post('/frame/index.php?m=api&c=sms&a=send',{flag:'verification',mobile:mobile},function(result){
			layer.closeAll('loading');
			layer.msg(result.msg);
			if(result.status){
				
			}
		},'json');
	})
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
function saveFloat()
{
	var title   = $('input[name="title"]').val();
	var name    = $('input[name="name"]').val();
	var mail    = $('input[name="mail"]').val();
	var content = $('#textarea').val();
	var keshi   = $('#keshi').val();
	console.log(content);
	if(title == ''){ alert('请填写标题'); return false;}
	if(name == ''){ alert('请填写姓名'); return false;}
	if(mail == ''){ alert('请填写邮箱'); return false;}
	if(content == ''){ alert('请填写内容'); return false;}
	$.post('/public/api/interlocution.php','type=save&title='+title+"&name="+name+"&mail="+mail+"&keshi="+keshi+"&content="+content,function(data){
			alert(data);
			window.location.href= window.location;
	})
	console.log($("#float").serialize());
}
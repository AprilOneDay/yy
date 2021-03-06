<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <title><?php echo $this->title; ?></title>
    <meta name="keywords" content="<?php echo $this->keywords; ?>">
    <meta name="description" content="<?php echo $this->description; ?>">

    <link rel="stylesheet" type="text/css" href="/css/dsyy/css.css"/>
<!--     <link rel="stylesheet" type="text/css" href="<?=vars('urls','puls')?>/layer/css/layer.css"> -->
    <script type="text/jscript" src="/js/dsyy/jquery-1.12.3.min.js"></script>
    <script type="text/jscript" src="<?=vars('urls','puls')?>/layer/layer.js"></script>
    <script type="text/javascript" src="/js/dsyy/style.js"></script>
</head>
<body>
<div class="top-bg w100">
    <div class="top w1000">
        <div class="top-hello left">
            <span>您好，欢迎来到重庆医科大学附属康复医院官方网站！</span>
        </div>
        <div class="top-nav right">
            <ul>
               <li>
                    <a href="javascript:;" class="one"><span>中文</span> <b>▼</b></a>
                    <a href="javascript:;" class="two"><span>EN</span></a>
                </li>
                <li><a href="javascript:;">医院OA</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="clear"></div>
<div class="top-logo-bg w100">
    <div class="top-logo w1000">
        <div class="logo left">
            <img src="/images/logo.png" />
        </div>
        <div class="top-tel right">
             <!--<img src="/images/top_tel.png" />-->
             <h4 class="tel">023-68823284</h4>
        </div>
    </div>
</div>
<div class="clear"></div>
<div class="home-nav w100">
    <div class="menus w1000" style="width: 1340px;">
        <ul>
            <li><a href="/">&nbsp;&nbsp;首页</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=6">医院概况</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=7">新闻中心</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=8">科室导航</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=9">党群工作</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=10">科研教学</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=11">通知公告</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=12">招贤纳士</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=13">康复人文</a></li>
            <li><a href="/index.php?m=content&c=index&a=lists&catid=82">谢家湾社区卫生服务中心</a></li>
        </ul>
    </div>
</div>
<div class="clear"></div>
<!--main-->
<div class="n-banner">
    <img src="/images/b.jpg">
</div>
<div class="n-middlen w100">
    <div class="n-content w1000">
        <div class="n-left left">
            <div class="title">
                <span>预约挂号</span>
                <small>Appointment</small>
            </div>
            <div class="lemid">
                <ul>
                    <?php
                    $category = table('category')->where(array('parentid' => 7))->field('model,controller,action,catid,catname,arrchildid,url')->find('array');
                    ?>
                    <?php if($category){ foreach($category as $k => $r){ ?>
                    <li <?php if($r['catid'] == $catid){ ?>class="curr"<?php } ?> >
                        <a href="/frame/index.php?m=<?php echo $r['model']; ?>&c=<?php echo $r['controller']; ?>&a=<?php echo $r['action']; ?>&catid=<?php echo $r['catid']; ?>"><?php echo $r['catname']; ?><b>></b></a>
                    </li>
                    <?php }} ?>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    <div class="n-right left">
            <div class="n-right-top">
                <div class="title left">查询/退号</div>
                <div class="location right">
                    您现在的位置：<a href="/">医院首页</a><span> > </span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
         	<div class="n-right-about">
         		<div class="chaxun">
                    <form method="post">
                       <table class="chaxun-from">
                            <input type="hidden" name="catid"  value="<?php echo $catid; ?>">
                            <tr>
                               <td align="right">个人姓名</td>
                               <td><input type="text" name="name" placeholder="请输入患者真实姓名"></td>
                            </tr>
                            <tr>
                               <td align="right">身份证号</td>
                               <td><input type="text" name="code" placeholder="请输入患者身份证号"></td>
                            </tr>
                            <tr>
                               <td align="right">手机号</td>
                               <td><input type="text" name="mobile" placeholder="请输入预约手机号"></td>
                            </tr>
                            <tr>
                               <td colspan="2"><input type="button" value="查询" class="btn-chaxun"></td>
                            </tr>
                       </table> 
                    </from>
         		</div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--end-->
<footer>
    <div class="footer-bg w100"></div>
    <div class="footer w1000">
        <div class="footer-logo left">
            <img src="/images/footer-logo.png">
        </div>
        <div class="footer-contact left">
            <span>联系我们</span>
            <ul>
                <li class="two">黄水院区</li>
                <li>地址：重庆市石柱土家族自治县黄水镇莼乡路287号</li> 
                <li>联系电话：(023)81500898</li>
                <li>电子邮箱：jlp-yjy@163.com</li>  
                <li class="two">大公馆院区</li>
                <li>地址：重庆市九龙坡区谢家湾文化七村50号</li>
                <li>邮政编码：400050</li>
                <li>联系电话：(023)68823284</li>
                <li>电子邮箱：jlp-yjy@163.com</li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="footer-code right">
            <ul>
                <!--<li><img src="/images/2wm.png" /><p>手机APP</p></li>-->
                <li><img src="/images/2wm_1.png" width="116" height="116" /><p>关注微信</p></li>
                <li><img src="/images/2wm_2.png" width="116" height="116" /><p>关注微博</p></li>
                <!--<li><img src="/images/2wm.png" /><p>支付宝挂号</p></li>-->
            </ul>
        </div>
        <div class="clear"></div>
    </div>
    <div class="footer-copy w100">
        <div class="footer-copy-content w1000">
            Copyright © 2017 重庆医科大学附属康复医院 All Rights reserved　
            <a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=50010702500651" target="_blank">
                <img src="images/file0003.jpg" style="vertical-align:middle;"> 渝公网安备 50010702500651号
            </a>
        </div>
    </div>
</footer>
</body>
</html>

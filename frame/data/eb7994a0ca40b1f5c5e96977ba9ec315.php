<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>重庆继续考试网考试平台</title>
        <!-- Framework CSS -->
        <link rel="stylesheet" href="<?=vars('urls','puls')?>bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=vars('urls','ststic')?>console/css/common.css">
        <link rel="stylesheet" href="<?=vars('urls','ststic')?>console/css/reset.css">
    </head>
    <body>
        <div class="container">
            <div class="span-24 last">
                <div class="content">
                   <!--  <h2>学分明细</h2> -->
                    <div class="mt8">
                    <form class="form-inline ng-pristine ng-valid" method="get" >
                        <div class="form-group">
                            <select class="form-control" name="field" data-selected="<?php echo isset($param['field']) ? $param['field'] : ''; ?>">
                                <option value="">请选择搜索条件</option>
                                <option value="order_sn">订单编号</option>
                                <option value="name">姓名</option>
                                <option value="code">身份证</option>
                                <option value="hospital">医院名称</option>
                            </select>
                            <input type="text" class="form-control w120" placeholder="Search" name="keyword" value="<?php echo isset($param['keyword']) ? $param['keyword'] : ''; ?>">
                            <button type="submit" class="btn btn-default">搜索</button>
                            <button type="button" class="btn btn-default"  onclick="batchApplyCredit()">批量审核</button>
                            <input type="hidden" name="m" value="<?=MODEL?>"> 
                            <input type="hidden" name="c" value="<?=CONTROLLER?>"> 
                            <input type="hidden" name="a" value="<?=ACTION?>"> 
                        </div>
                    </form>
                </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="allCheckbox"></th>
                                <th>id</th>
                                <th>订单编号</th>
                                <th>姓名</th>
                                <th>手机号</th>
                                <th>预约时间</th>
                                <th>预约医生</th>
                                <th>预约科室</th>
                                <th>订单状态</th>
                                <th>订单流程</th>
                                <th>下单时间</th>
                                <th>订单金额</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($list){ foreach($list as $key => $value){ ?>
                                <tr>
                                    <td><input type="checkbox" class="dataCheckbox" name="id[]" value="<?=$value['id']?>"></td>
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php echo $value['order_sn']; ?></td>
                                    <td><?php echo $value['name']; ?></td>
                                    <td><?php echo $value['mobile']; ?></td>
                                    <td><?php echo $value['data']['time_copy']; ?></td>
                                    <td><?php echo $value['data']['doctor']; ?></td>
                                    <td><?php echo $value['data']['keshi']; ?></td>
                                    <td><?php echo $statusCopy[$value['status']]; ?></td>
                                    <td><?php echo $logisticsCopy[$value['logistics_status']]; ?></td>
                                    <td><?=date('Y-m-d',$value['created'])?></td>
                                    <td><?php echo $value['amount']; ?></td>
                                    <td>
                                    <a href="javascript:;" class="btn-console-detail" data-href="<?=url('detail_doctor',array('order_sn'=>$value['order_sn']))?>">详情</a>
                                    <?php if($value['logistics_status'] == 1 && $value['status'] == 1){ ?>
                                        <a href="">确认有余</a>
                                    <?php } ?>
                                    </td>
                                </tr>
                            <?php }} ?>
                        </tbody>
                        <tfoot ng-show="pages">
                            <tr>
                                <td colspan="13">
                                    <?php echo $pages; ?>
                                </td>
                            </tr>
                            </tfoot>
                    </table>
                    
                </div>
            </div>
        </div>
    </body>
    <script src="<?=vars('urls','ststic')?>console/js/jquery-1.12.3.min.js"></script>
    <script src="<?=vars('urls','puls')?>/layer/layer.js"></script>
    <script src="<?=vars('urls','ststic')?>console/js/admin.js"></script>
</html>
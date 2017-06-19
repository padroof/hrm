<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                            data-toggle="tab"><?= lang('salary_template_list') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#create"
                                                            data-toggle="tab"><?= lang('new_template') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="col-sm-1"><?= lang('sl') ?></th>
                    <th><?= lang('salary_grade') ?></th>
                    <th><?= lang('basic_salary') ?></th>
                    <th><?= lang('overtime') ?>
                        <small>(<?= lang('per_hour') ?>)</small>
                    </th>
                    <th class="col-sm-2"><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $key = 1; ?>
                <?php if (!empty($all_salary_template)): foreach ($all_salary_template as $v_salary_info): ?>
                    <tr>
                        <td><?php echo $key; ?></td>
                        <td>
                            <a href="<?= base_url() ?>admin/payroll/salary_template_details/<?= $v_salary_info->salary_template_id ?>"
                               title="<?= lang('view') ?>" data-toggle="modal"
                               data-target="#myModal_lg">
                                <?php echo $v_salary_info->salary_grade; ?>
                            </a>
                        </td>
                        <td><?php
                            $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                            echo display_money($v_salary_info->basic_salary, $curency->symbol); ?></td>
                        <td><?php
                            if (!empty($v_salary_info->overtime_salary)) {
                                echo display_money($v_salary_info->overtime_salary, $curency->symbol);
                            }
                            ?></td>
                        <td>
                            <?php echo btn_view_modal('admin/payroll/salary_template_details/' . $v_salary_info->salary_template_id); ?>
                            <?php echo btn_edit('admin/payroll/salary_template/' . $v_salary_info->salary_template_id); ?>
                            <?php echo btn_delete('admin/payroll/delete_salary_template/' . $v_salary_info->salary_template_id); ?>
                        </td>
                    </tr>
                    <?php
                    $key++;
                endforeach;
                    ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
            <form id="form" role="form" enctype="multipart/form-data"
                  action="<?php echo base_url() ?>admin/payroll/set_salary_details/<?php
                  if (!empty($salary_template_info->salary_template_id)) {
                      echo $salary_template_info->salary_template_id;
                  }
                  ?>" method="post" class="form-horizontal form-groups-bordered">
                <div class="row">
                    <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('salary_grade') ?><span
                                class="required"> *</span></label>
                        <div class="col-sm-5">
                            <input type="text" name="salary_grade" value="<?php
                            if (!empty($salary_template_info->salary_grade)) {
                                echo $salary_template_info->salary_grade;
                            }
                            ?>" class="form-control" required
                                   placeholder="<?= lang('enter') . ' ' . lang('salary_grade') ?>">
                        </div>
                    </div>
                    <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('basic_salary') ?><span
                                class="required"> *</span></label>
                        <div class="col-sm-5">
                            <input type="number" name="basic_salary" value="<?php
                            if (!empty($salary_template_info->basic_salary)) {
                                echo $salary_template_info->basic_salary;
                            }
                            ?>" class="salary form-control" required
                                   placeholder="<?= lang('enter') . ' ' . lang('basic_salary') ?>">
                        </div>
                    </div>
                    <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('overtime_rate') ?>
                            <small> ( <?= lang('per_hour') ?>)</small>
                        </label>
                        <div class="col-sm-5">
                            <input type="number" name="overtime_salary" value="<?php
                            if (!empty($salary_template_info->overtime_salary)) {
                                echo $salary_template_info->overtime_salary;
                            }
                            ?>" class="form-control" placeholder="<?= lang('enter') . ' ' . lang('overtime_rate') ?>">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="panel panel-custom">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <strong><?= lang('allowances') ?></strong>
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php
                                $total_salary = 0;
                                if (!empty($salary_allowance_info)):foreach ($salary_allowance_info as $v_allowance_info):
                                    ?>
                                    <div class="">
                                        <input type="text" style="margin:5px 0px;height: 28px;width: 56%;"
                                               class="form-control" name="allowance_label[]"
                                               value="<?php echo $v_allowance_info->allowance_label; ?>" class="">
                                        <input type="number" name="allowance_value[]"
                                               value="<?php echo $v_allowance_info->allowance_value; ?>"
                                               class="salary form-control">
                                        <input type="hidden" name="salary_allowance_id[]"
                                               value="<?php echo $v_allowance_info->salary_allowance_id; ?>"
                                               class="salary form-control">
                                    </div>
                                    <?php $total_salary += $v_allowance_info->allowance_value; ?>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="">
                                        <label class="control-label"><?= lang('house_rent_allowance') ?> </label>
                                        <input type="number" name="house_rent_allowance" value=""
                                               class="salary form-control">
                                    </div>
                                    <div class="">
                                        <label class="control-label"><?= lang('medical_allowance') ?> </label>
                                        <input type="number" name="medical_allowance" value=""
                                               class="salary form-control">
                                    </div>
                                <?php endif; ?>
                                <div id="add_new">
                                </div>
                                <div class="margin">
                                    <strong><a href="javascript:void(0);" id="add_more" class="addCF "><i
                                                class="fa fa-plus"></i>&nbsp;<?= lang('add_more') ?></a></strong>
                                </div>
                            </div>
                        </div>
                    </div><!-- ********************Allowance End ******************-->

                    <!-- ************** Deduction Panel Column  **************-->
                    <div class="col-sm-6">
                        <div class="panel panel-custom">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <strong><?= lang('deductions') ?></strong>
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php
                                $total_deduction = 0;
                                if (!empty($salary_deduction_info)):foreach ($salary_deduction_info as $v_deduction_info):
                                    ?>
                                    <div class="">
                                        <input type="text" style="margin:5px 0px;height: 28px;width: 56%;"
                                               class="form-control" name="deduction_label[]"
                                               value="<?php echo $v_deduction_info->deduction_label; ?>" class="">
                                        <input type="number" name="deduction_value[]"
                                               value="<?php echo $v_deduction_info->deduction_value; ?>"
                                               class="deduction form-control">
                                        <input type="hidden" name="salary_deduction_id[]"
                                               value="<?php echo $v_deduction_info->salary_deduction_id; ?>"
                                               class="deduction form-control">
                                    </div>
                                    <?php $total_deduction += $v_deduction_info->deduction_value ?>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="">
                                        <label class="control-label"><?= lang('provident_fund') ?> </label>
                                        <input type="number" name="provident_fund" value=""
                                               class="deduction form-control">
                                    </div>
                                    <div class="">
                                        <label class="control-label"><?= lang('tax_deduction') ?> </label>
                                        <input type="number" name="tax_deduction" value="" class="deduction form-control">
                                    </div>
                                <?php endif; ?>
                                <div id="add_new_deduc">
                                </div>
                                <div class="margin">
                                    <strong><a href="javascript:void(0);" id="add_more_deduc" class="addCF "><i
                                                class="fa fa-plus"></i>&nbsp;<?= lang('add_more') ?></a></strong>
                                </div>
                            </div>
                        </div>
                    </div><!-- ****************** Deduction End  *******************-->
                    <!-- ************** Total Salary Details Start  **************-->
                </div>
                <div class="row">
                    <div class="col-md-8 pull-right">
                        <div class="panel panel-custom">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <strong><?= lang('total_salary_details') ?></strong>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered custom-table">
                                    <tr><!-- Sub total -->
                                        <th class="col-sm-8 vertical-td"><strong><?= lang('gross_salary') ?> :</strong>
                                        </th>
                                        <td class="">
                                            <input type="text" name="" disabled value="<?php
                                            if (!empty($total_salary) || !empty($salary_template_info->basic_salary)) {
                                                echo $total = $total_salary + $salary_template_info->basic_salary;
                                            }
                                            ?>" id="total" class="form-control">
                                        </td>
                                    </tr> <!-- / Sub total -->
                                    <tr><!-- Total tax -->
                                        <th class="col-sm-8 vertical-td"><strong><?= lang('total_deduction') ?>
                                                :</strong></th>
                                        <td class="">
                                            <input type="text" name="" disabled value="<?php
                                            if (!empty($total_deduction)) {
                                                echo $total_deduction;
                                            }
                                            ?>" id="deduc" class="form-control">
                                        </td>
                                    </tr><!-- / Total tax -->
                                    <tr><!-- Grand Total -->
                                        <th class="col-sm-8 vertical-td"><strong><?= lang('net_salary') ?> :</strong>
                                        </th>
                                        <td class="">
                                            <input type="text" name="" disabled required value="<?php
                                            if (!empty($total) || !empty($total_deduction)) {
                                                echo $total - $total_deduction;
                                            }
                                            ?>" id="net_salary" class="form-control">
                                        </td>
                                    </tr><!-- Grand Total -->
                                </table><!-- Order Total table list start -->

                            </div>
                        </div>
                    </div><!-- ****************** Total Salary Details End  *******************-->
                    <div class="col-sm-6 margin pull-right">
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 100) {
                alert("Maximum 100 File is allowed");
            } else {
                var add_new = $('<div class="row">\n\
    <div class="col-sm-12"><input type="text" name="allowance_label[]" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control"  placeholder="<?= lang('enter') . ' ' . lang('allowances') . ' ' . lang('label')?>" required ></div>\n\
<div class="col-sm-9"><input  type="number" name="allowance_value[]" placeholder="<?= lang('enter') . ' ' . lang('allowances') . ' ' . lang('value')?>" required  value="<?php
                    if (!empty($emp_salary->allowance_value)) {
                        echo $emp_salary->allowance_value;
                    }
                    ?>"  class="salary form-control"></div>\n\
<div class="col-sm-3"><strong><a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more_deduc").click(function () {
            if (maxAppend >= 100) {
                alert("Maximum 100 File is allowed");
            } else {
                var add_new = $('<div class="row">\n\
    <div class="col-sm-12"><input type="text" name="deduction_label[]" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control" placeholder="<?= lang('enter') . ' ' . lang('deductions') . ' ' . lang('label')?>" required></div>\n\
<div class="col-sm-9"><input  type="number" name="deduction_value[]" placeholder="<?= lang('enter') . ' ' . lang('deductions') . ' ' . lang('value')?>" required  value="<?php
                    if (!empty($emp_salary->other_deduction)) {
                        echo $emp_salary->other_deduction;
                    }
                    ?>"  class="deduction form-control"></div>\n\
<div class="col-sm-3"><strong><a href="javascript:void(0);" class="remCF_deduc"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div></div>');
                maxAppend++;
                $("#add_new_deduc").append(add_new);
            }
        });

        $("#add_new_deduc").on('click', '.remCF_deduc', function () {
            $(this).parent().parent().parent().remove();
        });
    });
</script>
<script type="text/javascript">
    $(document).on("change", function () {
        var sum = 0;
        var deduc = 0;
        $(".salary").each(function () {
            sum += +$(this).val();
        });

        $(".deduction").each(function () {
            deduc += +$(this).val();
        });
        var ctc = $("#ctc").val();

        $("#total").val(sum);
        $("#deduc").val(deduc);
        var net_salary = 0;
        net_salary = sum - deduc;
        $("#net_salary").val(net_salary);


    });
</script>

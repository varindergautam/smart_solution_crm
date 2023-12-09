<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <style>
        table tfoot tr td,
        .difference td {
            color: red !important;
            font-weight: bolder !important;
        }
    </style>

    <div class="content">
        <div class="row">
            <?php echo form_open_multipart($this->uri->uri_string(), ['class' => 'staff-form', 'autocomplete' => 'off', 'method' => 'get']); ?>

            <div class="col-md-12" id="small-table">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="horizontal-tabs">
                            <h4>
                                Budget Report
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <div class="form-group select-placeholder">
                                    <label for="budget" class="control-label"><?php echo _l('Budget'); ?></label>
                                    <select name="year" data-live-search="true" id="budget" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value="">Select Budget</option>
                                        <?php foreach ($financial_years as $financial_year) {
                                            $selected = '';
                                            if (isset($year)) {
                                                if ($year == $financial_year['id']) {
                                                    $selected = 'selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $financial_year['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo ucfirst($financial_year['year_name']); ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-bottom-toolbar text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <?php echo form_close(); ?>

            <?php
            if (isset($year)) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <h4>Income</h4>
                            <table class="table income" id="income">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Year</th>
                                        <th>Month</th>
                                        <th>Head Name</th>
                                        <th>Head Type</th>
                                        <th>Total Amount</th>
                                        <th>January</th>
                                        <th>February</th>
                                        <th>March</th>
                                        <th>April</th>
                                        <th>May</th>
                                        <th>June</th>
                                        <th>July</th>
                                        <th>August</th>
                                        <th>September</th>
                                        <th>October</th>
                                        <th>November</th>
                                        <th>December</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $incomeAmountTotal = 0;
                                    $incomeJanuaryTotal = 0;
                                    $incomeFebruaryTotal = 0;
                                    $incomeMarchTotal = 0;
                                    $incomeAprilTotal = 0;
                                    $incomeMayTotal = 0;
                                    $incomeJuneTotal = 0;
                                    $incomeJulyTotal = 0;
                                    $incomeAugustTotal = 0;
                                    $incomeSeptemberTotal = 0;
                                    $incomeOctoberTotal = 0;
                                    $incomeNovemberTotal = 0;
                                    $incomeDecemberTotal = 0;
                                    foreach ($income_head as $key => $income) {
                                        $incomeAmountTotal += $income->amount;
                                        $incomeJanuaryTotal += $income->january;
                                        $incomeFebruaryTotal += $income->february;
                                        $incomeMarchTotal += $income->march;
                                        $incomeAprilTotal += $income->april;
                                        $incomeMayTotal += $income->may;
                                        $incomeJuneTotal += $income->june;
                                        $incomeJulyTotal += $income->july;
                                        $incomeAugustTotal += $income->august;
                                        $incomeSeptemberTotal += $income->september;
                                        $incomeOctoberTotal += $income->october;
                                        $incomeNovemberTotal += $income->november;
                                        $incomeDecemberTotal += $income->december;

                                    ?>

                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $income->year_name; ?></td>
                                            <td><?php echo $income->into_month; ?></td>
                                            <td><?php echo $income->head_name; ?></td>
                                            <td><?php echo $income->head_type; ?></td>
                                            <td><?php echo $income->amount; ?></td>
                                            <td><?php echo $income->january; ?></td>
                                            <td><?php echo $income->february; ?></td>
                                            <td><?php echo $income->march; ?></td>
                                            <td><?php echo $income->april; ?></td>
                                            <td><?php echo $income->may; ?></td>
                                            <td><?php echo $income->june; ?></td>
                                            <td><?php echo $income->july; ?></td>
                                            <td><?php echo $income->august; ?></td>
                                            <td><?php echo $income->september; ?></td>
                                            <td><?php echo $income->october; ?></td>
                                            <td><?php echo $income->november; ?></td>
                                            <td><?php echo $income->december; ?></td>
                                        </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $incomeAmountTotal; ?></td>
                                        <td><?php echo $incomeJanuaryTotal; ?></td>
                                        <td><?php echo $incomeFebruaryTotal; ?></td>
                                        <td><?php echo $incomeMarchTotal; ?></td>
                                        <td><?php echo $incomeAprilTotal; ?></td>
                                        <td><?php echo $incomeMayTotal; ?></td>
                                        <td><?php echo $incomeJuneTotal; ?></td>
                                        <td><?php echo $incomeJulyTotal; ?></td>
                                        <td><?php echo $incomeAugustTotal; ?></td>
                                        <td><?php echo $incomeSeptemberTotal; ?></td>
                                        <td><?php echo $incomeOctoberTotal; ?></td>
                                        <td><?php echo $incomeNovemberTotal; ?></td>
                                        <td><?php echo $incomeDecemberTotal; ?></td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>


            <?php
            if (isset($year)) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <h4>Expense</h4>
                            <table class="table table-budget" id="expense">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Year</th>
                                        <th>Month</th>
                                        <th>Head Name</th>
                                        <th>Head Type</th>
                                        <th>Total Amount</th>
                                        <th>January</th>
                                        <th>February</th>
                                        <th>March</th>
                                        <th>April</th>
                                        <th>May</th>
                                        <th>June</th>
                                        <th>July</th>
                                        <th>August</th>
                                        <th>September</th>
                                        <th>October</th>
                                        <th>November</th>
                                        <th>December</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $expenseAmountTotal = 0;
                                    $expenseJanuaryTotal = 0;
                                    $expenseFebruaryTotal = 0;
                                    $expenseMarchTotal = 0;
                                    $expenseAprilTotal = 0;
                                    $expenseMayTotal = 0;
                                    $expenseJuneTotal = 0;
                                    $expenseJulyTotal = 0;
                                    $expenseAugustTotal = 0;
                                    $expenseSeptemberTotal = 0;
                                    $expenseOctoberTotal = 0;
                                    $expenseNovemberTotal = 0;
                                    $expenseDecemberTotal = 0;

                                    foreach ($expense_head as $expense) {

                                        $expenseAmountTotal += $expense->amount;
                                        $expenseJanuaryTotal += $expense->january;
                                        $expenseFebruaryTotal += $expense->february;
                                        $expenseMarchTotal += $expense->march;
                                        $expenseAprilTotal += $expense->april;
                                        $expenseMayTotal += $expense->may;
                                        $expenseJuneTotal += $expense->june;
                                        $expenseJulyTotal += $expense->july;
                                        $expenseAugustTotal += $expense->august;
                                        $expenseSeptemberTotal += $expense->september;
                                        $expenseOctoberTotal += $expense->october;
                                        $expenseNovemberTotal += $expense->november;
                                        $expenseDecemberTotal += $expense->december;
                                    ?>

                                        <tr>
                                            <td><?php echo $expense->id; ?></td>
                                            <td><?php echo $expense->year_name; ?></td>
                                            <td><?php echo $expense->into_month; ?></td>
                                            <td><?php echo $expense->head_name; ?></td>
                                            <td><?php echo $expense->head_type; ?></td>
                                            <td><?php echo $expense->amount; ?></td>
                                            <td><?php echo $expense->january; ?></td>
                                            <td><?php echo $expense->february; ?></td>
                                            <td><?php echo $expense->march; ?></td>
                                            <td><?php echo $expense->april; ?></td>
                                            <td><?php echo $expense->may; ?></td>
                                            <td><?php echo $expense->june; ?></td>
                                            <td><?php echo $expense->july; ?></td>
                                            <td><?php echo $expense->august; ?></td>
                                            <td><?php echo $expense->september; ?></td>
                                            <td><?php echo $expense->october; ?></td>
                                            <td><?php echo $expense->november; ?></td>
                                            <td><?php echo $expense->december; ?></td>
                                        </tr>

                                    <?php
                                    }
                                    ?>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $expenseAmountTotal; ?></td>
                                        <td><?php echo $expenseJanuaryTotal; ?></td>
                                        <td><?php echo $expenseFebruaryTotal; ?></td>
                                        <td><?php echo $expenseMarchTotal; ?></td>
                                        <td><?php echo $expenseAprilTotal; ?></td>
                                        <td><?php echo $expenseMayTotal; ?></td>
                                        <td><?php echo $expenseJuneTotal; ?></td>
                                        <td><?php echo $expenseJulyTotal; ?></td>
                                        <td><?php echo $expenseAugustTotal; ?></td>
                                        <td><?php echo $expenseSeptemberTotal; ?></td>
                                        <td><?php echo $expenseOctoberTotal; ?></td>
                                        <td><?php echo $expenseNovemberTotal; ?></td>
                                        <td><?php echo $expenseDecemberTotal; ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>


            <?php
            if (isset($year)) {
                $differenceAmountTotal = $incomeAmountTotal - $expenseAmountTotal;
                $differenceJanuaryTotal = $incomeJanuaryTotal - $expenseJanuaryTotal;
                $differenceFebruaryTotal = $incomeFebruaryTotal - $expenseFebruaryTotal;
                $differenceMarchTotal = $incomeMarchTotal - $expenseMarchTotal;
                $differenceAprilTotal = $incomeAprilTotal - $expenseAprilTotal;
                $differenceMayTotal = $incomeMayTotal - $expenseMayTotal;
                $differenceJuneTotal = $incomeJuneTotal - $expenseJuneTotal;
                $differenceJulyTotal = $incomeJulyTotal - $expenseJulyTotal;
                $differenceAugustTotal = $incomeAugustTotal - $expenseAugustTotal;
                $differenceSeptemberTotal = $incomeSeptemberTotal - $expenseSeptemberTotal;
                $differenceOctoberTotal = $incomeOctoberTotal - $expenseOctoberTotal;
                $differenceNovemberTotal = $incomeNovemberTotal - $expenseNovemberTotal;
                $differenceDecemberTotal = $incomeDecemberTotal - $expenseDecemberTotal;
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <h4>Balance</h4>
                            <table class="table table-budget" id="balance">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Total</th>
                                        <th>January</th>
                                        <th>February</th>
                                        <th>March</th>
                                        <th>April</th>
                                        <th>May</th>
                                        <th>June</th>
                                        <th>July</th>
                                        <th>August</th>
                                        <th>September</th>
                                        <th>October</th>
                                        <th>November</th>
                                        <th>December</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Income</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $incomeAmountTotal; ?></td>
                                        <td><?php echo $incomeJanuaryTotal; ?></td>
                                        <td><?php echo $incomeFebruaryTotal; ?></td>
                                        <td><?php echo $incomeMarchTotal; ?></td>
                                        <td><?php echo $incomeAprilTotal; ?></td>
                                        <td><?php echo $incomeMayTotal; ?></td>
                                        <td><?php echo $incomeJuneTotal; ?></td>
                                        <td><?php echo $incomeJulyTotal; ?></td>
                                        <td><?php echo $incomeAugustTotal; ?></td>
                                        <td><?php echo $incomeSeptemberTotal; ?></td>
                                        <td><?php echo $incomeOctoberTotal; ?></td>
                                        <td><?php echo $incomeNovemberTotal; ?></td>
                                        <td><?php echo $incomeDecemberTotal; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Expense</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $expenseAmountTotal; ?></td>
                                        <td><?php echo $expenseJanuaryTotal; ?></td>
                                        <td><?php echo $expenseFebruaryTotal; ?></td>
                                        <td><?php echo $expenseMarchTotal; ?></td>
                                        <td><?php echo $expenseAprilTotal; ?></td>
                                        <td><?php echo $expenseMayTotal; ?></td>
                                        <td><?php echo $expenseJuneTotal; ?></td>
                                        <td><?php echo $expenseJulyTotal; ?></td>
                                        <td><?php echo $expenseAugustTotal; ?></td>
                                        <td><?php echo $expenseSeptemberTotal; ?></td>
                                        <td><?php echo $expenseOctoberTotal; ?></td>
                                        <td><?php echo $expenseNovemberTotal; ?></td>
                                        <td><?php echo $expenseDecemberTotal; ?></td>
                                    </tr>
                                    <tr class="difference">
                                        <td>Balance</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $differenceAmountTotal; ?></td>
                                        <td><?php echo $differenceJanuaryTotal; ?></td>
                                        <td><?php echo $differenceFebruaryTotal; ?></td>
                                        <td><?php echo $differenceMarchTotal; ?></td>
                                        <td><?php echo $differenceAprilTotal; ?></td>
                                        <td><?php echo $differenceMayTotal; ?></td>
                                        <td><?php echo $differenceJuneTotal; ?></td>
                                        <td><?php echo $differenceJulyTotal; ?></td>
                                        <td><?php echo $differenceAugustTotal; ?></td>
                                        <td><?php echo $differenceSeptemberTotal; ?></td>
                                        <td><?php echo $differenceOctoberTotal; ?></td>
                                        <td><?php echo $differenceNovemberTotal; ?></td>
                                        <td><?php echo $differenceDecemberTotal; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            <?php
            }
            ?>

        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    $(document).ready(function() {
        $('#expense').DataTable({
            "scrollX": true,
            "scrollY": "300px",
            "scrollCollapse": true,
            "dom": 'lBfrtip',
        });
        $('#expense_wrapper').removeClass('table-loading');

        $('#income').DataTable({
            "scrollX": true,
            "scrollY": "300px",
            "scrollCollapse": true,
            "dom": 'lBfrtip',
        });
        $('#income_wrapper').removeClass('table-loading');

        $('#balance').DataTable({
            "scrollX": true,
            "scrollY": "300px",
            "scrollCollapse": true,
            order: [
                [1, 'asc']
            ],
            "dom": 'lBfrtip',
        });
        $('#balance_wrapper').removeClass('table-loading');
    });
</script>
</body>

</html>
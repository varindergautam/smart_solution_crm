<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
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
                            <table class="table table-budget11" id="income">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Year</th>
                                        <th>Total Amount</th>
                                        <th>Divide in Month</th>
                                        <th>Head Name</th>
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
                                    foreach ($income_head as $income) {
                                    ?>

                                        <tr>
                                            <td><?php echo $income->id; ?></td>
                                            <td><?php echo $income->year_name; ?></td>
                                            <td><?php echo $income->amount; ?></td>
                                            <td><?php echo $income->into_month; ?></td>
                                            <td><?php echo $income->head_type; ?></td>
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
                                        <th>ID</th>
                                        <th>Year</th>
                                        <th>Total Amount</th>
                                        <th>Divide in Month</th>
                                        <th>Head Name</th>
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
                                    foreach ($expense_head as $expense) {
                                    ?>

                                        <tr>
                                            <td><?php echo $expense->id; ?></td>
                                            <td><?php echo $expense->year_name; ?></td>
                                            <td><?php echo $expense->amount; ?></td>
                                            <td><?php echo $income->into_month; ?></td>
                                            <td><?php echo $income->head_type; ?></td>
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
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    $(document).ready(function() {
        // $('#expense').DataTable();
        // $('#income').DataTable();
    });
</script>
</body>

</html>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <style>
        table tfoot tr td {
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
                                Receivable Summarize Report
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                                <?php $value = (isset($s_year) ? $s_year : ''); ?>

                                <label>Year</label>
                                <select name="year" id="year" class="form-control">
                                    <option>Select year</option>
                                    <?php
                                    $startYear = 2015;
                                    $endYear = 2050;

                                    for ($year = $startYear; $year <= $endYear; $year++) {
                                        $selected = ($year == $s_year) ? 'selected' : '';
                                        echo "<option value=\"$year\" $selected>$year</option>";
                                    }
                                    ?>
                                </select>
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
            if (isset($s_year)) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <table id="summarize" class="table">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Company Name</th>
                                        <th>Invoice No.</th>
                                        <th>Invoice Due Date</th>
                                        <th>Amount</th>
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
                                    $reportAmountTotal = 0;
                                    $reportJanuaryTotal = 0;
                                    $reportFebruaryTotal = 0;
                                    $reportMarchTotal = 0;
                                    $reportAprilTotal = 0;
                                    $reportMayTotal = 0;
                                    $reportJuneTotal = 0;
                                    $reportJulyTotal = 0;
                                    $reportAugustTotal = 0;
                                    $reportSeptemberTotal = 0;
                                    $reportOctoberTotal = 0;
                                    $reportNovemberTotal = 0;
                                    $reportDecemberTotal = 0;

                                    foreach ($reports as $report) {
                                        $reportAmountTotal += $report->invoice_amount;
                                        $reportJanuaryTotal += $report->january;
                                        $reportFebruaryTotal += $report->february;
                                        $reportMarchTotal += $report->march;
                                        $reportAprilTotal += $report->april;
                                        $reportMayTotal += $report->may;
                                        $reportJuneTotal += $report->june;
                                        $reportJulyTotal += $report->july;
                                        $reportAugustTotal += $report->august;
                                        $reportSeptemberTotal += $report->september;
                                        $reportOctoberTotal += $report->october;
                                        $reportNovemberTotal += $report->november;
                                        $reportDecemberTotal += $report->december;
                                    ?>
                                        <tr>
                                            <td><?php echo $report->id; ?></td>
                                            <td><?php echo $report->company_name; ?></td>
                                            <td><?php echo $report->invoice_number; ?></td>
                                            <td><?php echo $report->invoice_due_date; ?></td>
                                            <td><?php echo $report->invoice_amount; ?></td>
                                            <td><?php echo $report->january; ?></td>
                                            <td><?php echo $report->february; ?></td>
                                            <td><?php echo $report->march; ?></td>
                                            <td><?php echo $report->april; ?></td>
                                            <td><?php echo $report->may; ?></td>
                                            <td><?php echo $report->june; ?></td>
                                            <td><?php echo $report->july; ?></td>
                                            <td><?php echo $report->august; ?></td>
                                            <td><?php echo $report->september; ?></td>
                                            <td><?php echo $report->october; ?></td>
                                            <td><?php echo $report->november; ?></td>
                                            <td><?php echo $report->december; ?></td>
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
                                        <td><?php echo $reportAmountTotal; ?></td>
                                        <td><?php echo $reportJanuaryTotal; ?></td>
                                        <td><?php echo $reportFebruaryTotal; ?></td>
                                        <td><?php echo $reportMarchTotal; ?></td>
                                        <td><?php echo $reportAprilTotal; ?></td>
                                        <td><?php echo $reportMayTotal; ?></td>
                                        <td><?php echo $reportJuneTotal; ?></td>
                                        <td><?php echo $reportJulyTotal; ?></td>
                                        <td><?php echo $reportAugustTotal; ?></td>
                                        <td><?php echo $reportSeptemberTotal; ?></td>
                                        <td><?php echo $reportOctoberTotal; ?></td>
                                        <td><?php echo $reportNovemberTotal; ?></td>
                                        <td><?php echo $reportDecemberTotal; ?></td>
                                    </tr>
                                </tfoot>
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
        $('#summarize').DataTable({
            "scrollX": true,
            "scrollY": "300px",
            "scrollCollapse": true,
            "dom": 'lBfrtip',
        });
        $('#summarize_wrapper').removeClass('table-loading');

    });
</script>
</body>

</html>
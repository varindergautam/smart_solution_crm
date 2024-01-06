<?php

defined('BASEPATH') or exit('No direct script access allowed');
$dimensions = $pdf->getPageDimensions();

$pdf_logo_url = pdf_logo_url();
// $pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $pdf_logo_url, 0, 1, false, true, 'L', true);

// $pdf->ln(4);
// // Get Y position for the separation
// $y = $pdf->getY();

// $proposal_info = '<div style="color:#424242;">';
//     $proposal_info .= format_organization_info();
// $proposal_info .= '</div>';

// $pdf->writeHTMLCell(($swap == '0' ? (($dimensions['wk'] / 2) - $dimensions['rm']) : ''), '', '', ($swap == '0' ? $y : ''), $proposal_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);

// $rowcount = max([$pdf->getNumLines($proposal_info, 80)]);

// // Proposal to
// $client_details = '<b>' . _l('proposal_to') . '</b>';
// $client_details .= '<div style="color:#424242;">';
//     $client_details .= format_proposal_info($proposal, 'pdf');
// $client_details .= '</div>';

// $pdf->writeHTMLCell(($dimensions['wk'] / 2) - $dimensions['lm'], $rowcount * 7, '', ($swap == '1' ? $y : ''), $client_details, 0, 1, false, true, ($swap == '1' ? 'J' : 'R'), true);

// $pdf->ln(6);

// $proposal_date = _l('proposal_date') . ': ' . _d($proposal->date);
// $open_till     = '';

// if (!empty($proposal->open_till)) {
//     $open_till = _l('proposal_open_till') . ': ' . _d($proposal->open_till) . '<br />';
// }


// $project = '';
// if ($proposal->project_id != '' && get_option('show_project_on_proposal') == 1) {
//     $project .= _l('project') . ': ' . get_project_name_by_id($proposal->project_id) . '<br />';
// }

// $qty_heading = _l('estimate_table_quantity_heading', '', false);

// if ($proposal->show_quantity_as == 2) {
//     $qty_heading = _l($this->type . '_table_hours_heading', '', false);
// } elseif ($proposal->show_quantity_as == 3) {
//     $qty_heading = _l('estimate_table_quantity_heading', '', false) . '/' . _l('estimate_table_hours_heading', '', false);
// }

// // The items table
$items = get_items_table_data($proposal, 'proposal', 'pdf')
        ->set_headings('estimate');

// $items_html = $items->table();

// $items_html .= '<br /><br />';
// $items_html .= '';
// $items_html .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';

// $items_html .= '
// <tr>
//     <td align="right" width="85%"><strong>' . _l('estimate_subtotal') . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($proposal->subtotal, $proposal->currency_name) . '</td>
// </tr>';

// if (is_sale_discount_applied($proposal)) {
//     $items_html .= '
//     <tr>
//         <td align="right" width="85%"><strong>' . _l('estimate_discount');
//     if (is_sale_discount($proposal, 'percent')) {
//         $items_html .= ' (' . app_format_number($proposal->discount_percent, true) . '%)';
//     }
//     $items_html .= '</strong>';
//     $items_html .= '</td>';
//     $items_html .= '<td align="right" width="15%">-' . app_format_money($proposal->discount_total, $proposal->currency_name) . '</td>
//     </tr>';
// }

// foreach ($items->taxes() as $tax) {
//     $items_html .= '<tr>
//     <td align="right" width="85%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($tax['total_tax'], $proposal->currency_name) . '</td>
// </tr>';
// }

// if ((int)$proposal->adjustment != 0) {
//     $items_html .= '<tr>
//     <td align="right" width="85%"><strong>' . _l('estimate_adjustment') . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($proposal->adjustment, $proposal->currency_name) . '</td>
// </tr>';
// }
// $items_html .= '
// <tr style="background-color:#f0f0f0;">
//     <td align="right" width="85%"><strong>' . _l('estimate_total') . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($proposal->total, $proposal->currency_name) . '</td>
// </tr>';
// $items_html .= '</table>';

// if (get_option('total_to_words_enabled') == 1) {
//     $items_html .= '<br /><br /><br />';
//     $items_html .= '<strong style="text-align:center;">' . _l('num_word') . ': ' . $CI->numberword->convert($proposal->total, $proposal->currency_name) . '</strong>';
// }

// $proposal->content = str_replace('{proposal_items}', $items_html, $proposal->content);

// Get the proposals css
// Theese lines should aways at the end of the document left side. Dont indent these lines

$format_proposal_number = format_proposal_number($proposal->id);
$gross_total = $proposal->currency_name . ' '.$proposal->subtotal;

$itemsData = '';
$totalAmount = 0;
$totalQty = 0;

if (!empty($proposal->items)) {
    foreach ($proposal->items as $key => $item) {
        $totalQty += $item['qty'];
        $totaQtyPrice = $item['qty'] * $item['rate'];
        $totalAmount += $totaQtyPrice;

        $itemsData .= 
        "<tr>
            <td >" . ($key + 1) . "</td>
            <td style='text-align:center;'>{$item['item_code']}</td>
            <td>{$item['description']}</td>
            <td>{$item['qty']}</td>
            <td>{$item['rate']}</td>
            <td>VAT 5.00%</td>
            <td class='amount'>" . number_format($totaQtyPrice, 2) . "</td>
        </tr>";
    }
}

$taxHtml = '';

foreach ($items->taxes() as $tax) {
    $taxHtml .= '<th class="cal_heading"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)</strong></th><td class="cal_colon">:</td><td style="text-align:right;" class="cal_total_data">' . $proposal->currency_name . ' '. number_format($tax['total_tax'] * 100,2) . '</td>';
}

$amountWords = $CI->numberword->convert($proposal->total, $proposal->currency_name) ;
$totalFinalAmount = $proposal->currency_name.' ' .$proposal->total;
$html = <<<EOF

<style>
    .customer_detail td {
        width: 50%;
    }

    .customer_detail_1 td.heading {
        width: 100px;
    }

    .customer_detail_1 td.colon {
        width: 30px;
        text-align: center;
    }

    .customer_detail_1 td.data-text {
        width: 65%;
    }

    .customer_detail_2 td.heading {
        width: 150px;
    }

    .customer_detail_2 td.colon {
        width: 30px;
        text-align: center;
    }

    .customer_detail_2 td.data-text {
        /* width: 65%; */
    }

    th.cal_heading{
        width: 200px;
    }

    .cal_colon{
        text-align:center;
    }

    .gross_table .cal_heading{
        width:150px;
    }
    .gross_table .cal_colon{
        width: 30px;
        text-align: center;
    }
    .gross_table .cal_total_data{
        width:170px;
    }

    .total_amount_table .cal_heading{
        width:150px;
    }
    .total_amount_table .cal_colon{
        width: 30px;
        text-align: center;
    }
    .total_amount_table .cal_total_data{
        width:180px;
    }

    .customer_detail tr th {
        padding:20px;
    }

    .item_list tr td{
        text-align:center;
    }

</style>

<table style="width: 100%;" class="print-font-size">
    <tr>
        <td style="width: 200px;">
        <img src="' . base_url('uploads/company/' . $pdf_logo_url) . '" class="img-responsive">
        </td>
        <td style="text-align: center;">
            <span style="font-size:17px; color:#1a3f85;font-weight:bold;">SHAMIS MOHAMED GENERAL TRADING LLC</span>
            <br>
            <br>
            <span class="" style="font-weight:bold;">Near Hor Al Anz Post Office Opp,Dubai Municipality <br>
                Dubai-U.A.E,P O BOX.21099 <br>
                Tel: +971 42964336 Mob:+971 521060170
            </span>
            <br>
            <br>
            <strong>CUSTOMER QUOTATION</strong><br>
            <strong>TRN: 100433744800003</strong>
        </td>
    </tr>
</table>
<br>
<br>
<table border="1px solid" class="customer_detail" >
    <tr>
        <td style="width:60%;">
            <table class="customer_detail_1" cellpadding="4" cellspacing="0">
                <tr>
                    <td class="heading">
                        Party Name
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        NEW MISK BUILDING MAITANANCE LLC.
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        Address
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        P.O.BOX : 50484,OFFICE 512
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        City
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        OUD METHA,BUR DUBAI
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        Contact
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        Mr Joshi - +971566986306
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        Emirate
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        Dubai
                    </td>
                </tr>
                <tr>
                    <td class="heading">
                        Country
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        UAE
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        TRN
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        100390022000003
                    </td>
                </tr>

            </table>
        </td>
        <td style="vertical-align: top; width:40%;">
            <table width="100%" class="customer_detail_2"  cellpadding="4" cellspacing="0">
                <tr>
                    <td class="heading">
                        Customer Qtn No
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        $format_proposal_number
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        Voucher Date
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        $proposal->date
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        Reference
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        Salesman
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        
                    </td>
                </tr>

                <tr>
                    <td class="heading">
                        Payment Terms
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="data-text">
                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<br>
<table width="100%" border="1px solid" style="" class="item_list">
    <tr>
        <th style="width:5%;text-align:center"><strong>#</strong></th>
        <th style="width:15%;text-align:center"><strong>Item Code</strong></th>
        <th style="width:25%;text-align:center"><strong>Item</strong></th>
        <th style="width:10%;text-align:center"><strong>Qty</strong></th>
        <th style="width:15%;text-align:center"><strong>Rate</strong></th>
        <th style="width:15%;text-align:center"><strong>Tax</strong></th>
        <th style="width:15%;text-align:center"><strong>Amount</strong></th>
    </tr>
    $itemsData

    <tr>
        <td colspan="3" style="text-align:right;"><strong>Total QTY</strong></td>
        <td style="text-align:center;" colspan="1">$totalQty</td>

        <td colspan="3" style="padding:0;">
            <table  class="gross_table" style=""  cellpadding="4" cellspacing="0">
                <tr>
                    <th class="cal_heading"><strong>Gross Total</strong></th>
                    <td class="cal_colon">:</td>
                    <td class="cal_total_data"  style="text-align:right;">
                    $gross_total
                    </td>
                </tr>
                <tr>
                $taxHtml
                </tr>
                
            </table>
        </td>
    </tr>
    
    <tr>
        <td colspan="4" style="text-align:center;">
            <strong>Amount in Words :</strong>
            $amountWords
        </td>

        <td colspan="3" style="padding:0;">
            <table class="total_amount_table" style="background:transparent;">
                <tr>
                    <th class="cal_heading"><strong>Total</strong></th>
                    <td class="cal_colon">:</td>
                    <td style="text-align:right;" class="cal_total_data">
                    $totalFinalAmount
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="100%" border="1px solid" style="" class="">
    <tr>
        <td style="font-size:12px;">
            <strong>Declaration:</strong> <br>
                We declare that this invoice shows the actual price of the goods describe and that all particulars are true and correct.<br>
                * For replacemens, bring the Origional invoice within 3 days from purchase date.<br>
                * Opened Packages will not be replaced.<br>
                * We do not provide cash refunds.
        </td>
        <td style="font-size:12px;">
            <strong>Bank Details:</strong><br>
                Shamis Mohamed General Trading LLC<br>
                RAKBANK ACCOUNT NUMBER - 0252675627001<br>
                IBAN AE430400000252675627001<br>
                SWIFT CODE NRAKAEAK<br>
                DEIRA SOUQ DUBAI
        </td>
    </tr>
</table>

</div>
EOF;


$pdf->setCellPaddings(2, 2, 2, 2);
$pdf->writeHTML($html, true, false, true, false, '');



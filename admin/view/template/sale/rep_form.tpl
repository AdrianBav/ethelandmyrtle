<?php echo $header; ?>

<div id="content">

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?>
        <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
        </a>
        <?php } ?>
    </div>

    <?php if ($error_warning) { ?>
    <div class="warning">
        <?php echo $error_warning; ?>
    </div>
    <?php } ?>

    <div class="box">

        <div class="heading">
            <h1>
                <img src="view/image/user-group.png" alt="" /> <?php echo $heading_title; ?>
            </h1>
            <div class="buttons">
                <a onclick="$('#form').submit();" class="button">
                    <?php echo $button_save; ?>
                </a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button">
                    <?php echo $button_cancel; ?>
                </a>
            </div>
        </div>

        <div class="content">

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

                <table class="form reps">
                    <tr>
                        <td>
                            <span class="required">*</span> <?php echo $entry_short_title; ?>
                        </td>
                        <td>
                            <input type="text" name="short_title" value="<?php echo $short_title; ?>" />
                            <?php if ($error_short_title) { ?>
                            <span class="error">
                                <?php echo $error_short_title; ?>
                            </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="required">*</span> <?php echo $entry_long_title; ?>
                        </td>
                        <td>
                            <input type="text" name="long_title" value="<?php echo $long_title; ?>" />
                            <?php if ($error_long_title) { ?>
                            <span class="error">
                                <?php echo $error_long_title; ?>
                            </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_address_1; ?>
                        </td>
                        <td>
                            <input type="text" name="address_1" value="<?php echo $address_1; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_address_2; ?>
                        </td>
                        <td>
                            <input type="text" name="address_2" value="<?php echo $address_2; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_city; ?>
                        </td>
                        <td>
                            <input type="text" name="city" value="<?php echo $city; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_country; ?>
                        </td>
                        <td>
                            <select name="country" id="country" onchange="$('select[name=\'state\']').load('index.php?route=sale/rep/zone&token=<?php echo $token; ?>&country=' + this.value + '&state_code=<?php echo $state_code; ?>');">
                                <option value="">
                                    <?php echo $text_select; ?>
                                </option>
                                <?php foreach ($countries as $country_code => $country_name) { ?>
                                <?php if ($country_code == $country) { ?>
                                <option value="<?php echo $country_code; ?>" selected="selected">
                                    <?php echo $country_name; ?>
                                </option>
                                <?php } else { ?>
                                <option value="<?php echo $country_code; ?>">
                                    <?php echo $country_name; ?>
                                </option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>          
                    <tr>
                        <td>
                            <?php echo $entry_state; ?>
                        </td>
                        <td>
                            <select name="state" id="state">
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_zipcode; ?>
                        </td>
                        <td>
                            <input type="text" name="zipcode" value="<?php echo $zipcode; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="required">*</span> <?php echo $entry_telephone_1; ?>
                        </td>
                        <td>
                            <input type="text" name="telephone_1" value="<?php echo $telephone_1; ?>" />
                            <?php if ($error_telephone_1) { ?>
                            <span class="error">
                                <?php echo $error_telephone_1; ?>
                            </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_telephone_2; ?>
                        </td>
                        <td>
                            <input type="text" name="telephone_2" value="<?php echo $telephone_2; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_fax; ?>
                        </td>
                        <td>
                            <input type="text" name="fax" value="<?php echo $fax; ?>" />
                        </td>
                    </tr>    
                    <tr>
                        <td>
                            <span class="required">*</span> <?php echo $entry_email; ?>
                        </td>
                        <td>
                            <input type="text" name="email" value="<?php echo $email; ?>" />
                            <?php if ($error_email) { ?>
                            <span class="error">
                                <?php echo $error_email; ?>
                            </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_website; ?>
                        </td>
                        <td>
                            <input type="text" name="website" value="<?php echo $website; ?>" />
                        </td>
                    </tr>    
                    <tr>
                        <td>
                            <?php echo $entry_notes; ?>
                        </td>
                        <td>
                            <textarea class="q1" name="notes" rows="6">
                                <?php echo $notes; ?>
                            </textarea>
                        </td>
                    </tr>    
                </table>

                <br>

                <table id="territory" class="list">
                    <thead>
                        <tr>
                            <td class="left">
                                <?php echo $entry_territory; ?>
                            </td>
                            <td class="left">
                                <?php echo $entry_sort_order; ?>
                            </td>
                            <td class="left">
                                <?php echo $entry_notes; ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                    </thead>
                    <?php $territory_row = 0; ?>
                    <?php foreach ($territories as $territory) { ?>
                    <tbody id="territory-row<?php echo $territory_row; ?>">
                        <tr>
                            <td class="left">
                                <select name="territory[<?php echo $territory_row; ?>][territory]">
                                    <option value="">
                                        <?php echo $text_select; ?>
                                    </option>
                                    <?php foreach ($zones as $zone) { ?>
                                    <?php if ($zone['zone_id'] == $territory['territory']) { ?>
                                    <option value="<?php echo $zone['zone_id']; ?>" selected="selected">
                                        <?php echo $zone['name']; ?>
                                    </option>
                                    <?php } else { ?>
                                    <option value="<?php echo $zone['zone_id']; ?>">
                                        <?php echo $zone['name']; ?>
                                    </option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </td>
                            <td class="left">
                                <input type="text" name="territory[<?php echo $territory_row; ?>][sort_order]" value="<?php echo $territory['sort_order']; ?>" />
                            </td>
                            <td class="left">
                                <input type="text" name="territory[<?php echo $territory_row; ?>][extra]" value="<?php echo $territory['extra']; ?>" />
                            </td>
                            <td class="left">
                                <a onclick="$('#territory-row<?php echo $territory_row; ?>').remove();" class="button">
                                    <?php echo $button_remove; ?>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <?php $territory_row++; ?>
                    <?php } ?>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                            </td>
                            <td class="left">
                                <a onclick="addTerritory();" class="button">
                                    <?php echo $button_add_territory; ?>
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </form>

        </div>    
    </div>
</div>

<script type="text/javascript">
<!--
    $('select[name=\'state\']').load('index.php?route=sale/rep/zone&token=<?php echo $token; ?>&country=<?php echo $country; ?>&state_code=<?php echo $state_code; ?>');
//-->
</script> 

<script type="text/javascript">
<!--
var territory_row = <?php echo $territory_row; ?>;

function addTerritory() {
    html  = '<tbody id="territory-row' + territory_row + '">';
    html += '  <tr>';
    html += '    <td class="left"><select name="territory[' + territory_row + '][territory]">';
    html += '    <option value=""><?php echo $text_select; ?></option>';
    <?php foreach ($zones as $zone) { ?>
        html += '<option value="<?php echo $zone['zone_id']; ?>"><?php echo addslashes($zone['name']); ?></option>';
    <?php } ?>   
    html += '    </select></td>';
    html += '    <td class="left"><input type="text" name="territory[' + territory_row + '][sort_order]" value="" /></td>';
    html += '    <td class="left"><input type="text" name="territory[' + territory_row + '][extra]" value="" /></td>';
    html += '    <td class="left"><a onclick="$(\'#territory-row' + territory_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
    html += '  </tr>';
    html += '</tbody>';
    $('#territory > tfoot').before(html);
    territory_row++;
}
//-->
</script> 

<?php echo $footer; ?>
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

    <?php if ($success) { ?>
    <div class="success">
        <?php echo $success; ?>
    </div>
    <?php } ?>

    <div class="box">

        <div class="heading">      
            <h1>
                <img src="view/image/user-group.png" alt="" /> <?php echo $heading_title; ?>
            </h1>

            <div class="buttons">
                <a onclick="location = '<?php echo $insert; ?>'" class="button">
                    <?php echo $button_insert; ?>
                </a>
                <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button">
                    <?php echo $button_delete; ?>
                </a>
            </div>
        </div>

        <div class="content">
            <form action="" method="post" enctype="multipart/form-data" id="form">
                <table class="list reps">
                    <thead>
                        <tr>
                            <td width="1" style="text-align: center;">
                                <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
                            </td>
                            <td class="left">
                                <?php echo $column_long_title; ?>
                            </td>
                            <td class="left">
                                <?php echo $column_telephone; ?>
                            </td>
                            <td class="left">
                                <?php echo $column_email; ?>
                            </td>
                            <td class="left">
                                <?php echo $column_website; ?>
                            </td>
                            <td class="right">
                                <?php echo $column_action; ?>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($reps) { ?>
                        <?php foreach ($reps as $rep) { ?>
                        <tr>
                            <td style="text-align: center;">
                                <?php if ($rep['selected']) { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $rep['rep_id']; ?>" checked="checked" />
                                <?php } else { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $rep['rep_id']; ?>" />
                                <?php } ?>
                            </td>
                            <td class="left">
                                <?php echo $rep['long_title']; ?>
                            </td>
                            <td class="left">
                                <?php echo $rep['telephone']; ?>
                            </td>
                            <td class="left">
                                <?php echo $rep['email']; ?>
                            </td>
                            <td class="left">
                                <a href="<?php echo $rep['website']; ?>" target="_blank">
                                    <?php echo $rep['website']; ?>
                                </a>
                            </td>
                            <td class="right thin">
                                <?php foreach ($rep['action'] as $action) { ?>
                                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td class="center" colspan="5">
                                <?php echo $text_no_results; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
            <div class="pagination">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?> 
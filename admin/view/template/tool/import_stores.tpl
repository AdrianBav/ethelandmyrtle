<?php echo $header; ?>

<div id="content">

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb[ 'separator']; ?>
        <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb[ 'text']; ?>
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
            <h1><img src="view/image/download.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_import; ?></span></a>
            </div>
        </div>
        
        <div class="content">

            <!-- Import Stores -->    
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td colspan="2">
                            <?php echo $entry_description; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <?php echo $entry_import; ?>
                        </td>
                        <td>
                            <input type="file" name="upload" />
                        </td>
                    </tr>
                </table>
            </form>

            <!-- Generate Markers -->
            <form action="<?php echo $markers_action; ?>" method="post" enctype="multipart/form-data" id="markers-form" style="margin-top: 50px;">
                <table class="form">
                    <tr>
                        <td colspan="2">
                            <?php echo $entry_markers_description; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <?php echo $entry_markers; ?>
                        </td>
                        <td>
                            <input type="submit" name="markers" id="markers" value="Generate Markers" />
                        </td>
                    </tr>
                    <tr id="waiting-message" style="display: none;">
                        <td></td>
                        <td>
                            <?php echo $waiting_message; ?>
                        </td>                        
                    </tr>
                </table>
            </form>            

        </div>
        
    </div>
</div>

<script>
jQuery(document).ready(function() {
    
    $('#markers-form').submit(function() {
        
        $('#markers').val('Generating...').attr('disabled', 'disabled');
        $('#waiting-message').show();
    });    

});
</script>

<?php echo $footer; ?>
<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content">
    <?php echo $content_top; ?>

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <h1><?php echo $heading_title; ?></h1>

    <div class="box-container sales-rep">

        <h3>Click on a State to see the Sales Representatives for that area...</h3>

        <p><a href="javascript:void(0);" id="canada" class="canada-link">For Canada click here.</a></p>

        <!-- Map Holder -->
        <div id="map"></div>
    </div>

    <?php echo $content_bottom; ?>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {

    /* User click on Canada link */
    $('#canada').on('click', function() {
        findReps('CAN');
    });

    /* User click on Map */
    $('#map').usmap({
        stateStyles: { fill: '#c1c2c6' },
        stateHoverStyles: { fill: '#5eb8b6' },
        includeTerritories: [ 'PR', 'VI' ],
        click: function(event, data) {
            findReps(data.name);
        }
    });

    /* request reps for the given state */
    function findReps(state) {
        jQuery.ajax({
            url: 'index.php?route=information/contact/rep',
            type: 'post',
            data: { state: state },
            dataType: 'json',
            success: function(json) {
                jQuery('.success, .warning, .attention, information, .error').remove();

                if (json['error']) {
                    alert(json['error']);
                } else if (json['success']) {
                    jQuery.fancybox(json['data'], {
                        autoScale : false,
                        autoDimensions : false
                    });
                }
            }
        });
    }

});
//--></script>

<?php echo $footer; ?>
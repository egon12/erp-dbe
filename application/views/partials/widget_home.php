<!-- Charts -->
<div class="widget chartWrapper">
    <div class="whead">
		<div class="titleIcon"><span class="icon-bars"></span></div>
		<h6>Gross Income Charts</h6>
        <div class="clear"></div>
    </div>
    <div class="body"><div class="chart"></div></div>
</div>
<script type="text/javascript" src="<?php echo asset_url('js/plugins/charts/excanvas.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/plugins/charts/jquery.flot.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/plugins/tables/naturalSort.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/plugins/ui/jquery.tipsy.js'); ?>"></script>
<script type="text/javascript">


    $(document).ready(function(){

        //===== Chart =====//
        if ($('.chart').length >  0) drawChart();

        function drawChart(){
            $.plot($(".chart"),
                [
        {
            label: "Last Month",
                data: <?php echo $last_month ?>,
                lines: {show: true},
                points: {show: true}
        },
        {
            label: "This Month",
                data: <?php echo $this_month ?>,
                lines: {show: true},
                points: {show: true}   
        }
            ], {
                grid: { hoverable: true, clickable: true },
                    yaxis: { min: 0, max: <?php echo $max ?> },
                    xaxis: { min: 0, max: 32 }
            });
            function showTooltip(x, y, contents) {
                $('<div id="tooltip" class="tooltip">' + contents + '</div>').css( {
                    position: 'absolute',
                        display: 'none',
                        top: y + 5,
                        left: x + 5,
                        'z-index': '9999',
                        'color': '#fff',
                        'font-size': '11px',
                        opacity: 0.8
                }).appendTo("body").fadeIn(200);
            }
            var previousPoint = null;
            $(".chart").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));

                if ($(".chart").length > 0) {
                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            showTooltip(item.pageX, item.pageY,
                                item.series.label + " of " + x + " = " + y);
                        }
                    }
                    else {
                        $("#tooltip").remove();
                        previousPoint = null;            
                    }
                }
            });
            $(".chart").bind("plotclick", function (event, pos, item) {
                if (item) {
                    $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
                    plot.highlight(item.series, item.datapoint);
                }
            });
        }

        //===== Tooltips =====//

        $('.tipN').tipsy({gravity: 'n', live: true, fade: true, html:true});
        $('.tipS').tipsy({gravity: 's', live: true, fade: true, html:true});
        $('.tipW').tipsy({gravity: 'w', live: true, fade: true, html:true});
        $('.tipE').tipsy({gravity: 'e', live: true, fade: true, html:true});
    })
</script>

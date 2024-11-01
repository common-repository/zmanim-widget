jQuery(document).ready(function() {
function formatItem(row) {
                if (row[4]!='') output = "<img src='../wp-content/plugins/zmanim-widget/img/flags/" + row[4].toLowerCase() + ".png'/> ";
                output += row[0]+", "+row[3];
                return output;
        }
        function formatResult(row) {
                return row[0].replace(/(<.+?>)/gi, '')+", "+row[3];
        }
jQuery("input#location").autocomplete("../wp-content/plugins/zmanim-widget/lib/search_location.php", {
                width: 260,
              autoFill: false,
                selectFirst: false,
                formatItem: formatItem,
                formatResult: formatResult,
                highlightItem: true,
                matchContains: true,
                formatMatch: function(row) {
                        return row[0]+", "+row[3];
                }
        });
jQuery("input#location").result(function(event, data, formatted) {
                if (data) {
                        jQuery('input#lat').val(data[1]);
                        jQuery('input#long').val(data[2]);
                }

        });

/*jQuery('#default_date').click(function() {
        state=jQuery(this).attr('checked');
        if (!state) jQuery('#date').removeAttr('disabled');
        else jQuery('#date').attr('disabled', true);
});*/

});


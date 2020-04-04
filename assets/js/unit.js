//Import Sortable list
import Sortable from 'sortablejs/modular/sortable.complete.esm.js';

//* Functions **/

/**
 * Set unit position
 */
function setUnitPositions() {
    var items = [];
    $("#sortable li").each(function (index) {
        items.push($(this).data('id'));
    });

    $.ajax({
        url: '/admin/unit/order',
        type: 'POST',
        data: {
            'items': items
        },
        dataType: 'json',
        async: true,
        success: function (data) {
            if (data.success == true) {
                $.each(items, function (order, id) {
                    var order = order + 1;
                    $("ul#sortable li[data-id='" + id + "']").children('span.row').children('span.order').html(order);

                });
            } else {
                alert('Ajax request failed.');
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ajax request failed.');
        }
    });


}

$(document).ready(function () {
    //Init sortable list
    var el = document.getElementById('sortable');
    var sortable = Sortable.create(el,
        {
            onUpdate: function (/**Event*/evt) {
                setUnitPositions();
            },
        });
});

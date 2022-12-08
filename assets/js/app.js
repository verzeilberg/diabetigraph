// assets/js/app.js

//Bootstrap
import '../css/global.scss';
//Font-awesome
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
//Default css
require('../css/app.css');

const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');


$(document).ready(function () {
    bsCustomFileInput.init();

    $('.deleteItem').click(function () {
        console.log('test');
        let deleteType = $(this).data('type')
        let deleteLink = $(this).data('link');
        let deleteDescription = $(this).data('description');
        $('#deleteLink').attr('href', deleteLink);
        $('#deleteType').html(deleteType);
        $('#deleteDescription').html(deleteDescription);
        $('#deleteModal').modal('toggle');
    });

    $( ".ajax-call" ).on( "click", function() {
       const href = $(this).data('href');
       const data = $(this).attr('data');
        $.ajax({
            url: href,
            type: 'POST',
            data: {
                'data': data
            },
            dataType: 'json',
            async: true,
            success: function (data) {

            },
            error: function (xhr, textStatus, errorThrown) {
                alert('Ajax request failed.');
            }
        });
    });

});
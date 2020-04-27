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



    $('.deleteItem').click(function () {
        let deleteType = $(this).data('type')
        let deleteLink = $(this).data('link');
        let deleteDescription = $(this).data('description');
        $('#deleteLink').attr('href', deleteLink);
        $('#deleteType').html(deleteType);
        $('#deleteDescription').html(deleteDescription);
        $('#deleteModal').modal('toggle');
    });

});
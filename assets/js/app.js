// assets/js/app.js

//Bootstrap
import '../css/global.scss';
//Font-awesome
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
//Default css
require('../css/app.css');


const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');


function setMainContentHeight() {
    var windowHeight = window.innerHeight;
    var headerHeight = $("#header").height()
    var footerHeight = $("footer.footer").height();

    console.log('headerHeight: ', headerHeight);
    console.log('footerHeight: ', footerHeight);

    windowHeight = windowHeight - footerHeight - headerHeight - 30;

    console.log('windowHeight: ', windowHeight)

    $('#mainContent').height(windowHeight);
}


$(document).ready(function () {
    $('[data-toggle="popover"]').popover();


    setMainContentHeight();


});

$(window).change(function () {
    console.log('change window');
    setMainContentHeight();
});

$(window).resize(function () {
    console.log('resize window');
    setMainContentHeight();
});

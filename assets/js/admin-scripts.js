/**
 * This code runs on every page on the admin-side. If something should only run in a 1-2 places
 * consider creating a new script and conditionally loading it from src/Assets/AdminSide.php
 *
 * This code provides support for select2 and flatpickr fields
 */
jQuery(document).ready(function ($) {
    require("flatpickr");
    require("flatpickr-css");

    require("select2");
    require("select2-css");

    $('.flatpickr').flatpickr();

    $('select.select2').filter(function () {
        return !$(this).data('select2');
    }).select2();

    $('.piklist-field').on('click', '.piklist-field-addmore-wrapper .piklist-addmore-add', function (event) {
        var $element;
        $(event.target).closest('.piklist-field').find('select.select2').filter(function () {
            return !$(this).data('select2');
        }).select2();
    });
});

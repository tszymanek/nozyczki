$(document).ready(function() {
    $('#collapseOptions').on('show.bs.collapse', function () {
        $(".dropdown-toggle").removeClass('dropup');
    });

    $('#collapseOptions').on('hide.bs.collapse', function () {
        $(".dropdown-toggle").addClass('dropup');
    });
});
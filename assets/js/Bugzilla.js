$(document).ready(function () {
    $('body').on('click', '.bugzilla-status-button', function (ev) {
        ev.preventDefault();
        var link = $(this);
        var bugzillaStatus = link.next('.bugzilla-status');
        bugzillaStatus.html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
        $.getJSON(link.attr('href'), function (data) {
            if (data.error) {
                bugzillaStatus.text(data.error);
            } else {
                bugzillaStatus.text(data.status);
            }
        });
    });
});

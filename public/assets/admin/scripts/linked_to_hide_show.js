/* Select options change */
$('.linked_to select').on('change', function () {
    var $this = $(this),
            value = $this.val();

    if (value === 'event') {
        $('.hidden-input-event').fadeIn(350);
    } else {
        $('.hidden-input-event').hide();
    }

    if (value === 'edition') {
        $('.hidden-input-edition').fadeIn(350);
    } else {
        $('.hidden-input-edition').hide();
    }

    if (value === 'race') {
        $('.hidden-input-race').fadeIn(350);
    } else {
        $('.hidden-input-race').hide();
    }

    if (value === 'sponsor') {
        $('.hidden-input-sponsor').fadeIn(350);
    } else {
        $('.hidden-input-sponsor').hide();
    }

    if (value === 'club') {
        $('.hidden-input-club').fadeIn(350);
    } else {
        $('.hidden-input-club').hide();
    }

    if (value === 'user') {
        $('.hidden-input-user').fadeIn(350);
    } else {
        $('.hidden-input-user').hide();
    }

    if (value === 'newsletter') {
        $('.hidden-input-newsletter').fadeIn(350);
    } else {
        $('.hidden-input-newsletter').hide();
    }
});
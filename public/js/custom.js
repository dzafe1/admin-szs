var app_domain = 'http://localhost:8080';
var config = {
    site: {
        paths: {
            news_images: app_domain + '/images/vijesti/galerija/'
        },
        defaults: {
            news_default_image: app_domain + '/images/vijesti/vijesti-dodaj-sliku.png'
        }
    }

};

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Prikazi modal za pregled i odobravanje vijesti
    $('body').on('click', '.display-news',function () {
       var id;

       id = $(this).data('id');

       getNewsById(id);
    });


    $('#displayNews').on('click', '#approveNews', function () {
        var id;
        id = $(this).data('id');

        console.log(id);

        var confirmation = confirm('Da li ste sigurni da želite odobriti ovu vijest?');

        if(confirmation) {
            // $(location).attr('href', '/news/' + id + '/approve');
            approveNews(id);
        }

    });


    $('#displayNews').on('click', '#deleteNews', function () {
        var id;
        id = $(this).data('id');

        console.log(id);

        var confirmation = confirm('Da li ste sigurni da želite izbrisati ovu vijest?');

        if(confirmation) {
            // $(location).attr('href', '/news/' + id + '/delete');
            deleteNews(id)
        }

    });

});

// Funkcije
function getNewsById(id) {
    $.ajax({
        method: 'GET',
        url: '/api/news/' + id
    }).done(function (response) {
        if(response.success) {
            addNewsToModal(response.vijest);
        }
    });
}

function addNewsToModal(vijest) {
    var displayNewsModal = $('#displayNews');

    displayNewsModal.find('.modal-title').text(vijest.naslov);
    displayNewsModal.find('.news-content').html(vijest.sadrzaj);
    displayNewsModal.find('.news-image img').attr('src', vijest.slika ? (config.site.paths.news_images + vijest.slika) : config.site.defaults.news_default_image);
    displayNewsModal.find('#deleteNews').data('id', vijest.id);
    displayNewsModal.find('#approveNews').data('id', vijest.id);

    displayNewsModal.find('.news-tags').html('');
    vijest.tagovi.forEach(function (tag) {
        displayNewsModal.find('.news-tags').append('<div class="badge badge-primary tag-badge">' + tag.tag + '</div>')
    });
}

function approveNews(id) {
    $.ajax({
        method: 'PATCH',
        data: {
            id: id
        },
        url: '/api/news/approve'
    }).done(function (response) {
        var type = 'danger';

        if(response.success) {
            type = 'success';

            $('.display-news[data-id="' + id + '"]').closest('tr').remove();
        }

        $('#displayNews').modal('hide');

        notifyUser(type, response.message)
    });
}

function deleteNews(id) {
    $.ajax({
        method: 'PATCH',
        data: {
            id: id
        },
        url: '/api/news/delete'
    }).done(function (response) {
        var type = 'danger';

        if(response.success) {
            type = 'success';

            $('.display-news[data-id="' + id + '"]').closest('tr').remove();
        }

        $('#displayNews').modal('hide');

        notifyUser(type, response.message)
    });
}

function notifyUser(type, message) {
    if(!type) {
        type = 'info';
    }

    var alertBox = $('.szs-alert');

    alertBox.hide();

    setTimeout(function () {
        alertBox.addClass('alert-' + type);
        alertBox.text(message);
        alertBox.show();

        setTimeout(function () {
            alertBox.removeClass('alert-' + type);
            alertBox.text('');
            alertBox.hide();
        }, 5000);
    }, 500);

}
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

       getNewsById(id, 'display');
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

        var confirmation = confirm('Da li ste sigurni da želite izbrisati ovu vijest?');

        if(confirmation) {
            // $(location).attr('href', '/news/' + id + '/delete');
            deleteNews(id)
        }

    });

    $('body').on('click', '.delete-news', function () {
        var id;
        id = $(this).data('id');

        var confirmation = confirm('Da li ste sigurni da želite izbrisati ovu vijest?');

        if(confirmation) {
            deleteNews(id)
        }

    });

    $('body').on('click', '.edit-news', function () {
        var id;
        id = $(this).data('id');

        getNewsById(id, 'edit');

    });

    $('#editNews').on('change', '#vijestSlika', function() {
        readURL(this);
    });

    // Edit news validation
    $('#editNewsForm').validate({
        ignore: [],
        rules: {
            naslov: {
                required: true,
                maxlength: 255
            },
            kategorija: {
                required: true
            },
            sadrzaj: {
                required: function(textarea) {
                    CKEDITOR.instances.sadrzaj.updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                    return editorcontent.length === 0;
                }
            },
            slika: {
                maxlength: 5120,
                accept: 'image/*'
            }
        },
        messages: {
            naslov: {
                required: 'Polje naslov je obavezno.',
                maxlength: 'Naslov mora sadržati manje od 255 karaktera.'
            },
            kategorija: {
                required: 'Polje kategorija je obavezno.'
            },
            sadrzaj: {
                required: 'Polje sadržaj je obavezno.'
            },
            slika: {
                maxlength: 'Slika ne može biti veća od 5MB.',
                accept: 'Odaberite validan format slike.'
            }
        }
    });

    $('#editNews').on('click', '#editNewsButton', function () {
        var validated = $('#editNewsForm').valid();
        var id;
        id = $(this).data('id');

        if(validated) {
            var vijest = new FormData();
            vijest.append('id', $('#editNewsForm').find('input[name="naslov"]').val());
            vijest.append('naslov', $('#editNewsForm').find('textarea[name="sadrzaj"]').val());
            vijest.append('sadrzaj', $('#editNewsForm').find('textarea[name="sadrzaj"]').val());
            vijest.append('slika', $('#editNewsForm').find('input[name="slika"]')[0].files[0]);
            vijest.append('kategorija', $('#editNewsForm').find('select[name="kategorija"]').val());
            vijest.append('tagovi', $('#editNewsForm').find('input[name="tagovi"]').val());
            editNews(id, vijest);
        }
    });

    /*
    $('#editNewsForm').on('change', '.check-change', function () {
        $('#editNewsForm').valid();
    });
    */
});

// Funkcije
function getNewsById(id, type) {
    $.ajax({
        method: 'GET',
        url: '/api/news/' + id
    }).done(function (response) {
        if(response.success) {
            if(type === 'display') {
                addNewsToModal(response.vijest);
            } else if(type === 'edit') {
                addNewsToEditModal(response.vijest);
            }
        }
    });
}

function addNewsToModal(vijest) {
    var displayNewsModal = $('#displayNews');

    if(vijest.odobreno) {
        displayNewsModal.find('.modal-footer').remove();
    }

    displayNewsModal.find('.modal-title').text(vijest.naslov);
    displayNewsModal.find('.news-content').html(vijest.sadrzaj);
    displayNewsModal.find('.news-image img').attr('src', vijest.slika ? (config.site.paths.news_images + vijest.slika) : config.site.defaults.news_default_image);
    displayNewsModal.find('#deleteNews').data('id', vijest.id);
    displayNewsModal.find('#approveNews').data('id', vijest.id);

    displayNewsModal.find('.news-tags').html('');
    vijest.tagovi.forEach(function (tag) {
        displayNewsModal.find('.news-tags').append('<div class="badge badge-primary tag-badge">' + tag.tag + '</div>')
    });

    displayNewsModal.modal('show');
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

function addNewsToEditModal(vijest) {
    var displayNewsModal = $('#editNews');

    displayNewsModal.find('input[name="naslov"]').val(vijest.naslov);
    //displayNewsModal.find('textarea[name="sadrzaj"]').val(vijest.sadrzaj);
    CKEDITOR.instances.sadrzaj.setData(vijest.sadrzaj);
    CKEDITOR.instances.sadrzaj.updateElement();
    displayNewsModal.find('select[name="kategorija"]').val(vijest.vijest_kategorija_id);
    displayNewsModal.find('.news-image img').attr('src', vijest.slika ? (config.site.paths.news_images + vijest.slika) : config.site.defaults.news_default_image);
    displayNewsModal.find('#editNewsButton').data('id', vijest.id);

    //displayNewsModal.find('input[name="tagovi"]').val(tagovi);
    var $select = $('#tagovi-vijesti').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    var selectize = $select[0].selectize;

    vijest.tagovi.forEach(function (tag) {
        selectize.addOption({value: tag.tag, text: tag.tag});
        selectize.addItem(tag.tag);
    });
    selectize.refreshOptions();

    displayNewsModal.modal('show');
}

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.news-image img').attr('src', e.target.result);
            var image = new Image();
            image.src = e.target.result;
            image.onload = function () {

                var height = this.height;
                var width = this.width;
                if (height < 720 || width < 980) {
                    $('.info-upload-slike').animate({
                        'color': 'red'
                    });
                    return false;
                }
                $('.info-upload-slike').animate({
                    'color': 'green'
                });
                return false;
            };
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function editNews(id, vijest) {
    $.ajax({
        method: 'PUT',
        data: vijest,
        url: '/api/news/' + id + '/edit',
        cache: false,
        processData: false,
        contentType: false
    }).done(function (response) {
        var type;

        if (!response.success) {

        } else {
            type = 'success';
            $('#displaynews').modal('hide');
            notifyUser(type, response.message);
        }
    });
}
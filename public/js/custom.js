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

        var validator = $('#editNewsForm').validate();
        validator.resetForm();

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
            var vijest = new FormData($('#editNewsForm')[0]);
            vijest.append('_method', 'PUT');

            editNews(id, vijest);
        }
    });

    $('body').on('click', '.edit-user', function () {
        var id;
        id = $(this).data('id');

        var validator = $('#editUserForm').validate();
        validator.resetForm();

        getUserById(id);

    });

    // Edit user validation
    $('#editUserForm').validate({
        ignore: [],
        rules: {
            name: {
                required: true,
                maxlength: 255
            },
            email: {
                required: true,
                email: true
            },
            spol: {
                required: true
            },
            address: {
                maxlength: 255
            },
            phone: {
                maxlength: 50,
                digits: true
            }
        },
        messages: {
            name: {
                required: 'Polje ime je obavezno.',
                maxlength: 'Ime mora sadržati manje od 255 karaktera.'
            },
            email: {
                required: 'Polje email je obavezno.',
                email: 'Polje email mora biti validna email adresa.'
            },
            spol: {
                required: 'Polje spol je obavezno.'
            },
            address: {
                maxlength: 'Polje adresa mora sadržati manje od 255 karaktera.'
            },
            phone: {
                maxlength: 'Polje broj telefona mora sadržati manje od 50 brojeva.',
                digits: 'Polje broj telefona mora biti broj.'
            }
        }
    });

    $('#editUser').on('click', '#editUserButton', function () {
        var validated = $('#editUserForm').valid();
        var id;
        id = $(this).data('id');

        if(validated) {
            var user = new FormData($('#editUserForm')[0]);
            var isAdmin = $('#editUserForm').find('input[name="isAdmin"]').prop('checked') === true ? 1 : 0;
            user.append('_method', 'PUT');
            user.delete('isAdmin');

            user.append('isAdmin', isAdmin);

            editUser(id, user);
        }
    });

    $('body').on('click', '.delete-user', function () {
        var id;
        id = $(this).data('id');

        var confirmation = confirm('Da li ste sigurni da želite izbrisati ovog korisnika?');

        if(confirmation) {
            deleteUser(id)
        }

    });

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
    selectize.clear();
    selectize.clearOptions();

    vijest.tagovi.forEach(function (tag) {
        selectize.addOption({value: tag.tag, text: tag.tag});
        selectize.addItem(tag.tag);
    });

    selectize.refreshItems();
    selectize.refreshOptions();

    $('.info-upload-slike').removeAttr('style');
    $('#editNewsServerErrors').hide(0);
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
        method: 'POST',
        data: vijest,
        url: '/api/news/' + id + '/edit',
        cache: false,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data'
    }).done(function (response) {
        var type;

        if (!response.success) {
            if(response.errors) {
                $('#editNewsServerErrors').find('ul').html('');
                response.errors.forEach(function (error) {
                    $('#editNewsServerErrors').find('ul').append('<li>' + error + '</li>');
                });
                $('#editNewsServerErrors').show();
            } else {
                type = 'danger';
                $('#editNews').modal('hide');
                notifyUser(type, response.message);
            }
        } else {
            type = 'success';
            $('#editNews').modal('hide');
            notifyUser(type, response.message);
        }
    });
}

function getUserById(id) {
    $.ajax({
        method: 'GET',
        url: '/api/users/' + id
    }).done(function (response) {
        if(response.success) {
            addUserToEditModal(response.user);
        }
    });
}

function addUserToEditModal(user) {
    var displayUserModal = $('#editUser');

    displayUserModal.find('.modal-title').text(user.name);

    displayUserModal.find('input[name="name"]').val(user.name);
    displayUserModal.find('input[name="email"]').val(user.email);
    displayUserModal.find('select[name="spol"]').val(user.spol);
    displayUserModal.find('input[name="address"]').val(user.address);
    displayUserModal.find('input[name="phone"]').val(user.phone);
    displayUserModal.find('input[name="isAdmin"]').prop('checked', user.isAdmin);

    displayUserModal.find('#editUserButton').data('id', user.id);

    $('#editUserServerErrors').hide(0);
    displayUserModal.modal('show');
}


function editUser(id, user) {

    $.ajax({
        method: 'POST',
        data: user,
        url: '/api/users/' + id + '/edit',
        cache: false,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data'
    }).done(function (response) {
        var type;

        if (!response.success) {
            if(response.errors) {
                $('#editUserServerErrors').find('ul').html('');
                response.errors.forEach(function (error) {
                    $('#editUserServerErrors').find('ul').append('<li>' + error + '</li>');
                });
                $('#editUserServerErrors').show();
            } else {
                type = 'danger';
                $('#editUser').modal('hide');
                notifyUser(type, response.message);
            }
        } else {
            type = 'success';
            $('#editUser').modal('hide');
            notifyUser(type, response.message);
        }
    });
}

function deleteUser(id) {
    $.ajax({
        method: 'PATCH',
        data: {
            id: id
        },
        url: '/api/users/delete'
    }).done(function (response) {
        var type = 'danger';

        if(response.success) {
            type = 'success';

            $('.delete-user[data-id="' + id + '"]').closest('tr').remove();
        }

        notifyUser(type, response.message)
    });
}
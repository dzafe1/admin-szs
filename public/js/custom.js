var app_domain = 'http://localhost:8080';
var config = {
    site: {
        paths: {
            news_images: app_domain + '/images/vijesti/galerija/',
            player_images: app_domain + '/images/athlete_avatars/',
            player_gallery: app_domain + '/images/galerija_sportista/'
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

        var confirmation = confirm('Da li ste sigurni da želite odobriti ovu vijest?');

        if(confirmation) {
            approveNews(id);
        }

    });


    $('#displayNews').on('click', '#deleteNews', function () {
        var id;
        id = $(this).data('id');

        var confirmation = confirm('Da li ste sigurni da želite izbrisati ovu vijest?');

        if(confirmation) {
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

    // Players
    $('body').on('click', '.display-player',function () {
        var id;

        id = $(this).data('id');

        getPlayerById(id, 'display');
    });

    $('#displayPlayer').on('click', '#approvePlayer', function () {
        var id;
        id = $(this).data('id');

        var confirmation = confirm('Da li ste sigurni da želite odobriti ovog igrača?');

        if(confirmation) {
            approvePlayer(id);
        }

    });

    $('#displayPlayer').on('click', '#deletePlayer', function () {
        var id;
        id = $(this).data('id');

        var confirmation = confirm('Da li ste sigurni da želite odbiti ovog igrača?');

        if(confirmation) {
            refusePlayer(id);
        }

    });

    $('body').on('click', '.delete-player', function () {
        var id;
        id = $(this).data('id');

        var confirmation = confirm('Da li ste sigurni da želite izbrisati ovog igrača?');

        if(confirmation) {
            deletePlayer(id);
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

function getPlayerById(id, type) {
    $.ajax({
        method: 'GET',
        url: '/api/players/' + id
    }).done(function (response) {
        if(response.success) {
            if(response.success) {
                if(type === 'display') {
                    addPlayerToModal(response.player);
                } else if(type === 'edit') {
                    addPlayerToEditModal(response.player);
                }
            }
        }
    });
}

function addPlayerToModal(player) {
    var displayPlayerModal = $('#displayPlayer');
    var dob = new Date(player.date_of_birth);

    if(player.status === 'active') {
        displayPlayerModal.find('.modal-footer').remove();
    }

    var player_data = '';
    Object.keys(player.player_data).forEach(function (key) {
        player_data += '<li><b>' + player.player_data_names[key].label.bs + '</b> ' + (player.player_data[key] || 'Nema') +'</li>';
    });

    var trophies = '';
    Object.keys(player.trophies).forEach(function (key) {
        trophies += '<div class="col-md-4">' +
            '<div class="custom-box-row">' +
            '<ul>' +
            '<li><b>Tip:</b> ' + player.trophies[key].type + '</li>' +
            '<li><b>Mjesto:</b> ' + player.trophies[key].place + '</li>' +
            '<li><b>Nivo takmičenja:</b> ' + player.trophies[key].level_of_competition + '</li>' +
            '<li><b>Naziv takmičenja:</b> ' + player.trophies[key].competition_name + '</li>' +
            '<li><b>Sezona:</b> ' + player.trophies[key].season + '</li>' +
            '</ul>' +
            '</div>' +
            '</div>';
    });

    var images = '';
    Object.keys(player.images).forEach(function (key) {
        images += '<div class="col-md-12">' +
            '<div class="custom-box-row">' +
            '<img class="responsive-img" src="' + config.site.paths.player_gallery + player.images[key].image + '">' +
            '</div>' +
            '</div>';
    });

    var playerInfo = '<div class="row custom-box-row">' +
        '<div class="col-md-6">' +
        '<h3>Osnovne informacije</h3>' +
        '<ul>' +
        '<li><b>Datum rođenja:</b> ' + dob.getDay() + '/' + dob.getMonth() + '/' + dob.getFullYear() + '</li>' +
        '<li><b>Klub: </b> ' + (player.club ? player.club.name : '<i>Nije uneseno</i>') + '</li>' +
        '<li><b>Takmičenje/Liga: </b> ' + (player.club ? player.club.competition : 'Nema') + '</li>' +
        '</ul>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<h3>Predispozicije</h3>' +
        '<ul>' +
        '<li><b>Visina:</b> ' + (player.height ? player.height + ' cm' : 'Nema') + '</li>' +
        '<li><b>Težina:</b> ' + (player.weight ? player.weight + ' kg' : 'Nema') + '</li>' +
        player_data +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="row custom-box-row">' +
        '<div class="col-md-12">' +
        '<h3>Regija</h3>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<ul>' +
        '<li><b>Kontinent:</b> ' + (player.regions.continent || 'Nema') + '</li>' +
        '<li><b>Država:</b> ' + (player.regions.country || 'Nema') + '</li>' +
        '<li><b>Entitet/Pokrajina:</b> ' + (player.regions.province || 'Nema') + '</li>' +
        '</ul>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<ul>' +
        '<li><b>Kanton/Regija:</b> ' + (player.regions.region || 'Nema') + '</li>' +
        '<li><b>Općina:</b> ' + (player.regions.municipality || 'Nema') + '</li>' +
        '<li><b>Grad/Mjesto:</b> ' + (player.regions.city || 'Nema') + '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="row custom-box-row">' +
        '<div class="col-md-12">' +
        '<h3>Biografija</h3>' +
        '</div>' +
        '<div class="col-md-12">' +
        (player.biography || 'Biografija nije unesena.') +
        '</div>' +
        '</div>' +
        '<div class="row custom-box-row">' +
        '<div class="col-md-12">' +
        '<h3>Trofeji/Nagrade</h3>' +
        '</div>' +
        (trophies || '<div class="col-md-12">Nema trofeja.</div>') +
        '</div>' +
        '<div class="row custom-box-row">' +
        '<div class="col-md-12">' +
        '<h3>Galerija</h3>' +
        '</div>' +
        (images || '<div class="col-md-12">Nema slika.</div>') +
        '</div>';

    displayPlayerModal.find('.modal-title').html('<b>' + player.firstname + ' ' + player.lastname + ' (<i>' + player.player_type.name + '</i>)</b>');
    displayPlayerModal.find('.player-content').html(playerInfo);
    displayPlayerModal.find('.player-image img').attr('src', config.site.paths.player_images + player.avatar);
    displayPlayerModal.find('#deletePlayer').data('id', player.id);
    displayPlayerModal.find('#approvePlayer').data('id', player.id);


    displayPlayerModal.modal('show');
}

function approvePlayer(id) {
    $.ajax({
        method: 'PATCH',
        data: {
            id: id
        },
        url: '/api/players/approve'
    }).done(function (response) {
        var type = 'danger';

        if(response.success) {
            type = 'success';

            $('.display-player[data-id="' + id + '"]').closest('tr').remove();
        }

        $('#displayPlayer').modal('hide');

        notifyUser(type, response.message)
    });
}

function refusePlayer(id) {
    $.ajax({
        method: 'PATCH',
        data: {
            id: id
        },
        url: '/api/players/refuse'
    }).done(function (response) {
        var type = 'danger';

        if(response.success) {
            type = 'success';

            $('.display-player[data-id="' + id + '"]').closest('tr').remove();
        }

        $('#displayPlayer').modal('hide');

        notifyUser(type, response.message)
    });
}

function deletePlayer(id) {
    $.ajax({
        method: 'PATCH',
        data: {
            id: id
        },
        url: '/api/players/delete'
    }).done(function (response) {
        var type = 'danger';

        if(response.success) {
            type = 'success';

            $('.display-player[data-id="' + id + '"]').closest('tr').remove();
        }

        $('#displayPlayer').modal('hide');

        notifyUser(type, response.message)
    });
}
var app_domain = 'http://localhost:8080';
var config = {
    site: {
        paths: {
            news_images: app_domain + '/images/vijesti/galerija/',
            player_images: app_domain + '/images/athlete_avatars/',
            player_gallery: app_domain + '/images/galerija_sportista/',
            object_images: app_domain + '/images/object_avatars/',
            object_gallery: app_domain + '/images/galerija_objekti/'
        },
        defaults: {
            news_default_image: app_domain + '/images/vijesti/vijesti-dodaj-sliku.png'
        }
    }

};

// Globalne varijable
var licnostiCount = 1;
var nagradeCount = 1;
var historyCount = 1;
var terenCount = 1;
var cjenovnikBalonCount = 1;
var stazeCount = 1;
var cjenovnikSkiCount = 1;

function previewFile(name, place, maxHeight, maxWidth, minHeight, minWidth) {
    if (typeof(maxHeight) === 'undefined') maxHeight = null;
    if (typeof(maxWidth) === 'undefined') maxWidth = null;
    if (typeof(minHeight) === 'undefined') minHeight = null;
    if (typeof(minWidth) === 'undefined') minWidth = null;


    var preview = $(place);
    var file_input = $(name);
    var file = $(name).get(0).files;
    var error = file_input.closest('.sadrzaj-slike').find('.info-upload-slike');
    var reader = new FileReader();

    reader.onloadend = function (e) {
        preview.attr('src', e.target.result);

        var image = new Image();
        image.src = e.target.result;

        image.onload = function () {
            var height = this.height;
            var width = this.width;

            if(maxHeight && maxWidth && minHeight && minWidth) {
                if ((height >= maxHeight || height <= minHeight) || (width >= maxWidth || width <= minWidth)) {
                    error.animate({
                        'color': 'red'
                    });
                    return false;
                }

                error.animate({
                    'color': 'green'
                });
                return true;
            } else if (maxHeight && maxWidth) {
                if (height >= maxHeight || width >= maxWidth) {
                    error.animate({
                        'color': 'red'
                    });
                    return false;
                }

                error.animate({
                    'color': 'green'
                });
                return true;
            } else if (minHeight && minWidth) {
                if (height <= minHeight || width <= minWidth) {
                    error.animate({
                        'color': 'red'
                    });
                    return false;
                }

                error.animate({
                    'color': 'green'
                });
                return true;
            }
        };
    };

    if (file.length > 0) {
        reader.readAsDataURL(file[0]);
    } else {
        preview.attr('src', '');

        error.animate({
            'color': 'red'
        });
    }
}

$.validator.setDefaults({ ignore: '' });

var continentSelectName = 'select#continent';
var countrySelectName = 'select#country';
var provinceSelectName = 'select#province';
var regionSelectName = 'select#region';
var municipalitySelectName = 'select#municipality';
var sportTypeSelectName = 'select#club-type';
var sportSelectName = 'select#sport';
var associationBoxName = '#associations';
var associationRadioName = 'input[name="association"]';

// Select boxovi za regione
var continentSelect = null;
var countrySelect = null;
var provinceSelect = null;
var regionSelect = null;
var municipalitySelect = null;

// Select boxes
var sportTypeSelect = null;
var sportSelect = null;
var associationBox = null;
var associationRadio = null;

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('form').keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    var imagesPreview = function (input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;

            $('#tab-galerija .form-objavi-klub-01').find('.newly-added').remove();

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onloadend = function (event) {
                        var adnew = '<div class="album__item col-xs-6 col-sm-3 newly-added"><div class="hover">Novo</div><div class="album__item-holder"><a href="' + event.target.result + '" class="album__item-link mp_gallery"><figure class="album__thumb"><img src="' + event.target.result + '" alt=""></figure></a></div></div>';
                        $('#tab-galerija .form-objavi-klub-01').append(adnew);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }
    };

    $('body').on('change', '.galerija', function () {
        imagesPreview(this);

    });

    $('body').on('change', '.galerija_edit', function () {
        imagesPreview(this);
    });

    $('#flash-overlay-modal').modal();

    $('#editClubForm').validate({
        ignore: ':hidden,:disabled',
        rules: {
            logo: {
                extension: 'png|jpg|jpeg'
            },
            name: {
                required: true,
                string: true,
                maxlength: 255
            },
            nature: {
                required: true,
                string: true,
                maxlength: 255
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            type: {
                required: true,
                digits: true
            },
            sport: {
                required: true,
                digits: true
            },
            category: {
                required: true,
                digits: true
            },
            established_in: {
                digits: true,
                range: [1800, new Date().getFullYear()]
            },
            home_field: {
                string: true,
                maxlength: 255
            },
            competition: {
                string: true,
                maxlength: 255
            },
            association: {
                required: true,
                digits: true
            },
            phone_1: {
                digits: true,
                maxlength: 255
            },
            phone_2: {
                digits: true,
                maxlength: 50
            },
            fax: {
                digits: true,
                maxlength: 50
            },
            email: {
                email: true,
                maxlength: 255
            },
            website: {
                string: true,
                maxlength: 255
            },
            address: {
                string: true,
                maxlength: 255
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            video: {
                string: true,
                maxlength: 255
            }
        }
    });

    $('#editLicnosti').validate({
        ignore: ':hidden,:disabled'
    });

    // Validacije forme za dodavanje kluba
    $('#createNewClub').validate({
        ignore: ':hidden,:disabled',
        rules: {
            logo: {
                required: true,
                extension: 'png|jpg|jpeg'
            },
            name: {
                required: true,
                string: true,
                maxlength: 255
            },
            nature: {
                required: true,
                string: true,
                maxlength: 255
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            type: {
                required: true,
                digits: true
            },
            sport: {
                required: true,
                digits: true
            },
            category: {
                required: true,
                digits: true
            },
            established_in: {
                digits: true,
                range: [1800, new Date().getFullYear()]
            },
            home_field: {
                string: true,
                maxlength: 255
            },
            competition: {
                string: true,
                maxlength: 255
            },
            association: {
                required: true,
                digits: true
            },
            phone_1: {
                digits: true,
                maxlength: 255
            },
            phone_2: {
                digits: true,
                maxlength: 50
            },
            fax: {
                digits: true,
                maxlength: 50
            },
            email: {
                email: true,
                maxlength: 255
            },
            website: {
                string: true,
                maxlength: 255
            },
            address: {
                string: true,
                maxlength: 255
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            video: {
                string: true,
                maxlength: 255
            },
            history: {
                string: true
            },
            'galerija[]' : {
                extension: 'png|jpg|jpeg'
            }
        }
    });

    $('#editClubHistory').validate({
        ignore: ':hidden,:disabled',
        rules: {
            history: {
                string: true
            }
        }
    });

    $('#editClubTrophies').validate({
        ignore: ':hidden,:disabled'
    });

    $('#editClubGallery').validate({
        ignore: ':hidden',
        'galerija[]': {
            extension: "jpg|jpeg|png"
        }
    });

    $('#createNewPlayer').validate({
        ignore: ':hidden,:disabled',
        rules: {
            avatar: {
                extension: 'png|jpg|jpeg'
            },
            firstname: {
                required: true,
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            lastname: {
                required: true,
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            player_nature: {
                required: true,
                digits: true
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            video: {
                string: true,
                maxlength: 255
            },
            requested_club: {
                digits: true
            },
            weight: {
                number: true
            },
            height: {
                number: true
            },
            preferred_leg: {
                string: true,
                maxlength: 255
            },
            preferred_arm: {
                string: true,
                maxlength: 255
            },
            rank: {
                digits: true
            },
            discipline: {
                string: true,
                maxlength: 255
            },
            best_result: {
                number: true
            },
            agent: {
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            position: {
                string: true,
                maxlength: 255
            },
            competition: {
                string: true,
                maxlength: 255
            },
            category: {
                string: true,
                maxlength: 255
            },
            market_value: {
                digits: true
            },
            branch: {
                string: true,
                maxlength: 255
            },
            belt: {
                string: true,
                maxlength: 255
            },
            stlye: {
                string: true,
                maxlength: 255
            },
            distance: {
                digits: true
            },
            coach: {
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            best_rank: {
                digits: true
            },
            biography: {
                string: true
            },
            'galerija[]': {
                extension: "jpg|jpeg|png"
            }
        }
    });

    $('#createNewStaff').validate({
        ignore: ':hidden,:disabled',
        rules: {
            avatar: {
                extension: 'png|jpg|jpeg'
            },
            firstname: {
                required: true,
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            lastname: {
                required: true,
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            profession: {
                required: true,
                digits: true
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            video: {
                string: true,
                maxlength: 255
            },
            requested_club: {
                digits: true
            },
            club_name: {
                string: true,
                maxlength: 255
            },
            education: {
                string: true,
                maxlength: 255
            },
            additional_education: {
                string: true,
                maxlength: 255
            },
            number_of_certificates: {
                digits: true
            },
            number_of_projects: {
                digits: true
            },
            work_experience: {
                number: true
            },
            biography: {
                string: true
            },
            'galerija[]': {
                extension: "jpg|jpeg|png"
            }
        }
    });

    $('#editStaffGeneral').validate({
        ignore: ':hidden,:disabled',
        rules: {
            avatar: {
                extension: 'png|jpg|jpeg'
            },
            firstname: {
                required: true,
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            lastname: {
                required: true,
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            profession: {
                required: true,
                digits: true
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            video: {
                string: true,
                maxlength: 255
            }
        }
    });

    $('#editStaffStatus').validate({
        ignore: ':hidden,:disabled',
        rules: {
            requested_club: {
                digits: true
            },
            club_name: {
                string: true,
                maxlength: 255
            },
            education: {
                string: true,
                maxlength: 255
            },
            additional_education: {
                string: true,
                maxlength: 255
            },
            number_of_certificates: {
                digits: true
            },
            number_of_projects: {
                digits: true
            },
            work_experience: {
                number: true
            }
        }
    });

    $('#editStaffBiography').validate({
        ignore: ':hidden,:disabled',
        rules: {
            biography: {
                string: true
            }
        }
    });

    $('#editStaffTrophies').validate({
        ignore: ':hidden,:disabled'
    });

    $('#editStaffGallery').validate({
        ignore: ':hidden',
        rules: {
            'galerija[]': {
                extension: "jpg|jpeg|png"
            }
        }
    });

    $('#createNewSchool').validate({
        ignore: ':hidden,:disabled',
        rules: {
            logo: {
                required: true,
                extension: 'png|jpg|jpeg'
            },
            name: {
                required: true,
                string: true,
                maxlength: 255
            },
            nature: {
                required: true,
                string: true,
                maxlength: 255
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            type: {
                required: true,
                digits: true
            },
            sport: {
                required: true,
                digits: true
            },
            category: {
                required: true,
                digits: true
            },
            established_in: {
                digits: true,
                range: [1800, new Date().getFullYear()]
            },
            home_field: {
                string: true,
                maxlength: 255
            },
            competition: {
                string: true,
                maxlength: 255
            },
            phone_1: {
                digits: true,
                maxlength: 255
            },
            phone_2: {
                digits: true,
                maxlength: 50
            },
            fax: {
                digits: true,
                maxlength: 50
            },
            email: {
                email: true,
                maxlength: 255
            },
            website: {
                string: true,
                maxlength: 255
            },
            address: {
                string: true,
                maxlength: 255
            },
            pioniri: {
                required: true,
                digits: true,
                range: [0, 1]
            },
            kadeti: {
                required: true,
                digits: true,
                range: [0, 1]
            },
            juniori: {
                required: true,
                digits: true,
                range: [0, 1]
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            video: {
                string: true,
                maxlength: 255
            },
            history: {
                string: true
            },
            'galerija[]': {
                extension: 'png|jpg|jpeg'
            }
        }
    });

    $('#editSchoolGeneral').validate({
        ignore: ':hidden,:disabled',
        rules: {
            logo: {
                extension: 'png|jpg|jpeg'
            },
            name: {
                required: true,
                string: true,
                maxlength: 255
            },
            nature: {
                required: true,
                string: true,
                maxlength: 255
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            type: {
                required: true,
                digits: true
            },
            sport: {
                required: true,
                digits: true
            },
            category: {
                required: true,
                digits: true
            },
            established_in: {
                digits: true,
                range: [1800, new Date().getFullYear()]
            },
            home_field: {
                string: true,
                maxlength: 255
            },
            competition: {
                string: true,
                maxlength: 255
            },
            phone_1: {
                digits: true,
                maxlength: 255
            },
            phone_2: {
                digits: true,
                maxlength: 50
            },
            fax: {
                digits: true,
                maxlength: 50
            },
            email: {
                email: true,
                maxlength: 255
            },
            website: {
                string: true,
                maxlength: 255
            },
            address: {
                string: true,
                maxlength: 255
            },
            pioniri: {
                required: true,
                digits: true,
                range: [0, 1]
            },
            kadeti: {
                required: true,
                digits: true,
                range: [0, 1]
            },
            juniori: {
                required: true,
                digits: true,
                range: [0, 1]
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            video: {
                string: true,
                maxlength: 255
            }
        }
    });

    $('#editSchoolMembers').validate({ignore: ':hidden,:disabled'});

    $('#editSchoolHistory').validate({
        ignore: ':hidden,:disabled',
        rules: {
            history: {
                string: true
            }
        }
    });

    $('#editSchoolTrophies').validate({
        ignore: ':hidden,:disabled'
    });

    $('#editSchoolGallery').validate({
        ignore: ':hidden',
        rules: {
            'galerija[]': {
                extension: "jpg|jpeg|png"
            }
        }
    });

    $('#createNewObject').validate({
        ignore: ':hidden,:disabled',
        rules: {
            image: {
                required: true,
                extension: 'png|jpg|jpeg'
            },
            name: {
                required: true,
                string: true,
                maxlength: 255
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            number_of_fields: {
                digits: true,
                range: [1, 50]
            },
            number_of_pools: {
                digits: true,
                range: [1, 50]
            },
            number_of_tracks: {
                digits: true,
                range: [1, 100]
            },
            number_of_balls: {
                digits: true,
                range: [1, 500]
            },
            number_of_shooting_places: {
                digits: true,
                range: [1, 500]
            },
            type_of_grass: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            elevation: {
                digits: true,
                range: [1, 8000]
            },
            stadium_length: {
                digits: true,
                range: [1, 300]
            },
            stadium_width: {
                digits: true,
                range: [1, 300]
            },
            number_of_ski_tracks: {
                digits: true,
                range: [1, 200]
            },
            number_of_ski_lifts: {
                digits: true,
                range: [1, 200]
            },
            water_effects: {
                digits: true,
                range: [0, 1]
            },
            area: {
                digits: true
            },
            water_area: {
                digits: true
            },
            capacity: {
                digits: true
            },
            pool_capacity: {
                digits: true
            },
            stadium_capacity: {
                digits: true
            },
            type_of_field: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            wifi: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            parking: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            restaurant: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            hotels: {
                string: true,
                maxlength: 255
            },
            cafe: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            access_to_disabled: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            number_of_locker_rooms: {
                digits: true,
                range: [0, 50]
            },
            rent_equipment: {
                digits: true,
                range: [0, 1]
            },
            hot_water_showers: {
                digits: true,
                range: [0, 1]
            },
            result_board: {
                digits: true,
                range: [0, 1]
            },
            kids_playground: {
                digits: true,
                range: [0, 1]
            },
            wardrobe_with_key: {
                digits: true,
                range: [0, 1]
            },
            props: {
                digits: true,
                range: [0, 1]
            },
            air_conditioning: {
                digits: true,
                range: [0, 1]
            },
            protective_net: {
                digits: true,
                range: [0, 1]
            },
            optimum_temperature: {
                digits: true,
                range: [0, 1]
            },
            video_surveillance: {
                digits: true,
                range: [0, 1]
            },
            equipment_rent: {
                digits: true,
                range: [0, 1]
            },
            kid_pools: {
                digits: true,
                range: [0, 1]
            },
            entering_a_props: {
                digits: true,
                range: [0, 1]
            },
            urine_detector: {
                digits: true,
                range: [0, 1]
            },
            reservations: {
                digits: true,
                range: [0, 1]
            },
            child_equipment: {
                digits: true,
                range: [0, 1]
            },
            special_shoes: {
                digits: true,
                range: [0, 1]
            },
            hygiene_equipment: {
                digits: true,
                range: [0, 1]
            },
            member_card: {
                digits: true,
                range: [0, 1]
            },
            maintenance_service: {
                digits: true,
                range: [0, 1]
            },
            emergency_intervention: {
                digits: true,
                range: [0, 1]
            },
            skiing_school: {
                digits: true,
                range: [0, 1]
            },
            snowboarding_school: {
                digits: true,
                range: [0, 1]
            },
            skiing_equipment_shops: {
                digits: true,
                range: [0, 1]
            },
            mobile_rescue_team: {
                digits: true,
                range: [0, 1]
            },
            night_skiing: {
                digits: true,
                range: [0, 1]
            },
            commenting_cabins: {
                digits: true,
                range: [0, 1]
            },
            speaker_system: {
                digits: true,
                range: [0, 1]
            },
            fan_shop: {
                digits: true,
                range: [0, 1]
            },
            use_own_equipment: {
                digits: true,
                range: [0, 1]
            },
            equipment_service: {
                digits: true,
                range: [0, 1]
            },
            shooting_school: {
                digits: true,
                range: [0, 1]
            },
            protective_equipment: {
                digits: true,
                range: [0, 1]
            },
            history: {
                string: true
            },
            'galerija[]' : {
                extension: 'png|jpg|jpeg'
            }
        }
    });

    $('#editObjectGeneral').validate({
        ignore: ':hidden,:disabled',
        rules: {
            image: {
                extension: 'png|jpg|jpeg'
            },
            name: {
                required: true,
                string: true,
                maxlength: 255
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            }
        }
    });

    $('#editObjectStatus').validate({
        ignore: ':hidden,:disabled',
        rules: {
            number_of_fields: {
                digits: true,
                range: [1, 50]
            },
            number_of_pools: {
                digits: true,
                range: [1, 50]
            },
            number_of_tracks: {
                digits: true,
                range: [1, 100]
            },
            number_of_balls: {
                digits: true,
                range: [1, 500]
            },
            number_of_shooting_places: {
                digits: true,
                range: [1, 500]
            },
            type_of_grass: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            elevation: {
                digits: true,
                range: [1, 8000]
            },
            stadium_length: {
                digits: true,
                range: [1, 300]
            },
            stadium_width: {
                digits: true,
                range: [1, 300]
            },
            number_of_ski_tracks: {
                digits: true,
                range: [1, 200]
            },
            number_of_ski_lifts: {
                digits: true,
                range: [1, 200]
            },
            water_effects: {
                digits: true,
                range: [0, 1]
            },
            area: {
                digits: true
            },
            water_area: {
                digits: true
            },
            capacity: {
                digits: true
            },
            pool_capacity: {
                digits: true
            },
            stadium_capacity: {
                digits: true
            },
            type_of_field: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            wifi: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            parking: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            restaurant: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            hotels: {
                string: true,
                maxlength: 255
            },
            cafe: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            access_to_disabled: {
                string: true,
                maxlength: 255,
                lettersonly: true
            },
            number_of_locker_rooms: {
                digits: true,
                range: [0, 50]
            },
            rent_equipment: {
                digits: true,
                range: [0, 1]
            },
            hot_water_showers: {
                digits: true,
                range: [0, 1]
            },
            result_board: {
                digits: true,
                range: [0, 1]
            },
            kids_playground: {
                digits: true,
                range: [0, 1]
            },
            wardrobe_with_key: {
                digits: true,
                range: [0, 1]
            },
            props: {
                digits: true,
                range: [0, 1]
            },
            air_conditioning: {
                digits: true,
                range: [0, 1]
            },
            protective_net: {
                digits: true,
                range: [0, 1]
            },
            optimum_temperature: {
                digits: true,
                range: [0, 1]
            },
            video_surveillance: {
                digits: true,
                range: [0, 1]
            },
            equipment_rent: {
                digits: true,
                range: [0, 1]
            },
            kid_pools: {
                digits: true,
                range: [0, 1]
            },
            entering_a_props: {
                digits: true,
                range: [0, 1]
            },
            urine_detector: {
                digits: true,
                range: [0, 1]
            },
            reservations: {
                digits: true,
                range: [0, 1]
            },
            child_equipment: {
                digits: true,
                range: [0, 1]
            },
            special_shoes: {
                digits: true,
                range: [0, 1]
            },
            hygiene_equipment: {
                digits: true,
                range: [0, 1]
            },
            member_card: {
                digits: true,
                range: [0, 1]
            },
            maintenance_service: {
                digits: true,
                range: [0, 1]
            },
            emergency_intervention: {
                digits: true,
                range: [0, 1]
            },
            skiing_school: {
                digits: true,
                range: [0, 1]
            },
            snowboarding_school: {
                digits: true,
                range: [0, 1]
            },
            skiing_equipment_shops: {
                digits: true,
                range: [0, 1]
            },
            mobile_rescue_team: {
                digits: true,
                range: [0, 1]
            },
            night_skiing: {
                digits: true,
                range: [0, 1]
            },
            commenting_cabins: {
                digits: true,
                range: [0, 1]
            },
            speaker_system: {
                digits: true,
                range: [0, 1]
            },
            fan_shop: {
                digits: true,
                range: [0, 1]
            },
            use_own_equipment: {
                digits: true,
                range: [0, 1]
            },
            equipment_service: {
                digits: true,
                range: [0, 1]
            },
            shooting_school: {
                digits: true,
                range: [0, 1]
            },
            protective_equipment: {
                digits: true,
                range: [0, 1]
            }
        }
    });

    $('#editObjectHistory').validate({
        ignore: ':hidden,:disabled',
        rules: {
            history: {
                string: true
            }
        }
    });

    $('#editObjectGallery').validate({
        ignore: ':hidden',
        rules: {
            'galerija[]': {
                extension: "jpg|jpeg|png"
            }
        }
    });

    $('#editObjectBalonFields').validate({
        ignore: ':hidden,:disabled'
    });

    $('#editObjectBalonPrices').validate({
        ignore: ':hidden,:disabled'
    });

    $('#editObjectSkiTracks').validate({
        ignore: ':hidden,:disabled'
    });

    $('#editObjectSkiPrices').validate({
        ignore: ':hidden,:disabled'
    });


    addLicnostValidation();
    addTrophyValidation();
    addHistoryValidation();
    addTerenValidation();
    addCjenovnikBalonValidation();
    addStazeValidation();
    addCjenovnikSkiValidation();

    // Nadji najveci array key od old inputa za licnost ako postoji
    if($('.licnostHover').length) {
        var num = $('.licnostHover').map(function() {
            return $(this).data('key');
        }).get();

        var highest = Math.max.apply(Math, num);

        licnostiCount = highest + 1;
    }

    // Nadji najveci array key od old inputa za nagradu/trofej ako postoji
    if($('.nagradaHover').length) {
        var num1 = $('.nagradaHover').map(function() {
            return $(this).data('key');
        }).get();

        var highest1 = Math.max.apply(Math, num1);

        nagradeCount = highest1 + 1;
    }

    // Nadji najveci array key od old inputa za klub/historija ako postoji
    if($('.historyHover').length) {
        var num2 = $('.historyHover').map(function() {
            return $(this).data('key');
        }).get();

        var highest2 = Math.max.apply(Math, num2);

        historyCount = highest2 + 1;
    }

    // Nadji najveci array key od old inputa za teren/sala ako postoji
    if($('.terenHover').length) {
        var num3 = $('.terenHover').map(function() {
            return $(this).data('key');
        }).get();

        var highest3 = Math.max.apply(Math, num3);

        terenCount = highest3 + 1;
    }

    // Nadji najveci array key od old inputa za balon cjenovnik ako postoji
    if($('.balonCjenovnikHover').length) {
        var num4 = $('.balonCjenovnikHover').map(function() {
            return $(this).data('key');
        }).get();

        var highest4 = Math.max.apply(Math, num4);

        cjenovnikBalonCount = highest4 + 1;
    }

    // Nadji najveci array key od old inputa za staze ako postoji
    if($('.stazeHover').length) {
        var num5 = $('.stazeHover').map(function() {
            return $(this).data('key');
        }).get();

        var highest5 = Math.max.apply(Math, num5);

        stazeCount = highest5 + 1;
    }

    // Nadji najveci array key od old inputa za ski cjenovnik ako postoji
    if($('.skiCjenovnikHover').length) {
        var num6 = $('.skiCjenovnikHover').map(function() {
            return $(this).data('key');
        }).get();

        var highest6 = Math.max.apply(Math, num6);

        cjenovnikSkiCount = highest6 + 1;
    }


    // Custom metode za validaciju
    // Provjera stringa
    jQuery.validator.addMethod("string", function(value, element){
        if(value === null) {
            return true;
        }
        if ((typeof value === 'string' || value instanceof String)) {
            return true;
        }
        return false;
    }, "Polje mora biti tipa string.");

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || XRegExp('^[\\p{L}|\\s]*$').test(value);
    }, "Polje mora sadržati samo slova");

    // Dodavanje kluba - Dodaj ličnost
    $('body').on('click', '#dodajLicnost', function () {
        var licnost_form_input = '<div class="row licnostHover"><div class="izbrisiLicnost"><i class="fa fa-times-circle-o"></i></div>' +
            '<div class="row identitet-style">' +
            '<div class="col-md-6 objavi-klub-logo-setup">' +
            '<div class="col-md-7">' +
            '<div class="alc-staff__photo">' +
            '<img class="slika-edit-profil" id="slika-licnost-prikaz' + licnostiCount + '" src="/images/default_avatar.png" alt="">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-5 sadrzaj-slike">' +
            '<p class="dodaj-sliku-naslov klub-a1">Slika ličnosti</p>' +
            '<p class="dodaj-sliku-call">Odaberite sliku za istaknutu ličnost</p>' +
            '<label class="btn btn-default btn-xs btn-file dodaj-sliku-button">' +
            'Odaberi sliku... <input type="file" name="licnost[' + licnostiCount + '][avatar]" id="licnostAvatar' + licnostiCount + '" accept="image/*" class="not-visible" onchange="previewFile(\'#licnostAvatar' + licnostiCount + '\',\'#slika-licnost-prikaz' + licnostiCount + '\', 1080, 1920, 250, 312)">' +
            '</label>' +
            '<div class="info001">' +
            '<p class="info-upload-slike">Preporučene dimenzije za sliku ličnosti:</p>' +
            '<p class="info-upload-slike">Minimalno: 312x250 px</p>' +
            '<p class="info-upload-slike">Maksimalno: 1920x1080 px</p>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="ime-kluba"><img class="flow-icons-013" src="/images/icons/edit.svg"> Ime</label>' +
            '<input type="text" name="licnost[' + licnostiCount + '][ime]" class="form-control" placeholder="Unesite ime ličnosti">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="ime-kluba"><img class="flow-icons-013" src="/images/icons/edit.svg"> Prezime</label>' +
            '<input type="text" name="licnost[' + licnostiCount + '][prezime]" class="form-control" placeholder="Unesite prezime ime ličnosti">' +
            '</div>' +
            '<div class="form-group col-md-12">' +
            '<label for="opis"><img class="flow-icons-013" src="/images/icons/edit.svg"> Opis i uloga</label>' +
            '<textarea class="form-control" rows="4" name="licnost[' + licnostiCount + '][opis]" placeholder="Upišite kratak opis uloge i funkcije navedene ličnosti u klubu..."></textarea>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        $(licnost_form_input).appendTo('#tab-licnosti #licnostiLista').hide().slideDown();
        licnostiCount++;

        addLicnostValidation();
    });

    // Dodavanje kluba - Obriši ličnost
    $('body').on('click', '.izbrisiLicnost',function () {
        $(this).closest('.licnostHover').slideUp('normal', function() { $(this).remove(); } );
    });

    // Dodavanje kluba - Dodaj nagradu
    $('body').on('click', '#dodajNagradu', function () {
        var nagrada_form_input = '<div class="row nagradaHover"><div class="izbrisiNagradu"><i class="fa fa-times-circle-o"></i></div><div class="col-md-6">' +
            '<div class="form-group col-md-6">' +
            '<label for="vrsta-nagrade"><img class="flow-icons-013" src="/images/icons/medalja.svg"> Vrsta nagrade</label>' +
            '<select name="nagrada[' + nagradeCount + '][vrsta]" class="form-control">' +
            '<option value="" selected>Izaberite vrstu osvojene nagrade</option>' +
            '<option value="Medalja">Medalja</option>' +
            '<option value="Trofej/Pehar">Trofej/Pehar</option>' +
            '<option value="Priznanje">Priznanje</option>' +
            '<option value="Plaketa">Plaketa</option>' +
            '</select>' +
            '</div>' +
            '<div class="form-group col-md-6">' +
            '<label for="tip-nagrade"><img class="flow-icons-013" src="/images/icons/medalja.svg"> Tip nagrade</label>' +
            '<select name="nagrada[' + nagradeCount + '][tip]" class="form-control">' +
            '<option value="" selected>Izaberite tip nagrade</option>' +
            '<option value="Zlato">Zlato (1. mjesto)</option>' +
            '<option value="Srebro">Srebro (2. mjesto)</option>' +
            '<option value="Bronza">Bronza (3. mjesto)</option>' +
            '<option value="Ostalo">Ostalo</option>' +
            '</select>' +
            '</div>' +
            '<div class="form-group col-md-12">' +
            '<label for="tip-nagrade"><img class="flow-icons-013" src="/images/icons/medalja.svg"> Nivo takmičenja</label>' +
            '<select name="nagrada[' + nagradeCount + '][nivo]" class="form-control">' +
            '<option value="" selected>Izaberite nivo takmičenja</option>' +
            '<option value="Internacionalni nivo">Internacionalni nivo</option>' +
            '<option value="Regionalni nivo">Regionalni nivo</option>' +
            '<option value="Državni nivo">Državni nivo</option>' +
            '<option value="Entitetski nivo">Entitetski nivo</option>' +
            '<option value="Drugo">Drugo</option>' +
            '</select>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group">' +
            '<label for="takmicenje"><img class="flow-icons-013" src="/images/icons/trophy.svg"> Naziv takmičenja</label>' +
            '<input type="text" name="nagrada[' + nagradeCount + '][takmicenje]" class="form-control" placeholder="Unesite naziv takmicenja za koje je osvojena nagrada">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="sezona"><img class="flow-icons-013" src="/images/icons/small-calendar.svg"> Sezona/Godina</label>' +
            '<input type="text" name="nagrada[' + nagradeCount + '][sezona]" class="form-control" placeholder="Unesite Sezonu/Godinu osvajanja trofeja">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="osvajanja"><img class="flow-icons-013" src="/images/icons/the-sum-of.svg"> Broj osvajanja</label>' +
            '<input type="number" name="nagrada[' + nagradeCount + '][osvajanja]" class="form-control" placeholder="Unesite broj osvajanja trofeja">' +
            '</div>' +
            '</div>' +
            '</div>';

        $(nagrada_form_input).appendTo('#tab-vitrina #nagradeLista').hide().slideDown();
        nagradeCount++;

        addTrophyValidation();
    });

    // Dodavanje kluba - Obriši nagradu
    $('body').on('click', '.izbrisiNagradu',function () {
        $(this).closest('.nagradaHover').slideUp('normal', function() { $(this).remove(); } );
    });

    // Dodavanje kluba - Promjena kontinenta
    $('body').on('change', continentSelectName, function () {
        var itemsToShow = countrySelect.children("option[data-parent^=" + continentSelect.val() + "]");

        if(itemsToShow.length > 0) {
            countrySelect.prop('disabled', false);
            countrySelect.children('option').hide();
            countrySelect.children('option:first').show();
            itemsToShow.show();
        } else {
            countrySelect.children('option:first').show();
            countrySelect.prop('disabled', 'disabled');
        }
        // Resetuj sve selecte poslije ovog
        countrySelect.prop("selectedIndex", 0);
        provinceSelect.prop("selectedIndex", 0).prop('disabled', 'disabled');
        regionSelect.prop("selectedIndex", 0).prop('disabled', 'disabled');
        municipalitySelect.prop("selectedIndex", 0).prop('disabled', 'disabled');
    });

    // Dodavanje kluba - Promjena države
    $('body').on('change', countrySelectName, function () {
        var itemsToShow = provinceSelect.children("option[data-parent^=" + countrySelect.val() + "]");

        if(itemsToShow.length > 0) {
            provinceSelect.prop('disabled', false);
            provinceSelect.children('option').hide();
            provinceSelect.children('option:first').show();
            itemsToShow.show();
        } else {
            provinceSelect.children('option:first').show();
            provinceSelect.prop('disabled', 'disabled');
        }
        // Resetuj sve selecte poslije ovog
        provinceSelect.prop("selectedIndex", 0);
        regionSelect.prop("selectedIndex", 0).prop('disabled', 'disabled');
        municipalitySelect.prop("selectedIndex", 0).prop('disabled', 'disabled');

        // Izlistaj sve saveze države ako postoje
        var associationsToShow = associationBox.find("input[data-region^=" + countrySelect.val() + "]");
        associationRadio.prop('checked', false);

        if(associationsToShow.length > 0) {
            associationRadio.closest('label').hide();
            associationsToShow.closest('label').css('display', 'inline-block');
            associationBox.show();
        } else {
            associationBox.hide();
        }

    });

    // Dodavanje kluba - Promjena pokrajine
    $('body').on('change', provinceSelectName, function () {
        var itemsToShow = regionSelect.children("option[data-parent^=" + provinceSelect.val() + "]");

        if(itemsToShow.length > 0) {
            regionSelect.prop('disabled', false);
            regionSelect.children('option').hide();
            regionSelect.children('option:first').show();
            regionSelect.parent().removeClass('search-input-disabled');
            itemsToShow.show();
        } else {
            regionSelect.children('option:first').show();
            regionSelect.prop('disabled', 'disabled');
            regionSelect.parent().addClass('search-input-disabled');
        }

        // Resetuj sve selecte poslije ovog
        regionSelect.prop("selectedIndex", 0);
        municipalitySelect.prop("selectedIndex", 0).prop('disabled', 'disabled');
        municipalitySelect.parent().addClass('search-input-disabled');
    });

    // Dodavanje kluba - Promjena regije
    $('body').on('change', regionSelectName, function () {
        var itemsToShow = municipalitySelect.children("option[data-parent^=" + regionSelect.val() + "]");

        if(itemsToShow.length > 0) {
            municipalitySelect.prop('disabled', false);
            municipalitySelect.children('option').hide();
            municipalitySelect.children('option:first').show();
            municipalitySelect.parent().removeClass('search-input-disabled');
            itemsToShow.show();
        } else {
            municipalitySelect.children('option:first').show();
            municipalitySelect.prop('disabled', 'disabled');
            municipalitySelect.parent().addClass('search-input-disabled');
        }

        // Resetuj sve selecte poslije ovog
        municipalitySelect.prop("selectedIndex", 0);
    });

    // Selekt za sportove
    $('body').on('change', sportTypeSelectName, function () {
        var itemsToShow;
        if(sportTypeSelect.val() == 1 || sportTypeSelect.val() == 2) {
            if(sportTypeSelect.val() == 1) {
                itemsToShow = sportSelect.children("option[data-disabled^='0']");
            } else if (sportTypeSelect.val() == 2) {
                itemsToShow = sportSelect.children("option[data-disabled^='1']");
            }
            sportSelect.prop('selectedIndex', 0);
            sportSelect.prop('disabled', false);
            sportSelect.children('option').hide();
            sportSelect.children('option:first').show();
            itemsToShow.show();
        } else {
            sportSelect.prop('disabled', 'disabled');
        }
    });

    // Dodavanje kluba - Dodaj ličnost
    $('body').on('click', '#dodajHistory', function () {
        var history_form_input = '<div class="row historyHover">' +
            '<div class="izbrisiHistory"><i class="fa fa-times-circle-o"></i></div>' +
            '<div class="row form-objavi-klub-01">' +
            '<div class="form-group col-md-6">' +
            '<label><img class="flow-icons-013" src="/images/icons/klubovi-icon.svg"> Sezona</label>' +
            '<input type="text" name="history[' + historyCount + '][season]" class="form-control" placeholder="npr. 2015-2016">' +
            '</div>' +
            '<div class="form-group col-md-6">' +
            '<label><img class="flow-icons-013" src="/images/icons/klubovi-icon.svg"> Klub</label>' +
            '<input type="text" name="history[' + historyCount + '][club]" class="form-control" placeholder="Unesite ime kluba">' +
            '</div>' +
            '</div>' +
            '</div>';

        $(history_form_input).appendTo('#tab-predispozicije #historijaLista').hide().slideDown();
        historyCount++;

        addHistoryValidation();
    });

    // Dodavanje kluba - Obriši ličnost
    $('body').on('click', '.izbrisiHistory',function () {
        $(this).closest('.historyHover').slideUp('normal', function() { $(this).remove(); } );
    });

    // Dodavanje objekta - Dodaj teren/salu
    $('body').on('click', '#dodajTeren', function () {
        var teren_form_input = '<div class="row terenHover"><div class="izbrisiTeren"><i class="fa fa-times-circle-o"></i></div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-12">' +
            '<label for="name">Naziv ili oznaka terena/sale</label>' +
            '<input type="text" name="tereni[' + terenCount + '][name]" id="name' + terenCount + '" class="form-control" placeholder="Unesite naziv ili oznaku  terena">' +
            '</div>' +
            '<div class="form-group col-md-12">' +
            '<label for="sport">Sport</label>' +
            '<div class="form-group">' +
            '<label class="checkbox checkbox-inline">' +
            '<input type="checkbox" name="tereni[' + terenCount + '][sports][]" value="Nogomet"> Nogomet' +
            '<span class="checkbox-indicator"></span>' +
            '</label>' +
            '<label class="checkbox checkbox-inline">' +
            '<input type="checkbox" name="tereni[' + terenCount + '][sports][]" value="Mali nogomet"> Mali nogomet' +
            '<span class="checkbox-indicator"></span>' +
            '</label>' +
            '<label class="checkbox checkbox-inline">' +
            '<input type="checkbox" name="tereni[' + terenCount + '][sports][]" value="Košarka"> Košarka' +
            '<span class="checkbox-indicator"></span>' +
            '</label>' +
            '<label class="checkbox checkbox-inline ">' +
            '<input type="checkbox" name="tereni[' + terenCount + '][sports][]" value="Tenis"> Tenis' +
            '<span class="checkbox-indicator"></span>' +
            '</label>' +
            '<label class="checkbox checkbox-inline ">' +
            '<input type="checkbox" name="tereni[' + terenCount + '][sports][]" value="Stoni tenis"> Stoni tenis' +
            '<span class="checkbox-indicator"></span>' +
            '</label>' +
            '<label class="checkbox checkbox-inline ">' +
            '<input type="checkbox" name="tereni[' + terenCount + '][sports][]" value="Odbojka"> Odbojka' +
            '<span class="checkbox-indicator"></span>' +
            '</label>' +
            '<label class="checkbox checkbox-inline ">' +
            '<input type="checkbox" name="tereni[' + terenCount + '][sports][]" value="Badminton"> Badminton' +
            '<span class="checkbox-indicator"></span>' +
            '</label>' +
            '<label class="checkbox checkbox-inline ">' +
            '<input type="checkbox" name="tereni[' + terenCount + '][sports][]" value="Univerzalan teren"> Univerzalan teren' +
            '<span class="checkbox-indicator"></span>' +
            '</label>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-6">' +
            '<label for="type_of_field">Vrsta podloge</label>' +
            '<select class="form-control" name="tereni[' + terenCount + '][type_of_field]" id="type_of_field' + terenCount + '">' +
            '<option value="" disabled="" selected>Izaberite podlogu terena</option>' +
            '<option value="Parket">Parket</option>' +
            '<option value="Bitumen">Bitumen</option>' +
            '<option value="Plastika">Plastika</option>' +
            '<option value="Guma">Guma</option>' +
            '<option value="Zemlja">Zemlja</option>' +
            '<option value="Ostalo">Ostalo</option>' +
            '</select>' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="capacity">Kapacitet korisnika</label>' +
            '<input type="number" name="tereni[' + terenCount + '][capacity]" id="capacity' + terenCount + '" class="form-control" placeholder="Unesite maksimalan broj korisnika">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="public_capacity">Kapacitet gledaoca</label>' +
            '<input type="number" name="tereni[' + terenCount + '][public_capacity]" id="public_capacity' + terenCount + '" class="form-control" placeholder="Unesite maksimalan broj gledaoca">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="length">Dužina terena (m)</label>' +
            '<input type="number" name="tereni[' + terenCount + '][length]" id="length' + terenCount + '" class="form-control" placeholder="Unesite dužinu terena u metrima">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="width">Širina terena (m)</label>' +
            '<input type="number" name="tereni[' + terenCount + '][width]" id="width' + terenCount + '" class="form-control" placeholder="Unesite širinu terena u metrima">' +
            '</div>' +
            '</div>' +
            '</div>';

        $(teren_form_input).appendTo('#tab-tereni #tereniLista').hide().slideDown();
        terenCount++;

        addTerenValidation();
    });

    // Dodavanje objekta - Obriši teren/salu
    $('body').on('click', '.izbrisiTeren',function () {
        $(this).closest('.terenHover').slideUp('normal', function() { $(this).remove(); } );
    });

    // Dodavanje objekta - Dodaj cjenovnik
    $('body').on('click', '#balonDodajCjenovnik', function () {
        var cjenovnik_form_input = '<div class="row balonCjenovnikHover">' +
            '<div class="izbrisiBalonCjenovnik"><i class="fa fa-times-circle-o"></i></div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-12">' +
            '<label for="sport' + cjenovnikBalonCount + '">Sport</label>' +
            '<select class="form-control" id="sport' + cjenovnikBalonCount + '" name="cjenovnik[' + cjenovnikBalonCount + '][sport]">' +
            '<option disabled="" selected="">Izaberite sport</option>' +
            '<option value="Nogomet">Nogomet</option>' +
            '<option value="Mali nogomet">Mali nogomet</option>' +
            '<option value="Košarka">Košarka</option>' +
            '<option value="Tenis">Tenis</option>' +
            '<option value="Stoni Tenis">Stoni Tenis</option>' +
            '<option value="Odbojka">Odbojka</option>' +
            '<option value="Badminton">Badminton</option>' +
            '<option value="Univerzalan teren">Univerzalan teren</option>' +
            '</select>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="name' + cjenovnikBalonCount + '">Naziv/oznaka terena</label>' +
            '<input type="text" name="cjenovnik[' + cjenovnikBalonCount + '][name]" id="name' + cjenovnikBalonCount + '" class="form-control" placeholder="Unesite naziv ili oznaku terena">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="price_per_hour' + cjenovnikBalonCount + '">Cijena termina (60 min)</label>' +
            '<input type="number" name="cjenovnik[' + cjenovnikBalonCount + '][price_per_hour]" id="price_per_hour' + cjenovnikBalonCount + '" class="form-control" placeholder="Unesite cijenu u KM">' +
            '</div>' +
            '</div>' +
            '</div>';

        $(cjenovnik_form_input).appendTo('#tab-cjenovnik-balon #balonCjenovnikLista').hide().slideDown();
        cjenovnikBalonCount++;

        addCjenovnikBalonValidation();
    });

    // Dodavanje objekta - Obriši cjenovnik
    $('body').on('click', '.izbrisiBalonCjenovnik',function () {
        $(this).closest('.balonCjenovnikHover').slideUp('normal', function() { $(this).remove(); } );
    });

    // Dodavanje objekta - Dodaj stazu
    $('body').on('click', '#dodajStazu', function () {
        var staze_form_input = '<div class="row stazeHover">' +
            '<div class="izbrisiStazu"><i class="fa fa-times-circle-o"></i></div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-12">' +
            '<label for="name' + stazeCount + '">Naziv staze</label>' +
            '<input type="text" name="staze[' + stazeCount + '][name]" id="name' + stazeCount + '" class="form-control" placeholder="Unesite naziv staze">' +
            '</div>' +
            '<div class="form-group col-md-12">' +
            '<label for="level' + stazeCount + '">Težina staze</label>' +
            '<select class="form-control" id="level' + stazeCount + '" name="staze[' + stazeCount + '][level]">' +
            '<option value="" disabled="" selected>Odaberite</option>' +
            '<option value="Lahko">Lahko</option>' +
            '<option value="Srednje">Srednje</option>' +
            '<option value="Teško">Teško</option>' +
            '</select>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="length' + stazeCount + '">Dužina staze</label>' +
            '<input type="number" name="staze[' + stazeCount + '][length]" id="length' + stazeCount + '" class="form-control" placeholder="Unesite dužinu staze u metrima">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="time' + stazeCount + '">Trajanje spusta</label>' +
            '<input type="number" name="staze[' + stazeCount + '][time]" id="time' + stazeCount + '" class="form-control" placeholder="Unesite vrijeme trajanja spusta u minutama">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="start_point' + stazeCount + '">Tačka polazišta (m)</label>' +
            '<input type="number" name="staze[' + stazeCount + '][start_point]" id="start_point' + stazeCount + '" class="form-control" placeholder="Unesite nadmorsku visinu tačke polazišta">' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="end_point' + stazeCount + '">Tačka izlaza (m)</label>' +
            '<input type="number" name="staze[' + stazeCount + '][end_point]" id="end_point' + stazeCount + '" class="form-control" placeholder="Unesite nadmorsku visinu tačke izlaza">' +
            '</div>' +
            '</div>' +
            '</div>';

        $(staze_form_input).appendTo('#tab-staze #stazeLista').hide().slideDown();
        stazeCount++;

        addStazeValidation();
    });

    // Dodavanje objekta - Obriši stazu
    $('body').on('click', '.izbrisiStazu',function () {
        $(this).closest('.stazeHover').slideUp('normal', function() { $(this).remove(); } );
    });

    // Dodavanje objekta - Dodaj cjenovnik
    $('body').on('click', '#skiDodajCjenovnik', function () {
        var cjenovnik_form_input = '<div class="row skiCjenovnikHover">' +
            '<div class="izbrisiSkiCjenovnik"><i class="fa fa-times-circle-o"></i></div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-12">' +
            '<label for="description' + cjenovnikSkiCount + '">Opis karte</label>' +
            '<input type="text" name="cjenovnik[' + cjenovnikSkiCount + '][description]" id="description' + cjenovnikSkiCount + '" class="form-control" placeholder="Unesite opis karte">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="price' + cjenovnikSkiCount + '">Cijena karte odrasli</label>' +
            '<input type="text" name="cjenovnik[' + cjenovnikSkiCount + '][price]" id="price' + cjenovnikSkiCount + '" class="form-control" placeholder="Unesite cijenu u KM" >' +
            '</div>' +
            '<div class="form-group col-md-6 col-xs-12">' +
            '<label for="price_kid' + cjenovnikSkiCount + '">Cijena karte djeca</label>' +
            '<input type="text" name="cjenovnik[' + cjenovnikSkiCount + '][price_kids]" id="price_kids' + cjenovnikSkiCount + '" class="form-control" placeholder="Unesite cijenu u KM">' +
            '</div>' +
            '</div>' +
            '</div>';

        $(cjenovnik_form_input).appendTo('#tab-cjenovnik-skijaliste #skiCjenovnikLista').hide().slideDown();
        cjenovnikSkiCount++;

        addCjenovnikSkiValidation();
    });

    // Dodavanje objekta - Obriši cjenovnik
    $('body').on('click', '.izbrisiSkiCjenovnik',function () {
        $(this).closest('.skiCjenovnikHover').slideUp('normal', function() { $(this).remove(); } );
    });


    var date_input1 = $('input[name="date_of_birth"]'); //our date input has the name "date"
    var container1 = "body";
    var options1 = {
        format: 'mm/dd/yyyy',
        container: container1,
        todayHighlight: true,
        autoclose: true,
        maxDate: new Date()
    };
    date_input1.datepicker(options1);

    var date_input = $('input[name="date"]'); //our date input has the name "date"
    var container = $('form').length > 0 ? $('form').parent() : "body";
    var options = {
        format: 'mm/dd/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        maxDate: new Date()
    };
    date_input.datepicker(options);

    var date_input2 = $('input[name="dob"]'); //our date input has the name "date"
    var container2 = $('form').length > 0 ? $('form').parent() : "body";
    var options2 = {
        format: 'mm/dd/yyyy',
        container: container2,
        todayHighlight: true,
        autoclose: true,
        maxDate: new Date()
    };
    date_input2.datepicker(options2);

    var date_input3 = $('input.pickDate'); //our date input has the name "date"
    var container3 = $('form').length > 0 ? $('form').parent() : "body";
    var options3 = {
        format: 'mm/dd/yyyy',
        container: container3,
        todayHighlight: true,
        autoclose: true,
        maxDate: new Date()
    };
    date_input3.datepicker(options3);

    $('.btn-dalje').on('click', function () {
        if ($(this).closest('form').valid()) {
            var sledeci = $('.nav-product-tabs').find('.active').next();
            var sljedeci_tab = $('.tab-pane.active').next();

            $('.nav-product-tabs li').removeClass('active');
            $('.tab-pane').removeClass('active in');

            sljedeci_tab.addClass('active in');
            sledeci.addClass('active');
        }
    });

    $('.btn-nazad').on('click', function () {
        var prethodni = $('.nav-product-tabs').find('.active').prev();
        $('.nav-product-tabs li').removeClass('active');

        prethodni.addClass('active');
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

    $('body').on('click', '.edit-player',function () {
        var id;

        id = $(this).data('id');

        getPlayerEditFormById(id);
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

    $('body').on('click', '#buttonSavePlayerGeneral', function (e) {
        e.preventDefault();
        var form = $(this).closest('form');

        if(!form.valid()) {
            $(".modal").animate({ scrollTop: 0 }, "fast");
            return false;
        }

        var id;
        id = $(this).data('id');
        var player = new FormData(form[0]);
        player.append('_method', 'PATCH');

        editPlayer(id, player, 'general');
    });

    $('body').on('click', '#buttonSavePlayerStatus', function (e) {
        e.preventDefault();
        var form = $(this).closest('form');

        if(!form.valid()) {
            $(".modal").animate({ scrollTop: 0 }, "fast");
            return false;
        }

        var id;
        id = $(this).data('id');
        var player = new FormData(form[0]);
        player.append('_method', 'PATCH');

        editPlayer(id, player, 'status');
    });

    $('body').on('click', '#buttonSavePlayerBiography', function (e) {
        e.preventDefault();
        var form = $(this).closest('form');

        if(!form.valid()) {
            $(".modal").animate({ scrollTop: 0 }, "fast");
            return false;
        }

        var id;
        id = $(this).data('id');
        var player = new FormData(form[0]);
        player.append('_method', 'PATCH');

        editPlayer(id, player, 'biography');
    });

    $('body').on('click', '#buttonSavePlayerTrophies', function (e) {
        e.preventDefault();
        var form = $(this).closest('form');

        if(!form.valid()) {
            $(".modal").animate({ scrollTop: 0 }, "fast");
            return false;
        }

        var id;
        id = $(this).data('id');
        var player = new FormData(form[0]);
        player.append('_method', 'PATCH');

        editPlayer(id, player, 'trophies');
    });

    $('body').on('click', '#buttonSavePlayerGallery', function (e) {
        e.preventDefault();
        var form = $(this).closest('form');

        if(!form.valid()) {
            $(".modal").animate({ scrollTop: 0 }, "fast");
            return false;
        }

        var id;
        id = $(this).data('id');
        var player = new FormData(form[0]);
        player.append('_method', 'PATCH');

        editPlayer(id, player, 'gallery');
    });

    // Objects
    $('body').on('click', '.display-object',function () {
        var id;

        id = $(this).data('id');

        getObjectById(id, 'display');
    });

    $('body').on('click', '.edit-object',function () {
        var id;

        id = $(this).data('id');

        getObjectEditFormById(id);
    });

    $('body').on('click', '.delete-object', function () {
        var id;
        id = $(this).data('id');

        var confirmation = confirm('Da li ste sigurni da želite izbrisati ovaj sportski objekat?');

        if(confirmation) {
            deleteObject(id);
        }

    });
});

jQuery.extend(jQuery.validator.messages, {
    required: "Ovo polje je obavezno.",
    remote: "Please fix this field.",
    email: "Unesite validnu e-mail adresu.",
    url: "Please enter a valid URL.",
    date: "Unesite validan datum",
    dateISO: "Please enter a valid date (ISO).",
    number: "Unesite validan broj.",
    digits: "Ovo polje može sadržati samo cifre.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Unesite istu vrijednost.",
    accept: "Molimo vas da unesete fajl validne ekstenzije.",
    maxlength: jQuery.validator.format("Unesite manje od {0} karaktera."),
    minlength: jQuery.validator.format("Unesite najmanje {0} karaktera."),
    rangelength: jQuery.validator.format("Unesite između {0} i {1} karaktera."),
    range: jQuery.validator.format("Molimo Vas unseite broj između {0} i {1}."),
    max: jQuery.validator.format("Molimo vas unesite manji broj ili broj {0}."),
    min: jQuery.validator.format("Molimo vas unserite veći broj ili broj {0}."),
    extension: "Molimo vas da unesete fajl validne ekstenzije."
});

function addLicnostValidation() {
    var licnost = $('form').find('input[name^="licnost"]');

    licnost.filter('input[name$="[ime]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    licnost.filter('input[name$="[prezime]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    licnost.filter('input[name$="[opis]"]').each(function() {
        $(this).rules("add", {
            string: true,
            maxlength: 1000
        });
    });

    licnost.filter('input[name$="[avatar]"]').each(function() {
        $(this).rules("add", {
            extension: 'png|jpg|jpeg'
        });
    });
}

function addTrophyValidation() {
    var nagrada = $('form').find('input[name^="nagrada"], select[name^="nagrada"]');

    nagrada.filter('select[name$="[vrsta]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    nagrada.filter('select[name$="[tip]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    nagrada.filter('select[name$="[nivo]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    nagrada.filter('input[name$="[takmicenje]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    nagrada.filter('input[name$="[sezona]"]').each(function() {
        $(this).rules("add", {
            required: true,
            digits: true,
            range: [1800, new Date().getFullYear()]
        });
    });


    nagrada.filter('input[name$="[osvajanja]"]').each(function() {
        $(this).rules("add", {
            digits: true
        });
    });
}

function addGalleryValidation() {
    var gallery = $('form').find('input[name^="galerija"]');

    gallery.each(function() {
        $(this).rules("add", {
            accept: 'image/*'
        });
    });
}

function addHistoryValidation() {
    var history = $('form').find('input[name^="history"]');

    history.filter('input[name$="[season]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    history.filter('input[name$="[club]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });
}

function addTerenValidation() {
    var teren = $('form').find('input[name^="tereni"], select[name^="tereni"]');

    teren.filter('input[name$="[name]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    teren.filter('input[name$="[sports][]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    teren.filter('select[name$="[type_of_field]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            lettersonly: true,
            maxlength: 255
        });
    });

    teren.filter('input[name$="[capacity]"]').each(function() {
        $(this).rules("add", {
            digits: true
        });
    });

    teren.filter('input[name$="[public_capacity]"]').each(function() {
        $(this).rules("add", {
            digits: true
        });
    });

    teren.filter('input[name$="[length]"]').each(function() {
        $(this).rules("add", {
            number: true
        });
    });

    teren.filter('input[name$="[width]"]').each(function() {
        $(this).rules("add", {
            number: true
        });
    });
}

function addCjenovnikBalonValidation() {
    var cjenovnik = $('form').find('input[name^="cjenovnik"], select[name^="cjenovnik"]');

    cjenovnik.filter('select[name$="[sport]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    cjenovnik.filter('input[name$="[name]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    cjenovnik.filter('input[name$="[price_per_hour]"]').each(function() {
        $(this).rules("add", {
            required: true,
            number: true,
            range: [1, 1000]
        });
    });
}

function addStazeValidation() {
    var staze = $('form').find('input[name^="staze"], select[name^="staze"]');

    staze.filter('input[name$="[name]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    staze.filter('select[name$="[level]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            lettersonly: true,
            maxlength: 255
        });
    });

    staze.filter('input[name$="[length]"]').each(function() {
        $(this).rules("add", {
            number: true,
            range: [1, 30000]
        });
    });

    staze.filter('input[name$="[time]"]').each(function() {
        $(this).rules("add", {
            number: true,
            range: [1, 1000]
        });
    });

    staze.filter('input[name$="[start_point]"]').each(function() {
        $(this).rules("add", {
            number: true,
            range: [1, 8000]
        });
    });

    staze.filter('input[name$="[end_point]"]').each(function() {
        $(this).rules("add", {
            number: true,
            range: [1, 8000]
        });
    });
}

function addCjenovnikSkiValidation() {
    var cjenovnik = $('form').find('input[name^="cjenovnik"], select[name^="cjenovnik"]');

    cjenovnik.filter('input[name$="[description]"]').each(function() {
        $(this).rules("add", {
            required: true,
            string: true,
            maxlength: 255
        });
    });

    cjenovnik.filter('input[name$="[price]"]').each(function() {
        $(this).rules("add", {
            required: true,
            number: true,
            range: [0, 10000]
        });
    });

    cjenovnik.filter('input[name$="[price_kids]"]').each(function() {
        $(this).rules("add", {
            required: true,
            number: true,
            range: [0, 10000]
        });
    });
}

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

function getPlayerEditFormById(id) {
    $.ajax({
        method: 'GET',
        url: '/api/players/editForm/' + id
    }).done(function (response) {
        if(response) {
            addPlayerToEditModal(response);
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

function addPlayerToEditModal(htmlForm) {
    var editPlayerModal = $('#editPlayer');

    editPlayerModal.find('.player-content').html(htmlForm);
    addPlayerAllValidations();
    addRegionSelects();
    editPlayerModal.modal('show');
}

function addPlayerAllValidations() {
    $('#editPlayerGeneral').validate({
        ignore: ':hidden,:disabled',
        rules: {
            avatar: {
                extension: 'png|jpg|jpeg'
            },
            firstname: {
                required: true,
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            lastname: {
                required: true,
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            player_nature: {
                required: true,
                digits: true
            },
            continent: {
                required: true,
                digits: true
            },
            country: {
                required: true,
                digits: true
            },
            province: {
                required: true,
                digits: true
            },
            region: {
                required: true,
                digits: true
            },
            municipality: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                string: true,
                maxlength: 255
            },
            facebook: {
                string: true,
                maxlength: 255
            },
            instagram: {
                string: true,
                maxlength: 255
            },
            twitter: {
                string: true,
                maxlength: 255
            },
            youtube: {
                string: true,
                maxlength: 255
            },
            video: {
                string: true,
                maxlength: 255
            }
        }
    });

    $('#editPlayerStatus').validate({
        ignore: ':hidden,:disabled',
        rules: {
            requested_club: {
                digits: true
            },
            weight: {
                number: true
            },
            height: {
                number: true
            },
            preferred_leg: {
                string: true,
                maxlength: 255
            },
            preferred_arm: {
                string: true,
                maxlength: 255
            },
            rank: {
                digits: true
            },
            discipline: {
                string: true,
                maxlength: 255
            },
            best_result: {
                number: true
            },
            agent: {
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            position: {
                string: true,
                maxlength: 255
            },
            competition: {
                string: true,
                maxlength: 255
            },
            category: {
                string: true,
                maxlength: 255
            },
            market_value: {
                digits: true
            },
            branch: {
                string: true,
                maxlength: 255
            },
            belt: {
                string: true,
                maxlength: 255
            },
            stlye: {
                string: true,
                maxlength: 255
            },
            distance: {
                digits: true
            },
            coach: {
                string: true,
                lettersonly: true,
                maxlength: 255
            },
            best_rank: {
                digits: true
            }
        }
    });

    $('#editPlayerBiography').validate({
        ignore: ':hidden,:disabled',
        rules: {
            biography: {
                string: true
            }
        }
    });

    $('#editPlayerTrophies').validate({
        ignore: ':hidden,:disabled'
    });

    $('#editPlayerGallery').validate({
        ignore: ':hidden',
        rules: {
            'galerija[]': {
                extension: "jpg|jpeg|png"
            }
        }
    });
}

function addRegionSelects() {
    // Select boxovi za regione
    continentSelect = $('select#continent');
    countrySelect = $('select#country');
    provinceSelect = $('select#province');
    regionSelect = $('select#region');
    municipalitySelect = $('select#municipality');

    // Select boxes
    sportTypeSelect = $('select#club-type');
    sportSelect = $('select#sport');
    associationBox = $('#associations');
    associationRadio = $('input[name="association"]');
}

function editPlayer(id, player, type) {

    $.ajax({
        method: 'POST',
        data: player,
        url: '/api/players/' + id + '/edit/' + type,
        cache: false,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data'
    }).done(function (response) {
        var errors = null;

        $(".modal").animate({ scrollTop: 0 }, "fast");
        $('#editPlayerServerErrors').html('');

        if (!response.success) {
            if(response.errors) {
                errors += '<div class="alert alert-danger"><ul>';
                response.errors.forEach(function (error) {
                    errors += '<li>' + error + '</li>';
                });
                errors += '</ul></div>';

                $('#editPlayerServerErrors').html(errors);
            } else {
                $('#editPlayerServerErrors').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        } else {
            $('#editPlayerServerErrors').html('<div class="alert alert-success">' + response.message + '</div>');
        }

        $('#editPlayerServerErrors').show().delay(3000).fadeOut();
    });
}

function getObjectById(id, type) {
    $.ajax({
        method: 'GET',
        url: '/api/objects/' + id
    }).done(function (response) {
        if(response.success) {
            if(response.success) {
                if(type === 'display') {
                    addObjectToModal(response.object);
                } else if(type === 'edit') {
                    addObjectToEditModal(response.object);
                }
            }
        }
    });
}

function addObjectToModal(object) {
    var displayObjectModal = $('#displayObject');
    var established_in = new Date(object.established_in);

    if(object.status === 'active') {
        displayObjectModal.find('.modal-footer').remove();
    }

    var object_data_general = '';
    Object.keys(object.object_data_general.data).forEach(function (key) {
        object_data_general += '<li><b>' + object.object_data_general.names[key].label.bs + ':</b> ' + (object.object_data_general.data[key] || 'Nema') +'</li>';
    });

    var object_data_additional = '';
    Object.keys(object.object_data_additional.data).forEach(function (key) {
        object_data_additional += '<li><b>' + object.object_data_additional.names[key].label.bs + ':</b> ' + (object.object_data_additional.data[key] || 'Nema') +'</li>';
    });

    var object_data_szs = '';
    Object.keys(object.object_data_szs.data).forEach(function (key) {
        object_data_szs += '<li><b>' + object.object_data_szs.names[key].label.bs + ':</b> ' + (object.object_data_szs.data[key] ? 'Ima' : 'Nema') +'</li>';
    });

    var specific_tabs = '';
    if(object.type.type === 'Balon') {
        var fields = '';
        Object.keys(object.fields).forEach(function (key) {
            fields += '<div class="col-md-4">' +
                '<div class="custom-box-row">' +
                '<ul>' +
                '<li><b>Naziv:</b> ' + object.fields[key].name + '</li>' +
                '<li><b>Sportovi:</b> ' + object.fields[key].sports + '</li>' +
                '<li><b>Tip:</b> ' + object.fields[key].type_of_field + '</li>' +
                '<li><b>Kapacitet:</b> ' + (object.fields[key].capacity || '-') + '</li>' +
                '<li><b>Dužina:</b> ' + (object.fields[key].length || '-') + '</li>' +
                '<li><b>Širina:</b> ' + (object.fields[key].width || '-') + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
        });

        var balon_prices = '';
        Object.keys(object.prices).forEach(function (key) {
            balon_prices += '<div class="col-md-4">' +
                '<div class="custom-box-row">' +
                '<ul>' +
                '<li><b>Teren:</b> ' + object.prices[key].name + '</li>' +
                '<li><b>Sport:</b> ' + object.prices[key].sport + '</li>' +
                '<li><b>Cijena (po satu):</b> ' + object.prices[key].price_per_hour + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
        });

        specific_tabs = '<div class="row custom-box-row">' +
            '<div class="col-md-12">' +
            '<h3>Sale/Tereni</h3>' +
            '</div>' +
            (fields || '<div class="col-md-12">Nema terena.</div>') +
            '</div>' +
            '<div class="row custom-box-row">' +
            '<div class="col-md-12">' +
            '<h3>Cjenovnik</h3>' +
            '</div>' +
            (balon_prices || '<div class="col-md-12">Nema cjenovnika.</div>') +
            '</div>';

    } else if(object.type.type === 'Skijalište') {
        var tracks = '';
        Object.keys(object.tracks).forEach(function (key) {
            tracks += '<div class="col-md-4">' +
                '<div class="custom-box-row">' +
                '<ul>' +
                '<li><b>Naziv:</b> ' + object.tracks[key].name + '</li>' +
                '<li><b>Težina:</b> ' + object.tracks[key].level + '</li>' +
                '<li><b>Dužina:</b> ' + object.tracks[key].length + ' m' + '</li>' +
                '<li><b>Vrijeme trajanja:</b> ' + object.tracks[key].time + ' min' + '</li>' +
                '<li><b>Početna tačka:</b> ' + object.tracks[key].start_point + ' m' + '</li>' +
                '<li><b>Završna tačka:</b> ' + object.tracks[key].end_point + ' m' + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
        });

        var skiing_prices = '';
        Object.keys(object.prices).forEach(function (key) {
            skiing_prices += '<div class="col-md-4">' +
                '<div class="custom-box-row">' +
                '<ul>' +
                '<li><b>Opis:</b> ' + object.prices[key].description + '</li>' +
                '<li><b>Cijena odrasli:</b> ' + object.prices[key].price + '</li>' +
                '<li><b>Cijena djeca:</b> ' + object.prices[key].price_kids + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
        });

        specific_tabs = '<div class="row custom-box-row">' +
            '<div class="col-md-12">' +
            '<h3>Staze</h3>' +
            '</div>' +
            (tracks || '<div class="col-md-12">Nema staza.</div>') +
            '</div>' +
            '<div class="row custom-box-row">' +
            '<div class="col-md-12">' +
            '<h3>Cjenovnik</h3>' +
            '</div>' +
            (skiing_prices || '<div class="col-md-12">Nema cjenovnika.</div>') +
            '</div>';
    }

    var images = '';
    Object.keys(object.images).forEach(function (key) {
        images += '<div class="col-md-12">' +
            '<div class="custom-box-row">' +
            '<img class="responsive-img" src="' + config.site.paths.object_gallery + object.images[key].image + '">' +
            '</div>' +
            '</div>';
    });

    var objectInfo = '<div class="row custom-box-row">' +
        '<div class="col-md-6">' +
        '<h3>Osnovne informacije</h3>' +
        '<ul>' +
        '<li><b>Naziv: </b> ' + object.name + '</li>' +
        '<li><b>Datum osnivanja:</b> ' + established_in.getDay() + '/' + established_in.getMonth() + '/' + established_in.getFullYear() + '</li>' +
        '<li><b>Facebook: </b> ' + (object.facebook || '-') + '</li>' +
        '<li><b>Twitter: </b> ' + (object.twitter || '-') + '</li>' +
        '<li><b>Instagram: </b> ' + (object.instagram || '-') + '</li>' +
        '<li><b>Youtube: </b> ' + (object.youtube || '-') + '</li>' +
        '<li><b>Video: </b> ' + (object.video || '-') + '</li>' +
        '</ul>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<h3>Generalne karakteristike</h3>' +
        '<ul>' +
        object_data_general +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="row custom-box-row">' +
        '<div class="col-md-6">' +
        '<h3>Dodatne karakteristike</h3>' +
        '<ul>' +
        object_data_additional +
        '</ul>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<h3>Sve za sport karakteristike</h3>' +
        '<ul>' +
        object_data_szs +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="row custom-box-row">' +
        '<div class="col-md-12">' +
        '<h3>Regija</h3>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<ul>' +
        '<li><b>Kontinent:</b> ' + (object.regions.continent || 'Nema') + '</li>' +
        '<li><b>Država:</b> ' + (object.regions.country || 'Nema') + '</li>' +
        '<li><b>Entitet/Pokrajina:</b> ' + (object.regions.province || 'Nema') + '</li>' +
        '</ul>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<ul>' +
        '<li><b>Kanton/Regija:</b> ' + (object.regions.region || 'Nema') + '</li>' +
        '<li><b>Općina:</b> ' + (object.regions.municipality || 'Nema') + '</li>' +
        '<li><b>Grad/Mjesto:</b> ' + (object.regions.city || 'Nema') + '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="row custom-box-row">' +
        '<div class="col-md-12">' +
        '<h3>Historija</h3>' +
        '</div>' +
        '<div class="col-md-12">' +
        (object.history || 'Historija nije unesena.') +
        '</div>' +
        '</div>' +
        specific_tabs +
        '<div class="row custom-box-row">' +
        '<div class="col-md-12">' +
        '<h3>Galerija</h3>' +
        '</div>' +
        (images || '<div class="col-md-12">Nema slika.</div>') +
        '</div>';

    displayObjectModal.find('.modal-title').html('<b>' + object.name + '(<i>' + object.type.type + '</i>)</b>');
    displayObjectModal.find('.object-content').html(objectInfo);
    displayObjectModal.find('.object-image img').attr('src', config.site.paths.object_images + object.image);
    displayObjectModal.find('#deleteObject').data('id', object.id);
    displayObjectModal.find('#approveObject').data('id', object.id);


    displayObjectModal.modal('show');
}
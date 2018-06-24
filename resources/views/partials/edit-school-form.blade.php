<div class="row profil-content-b06">
    <!-- Main content -->
    <div class="sidebar col-md-12 overscreen">

        <!-- Single Product Tabbed Content -->
        <div class="product-tabs card card--xlg">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified nav-product-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-opcenito" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i><small>O školi</small>Općenito</a></li>
                <li role="presentation"><a href="#tab-licnosti" role="tab" data-toggle="tab"><i class="fa fa-certificate"></i><small>Naša</small>Djeca</a></li>
                <li role="presentation"><a href="#tab-vremeplov" role="tab" data-toggle="tab"><i class="fa fa-history"></i><small>Školski</small>Vremeplov</a></li>
                <li role="presentation"><a href="#tab-vitrina" role="tab" data-toggle="tab"><i class="fa fa-trophy"></i><small>Trofejna</small>Vitrina</a></li>
                <li role="presentation"><a href="#tab-galerija" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><small>Foto</small>Galerija</a></li>
            </ul>

            <div class="row form-segment">
                <div class="col-md-12" id="editSchoolServerErrors">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Tab panes -->
            <div class="tab-content card__content">


                <!-- Tab: Općenito -->
                <div role="tabpanel" class="tab-pane fade in active" id="tab-opcenito">
                    <form id="editSchoolGeneral" role="form" action="{{ url('/schools/' . $school->id . '/edit/general') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row">
                            <div class="row identitet-style">

                                <div class="col-md-6 objavi-klub-logo-setup">

                                    <div class="col-md-7">

                                        <div class="alc-staff__photo">
                                            <img class="slika-upload-klub" id="schoolLogoPreview" src="{{ asset(config('general.site_paths.school_images') . $school->logo) }}" alt="">
                                        </div>

                                    </div>

                                    <div class="col-md-5 sadrzaj-slike">

                                        <p class="dodaj-sliku-naslov klub-a1">Logo škole</p>
                                        <p class="dodaj-sliku-call">Identitet škole</p>
                                        <label class="btn btn-default btn-file dodaj-sliku-button">
                                            Odaberi logo... <input type="file" id="schoolLogo" name="logo" class="not-visible" onchange="previewFile('#schoolLogo', '#schoolLogoPreview', 1024, 1024, 512, 512)">
                                        </label>
                                        <div class="info001">
                                            <p class="info-upload-slike">Preporučene dimenzije za logo:</p>
                                            <p class="info-upload-slike">Minimalno: 512x512 px</p>
                                            <p class="info-upload-slike">Maksimalno: 1024x1024 px</p>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group col-md-12">
                                        <label for="name"><img class="flow-icons-013" src="{{ asset('images/icons/edit.svg') }}">Ime/Naziv škole sporta*</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Unesite ime/naziv škole" value="{{ $school->name }}">
                                    </div>
                                    <div class="form-group has-success col-md-12">
                                        <label for="nature"><img class="flow-icons-013" src="{{ asset('images/icons/edit.svg') }}"> Karakter škole sporta <span>(izmjenjivo)*</span></label>
                                        <input type="text" name="nature" id="nature" class="form-control" value="{{ $school->nature ? $school->nature : 'Škola fudbala' }}" placeholder="Unesite karakter škole">
                                        <span>Prilikom unosa karaktera škole ne unositi kratice kao što su: FK, NK, KK, OK i sl.</span>
                                    </div>

                                </div>

                            </div>


                            <div class="row form-segment">
                                <header class="card__header">
                                    <h4><i class="fa fa-location-arrow"></i> Navigacija</h4>
                                </header>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="continent"><img class="flow-icons-013" src="{{asset('images/icons/international-delivery.svg')}}"> Kontinent*</label>
                                    <select name="continent" class="form-control" id="continent">
                                        <option selected disabled>Izaberite kontinent</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Continent')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $school->regions->get('continent') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="country"><img class="flow-icons-013" src="{{asset('images/icons/earth.svg')}}"> Država*</label>
                                    <select name="country" class="form-control" id="country" {{ (old('country') || $school->regions->has('country')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite državu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Country')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $school->regions->get('country') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="province"><img class="flow-icons-013" src="{{asset('images/icons/map.svg')}}"> Pokrajina*</label>
                                    <select name="province" class="form-control" id="province" {{ (old('province') || $school->regions->has('province')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite pokrajinu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Province')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $school->regions->get('province') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="region"><img class="flow-icons-013" src="{{asset('images/icons/placeholder.svg')}}"> Regija*</label>
                                    <select name="region" class="form-control" id="region" {{ (old('region') || $school->regions->has('region')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite regiju</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Region')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $school->regions->get('region') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="municipality"><img class="flow-icons-013" src="{{asset('images/icons/opcina.svg')}}"> Općina*</label>
                                    <select name="municipality" class="form-control" id="municipality" {{ (old('municipality') || $school->regions->has('municipality')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite općinu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Municipality')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $school->regions->get('municipality') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="mjesto"><img class="flow-icons-013" src="{{asset('images/icons/small-calendar.svg')}}"> Mjesto/Grad*</label>
                                    <input name="city" id="mjesto" class="form-control" placeholder="Unesite mjesto škole" value="{{ $school->city }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="club-type"><img class="flow-icons-013" src="{{asset('images/icons/klubovi-icon.svg')}}"> Tip škole*</label>
                                    <select name="type" class="form-control" id="club-type">
                                        <option value="" disabled {{ $school->sport == '' ? 'selected' : '' }}>Izaberite tip kluba</option>
                                        <option value="1" {{ !$school->sport->with_disabilities ? 'selected' : '' }}>Sportski klub</option>
                                        <option value="2" {{ $school->sport->with_disabilities ? 'selected' : '' }}>Invalidski sportski klub</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="sport"><img class="flow-icons-013" src="{{asset('images/icons/menu-circular-button.svg')}}"> Sport škole*</label>
                                    <select name="sport" class="form-control" id="sport" {{ (old('sport') || $school->sport->id) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite sport kluba</option>
                                        @foreach($sports as $sport)
                                            <option data-disabled="{{ $sport->with_disabilities }}" value="{{ $sport->id }}" {{ $school->sport->id == $sport->id ? 'selected' : '' }}>{{ $sport->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="club-category"><img class="flow-icons-013" src="{{asset('images/icons/gender-symbols.svg')}}"> Tip škole*</label>
                                    <select name="category" class="form-control" id="club-category">
                                        <option selected disabled>Izaberite sport kluba</option>
                                        @foreach($clubCategories as $category)
                                            <option value="{{ $category->id }}" {{ $school->category->id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Osnovne informacije</h4>
                            </header>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="godina-osnivanja"><img class="flow-icons-013" src="{{asset('images/icons/small-calendar.svg')}}"> Godina osnivanja kluba</label>
                                <input type="number" name="established_in" id="godina-osnivanja" class="form-control" placeholder="Unesite godinu osnivanja kluba" value="{{$school->established_in}}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="domaci-teren"><img class="flow-icons-013" src="{{asset('images/icons/stadium-icon.svg')}}"> Domaći teren</label>
                                <input type="text" name="home_field" id="domaci-teren" class="form-control" placeholder="Unesite naziv domaćeg terena kluba" value="{{$school->home_field}}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="takmicenje"><img class="flow-icons-013" src="{{asset('images/icons/trophy.svg')}}"> Takmičenje</label>
                                <input type="text" name="competition" id="takmicenje" class="form-control" placeholder="Unesite naziv takmičenja u kojem klub nastupa" value="{{$school->competition}}">
                            </div>
                        </div>

                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Kontakt informacije</h4>
                            </header>
                        </div>
                        <div class="row">


                            <div class="form-group col-md-4">
                                <label for="tel1"><img class="flow-icons-013" src="{{asset('images/icons/phone-call.svg')}}"> Telefon 1</label>
                                <input type="number" name="phone_1" id="tel1" class="form-control" placeholder="Unesite broj za službeni telefon 1" value="{{ $school->phone_1 }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="tel2"><img class="flow-icons-013" src="{{asset('images/icons/phone-call.svg')}}">  Telefon 2</label>
                                <input type="number" name="phone_2" id="tel2" class="form-control" placeholder="Unesite broj za službeni telefon 2" value="{{ $school->phone_2 }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="fax"><img class="flow-icons-013" src="{{asset('images/icons/fax-with-phone.svg')}}"> Fax</label>
                                <input type="number" name="fax" id="fax" class="form-control" placeholder="Unesite broj za službeni fax" value="{{ $school->fax }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="mail"><img class="flow-icons-013" src="{{asset('images/icons/envelope.svg')}}"> E-mail</label>
                                <input type="email" name="email" id="mail" class="form-control" placeholder="Unesite službeni E-mail" value="{{ $school->email }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="web"><img class="flow-icons-013" src="{{asset('images/icons/worldwide.svg')}}"> Web stranica</label>
                                <input type="text" name="website" id="web" class="form-control" placeholder="Unesite link službene web stranice" value="{{ $school->website }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="adresa"><img class="flow-icons-013" src="{{asset('images/icons/icon.svg')}}"> Adresa (ne prikazuje se)</label>
                                <input type="text" name="address" id="adresa" class="form-control" placeholder="Unesite adresu sjedišta kluba" value="{{ $school->address }}">
                            </div>


                        </div>

                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Uzrasti</h4>
                            </header>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="pioniri"><img class="flow-icons-013" src="{{ asset('images/icons/pionir.svg') }}"> Pioniri*</label>
                                <select class="form-control" id="pioniri" name="pioniri">
                                    <option value="" disabled selected>Izaberite opciju</option>
                                    <option value="1" {{ $school->pioniri == 1 ? 'selected' : '' }}>Da</option>
                                    <option value="0" {{ $school->pioniri == 0 ? 'selected' : '' }}>Ne</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="kadeti"><img class="flow-icons-013" src="{{ asset('images/icons/kadet.svg') }}"> Kadeti*</label>
                                <select class="form-control" id="kadeti" name="kadeti">
                                    <option value="" disabled selected>Izaberite opciju</option>
                                    <option value="1" {{ $school->kadeti == 1 ? 'selected' : '' }}>Da</option>
                                    <option value="0" {{ $school->kadeti == 0 ? 'selected' : '' }}>Ne</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="juniori"><img class="flow-icons-013" src="{{ asset('images/icons/junior.svg') }}"> Juniori*</label>
                                <select class="form-control" id="juniori" name="juniori">
                                    <option value="" disabled selected>Izaberite opciju</option>
                                    <option value="1" {{ $school->juniori == 1 ? 'selected' : '' }}>Da</option>
                                    <option value="0" {{ $school->juniori == 0 ? 'selected' : '' }}>Ne</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-share-alt"></i> Socijalne mreže</h4>
                            </header>
                        </div>
                        <div class="row">


                            <div class="form-group col-md-6">
                                <label for="fcb"><img class="flow-icons-013" src="{{asset('images/icons/facebook.svg')}}"> Facebook stranica</label>
                                <input type="text" name="facebook" id="fcb" class="form-control" placeholder="Unesite link službenog facebook profila" value="{{ $school->facebook }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="twt"><img class="flow-icons-013" src="{{asset('images/icons/twitter.svg')}}"> Twitter profil</label>
                                <input type="text" name="twitter" id="twt" class="form-control" placeholder="Unesite link službenog twitter profila" value="{{ $school->twitter }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="inst"><img class="flow-icons-013" src="{{asset('images/icons/instagram.svg')}}"> Instagram profil</label>
                                <input type="text" name="instagram" id="inst" class="form-control" placeholder="Unesite link službenog instagram profila" value="{{ $school->instagram }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="yt"><img class="flow-icons-013" src="{{asset('images/icons/youtube.svg')}}"> YouTUBE profil</label>
                                <input type="text" name="youtube" id="yt" class="form-control" placeholder="Unesite link službenog YouTUBE kanala" value="{{ $school->youtube }}">
                            </div>


                        </div>

                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-youtube-play"></i> Video prezentacija</h4>
                            </header>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="videoprez"><img class="flow-icons-013" src="{{asset('images/icons/play-button.svg')}}"> Video prezentacija</label>
                                <input type="text" name="video" id="videoprez" class="form-control" placeholder="Unesite link videa (YouTUBE)" value="{{ $school->video }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveSchoolGeneral" data-id="{{ $school->id }}">Spremi općenito</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Općenito / End -->

                <!-- Tab: Ličnosti -->

                <div role="tabpanel" class="tab-pane fade" id="tab-licnosti">
                    <form id="editSchoolMembers" role="form" action="{{ url('/schools/' . $school->id . '/edit/staff') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div id="dodajLicnostButtons" class="row">
                            <div id="licnostiLista">
                                @if($school->members)
                                    @foreach($school->members as $key => $licnost)
                                        <div class="row licnostHover" data-key="{{ $key }}">
                                            <input type="hidden" name="licnost[{{ $key }}][id]" value="{{ $licnost->id }}">
                                            <div class="izbrisiLicnost"><i class="fa fa-times-circle-o"></i></div>
                                            <div class="row identitet-style">
                                                <div class="col-md-6 objavi-klub-logo-setup">
                                                    <div class="col-md-7">
                                                        <div class="alc-staff__photo">
                                                            <img class="slika-edit-profil" id="slika-licnost-prikaz{{ $key }}" src="{{ asset(config('general.site_paths.licnost_images') . $licnost->avatar) }}" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 sadrzaj-slike">
                                                        <p class="dodaj-sliku-naslov klub-a1">Slika ličnosti</p>
                                                        <p class="dodaj-sliku-call">Odaberite sliku za istaknutu ličnost</p>
                                                        <label class="btn btn-default btn-file dodaj-sliku-button">
                                                            Odaberi sliku... <input type="file" name="licnost[{{ $key }}][avatar]" id="licnostAvatar{{ $key }}" accept="image/*" class="not-visible" onchange="previewFile('#licnostAvatar{{ $key }}', '#slika-licnost-prikaz{{ $key }}', 1080, 1920, 250, 312)">
                                                        </label>
                                                        <div class="info001">
                                                            <p class="info-upload-slike">Preporučene dimenzije za sliku ličnosti:</p>
                                                            <p class="info-upload-slike">Minimalno: 312x250 px</p>
                                                            <p class="info-upload-slike">Maksimalno: 1920x1080 px</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="ime-kluba"><img class="flow-icons-013" src="{{ asset('images/icons/edit.svg') }}"> Ime</label>
                                                        <input type="text" name="licnost[{{ $key }}][ime]" class="form-control" placeholder="Unesite ime ličnosti" value="{{ $licnost->firstname }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="ime-kluba"><img class="flow-icons-013" src="{{ asset('images/icons/edit.svg') }}"> Prezime</label>
                                                        <input type="text" name="licnost[{{ $key }}][prezime]" class="form-control" placeholder="Unesite prezime ime ličnosti" value="{{ $licnost->lastname }}">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="opis"><img class="flow-icons-013" src="{{ asset('images/icons/edit.svg') }}"> Opis i uloga</label>
                                                        <textarea class="form-control" rows="4" name="licnost[{{ $key }}][opis]" placeholder="Upišite kratak opis uloge i funkcije navedene ličnosti u klubu...">{{ $licnost->biography }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary" type="button" id="dodajLicnost">Dodaj ličnost</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveSchoolMembers" data-id="{{ $school->id }}">Spremi članove</button>
                            </div>
                        </div>
                    </form>

                </div>

                <!-- Tab: Ličnosti / End -->

                <!-- Tab: Vremeplov -->

                <div role="tabpanel" class="tab-pane fade" id="tab-vremeplov">
                    <form id="editSchoolHistory" role="form" action="{{ url('/schools/' . $school->id . '/edit/history') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row">
                            <div class="row identitet-style">

                                <div class="col-md-12">

                                    <div class="form-group col-md-12">
                                        <label for="history"><img class="flow-icons-013" src="{{ asset('images/icons/edit.svg') }}"> Historija</label>
                                        <textarea class="form-control" rows="20" id="history" name="history" maxlength="1050" placeholder="Upišite kratku historiju škole sporta...">{{ $school->history }}</textarea>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="row">
                                <div class="form-group form-group--submit col-md-12">
                                    <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveSchoolHistory" data-id="{{ $school->id }}">Spremi historiju</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tab: Vremeplov / End -->

                <!-- Tab: Vitrina -->
                <div role="tabpanel" class="tab-pane fade" id="tab-vitrina">
                    <form id="editSchoolTrophies" role="form" action="{{ url('/schools/' . $school->id . '/edit/trophies') }}" method="POST" enctype="multipart/form-data" >
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row">
                            <div class="row form-segment">
                                <header class="card__header">
                                    <h4><i class="fa fa-plus-circle"></i> Unos osvojenog trofeja/nagrade</h4>
                                </header>
                            </div>
                            <div id="nagradeLista">
                                @if($school->trophies)
                                    @foreach($school->trophies as $key => $nagrada)
                                        <div class="row nagradaHover" data-key="{{ $key }}">
                                            <input type="hidden" name="nagrada[{{ $key }}][id]" value="{{ $nagrada->id }}">
                                            <div class="izbrisiNagradu"><i class="fa fa-times-circle-o"></i></div>
                                            <div class="col-md-6">
                                                <div class="form-group col-md-6">
                                                    <label for="vrsta-nagrade"><img class="flow-icons-013" src="{{ asset('images/icons/medalja.svg') }}"> Vrsta nagrade</label>
                                                    <select name="nagrada[{{ $key }}][vrsta]" class="form-control">
                                                        <option value="" {{ $nagrada->type == '' ? 'selected' : '' }}>Izaberite vrstu osvojene nagrade</option>
                                                        <option value="Medalja" {{ $nagrada->type == 'Medalja' ? 'selected' : '' }}>Medalja</option>
                                                        <option value="Trofej/Pehar" {{ $nagrada->type == 'Trofej/Pehar' ? 'selected' : '' }}>Trofej/Pehar</option>
                                                        <option value="Priznanje" {{ $nagrada->type == 'Priznanje' ? 'selected' : '' }}>Priznanje</option>
                                                        <option value="Plaketa" {{ $nagrada->type == 'Plaketa' ? 'selected' : '' }}>Plaketa</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="tip-nagrade"><img class="flow-icons-013" src="{{ asset('images/icons/medalja.svg') }}"> Tip nagrade</label>
                                                    <select name="nagrada[{{ $key }}][tip]" class="form-control">
                                                        <option value="" {{ $nagrada->place == '' ? 'selected' : '' }}>Izaberite tip nagrade</option>
                                                        <option value="Zlato" {{ $nagrada->place == 'Zlato' ? 'selected' : '' }}>Zlato (1. mjesto)</option>
                                                        <option value="Srebro" {{ $nagrada->place == 'Srebro' ? 'selected' : '' }}>Srebro (2. mjesto)</option>
                                                        <option value="Bronza" {{ $nagrada->place == 'Bronza' ? 'selected' : '' }}>Bronza (3. mjesto)</option>
                                                        <option value="Ostalo" {{ $nagrada->place == 'Ostalo' ? 'selected' : '' }}>Ostalo</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="tip-nagrade"><img class="flow-icons-013" src="{{ asset('images/icons/medalja.svg') }}"> Nivo takmičenja</label>
                                                    <select name="nagrada[{{ $key }}][nivo]" class="form-control">
                                                        <option value="" {{ $nagrada->level_of_competition == '' ? 'selected' : '' }}>Izaberite nivo takmičenja</option>
                                                        <option value="Internacionalni nivo" {{ $nagrada->level_of_competition == 'Internacionalni nivo' ? 'selected' : '' }}>Internacionalni nivo</option>
                                                        <option value="Regionalni nivo" {{ $nagrada->level_of_competition == 'Regionalni nivo' ? 'selected' : '' }}>Regionalni nivo</option>
                                                        <option value="Državni nivo" {{ $nagrada->level_of_competition == 'Državni nivo' ? 'selected' : '' }}>Državni nivo</option>
                                                        <option value="Entitetski nivo" {{ $nagrada->level_of_competition == 'Entitetski nivo' ? 'selected' : '' }}>Entitetski nivo</option>
                                                        <option value="Drugo" {{ $nagrada->level_of_competition == 'Drugo' ? 'selected' : '' }}>Drugo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="takmicenje"><img class="flow-icons-013" src="{{ asset('images/icons/trophy.svg') }}"> Naziv takmičenja</label>
                                                    <input type="text" name="nagrada[{{ $key }}][takmicenje]" class="form-control" placeholder="Unesite naziv takmicenja za koje je osvojena nagrada" value="{{ $nagrada->competition_name }}">
                                                </div>
                                                <div class="form-group col-md-6 col-xs-12">
                                                    <label for="sezona"><img class="flow-icons-013" src="{{ asset('images/icons/small-calendar.svg') }}"> Sezona/Godina</label>
                                                    <input type="text" name="nagrada[{{ $key }}][sezona]" class="form-control" placeholder="Unesite Sezonu/Godinu osvajanja trofeja" value="{{ $nagrada->season }}">
                                                </div>
                                                <div class="form-group col-md-6 col-xs-12">
                                                    <label for="osvajanja"><img class="flow-icons-013" src="{{ asset('images/icons/the-sum-of.svg') }}"> Broj osvajanja</label>
                                                    <input type="number" name="nagrada[{{ $key }}][osvajanja]" class="form-control" placeholder="Unesite broj osvajanja trofeja" value="">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary" type="button" id="dodajNagradu">Dodaj trofej</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveSchoolTrophies" data-id="{{ $school->id }}">Spremi trofeje</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Vitrina / End -->

                <!-- Tab: Foto galerija -->
                <div role="tabpanel" class="tab-pane fade" id="tab-galerija">
                    <form id="editSchoolGallery" role="form" action="{{ url('/schools/' . $school->id . '/edit/gallery') }}" method="POST" enctype="multipart/form-data" >
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row dodavanje-slika">
                            <div class="col-md-12 sadrzaj-slike">
                                <p class="dodaj-sliku-naslov">Dodajte slike</p>
                                <p class="dodaj-sliku-call">u Vašu galeriju</p>
                                <label class="btn btn-default btn-file dodaj-sliku-button">
                                    Odaberi slike... <input type="file" class="galerija_edit not-visible" name="galerija[]" accept="image/*" multiple>
                                </label>
                                <div class="info001">
                                    <p class="info-upload-slike">Preporučena dimenzija za vaše slike:</p>
                                    <p class="info-upload-slike">1920x1080 px</p>
                                </div>
                            </div>
                        </div>
                        <div class="row form-objavi-klub-01">
                            @foreach($school->images as $slika)
                                <div class="album__item col-xs-6 col-sm-3" id="galerija_klub">
                                    <div class="album__item-holder">
                                        <a href="{{asset(config('general.site_paths.school_gallery') . $slika->image)}}" class="album__item-link mp_gallery">
                                            <figure class="album__thumb">
                                                <img src="{{asset(config('general.site_paths.school_gallery') . $slika->image)}}" alt="">
                                            </figure>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveSchoolGallery" data-id="{{ $school->id }}">Spremi galeriju</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Foto galerija / End -->

                <!-- Single Product Tabbed Content / End -->
            </div>
        </div>
    </div>
</div>
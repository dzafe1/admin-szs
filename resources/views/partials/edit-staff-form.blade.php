<div class="row profil-content-b06">
    <!-- Main content -->
    <div class="sidebar col-md-12 overscreen">

        <!-- Single Product Tabbed Content -->
        <div class="product-tabs card card--xlg">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified nav-product-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-opcenito" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i><small>O kadru</small>Općenito</a></li>
                <li role="presentation"><a href="#tab-predispozicije" role="tab" data-toggle="tab"><i class="fa fa-bolt"></i><small>Predispozicije</small>Kadra</a></li>
                <li role="presentation"><a href="#tab-biografija" role="tab" data-toggle="tab"><i class="fa fa-history"></i><small>Biografija</small>Kadra</a></li>
                <li role="presentation"><a href="#tab-vitrina" role="tab" data-toggle="tab"><i class="fa fa-trophy"></i><small>Trofejna</small>Vitrina</a></li>
                <li role="presentation"><a href="#tab-galerija" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><small>Foto</small>Galerija</a></li>
            </ul>
            <div class="row form-segment">
                <div class="col-md-12" id="editStaffServerErrors">
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
                    <form id="editStaffGeneral" role="form" action="{{ url('/staff/' . $staff->id . '/edit/general') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row">
                            <div class="row identitet-style">

                                <div class="col-md-6 objavi-klub-logo-setup">

                                    <div class="col-md-7">

                                        <div class="alc-staff__photo">
                                            <img class="slika-upload-klub" id="slika_upload_klub" src="{{asset(config('general.site_paths.staff_images') . $staff->avatar)}}">
                                        </div>

                                    </div>

                                    <div class="col-md-5 sadrzaj-slike">

                                        <p class="dodaj-sliku-naslov klub-a1">Slika kadra</p>
                                        <p class="dodaj-sliku-call">Vaš identitet</p>
                                        <label class="btn btn-default btn-file dodaj-sliku-button">
                                            Odaberi sliku... <input type="file" id="slikaprof" name="avatar" class="not-visible" accept="image/*" onchange="previewFile('#slikaprof', '#slika_upload_klub', 2048, 2048, 512, 512)">
                                        </label>
                                        <div class="info001">
                                            <p class="info-upload-slike">Preporučene dimenzije za
                                                sliku:</p>
                                            <p class="info-upload-slike">Minimalno: 512x512 px</p>
                                            <p class="info-upload-slike">Maksimalno: 2048x2048 px</p>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group col-md-6">
                                        <label for="firstname"><img class="flow-icons-013" src="{{asset('images/icons/edit.svg')}}"> Ime sportskog kadra*</label>
                                        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Unesite ime sportskog kadra" value="{{ $staff->firstname }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lastname"><img class="flow-icons-013" src="{{asset('images/icons/edit.svg')}}"> Prezime sportskog kadra*</label>
                                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Unesite prezime sportskog kadra" value="{{ $staff->lastname }}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="profession"><img class="flow-icons-013" src="{{asset('images/icons/edit.svg')}}"> Zanimanje*</label>
                                        <select class="form-control" id="profession" name="profession">
                                            <option selected disabled>Izberite zanimanje sportskog kadra</option>
                                            @foreach($professions as $profession)
                                                <option value="{{ $profession->id }}" {{ $staff->profession->id == $profession->id ? 'selected' : '' }}>{{ $profession->name }}</option>
                                            @endforeach
                                        </select>
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
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $staff->regions->get('continent') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="country"><img class="flow-icons-013" src="{{asset('images/icons/earth.svg')}}"> Država*</label>
                                    <select name="country" class="form-control" id="country" {{ (old('country') || $staff->regions->has('country')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite državu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Country')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $staff->regions->get('country') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="province"><img class="flow-icons-013" src="{{asset('images/icons/map.svg')}}"> Pokrajina*</label>
                                    <select name="province" class="form-control" id="province" {{ (old('province') || $staff->regions->has('province')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite pokrajinu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Province')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $staff->regions->get('province') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="region"><img class="flow-icons-013" src="{{asset('images/icons/placeholder.svg')}}"> Regija*</label>
                                    <select name="region" class="form-control" id="region" {{ (old('region') || $staff->regions->has('region')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite regiju</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Region')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $staff->regions->get('region') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="municipality"><img class="flow-icons-013" src="{{asset('images/icons/opcina.svg')}}"> Općina*</label>
                                    <select name="municipality" class="form-control" id="municipality" {{ (old('municipality') || $staff->regions->has('municipality')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite općinu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Municipality')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $staff->regions->get('municipality') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="mjesto"><img class="flow-icons-013" src="{{asset('images/icons/small-calendar.svg')}}"> Mjesto/Grad*</label>
                                    <input name="city" id="mjesto" class="form-control" placeholder="Unesite mjesto sportskog kadra" value="{{ $staff->city }}">
                                </div>
                            </div>
                        </div>

                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-share-alt"></i> Socijalne mreže</h4>
                            </header>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="facebook"><img class="flow-icons-013" src="{{asset('images/icons/facebook.svg')}}"> Facebook stranica</label>
                                <input type="text" name="facebook" id="facebook" class="form-control" placeholder="Unesite link službene facebook stranice" value="{{ $staff->facebook }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="twitter"><img class="flow-icons-013" src="{{asset('images/icons/twitter.svg')}}"> Twitter profil</label>
                                <input type="text" name="twitter" id="twitter" class="form-control" placeholder="Unesite link službenog twitter profila" value="{{ $staff->twitter }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="instagram"><img class="flow-icons-013" src="{{asset('images/icons/instagram.svg')}}"> Instagram profil</label>
                                <input type="text" name="instagram" id="instagram" class="form-control" placeholder="Unesite link službenog instagram profila" value="{{ $staff->instagram }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="youtube"><img class="flow-icons-013" src="{{asset('images/icons/youtube.svg')}}"> YouTUBE pofil</label>
                                <input type="text" name="youtube" id="youtube" class="form-control" placeholder="Unesite link službenog YouTUBE kanala" value="{{ $staff->youtube }}">
                            </div>

                        </div>

                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-youtube-play"></i> Video prezentacija</h4>
                            </header>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-12">
                                <label for="video"><img class="flow-icons-013" src="{{asset('images/icons/play-button.svg')}}"> Video prezentacija</label>
                                <input type="text" name="video" id="video" class="form-control" placeholder="Unesite link videa (YouTUBE)" value="{{ $staff->video }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveStaffGeneral" data-id="{{ $staff->id }}">Spremi općenito</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Općenito / End -->

                <!-- Tab: Predispozicije -->

                <div role="tabpanel" class="tab-pane fade neaktivno" id="tab-predispozicije">
                    <form id="editStaffStatus" role="form" action="{{ url('/staff/' . $staff->id . '/edit/status') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Osnovne informacije</h4>
                            </header>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label" for="date_of_birth"><i class="fa fa-calendar-o"></i> Datum rođenja</label>
                                <input class="form-control" id="date" name="date_of_birth" placeholder="Izaberite datum rođenja" value="{{ \Carbon\Carbon::parse($staff->date_of_birth)->format('m/d/Y')  }}">
                            </div>

                            <div class="form-group col-md-8">
                                <label for="requested_club"><img class="flow-icons-013" src="{{asset('images/icons/klubovi-icon.svg')}}"> Klub</label>
                                <div class="input-group">
                                    <select class="form-control" id="requested_club" name="requested_club">
                                        <option selected disabled>Izberite trenutni klub sportskog kadra</option>
                                        @foreach($clubs as $club)
                                            <option value="{{ $club->id }}" {{ $staff->requested_club == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-addon">ili</span>
                                    <input type="text" name="club_name" id="club_name" class="form-control" placeholder="Unesite naziv kluba" value="{{ $staff->club_name }}">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="education"><img class="flow-icons-013" src="{{ asset('images/icons/graduation-cap.svg') }}"> Edukacija</label>
                                <input type="text" name="education" id="education" class="form-control" placeholder="Unesite edukaciju" value="{{ $staff->education }}">
                            </div>
                        </div>
                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Dodatne informacije</h4>
                            </header>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="additional_education"><img class="flow-icons-013" src="{{ asset('images/icons/graduation-cap.svg') }}"> Dodatno obrazovanje</label>
                                <input type="text" name="additional_education" id="additional_education" class="form-control" placeholder="Unesite dodatno obrazovanje" value="{{ $staff->additional_education }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="number_of_certificates"><img class="flow-icons-013" src="{{ asset('images/icons/certifikat.svg') }}"> Certifikati</label>
                                <input type="number" name="number_of_certificates" id="number_of_certificates" class="form-control" placeholder="Unesite broj certifikata" value="{{ $staff->number_of_certificates }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="number_of_projects"><img class="flow-icons-013" src="{{ asset('images/icons/idea.svg') }}"> Sportski projekti</label>
                                <input type="number" name="number_of_projects" id="number_of_projects" class="form-control" placeholder="Ukupno realizovanih sportskih projekata" value="{{ $staff->number_of_projects }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="work_experience"><img class="flow-icons-013" src="{{ asset('images/icons/zanimanje.svg') }}"> Rad u struci</label>
                                <input type="number" step="0.5" name="work_experience" id="work_experience" class="form-control" placeholder="Dužina rada u struci u godinama" value="{{ $staff->work_experience }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="languages"><img class="flow-icons-013" src="{{ asset('images/icons/savez.svg') }}"> Strani jezici</label>
                                <div class="form-group">
                                    @foreach($languages as $language)
                                        <label class="checkbox checkbox-inline">
                                            <input type="checkbox" name="languages[]" value="{{ $language->id }}" {{ $staff->languages->contains('id', $language->id) ? ' checked' : '' }}> {{ $language->name }}
                                            <span class="checkbox-indicator"></span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Poslovna historija</h4>
                            </header>
                        </div>
                        <div id="dodajHistorijuButtons" class="row">
                            <div id="historijaLista">
                                @if($staff->work_history)
                                    @foreach($staff->work_history as $key => $licnost)
                                        <div class="row historyHover" data-key="{{ $key }}">
                                            <input type="hidden" name="history[{{ $key }}][id]" value="{{ $licnost->id }}">
                                            <div class="izbrisiHistory"><i class="fa fa-times-circle-o"></i></div>
                                            <div class="row form-objavi-klub-01">
                                                <div class="form-group col-md-6">
                                                    <label><img class="flow-icons-013" src="{{asset('images/icons/klubovi-icon.svg')}}"> Sezona</label>
                                                    <input type="text" name="history[{{ $key }}][season]" class="form-control" placeholder="npr. 2015-2016" value="{{ $licnost->season }}">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label><img class="flow-icons-013" src="{{asset('images/icons/klubovi-icon.svg')}}"> Klub</label>
                                                    <input type="text" name="history[{{ $key }}][club]" class="form-control" placeholder="Unesite ime kluba" value="{{ $licnost->club }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary" type="button" id="dodajHistory">Dodaj klub</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveStaffStatus" data-id="{{ $staff->id }}">Spremi predispozicije</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tab: Predispozicije / End -->

                <!-- Tab: Vremeplov -->

                <div role="tabpanel" class="tab-pane fade neaktivno" id="tab-biografija">
                    <form id="editStaffBiography" role="form" action="{{ url('/staff/' . $staff->id . '/edit/biography') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row">
                            <div class="row identitet-style">

                                <div class="col-md-12">

                                    <div class="form-group col-md-12">
                                        <label for="biography"><img class="flow-icons-013" src="{{ asset('images/icons/edit.svg') }}"> Biografija</label>
                                        <textarea class="form-control" rows="20" id="biography" name="biography" maxlength="1050" placeholder="Upišite kratku biografiju sportskog kadra...">{{ $staff->biography }}</textarea>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveStaffBiography" data-id="{{ $staff->id }}">Spremi biografiju</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tab: Vremeplov / End -->

                <!-- Tab: Vitrina -->
                <div role="tabpanel" class="tab-pane fade" id="tab-vitrina">
                    <form id="editStaffTrophies" role="form" action="{{ url('/staff/' . $staff->id . '/edit/trophies') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row">
                            <div class="row form-segment">
                                <header class="card__header">
                                    <h4><i class="fa fa-plus-circle"></i> Unos osvojenog trofeja/nagrade</h4>
                                </header>
                            </div>
                            <div id="nagradeLista">
                                @if($staff->trophies)
                                    @foreach($staff->trophies as $key => $nagrada)
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
                                                    <input type="number" name="nagrada[{{ $key }}][osvajanja]" class="form-control" placeholder="Unesite broj osvajanja trofeja">
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
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveStaffTrophies" data-id="{{ $staff->id }}">Spremi trofeje</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Vitrina / End -->

                <!-- Tab: Foto galerija -->
                <div role="tabpanel" class="tab-pane fade neaktivno" id="tab-galerija">
                    <form id="editStaffGallery" role="form" action="{{ url('/staff/' . $staff->id . '/edit/gallery') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="row dodavanje-slika">
                            <div class="col-md-12 sadrzaj-slike">
                                <p class="dodaj-sliku-naslov">Dodajte slike</p>
                                <p class="dodaj-sliku-call">u Vašu galeriju</p>
                                <label class="btn btn-default btn-file dodaj-sliku-button">
                                    Odaberi slike... <input type="file" class="galerija not-visible" name="galerija[]" accept="image/*" multiple>
                                </label>
                                <div class="info001">
                                    <p class="info-upload-slike">Preporučena dimenzija za vaše
                                        slike:</p>
                                    <p class="info-upload-slike">1920x1080 px</p>
                                </div>
                            </div>
                        </div>


                        <div class="row form-objavi-klub-01" id="galerija_prikaz">
                            @foreach($staff->images as $slika)
                                <div class="album__item col-xs-6 col-sm-3" id="galerija_klub">
                                    <div class="album__item-holder">
                                        <a href="{{asset(config('general.site_paths.staff_gallery') . $slika->image)}}" class="album__item-link mp_gallery">
                                            <figure class="album__thumb">
                                                <img src="{{asset(config('general.site_paths.staff_gallery') . $slika->image)}}" alt="">
                                            </figure>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveStaffGallery" data-id="{{ $staff->id }}">Spremi galeriju</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Foto galerija / End -->

            </div>
            <!-- Single Product Tabbed Content / End -->
        </div>
    </div>
</div>
<div class="row profil-content-b06">
    <!-- Main content -->
    <div class="sidebar col-md-12 overscreen">

        <!-- Single Product Tabbed Content -->
        <div class="product-tabs card card--xlg">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified nav-product-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-opcenito" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i><small>O objektu</small>Općenito</a></li>
                <li role="presentation" class="preslic"><a href="#tab-predispozicije" role="tab" data-toggle="tab"><i class="fa fa-bolt"></i><small>Karakteristike</small>Objekta</a></li>
                <li role="presentation"><a href="#tab-biografija" role="tab" data-toggle="tab"><i class="fa fa-history"></i><small>Vremeplov</small>Objekta</a></li>
                @if($object->type->type == 'Balon')
                    <li role="presentation"><a href="#tab-tereni" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-window-restore"></i><small>Sale i</small>Tereni</a></li>
                    <li role="presentation"><a href="#tab-cjenovnik-balon" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-database"></i><small>Cjenovnik</small>Usluga</a></li>
                @endif
                @if($object->type->type == 'Skijalište')
                    <li role="presentation"><a href="#tab-staze" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-road"></i><small>Staze i</small>Liftovi</a></li>
                    <li role="presentation"><a href="#tab-cjenovnik-skijaliste" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-database"></i><small>Cjenovnik</small>Usluga</a></li>
                @endif
                <li role="presentation"><a href="#tab-galerija" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><small>Foto</small>Galerija</a></li>
            </ul>

            <div class="row form-segment">
                <div class="col-md-12" id="editObjectServerErrors">
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
                    <form id="editObjectGeneral" role="form" action="{{ url('/objects/' . $object->id . '/edit/general') }}" method="POST" enctype="multipart/form-data" >
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="latitude" id="latitude" value="{{ $object->latitude }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $object->longitude }}">
                        <div class="row">
                            <div class="row identitet-style">
                                <div class="col-md-6 objavi-klub-logo-setup">
                                    <div class="col-md-7">
                                        <div class="alc-staff__photo">
                                            <img class="slika-upload-klub" id="slika_upload_klub" src="{{asset(config('general.site_paths.object_images') . $object->image)}}" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-5 sadrzaj-slike">
                                        <p class="dodaj-sliku-naslov klub-a1">Slika profila</p>
                                        <p class="dodaj-sliku-call">Identitet objekta</p>
                                        <label class="btn btn-default btn-file dodaj-sliku-button">
                                            Odaberi sliku... <input type="file" id="slikaprof" name="image" class="not-visible" onchange="previewFile('#slikaprof','#slika_upload_klub',2048, 2048, 600, 800)">
                                        </label>
                                        <div class="info001">
                                            <p class="info-upload-slike">Preporučene dimenzije za sliku:</p>
                                            <p class="info-upload-slike">Minimalno: 800x600 px</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group col-md-12">
                                        <label for="name"><img class="flow-icons-013" src="{{asset('assets/images/icons/edit.svg')}}"> Naziv objekta*</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Unesite puni naziv objekta" value="{{ $object->name }}">
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
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $object->regions->get('continent') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="country"><img class="flow-icons-013" src="{{asset('images/icons/earth.svg')}}"> Država*</label>
                                    <select name="country" class="form-control" id="country" {{ (old('country') || $object->regions->has('country')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite državu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Country')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $object->regions->get('country') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="province"><img class="flow-icons-013" src="{{asset('images/icons/map.svg')}}"> Pokrajina*</label>
                                    <select name="province" class="form-control" id="province" {{ (old('province') || $object->regions->has('province')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite pokrajinu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Province')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $object->regions->get('province') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="region"><img class="flow-icons-013" src="{{asset('images/icons/placeholder.svg')}}"> Regija*</label>
                                    <select name="region" class="form-control" id="region" {{ (old('region') || $object->regions->has('region')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite regiju</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Region')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $object->regions->get('region') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="municipality"><img class="flow-icons-013" src="{{asset('images/icons/opcina.svg')}}"> Općina*</label>
                                    <select name="municipality" class="form-control" id="municipality" {{ (old('municipality') || $object->regions->has('municipality')) ? '' : 'disabled' }}>
                                        <option selected disabled>Izaberite općinu</option>
                                        @foreach($regions as $region)
                                            @if($region->region_type->type == 'Municipality')
                                                <option data-parent="{{ $region->region_parent }}" value="{{ $region->id }}" {{ $object->regions->get('municipality') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="city"><img class="flow-icons-013" src="{{asset('images/icons/small-calendar.svg')}}"> Mjesto/Grad*</label>
                                    <input name="city" class="form-control" placeholder="Unesite mjesto sportskog kadra" onfocus="initAutocomplete()" value="{{ $object->city }}">
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
                                <input type="text" name="facebook" id="facebook" class="form-control" placeholder="Unesite link službene facebook stranice" value="{{ $object->facebook }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="twitter"><img class="flow-icons-013" src="{{asset('images/icons/twitter.svg')}}"> Twitter profil</label>
                                <input type="text" name="twitter" id="twitter" class="form-control" placeholder="Unesite link službenog twitter profila" value="{{ $object->twitter }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="instagram"><img class="flow-icons-013" src="{{asset('images/icons/instagram.svg')}}"> Instagram profil</label>
                                <input type="text" name="instagram" id="instagram" class="form-control" placeholder="Unesite link službenog instagram profila" value="{{ $object->instagram }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="youtube"><img class="flow-icons-013" src="{{asset('images/icons/youtube.svg')}}"> YouTUBE pofil</label>
                                <input type="text" name="youtube" id="youtube" class="form-control" placeholder="Unesite link službenog YouTUBE kanala" value="{{ $object->youtube }}">
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveObjectGeneral" data-id="{{ $object->id }}">Spremi općenito</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Općenito / End -->

                <!-- Tab: Predispozicije -->
                <div role="tabpanel" class="tab-pane fade" id="tab-predispozicije">
                    <form id="editObjectStatus" role="form" action="{{ url('/objects/' . $object->id . '/edit/status') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Osnovne informacije</h4>
                            </header>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label" for="established_in"><i class="fa fa-calendar-o"></i> Datum otvorenja</label>
                                <input class="form-control pickDate" id="established_in" name="established_in" placeholder="Unesite datum otvorenja" value="{{ \Carbon\Carbon::parse($object->established_in)->format('m/d/Y') }}"/>
                            </div>

                            @foreach($inputs as $input)
                                @if($input->group == 'g')
                                    <div class="form-group col-md-4">
                                        <label for="{{ $input->name }}"><img class="flow-icons-013" src="{{ asset('images/icons/' . $input->icon) }}"> {{ $input->label }}</label>
                                        @if($input->type == 'input')
                                            <input type="text" name="{{ $input->name }}" id="{{ $input->name }}" class="form-control" placeholder="{{ $input->placeholder }}" value="{{ $object->object_data_general['data'][$input->name] }}">
                                        @elseif($input->type == 'select')
                                            <select name="{{ $input->name }}" id="{{ $input->name }}" class="form-control">
                                                <option selected disabled>{{ $input->default }}</option>
                                                @foreach($input->options as $option)
                                                    @if($option == 'Da')
                                                        <option value="1" {{ $object->object_data_general['data'][$input->name] === 1 ? 'selected' : '' }}>{{ $option }}</option>
                                                    @elseif($option == 'Ne')
                                                        <option value="0" {{ $object->object_data_general['data'][$input->name] === 0 ? 'selected' : '' }}>{{ $option }}</option>
                                                    @else
                                                        <option value="{{ $option }}" {{ $object->object_data_general['data'][$input->name] == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Osobine objekta</h4>
                            </header>
                        </div>
                        <div class="row">

                            @foreach($inputs as $input)
                                @if($input->group == 'a')
                                    <div class="form-group col-md-4">
                                        <label for="{{ $input->name }}"><img class="flow-icons-013" src="{{ asset('images/icons/' . $input->icon) }}"> {{ $input->label }}</label>
                                        @if($input->type == 'input')
                                            <input type="text" name="{{ $input->name }}" id="{{ $input->name }}" class="form-control" placeholder="{{ $input->placeholder }}" value="{{ $object->object_data_additional['data'][$input->name] }}">
                                        @elseif($input->type == 'select')
                                            <select name="{{ $input->name }}" id="{{ $input->name }}" class="form-control">
                                                <option selected disabled>{{ $input->default }}</option>
                                                @foreach($input->options as $option)
                                                    @if($option == 'Da')
                                                        <option value="1" {{ $object->object_data_additional['data'][$input->name] === 1 ? 'selected' : '' }}>{{ $option }}</option>
                                                    @elseif($option == 'Ne')
                                                        <option value="0" {{ $object->object_data_additional['data'][$input->name] === 0 ? 'selected' : '' }}>{{ $option }}</option>
                                                    @else
                                                        <option value="{{ $option }}" {{ $object->object_data_additional['data'][$input->name] == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                @endif
                            @endforeach

                        </div>
                        <div class="row form-objavi-klub-01">
                            <header class="card__header">
                                <h4><i class="fa fa-info-circle"></i> Standardi po Sve Za Sportu</h4>
                            </header>
                        </div>
                        <div class="row">
                            @foreach($inputs as $input)
                                @if($input->group == 's')
                                    <div class="form-group col-md-3">
                                        <label for="{{ $input->name }}">{{ $input->label }}</label>
                                        @if($input->type == 'input')
                                            <input type="text" name="{{ $input->name }}" id="{{ $input->name }}" class="form-control" placeholder="{{ $input->placeholder }}" value="{{ $object->object_data_szs['data'][$input->name] }}">
                                        @elseif($input->type == 'select')
                                            <select name="{{ $input->name }}" id="{{ $input->name }}" class="form-control">
                                                <option selected disabled>{{ $input->default }}</option>
                                                @foreach($input->options as $option)
                                                    @if($option == 'Da')
                                                        <option value="1" {{ $object->object_data_szs['data'][$input->name] === 1 ? 'selected' : '' }}>{{ $option }}</option>
                                                    @elseif($option == 'Ne')
                                                        <option value="0" {{ $object->object_data_szs['data'][$input->name] === 0 ? 'selected' : '' }}>{{ $option }}</option>
                                                    @else
                                                        <option value="{{ $option }}" {{ $object->object_data_szs['data'][$input->name] == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveObjectStatus" data-id="{{ $object->id }}">Spremi karakteristike</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Predispozicije / End -->

                <!-- Tab: Vremeplov -->
                <div role="tabpanel" class="tab-pane fade" id="tab-biografija">
                    <form id="editObjectHistory" role="form" action="{{ url('/objects/' . $object->id . '/edit/history') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="row">
                            <div class="row identitet-style">
                                <div class="col-md-12">
                                    <div class="form-group col-md-12">
                                        <label for="history"><img class="flow-icons-013" src="{{asset('assets/images/icons/edit.svg')}}"> Vremeplov objekta</label>
                                        <textarea name="history" class="form-control" rows="20" id="history" maxlength="1050" placeholder="Upišite ukratko informacije vezane za historijat vašeg objekta i njegovu tradiciju...">{{ $object->history }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveObjectHistory" data-id="{{ $object->id }}">Spremi historiju</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab: Vremeplov / End -->

            @if($object->type->type == 'Balon')
                <!-- Tab: Sale i Tereni -->
                    <div role="tabpanel" class="tab-pane fade" id="tab-tereni">
                        <form id="editObjectBalonFields" role="form" action="{{ url('/objects/' . $object->id . '/edit/balon-fields') }}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="row">
                                <div class="row form-segment">
                                    <header class="card__header">
                                        <h4><i class="fa fa-plus-circle"></i> Unos terena/sale</h4>
                                    </header>
                                </div>
                                <div id="tereniLista">
                                    @if($object->fields)
                                        @foreach($object->fields as $key => $teren)
                                            <div class="row terenHover" data-key="{{ $key }}">
                                                <input type="hidden" name="tereni[{{ $key }}][id]" value="{{ $teren->id }}">
                                                <div class="izbrisiTeren"><i class="fa fa-times-circle-o"></i></div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-12">
                                                        <label for="name">Naziv ili oznaka terena/sale</label>
                                                        <input type="text" name="tereni[{{ $key }}][name]" id="name{{ $key }}" class="form-control" placeholder="Unesite naziv ili oznaku  terena" value="{{ $teren->name }}">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="sport">Sport</label>
                                                        <div class="form-group">
                                                            <label class="checkbox checkbox-inline">
                                                                <input type="checkbox" name="tereni[{{ $key }}][sports][]" value="Nogomet" {{ (is_array(explode(',', $teren->sports)) and in_array('Nogomet', explode(',', $teren->sports))) ? ' checked' : '' }}> Nogomet
                                                                <span class="checkbox-indicator"></span>
                                                            </label>
                                                            <label class="checkbox checkbox-inline">
                                                                <input type="checkbox" name="tereni[{{ $key }}][sports][]" value="Mali nogomet" {{ (is_array(explode(',', $teren->sports)) and in_array('Mali nogomet', explode(',', $teren->sports))) ? ' checked' : '' }}> Mali nogomet
                                                                <span class="checkbox-indicator"></span>
                                                            </label>
                                                            <label class="checkbox checkbox-inline">
                                                                <input type="checkbox" name="tereni[{{ $key }}][sports][]" value="Košarka" {{ (is_array(explode(',', $teren->sports)) and in_array('Košarka', explode(',', $teren->sports))) ? ' checked' : '' }}> Košarka
                                                                <span class="checkbox-indicator"></span>
                                                            </label>
                                                            <label class="checkbox checkbox-inline ">
                                                                <input type="checkbox" name="tereni[{{ $key }}][sports][]" value="Tenis" {{ (is_array(explode(',', $teren->sports)) and in_array('Tenis', explode(',', $teren->sports))) ? ' checked' : '' }}> Tenis
                                                                <span class="checkbox-indicator"></span>
                                                            </label>
                                                            <label class="checkbox checkbox-inline ">
                                                                <input type="checkbox" name="tereni[{{ $key }}][sports][]" value="Stoni tenis" {{ (is_array(explode(',', $teren->sports)) and in_array('Stoni tenis', explode(',', $teren->sports))) ? ' checked' : '' }}> Stoni tenis
                                                                <span class="checkbox-indicator"></span>
                                                            </label>
                                                            <label class="checkbox checkbox-inline ">
                                                                <input type="checkbox" name="tereni[{{ $key }}][sports][]" value="Odbojka" {{ (is_array(explode(',', $teren->sports)) and in_array('Odbojka', explode(',', $teren->sports))) ? ' checked' : '' }}> Odbojka
                                                                <span class="checkbox-indicator"></span>
                                                            </label>
                                                            <label class="checkbox checkbox-inline ">
                                                                <input type="checkbox" name="tereni[{{ $key }}][sports][]" value="Badminton" {{ (is_array(explode(',', $teren->sports)) and in_array('Badminton', explode(',', $teren->sports))) ? ' checked' : '' }}> Badminton
                                                                <span class="checkbox-indicator"></span>
                                                            </label>
                                                            <label class="checkbox checkbox-inline ">
                                                                <input type="checkbox" name="tereni[{{ $key }}][sports][]" value="Univerzalan teren" {{ (is_array(explode(',', $teren->sports)) and in_array('Univerzalan teren', explode(',', $teren->sports))) ? ' checked' : '' }}> Univerzalan teren
                                                                <span class="checkbox-indicator"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-6">
                                                        <label for="type_of_field">Vrsta podloge</label>
                                                        <select class="form-control" name="tereni[{{ $key }}][type_of_field]" id="type_of_field{{ $key }}">
                                                            <option value="" disabled="" {{ $teren->type_of_field == '' ? 'selected' : '' }}>Izaberite podlogu terena</option>
                                                            <option value="Parket" {{ $teren->type_of_field == 'Parket' ? 'selected' : '' }}>Parket</option>
                                                            <option value="Bitumen" {{ $teren->type_of_field == 'Bitumen' ? 'selected' : '' }}>Bitumen</option>
                                                            <option value="Plastika" {{ $teren->type_of_field == 'Plastika' ? 'selected' : '' }}>Plastika</option>
                                                            <option value="Guma" {{ $teren->type_of_field == 'Guma' ? 'selected' : '' }}>Guma</option>
                                                            <option value="Zemlja" {{ $teren->type_of_field == 'Zemlja' ? 'selected' : '' }}>Zemlja</option>
                                                            <option value="Ostalo" {{ $teren->type_of_field == 'Ostalo' ? 'selected' : '' }}>Ostalo</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="capacity">Kapacitet korisnika</label>
                                                        <input type="number" name="tereni[{{ $key }}][capacity]" id="capacity{{ $key }}" class="form-control" placeholder="Unesite maksimalan broj korisnika" value="{{ $teren->capacity }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="public_capacity">Kapacitet gledaoca</label>
                                                        <input type="number" name="tereni[{{ $key }}][public_capacity]" id="public_capacity{{ $key }}" class="form-control" placeholder="Unesite maksimalan broj gledaoca" value="{{ $teren->public_capacity }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="length">Dužina terena (m)</label>
                                                        <input type="number" name="tereni[{{ $key }}][length]" id="length{{ $key }}" class="form-control" placeholder="Unesite dužinu terena u metrima" value="{{ $teren->length }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="width">Širina terena (m)</label>
                                                        <input type="number" name="tereni[{{ $key }}][width]" id="width{{ $key }}" class="form-control" placeholder="Unesite širinu terena u metrima" value="{{ $teren->width }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary" type="button" id="dodajTeren">Dodaj teren/salu</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-group--submit col-md-12">
                                    <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveObjectBalonFields" data-id="{{ $object->id }}">Spremi terene/sale</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Tab: Sale i Tereni / End -->
                    <!-- Tab: Cjenovnik -->
                    <div role="tabpanel" class="tab-pane fade" id="tab-cjenovnik-balon">
                        <form id="editObjectBalonPrices" role="form" action="{{ url('/objects/' . $object->id . '/edit/balon-prices') }}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="row">
                                <div class="row form-segment">
                                    <header class="card__header">
                                        <h4><i class="fa fa-plus-circle"></i> Dodavanje cijene</h4>
                                    </header>
                                </div>
                                <div id="balonCjenovnikLista">
                                    @if($object->prices)
                                        @foreach($object->prices as $key => $cjenovnik)
                                            <div class="row balonCjenovnikHover" data-key="{{ $key }}">
                                                <input type="hidden" name="cjenovnik[{{ $key }}][id]" value="{{ $cjenovnik->id }}">
                                                <div class="izbrisiBalonCjenovnik"><i class="fa fa-times-circle-o"></i></div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-12">
                                                        <label for="sport{{ $key }}">Sport</label>
                                                        <select class="form-control" id="sport{{ $key }}" name="cjenovnik[{{ $key }}][sport]">
                                                            <option disabled="" {{ $cjenovnik->sport == '' ? 'selected' : '' }}>Izaberite sport</option>
                                                            <option value="Nogomet" {{ $cjenovnik->sport == 'Nogomet' ? 'selected' : '' }}>Nogomet</option>
                                                            <option value="Mali nogomet" {{ $cjenovnik->sport == 'Mali nogomet' ? 'selected' : '' }}>Mali nogomet</option>
                                                            <option value="Košarka" {{ $cjenovnik->sport == 'Košarka' ? 'selected' : '' }}>Košarka</option>
                                                            <option value="Tenis" {{ $cjenovnik->sport == 'Tenis' ? 'selected' : '' }}>Tenis</option>
                                                            <option value="Stoni tenis" {{ $cjenovnik->sport == 'Stoni tenis' ? 'selected' : '' }}>Stoni tenis</option>
                                                            <option value="Odbojka" {{ $cjenovnik->sport == 'Odbojka' ? 'selected' : '' }}>Odbojka</option>
                                                            <option value="Badminton" {{ $cjenovnik->sport == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                                            <option value="Univerzalan teren" {{ $cjenovnik->sport == 'Univerzalan teren' ? 'selected' : '' }}>Univerzalan teren</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="name{{ $key }}">Naziv/oznaka terena</label>
                                                        <input type="text" name="cjenovnik[{{ $key }}][name]" id="name{{ $key }}" class="form-control" placeholder="Unesite naziv ili oznaku terena" value="{{ $cjenovnik->name }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="price_per_hour{{ $key }}">Cijena termina (60 min)</label>
                                                        <input type="number" name="cjenovnik[{{ $key }}][price_per_hour]" id="price_per_hour{{ $key }}" class="form-control" placeholder="Unesite cijenu u KM" value="{{ $cjenovnik->price_per_hour }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary" type="button" id="balonDodajCjenovnik">Dodaj cjenovnik</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-group--submit col-md-12">
                                    <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveObjectBalonPrices" data-id="{{ $object->id }}">Spremi cjenovnik</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Tab: Cjenovnik / End -->
            @endif

            @if($object->type->type == 'Skijalište')
                <!-- Tab: Staze -->
                    <div role="tabpanel" class="tab-pane fade" id="tab-staze">
                        <form id="editObjectSkiTracks" role="form" action="{{ url('/objects/' . $object->id . '/edit/ski-tracks') }}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="row">
                                <div class="row form-segment">
                                    <header class="card__header">
                                        <h4><i class="fa fa-plus-circle"></i> Unos staze skijališta</h4>
                                    </header>
                                </div>
                                <div id="stazeLista">
                                    @if($object->tracks)
                                        @foreach($object->tracks as $key => $staza)
                                            <div class="row stazeHover" data-key="{{ $key }}">
                                                <input type="hidden" name="staze[{{ $key }}][id]" value="{{ $staza->id }}">
                                                <div class="izbrisiStazu"><i class="fa fa-times-circle-o"></i></div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-12">
                                                        <label for="name{{ $key }}">Naziv staze</label>
                                                        <input type="text" name="staze[{{ $key }}][name]" id="name{{ $key }}" class="form-control" placeholder="Unesite naziv staze" value="{{ $staza->name }}">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="level{{ $key }}">Težina staze</label>
                                                        <select class="form-control" id="level{{ $key }}" name="staze[{{ $key }}][level]">
                                                            <option value="" disabled="" {{ $staza->level == '' ? 'selected' : '' }}>Odaberite</option>
                                                            <option value="Lahko" {{ $staza->level == 'Lahko' ? 'selected' : '' }}>Lahko</option>
                                                            <option value="Srednje" {{ $staza->level == 'Srednje' ? 'selected' : '' }}>Srednje</option>
                                                            <option value="Teško" {{ $staza->level == 'Teško' ? 'selected' : '' }}>Teško</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="length{{ $key }}">Dužina staze</label>
                                                        <input type="number" name="staze[{{ $key }}][length]" id="length{{ $key }}" class="form-control" placeholder="Unesite dužinu staze u metrima" value="{{ $staza->length }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="time{{ $key }}">Trajanje spusta</label>
                                                        <input type="number" name="staze[{{ $key }}][time]" id="time{{ $key }}" class="form-control" placeholder="Unesite vrijeme trajanja spusta u minutama" value="{{ $staza->time }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="start_point{{ $key }}">Tačka polazišta (m)</label>
                                                        <input type="number" name="staze[{{ $key }}][start_point]" id="start_point{{ $key }}" class="form-control" placeholder="Unesite nadmorsku visinu tačke polazišta" value="{{ $staza->start_point }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="end_point{{ $key }}">Tačka izlaza (m)</label>
                                                        <input type="number" name="staze[{{ $key }}][end_point]" id="end_point{{ $key }}" class="form-control" placeholder="Unesite nadmorsku visinu tačke izlaza" value="{{ $staza->end_point }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary" type="button" id="dodajStazu">Dodaj stazu</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-group--submit col-md-12">
                                    <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveObjectSkiTracks" data-id="{{ $object->id }}">Spremi staze</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Tab: Staze / End -->
                    <!-- Tab: Cjenovnik -->
                    <div role="tabpanel" class="tab-pane fade" id="tab-cjenovnik-skijaliste">
                        <form id="editObjectSkiPrices" role="form" action="{{ url('/objects/' . $object->id . '/edit/ski-prices') }}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="row">
                                <div class="row form-segment">
                                    <header class="card__header">
                                        <h4><i class="fa fa-plus-circle"></i> Dodavanje cijene</h4>
                                    </header>
                                </div>
                                <div id="skiCjenovnikLista">
                                    @if($object->prices)
                                        @foreach($object->prices as $key => $cjenovnik)
                                            <div class="row skiCjenovnikHover" data-key="{{ $key }}">
                                                <input type="hidden" name="cjenovnik[{{ $key }}][id]" value="{{ $cjenovnik->id }}">
                                                <div class="izbrisiSkiCjenovnik"><i class="fa fa-times-circle-o"></i></div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-12">
                                                        <label for="description{{ $key }}">Opis karte</label>
                                                        <input type="text" name="cjenovnik[{{ $key }}][description]" id="description{{ $key }}" class="form-control" placeholder="Unesite opis karte" value="{{ $cjenovnik->description }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="price{{ $key }}">Cijena karte odrasli</label>
                                                        <input type="text" name="cjenovnik[{{ $key }}][price]" id="price{{ $key }}" class="form-control" placeholder="Unesite cijenu u KM" value="{{ $cjenovnik->price }}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-xs-12">
                                                        <label for="price_kids{{ $key }}">Cijena karte djeca</label>
                                                        <input type="text" name="cjenovnik[{{ $key }}][price_kids]" id="price_kids{{ $key }}" class="form-control" placeholder="Unesite cijenu u KM" value="{{ $cjenovnik->price_kids }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary" type="button" id="skiDodajCjenovnik">Dodaj cjenovnik</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-group--submit col-md-12">
                                    <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveObjectSkiPrices" data-id="{{ $object->id }}">Spremi cjenovnik</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Tab: Cjenovnik / End -->
            @endif


            <!-- Tab: Foto galerija -->
                <div role="tabpanel" class="tab-pane fade" id="tab-galerija">
                    <form id="editObjectGallery" role="form" action="{{ url('/objects/' . $object->id . '/edit/gallery') }}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PATCH">
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
                            @foreach($object->images as $slika)
                                <div class="album__item col-xs-6 col-sm-3" id="galerija_klub">
                                    <div class="album__item-holder">
                                        <a href="{{asset(config('general.site_paths.object_gallery') . $slika->image)}}" class="album__item-link mp_gallery">
                                            <figure class="album__thumb">
                                                <img src="{{asset(config('general.site_paths.object_gallery') . $slika->image)}}" alt="">
                                            </figure>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="form-group form-group--submit col-md-12">
                                <button type="submit" class="btn btn-default btn-sm btn-block btn-spasi" id="buttonSaveObjectGallery" data-id="{{ $object->id }}">
                                    Spremi galeriju
                                </button>
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
<div id="addAssociation" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    Dodaj savez
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="addAssociationServerErrors" class="alert alert-danger"><ul></ul></div>
                <form id="addAssociationForm" role="form" enctype="multipart/form-data">
                    <div class="row">
                    <div class="col-md-12">

                        <div class="col-md-7">

                            <div class="alc-staff__photo">
                                <img id="slika-upload" src="{{asset('images/default_avatar.png')}}" alt="">
                            </div>

                        </div>

                        <div class="col-md-5 sadrzaj-slike">

                            <p class="dodaj-sliku-naslov klub-a1"> Logo saveza</p>
                            <p class="dodaj-sliku-call">Identitet saveza</p>
                            <label class="btn btn-default btn-file dodaj-sliku-button">
                                Odaberi logo... <input type="file" id="file_logo_savez" name="image" class="not-visible" onchange="previewFile('#file_logo_savez','#slika-upload', 1024, 1024, 512, 512)">
                            </label>
                            <div class="info001">
                                <p class="info-upload-slike">Preporučene dimenzije za logo:</p>
                                <p class="info-upload-slike">Minimalno: 512x512 px</p>
                                <p class="info-upload-slike">Maksimalno: 1024x1024 px</p>
                            </div>

                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Naziv *</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Unesite naziv">
                            </div>

                            <div class="form-group">
                                <label for="established_in">Datum osnivanja *</label>
                                <input type="date" name="established_in" id="established_in" class="form-control" placeholder="Unesite datum osnivanja" max="{{ date("Y-m-d") }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="president">Predsjednik</label>
                                <input type="text" name="president" id="president" class="form-control" placeholder="Unesite ime i prezime predsjednika saveza">
                            </div>

                            <div class="form-group">
                                <label for="vice_president">Podpredsjednik</label>
                                <input type="text" name="vice_president" id="vice_president" class="form-control" placeholder="Unesite ime i prezime podpredsjednika saveza">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Opis</label>
                        <textarea type="text" name="description" id="description" class="form-control" placeholder="Unesite opis saveza"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="region_id">Država *</label>
                        <select name="region_id" class="form-control" id="region_id">
                            <option selected disabled>Izaberite državu</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="club-type">Tip sporta *</label>
                                <select name="type" class="form-control" id="club-type">
                                    <option value="" selected disabled>Izaberite tip</option>
                                    <option value="1">Sportski klub</option>
                                    <option value="2">Invalidski sportski klub</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sport">Sport *</label>
                                <select name="sport_id" class="form-control" id="sport" disabled>
                                    <option selected disabled>Izaberite sport</option>
                                    @foreach($sports as $sport)
                                        <option data-disabled="{{ $sport->with_disabilities }}" value="{{ $sport->id }}">{{ $sport->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="addAssociationButton" type="button" class="btn btn-primary">Dodaj savez</button>
            </div>
        </div>
    </div>
</div>

<div id="editAssociationServerErrors"></div>
<form id="editAssociationForm" role="form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">

            <div class="col-md-7">

                <div class="alc-staff__photo">
                    <img id="slika-upload1" src="{{ asset(config('general.site_paths.association_images') . $association->image) }}">
                </div>

            </div>

            <div class="col-md-5 sadrzaj-slike">

                <p class="dodaj-sliku-naslov klub-a1"> Logo saveza</p>
                <p class="dodaj-sliku-call">Identitet saveza</p>
                <label class="btn btn-default btn-file dodaj-sliku-button">
                    Odaberi logo... <input type="file" id="file_logo_savez1" name="image" class="not-visible" onchange="previewFile('#file_logo_savez1','#slika-upload1', 1024, 1024, 512, 512)">
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
                <input type="text" name="name" id="name" class="form-control" placeholder="Unesite naziv" value="{{ $association->name }}">
            </div>

            <div class="form-group">
                <label for="established_in">Datum osnivanja *</label>
                <input type="date" name="established_in" id="established_in" class="form-control" placeholder="Unesite datum osnivanja" max="{{ date("Y-m-d") }}" value="{{ \Carbon\Carbon::parse($association->established_in)->format('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="president">Predsjednik</label>
                <input type="text" name="president" id="president" class="form-control" placeholder="Unesite ime i prezime predsjednika saveza" value="{{ $association->president }}">
            </div>

            <div class="form-group">
                <label for="vice_president">Podpredsjednik</label>
                <input type="text" name="vice_president" id="vice_president" class="form-control" placeholder="Unesite ime i prezime podpredsjednika saveza" value="{{ $association->vice_president }}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="description">Opis</label>
        <textarea type="text" name="description" id="description" class="form-control" placeholder="Unesite opis saveza">{{ $association->description }}</textarea>
    </div>

    <div class="form-group">
        <label for="region_id">Država *</label>
        <select name="region_id" class="form-control" id="region_id">
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ $association->region_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="club-type">Tip sporta *</label>
                <select name="type" class="form-control" id="club-type">
                    <option value="1" {{ !$association->sport->with_disabilities ? 'selected' : '' }}>Sportski klub</option>
                    <option value="2" {{ $association->sport->with_disabilities ? 'selected' : '' }}>Invalidski sportski klub</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="sport">Sport *</label>
                <select name="sport_id" class="form-control" id="sport">
                    @foreach($sports as $sport)
                        <option data-disabled="{{ $sport->with_disabilities }}" value="{{ $sport->id }}" {{ $association->sport_id == $sport->id ? 'selected' : '' }}>{{ $sport->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary" id="editAssociationButton" data-id="{{ $association->id }}">Spremi izmjene</button>
        </div>
    </div>
</form>
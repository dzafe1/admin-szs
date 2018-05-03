<div id="editNews" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="news-image">
                <img class="repsponsive-centered-image" src="">
            </div>
            <div class="modal-body">
                <div id="editNewsServerErrors" class="alert alert-danger"><ul></ul></div>
                <form id="editNewsForm" role="form" enctype="multipart/form-data">
                    <div class="row identitet-style">

                        <div class="col-md-12">
                            <div class="sadrzaj-slike">
                                <label class="btn btn-default">
                                    Odaberi sliku... <input type="file" id="vijestSlika" name="slika" class="check-change" style="display: none;">
                                </label>
                                <div class="info001">
                                    <p class="info-upload-slike">Preporučene dimenzije za sliku:</p>
                                    <p class="info-upload-slike">Minimalno: 980x720 px</p>
                                </div>

                            </div>
                        </div>


                        <div class="col-md-12">

                            <div class="form-group col-md-12">
                                <label for="naslov-vijesti"><img class="flow-icons-013" src="{{asset('images/icons/edit.svg')}}"> Naslov vijesti*</label>
                                <input type="text" name="naslov" id="naslov-vijesti" class="form-control check-change" placeholder="Unesite naslov vijesti">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="kategorija-vijesti"><img class="flow-icons-013" src="{{asset('images/icons/edit.svg')}}"> Kategorija vijesti*</label>
                                <select class="form-control check-change" name="kategorija" id="kategorija-vijesti" placeholder="Unesite kategoriju vijesti">
                                    @foreach($vijest_kategorija as $kategorija)
                                        <option value="{{ $kategorija->id }}">{{ $kategorija->naziv }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="form-group col-md-12">
                        <label for="sadrzaj"><img class="flow-icons-013" src="{{asset('images/icons/edit.svg')}}"> Sadržaj*</label>
                        <textarea class="form-control check-change" rows="20" id="sadrzaj" name="sadrzaj"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="tagovi-vijesti"><img class="flow-icons-013" src="{{asset('images/icons/edit.svg')}}"> Tagovi vijesti</label>
                        <input type="text" name="tagovi" id="tagovi-vijesti" class="form-control check-change" placeholder="Unesite tagove vijesti">
                    </div>

                    <div class="row">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="editNewsButton" type="button" class="btn btn-primary" data-id="">Spasi promjene</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('sadrzaj');
</script>
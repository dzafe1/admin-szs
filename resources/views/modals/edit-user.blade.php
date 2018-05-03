<div id="editUser" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="editUserServerErrors" class="alert alert-danger"><ul></ul></div>
                <form id="editUserForm" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Ime *</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Unesite ime korisnika">
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Unesite email korisnika">
                    </div>

                    <div class="form-group">
                        <label for="spol">Spol *</label>
                        <select class="form-control" name="spol" id="spol" placeholder="Unesite spol korisnika">
                            <option value="Žensko">Žensko</option>
                            <option value="Muško">Muško</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address">Adresa</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="Unesite adresu korisnika">
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefon</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Unesite broj telefona korisnika">
                    </div>

                    <div class="form-group">
                        <label>Admin korisnik?</label>
                        <div class="switch">
                            <label>
                                Ne
                                <input type="checkbox" name="isAdmin" id="isAdmin">
                                <span class="lever"></span> Da
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="editUserButton" type="button" class="btn btn-primary" data-id="">Spasi promjene</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@extends('app')

@section('content')

<div class="page-content">
            <!-- Content Header (Page header) -->
            <section class="content-header z-depth-1">
                <div class="header-icon">
                    <i class="fa fa-table"></i>
                </div>
                <div class="header-title">
                    <h1> Pregled neodobrenih klubova</h1>
                    <small>Klubovi</small>
                    <ul class="link hidden-xs">
                        <li><a href="kontrolna-ploča.html"><i class="fa fa-home"></i>Početna</a></li>
                        <li><a href="datatable.html">Pregled neodobrenih klubova</a></li>
                    </ul>
                </div>
            </section>
            <!-- page section -->
            <div class="container-fluid">
                <div class="row">
                    <!-- Data tables -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="szs-alert alert"></div>
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-table fa-lg"></i>
                                <h2>Lista svih neodobrenih klubova</h2>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Naziv</th>
                                            <th>Napravio</th>
                                            <th>Adresa</th>
                                            <th>Tip</th>
                                            <th>Sport</th>
                                            <th>Kategorija</th>
                                            <th>Kreiran</th>
                                            <th>Pogledaj</th>
                                            <th>Edituj</th>
                                            <th>Obriši</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($clubs as $k)
                                            <tr>
                                                <td>{{$k->name}}</td>
                                                <td>{{$k->creator->name}}</td>
                                                <td>{{$k->address or '-'}}</td>
                                                <td>{{$k->sport->with_disabilities ? 'Invalidski sportski klub' : 'Sportski klub'}}</td>
                                                <td>{{$k->sport->name}}</td>
                                                <td>{{$k->category->name}}</td>
                                                <td>{{\Carbon\Carbon::parse($k->created_at)->format('d.m.Y.')}}</td>
                                                <td>
                                                    <a data-id="{{ $k->id }}" class="display-club">
                                                        <i class="fa fa-external-link"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a data-id="{{ $k->id }}" class="edit-club">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a data-id="{{ $k->id }}" class="delete-club">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </td>
                                                @if($k->status == 'waiting')
                                                    <td>NA ČEKANJU</td>
                                                @elseif($k->status == 'refused')
                                                    <td>ODBIJEN</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./row -->
                </div>
                <!-- ./cotainer -->
            </div>
            <!-- ./page-content -->
        </div>

    <!-- Modals -->
    @include('modals.display-club')
    @include('modals.edit-club')
    <!-- Modals End -->
@endsection
@extends('app')

@section('content')

<div class="page-content">
            <!-- Content Header (Page header) -->
            <section class="content-header z-depth-1">
                <div class="header-icon">
                    <i class="fa fa-table"></i>
                </div>
                <div class="header-title">
                    <h1> Pregled korisnika</h1>
                    <small>Korisnici</small>
                    <ul class="link hidden-xs">
                        <li><a href="kontrolna-ploča.html"><i class="fa fa-home"></i>Početna</a></li>
                        <li><a href="datatable.html">Pregled korisnika</a></li>
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
                                <h2>Lista svih korisnika</h2>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Ime i prezime</th>
                                            <th>Napravio</th>
                                            <th>Klub</th>
                                            <th>Tip</th>
                                            <th>Sport</th>
                                            <th>Kreiran</th>
                                            <th>Pogledaj</th>
                                            <th>Edituj</th>
                                            <th>Obriši</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($players as $k)
                                        <tr>
                                            <td>{{$k->firstname}} {{$k->lastname}}</td>
                                            <td>{{$k->user->name}}</td>
                                            <td>{{$k->club_id ? $k->club->name : 'Nije dodjeljen klubu'}}</td>
                                            <td>{{$k->nature->name}}</td>
                                            <td>{{$k->player_type->name}}</td>
                                            <td>{{\Carbon\Carbon::parse($k->created_at)->format('d.m.Y.')}}</td>
                                            <td>
                                                <a data-id="{{ $k->id }}" class="display-player">
                                                    <i class="fa fa-external-link"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a data-id="{{ $k->id }}" class="edit-player">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a data-id="{{ $k->id }}" class="delete-player">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
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
    @include('modals.display-player')
    @include('modals.edit-player')
    <!-- Modals End -->

@endsection
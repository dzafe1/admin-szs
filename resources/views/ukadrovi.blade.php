@extends('app')

@section('content')

    <div class="page-content">
        <!-- Content Header (Page header) -->
        <section class="content-header z-depth-1">
            <div class="header-icon">
                <i class="fa fa-table"></i>
            </div>
            <div class="header-title">
                <h1> Pregled neodobrenih stručnih kadrova</h1>
                <small>Kadrovi</small>
                <ul class="link hidden-xs">
                    <li><a href="kontrolna-ploča.html"><i class="fa fa-home"></i>Početna</a></li>
                    <li><a href="datatable.html">Pregled neodobrenih kadrova</a></li>
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
                            <h2>Lista svih neodobrenih kadrova</h2>
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Ime i prezime</th>
                                        <th>Napravio</th>
                                        <th>Klub</th>
                                        <th>Profesija</th>
                                        <th>Kreiran</th>
                                        <th>Pogledaj</th>
                                        <th>Edituj</th>
                                        <th>Obriši</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($staff as $k)
                                        <tr>
                                            <td>{{$k->firstname}} {{$k->lastname}}</td>
                                            <td>{{$k->user->name}}</td>
                                            <td>{{$k->club_id ? $k->club->name : ($k->club_name ? $k->club_name : 'Nije dodjeljen klubu')}}</td>
                                            <td>{{$k->profession->name}}</td>
                                            <td>{{\Carbon\Carbon::parse($k->created_at)->format('d.m.Y.')}}</td>
                                            <td>
                                                <a data-id="{{ $k->id }}" class="display-staff">
                                                    <i class="fa fa-external-link"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a data-id="{{ $k->id }}" class="edit-staff">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a data-id="{{ $k->id }}" class="delete-staff">
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
    @include('modals.display-staff')
    @include('modals.edit-staff')
    <!-- Modals End -->

@endsection
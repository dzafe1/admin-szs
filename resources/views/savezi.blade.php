@extends('app')

@section('content')
<div class="page-content">
            <!-- Content Header (Page header) -->
            <section class="content-header z-depth-1">
                <div class="header-icon">
                    <i class="fa fa-table"></i>
                </div>
                <div class="header-title">
                    <h1> Pregled saveza</h1>
                    <small>Savezi</small>
                    <ul class="link hidden-xs">
                        <li><a href="{{ url('dashboard') }}"><i class="fa fa-home"></i>Početna</a></li>
                        <li><a href="{{ url('savezi') }}">Pregled saveza</a></li>
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
                                <h2>Lista svih saveza</h2>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Naziv</th>
                                            <th>Država</th>
                                            <th>Godina osnivanja</th>
                                            <th>Predsjednik</th>
                                            <th>Podpredsjednik</th>
                                            <th>Sport</th>
                                            <th>Kreiran</th>
                                            <th>Uredi</th>
                                        </tr>
                                        </thead>
                                        <tbody id="associationsDataTable">
                                        @foreach($associations as $association)
                                        <tr>
                                            <td>{{$association->name}}</td>
                                            <td>{{$association->region->name}}</td>
                                            <td>{{$association->established_in}}</td>
                                            <td>{{$association->president}}</td>
                                            <td>{{$association->vice_president}}</td>
                                            <td>{{$association->sport->name}}</td>
                                            <td>{{$association->created_at}}</td>
                                            <td>
                                                <a data-id="{{ $association->id }}" class="edit-association">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="add-button text-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAssociation"><i class="fa fa-plus"></i> Dodaj savez</button>
                        </div>
                    </div>
                    <!-- ./row -->
                </div>
                <!-- ./cotainer -->
            </div>
            <!-- ./page-content -->
        </div>

    <!-- Modals -->
    @include('modals.add-association', ['countries' => $countries, 'sports' => $sports])
    @include('modals.edit-association')
    <!-- Modals End -->

@endsection('content')
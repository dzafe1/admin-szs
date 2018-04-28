@extends('app')

@section('content')

<div class="page-content">
            <!-- Content Header (Page header) -->
            <section class="content-header z-depth-1">
                <div class="header-icon">
                    <i class="fa fa-table"></i>
                </div>
                <div class="header-title">
                    <h1> Pregled vijesti</h1>
                    <small>Vijesti</small>
                    <ul class="link hidden-xs">
                        <li><a href="kontrolna-ploča.html"><i class="fa fa-home"></i>Početna</a></li>
                        <li><a href="datatable.html">Pregled vijesti</a></li>
                    </ul>
                </div>
            </section>
            <!-- page section -->
            <div class="container-fluid">
                <div class="row">
                    <!-- Data tables -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-table fa-lg"></i>
                                <h2>Lista svih vijesti</h2>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Naziv</th>
                                            <th>Napravio</th>
                                            <th>Tip</th>
                                            <th>Kreirana</th>
                                            <th>Pogledaj</th>
                                            <th>Edituj</th>
                                            <th>Obriši</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($vijesti)
                                            @foreach($vijesti as $vijest)
                                                <tr>
                                                    <td>{{ $vijest->naslov }}</td>
                                                    <td>{{ $vijest->user->name }}</td>
                                                    <td>{{ $vijest->kategorija->naziv }}</td>
                                                    <td>21-02-2017</td>
                                                    <td><i class="fa fa-external-link"></i></td>
                                                    <td><i class="fa fa-edit"></i></td>
                                                    <td><i class="fa fa-times"></i></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <p>Trenutno nepostoji niti jedna vijest.</p>
                                        @endif
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

@endsection
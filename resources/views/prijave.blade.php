@extends('app')

@section('content')

<div class="page-content">
            <!-- Content Header (Page header) -->
            <section class="content-header z-depth-1">
                <div class="header-icon">
                    <i class="fa fa-table"></i>
                </div>
                <div class="header-title">
                    <h1> Pregled prijava</h1>
                    <small>Prijave</small>
                    <ul class="link hidden-xs">
                        <li><a href="kontrolna-ploča.html"><i class="fa fa-home"></i>Početna</a></li>
                        <li><a href="datatable.html">Pregled prijava</a></li>
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
                                <h2>Lista svih prijava</h2>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Prijavio</th>
                                            <th>Email</th>
                                            <th>Komentar</th>
                                            <th>Kreirana</th>
                                            <th>Link</th>
                                            <th>Rješena</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Meho Mehić</td>
                                            <td>meho@mehic.com</td>
                                            <td>Ukro mi ovcu kloc</td>
                                            <td>2011/01/25</td>
                                            <td><i class="fa fa-external-link"></i> </td>
                                            <td><i class="fa fa-check"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Meho Mehić</td>
                                            <td>meho@mehic.com</td>
                                            <td>Ukro mi ovcu kloc</td>
                                            <td>2011/01/25</td>
                                            <td><i class="fa fa-external-link"></i> </td>
                                            <td><i class="fa fa-check"></i></td>
                                        </tr>
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
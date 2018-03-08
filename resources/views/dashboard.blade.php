@extends('app')

@section('content')
<div class="page-content">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="header-icon">
                            <i class="fa fa-tachometer"></i>
                        </div>
                        <div class="header-title">
                            <h1> Kontrolna ploča</h1>
                            <ul class="link hidden-xs">
                                <li><a href="kontrolna-ploča.html"><i class="fa fa-home"></i>Početna</a></li>
                                <li><a href="kontrolna-ploča.html">Kontrolna ploča</a></li>
                            </ul>
                        </div>
                    </section>
                    <!-- page section -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="panel cardbox bg-primary">
                                    <div class="panel-body card-item panel-refresh">
                                        <a class="refresh" href="#">
                                            <span class="fa fa-refresh"></span>
                                        </a> 
                                        <div class="refresh-container"><i class="refresh-spinner fa fa-spinner fa-spin fa-5x"></i></div>
                                        <div class="timer" data-to="780" data-speed="1500">0</div>
                                        <div class="cardbox-icon">
                                            <i class="material-icons">monetization_on</i>
                                        </div>
                                        <div class="card-details">
                                            <h4>Klubovi</h4>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="panel cardbox bg-success">
                                    <div class="panel-body card-item panel-refresh">
                                        <a class="refresh" href="#">
                                            <span class="fa fa-refresh"></span>
                                        </a> 
                                        <div class="refresh-container"><i class="refresh-spinner fa fa-spinner fa-spin fa-5x"></i></div>
                                        <div class="timer" data-to="1285" data-speed="1500">0</div>
                                        <div class="cardbox-icon">
                                            <i class="material-icons">monetization_on</i>
                                        </div>
                                        <div class="card-details">
                                            <h4>Sportisti</h4>
                                            <span>10% Higher than last week</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="panel cardbox bg-warning">
                                    <div class="panel-body card-item panel-refresh">
                                        <a class="refresh2" href="#">
                                            <span class="fa fa-refresh"></span>
                                        </a> 
                                        <div class="refresh-container"><i class="refresh-spinner fa fa-spinner fa-spin fa-5x"></i></div>
                                        <div class="timer" data-to="920" data-speed="1500">0</div>
                                        <div class="cardbox-icon">
                                            <i class="material-icons">monetization_on</i>
                                        </div>
                                        <div class="card-details">
                                            <h4>Objekti</h4>
                                            <span>10% Higher than last week</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="panel cardbox bg-dark">
                                    <div class="panel-body card-item panel-refresh">
                                        <a class="refresh" href="#">
                                            <span class="fa fa-refresh"></span>
                                        </a> 
                                        <div class="refresh-container"><i class="refresh-spinner fa fa-spinner fa-spin fa-5x"></i></div>
                                        <div class="timer" data-to="51" data-speed="1500">0</div>
                                        <div class="cardbox-icon">
                                            <i class="material-icons">monetization_on</i>
                                        </div>
                                        <div class="card-details">
                                            <h4>Vijesti </h4>
                                            <span>10% Higher than last week</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./counter Number -->
                        </div>
                        <!-- ./row -->
                    </div>
                    <!-- ./cotainer -->
                </div>

@endsection
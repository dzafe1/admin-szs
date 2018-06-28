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
                                <li><a href="{{ url('dashboard') }}"><i class="fa fa-home"></i>Početna</a></li>
                                <li><a href="{{ url('dashboard') }}">Kontrolna ploča</a></li>
                            </ul>
                        </div>
                    </section>
                    <!-- page section -->
                    <div class="container-fluid">
                        <div class="row">
                            @foreach($statistics as $statistic)
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="panel cardbox bg-{{ $statistic->bg }}">
                                        <div class="panel-body card-item panel-refresh">
                                            <a class="refresh" href="#">
                                                <span class="fa fa-refresh"></span>
                                            </a>
                                            <div class="refresh-container"><i class="refresh-spinner fa fa-spinner fa-spin fa-5x"></i></div>
                                            <div class="timer" data-to="{{ $statistic->all }}" data-speed="1500">0</div>
                                            <div class="cardbox-icon">
                                                <i class="material-icons">monetization_on</i>
                                            </div>
                                            <div class="card-details">
                                                <h4>{{ $statistic->label }}</h4>
                                                <span>{{ $statistic->sub }} {{ $statistic->sub_label }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- ./counter Number -->
                        </div>
                        <!-- ./row -->
                    </div>
                    <!-- ./cotainer -->
                </div>

@endsection
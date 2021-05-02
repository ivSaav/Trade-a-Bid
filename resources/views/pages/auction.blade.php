@extends('layouts.app')

@inject('helper', \App\Helpers\LbawUtils::class)

@section('content')
    <!-- Chart.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <script src={{ asset("js/edit-auction-modal.js") }} defer></script>
    <script defer src={{ asset("js/auction.js") }}></script>
    <script defer src={{ asset("js/bookmark.js") }}></script>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row m-2">
        <h1>Auction</h1>

        <section class="container-fluid bg-light">
            <div class="row">
                {{-- Product images --}}
                <div id="product-images" class="carousel slide col-md-5" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#product-images" data-bs-slide-to="0" class="active"
                                aria-current="true" aria-label="Thumbnail"></button>
                        @foreach ($auction->genImages('medium') as $img)
                            <button type="button" data-bs-target="#product-images"
                                    data-bs-slide-to="{{$loop->iteration}}"
                                    aria-label="Image {{$loop->iteration}}"></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block m-auto"
                                 src="{{ $auction->getThumbnail('medium') }}"
                                 alt="Auction Thumbnail">
                        </div>

                        @foreach ($auction->genImages('medium') as $img)
                            <div class="carousel-item">
                                <img class="d-block m-auto"
                                     src="{{$img}}"
                                     alt="Auction image">
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#product-images"
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon handle-background" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#product-images"
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon handle-background" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                {{-- Auction information --}}
                <div id="auction-information" class="col-md my-4 d-flex flex-column justify-content-between">
                    {{-- Product information --}}
                    <div class="row" id="product-information">
                        <div class="row">
                            <h2 class="col d-flex order-2 order-sm-1 order-md-2 order-lg-1 align-items-center">
                                {{$auction->title}}
                            </h2>
                            <div
                                class="p-0 justify-content-center justify-content-sm-end justify-content-md-start justify-content-lg-end col-12 col-sm-4 col-md-12 col-lg-4 order-1 order-sm-2 order-md-1 order-lg-2 d-flex">
                                @auth
                                    @if (Auth::id() != $auction->seller_id)
                                        {{-- button for reporting auction (only for the users who did not create it) --}}
                                        <button type="button" class="btn hover-scale" data-bs-toggle="modal"
                                                data-bs-target="#report-modal">
                                            <i class="bi bi-flag-fill text-danger" style="font-size:1.5em;"></i>
                                            <span>Report auction</span>
                                        </button>
                                    @else
                                        {{-- button for editing auction information (only for the user who created it) --}}
                                        <button type="button" class="btn hover-scale" data-bs-toggle="modal"
                                                data-bs-target="#edit-modal">
                                            <i class="bi bi-pencil" style="font-size: 1.5em; text-align: right"></i>
                                        </button>
                                    @endif

                                    {{-- button for bookmarking auction --}}
                                    <button type="button" class="btn hover-scale auction-bookmark">
                                        <i class="bi bi-bookmark-plus" style="font-size: 1.5em; text-align: right"></i>
                                    </button>
                                @endauth
                            </div>

                        </div>
                        <p class="text-overflow-ellipsis">{{$auction->description}}</p>
                    </div>

                    <div class="row">
                        <div
                            class="col-sm-4 col-xl-6 order-sm-2 d-flex flex-column align-items-sm-end justify-content-sm-end mb-4 mb-sm-0 ml-1">
                            <h3 class="d-sm-none">Seller</h3>
                            <a href="{{ route('user_profile', ['username' => $auction->seller->username]) }}"
                               class="text-decoration-none link-dark d-flex align-items-center flex-row-reverse
                               justify-content-end flex-sm-row justify-content-sm-start">
                                <span class="ms-3 ms-sm-0 me-0 me-sm-3">{{$auction->seller->name}}</span>
                                <div class="d-flex p-0 align-self-center" style="width: 40px; height: 40px;">
                                    <img style="border-radius:50%;" width="40" height="40"
                                         src="{{ $auction->seller->getImage('small') }}"
                                         alt="Seller Profile Image">
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-8 col-xl-6 order-sm-1">
                            {{--TODO: THIS--}}
                            @if ($auction->ended)
                                {{-- AUCTION ENDED --}}
                                <h3>Winning Bid</h3>
                                <div class="row">
                                    <div class="col d-flex flex-column">
                                        @if ($auction->current_bid != null)
                                            <h4>{{$helper->formatCurrency($auction->current_bid)}} &phi;</h4>
                                        @else
                                            <h4>No bids were made</h4>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- AUCTION IS OPEN --}}
                                <h3>Bids</h3>
                                <div class="row">
                                    <div class="col d-flex flex-column">
                                        <span>Current bid</span>
                                        @if ($auction->current_bid != null)
                                            <h4>{{$helper->formatCurrency($auction->current_bid)}} &phi;</h4>
                                        @else
                                            <h4>No bids</h4>
                                        @endif
                                    </div>

                                    <div class="col d-flex flex-column">
                                        <span>Next bid starts at</span>
                                        <h4>{{$helper->formatCurrency($auction->next_bid)}} &phi;</h4>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">&phi;</span>
                                    </div>
                                    <input type="number" class="form-control hide-appearence" placeholder="Enter bid"
                                           min={{ number_format($auction->next_bid / 100, 2) }}>
                                    <button class="btn bg-primary text-light" type="button" role="button">Bid</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Auction details --}}
        <section class="container-fluid p-4">
            <div class="row">
                <span class="d-flex align-items-end">
                    <h3 class="m-0 p-0">Auction Details</h3>
                </span>
                <hr class="my-1">

                @if (!$auction->ended)
                    @include("partials.auction_detail", ["key" => "Closes", "value" => $auction->end_date->diffForHumans(), "subgroup" => true])
                @endif

                @include("partials.auction_detail", ["key" => "Duration", "value" => $auction->end_date->diffForHumans($auction->start_date), "subgroup" => false])
                @include("partials.auction_detail", ["key" => "Start Date", "value" => $auction->start_date, "subgroup" => false])
                @include("partials.auction_detail", ["key" => "End Date", "value" => $auction->end_date, "subgroup" => false])
                @include("partials.auction_detail", ["key" => "Bidders", "value" => $auction->n_bidders . " different bidders", "subgroup" => true])
                @include("partials.auction_detail", ["key" => "Total Bids", "value" => $auction->n_bids . " bids", "subgroup" => false])
                @include("partials.auction_detail", ["key" => "Starting Bid", "value" => $helper->formatCurrency($auction->starting_bid) . " φ", "subgroup" => true])
                @include("partials.auction_detail", ["key" => "Bid Increment", "value" => $auction->getIncrementString(), "subgroup" => false])
            </div>
        </section>


        {{-- Auction details --}}
        <section class="container-fluid p-4">
            <div class="row">
            <span class="d-flex align-items-end">
                <h3 class="m-0 p-0">Auction Details</h3>
            </span>
                <hr class="my-1">

                @if (!$auction->ended)
                    @include("partials.auction_detail", ["key" => "Closes", "value" => $auction->end_date->diffForHumans(), "subgroup" => true])
                @endif

                @include("partials.auction_detail", ["key" => "Duration", "value" => $auction->end_date->diffForHumans($auction->start_date), "subgroup" => false])
                @include("partials.auction_detail", ["key" => "Start Date", "value" => $auction->start_date, "subgroup" => false])
                @include("partials.auction_detail", ["key" => "End Date", "value" => $auction->end_date, "subgroup" => false])
                @include("partials.auction_detail", ["key" => "Bidders", "value" => $auction->n_bidders . " different bidders", "subgroup" => true])
                @include("partials.auction_detail", ["key" => "Total Bids", "value" => $auction->n_bids . " bids", "subgroup" => false])
                @include("partials.auction_detail", ["key" => "Starting Bid", "value" => $helper->formatCurrency($auction->starting_bid) . " φ", "subgroup" => true])
                @include("partials.auction_detail", ["key" => "Bid Increment", "value" => $auction->getIncrementString(), "subgroup" => false])
            </div>
        </section>

        {{-- Bid history --}}
        @if ($auction->has_bids)
            <section class="container-fluid p-4">
                <div class="row d-flex flex-row">
                    <span class="d-flex align-items-end">
                        <h3 class="m-0 p-0">Bid History</h3>
                        <a class="ms-2" style="font-size: smaller;" href={{ route('auction_details', ['id' => $auction->id]) }}>See more</a>
                    </span>

                    <hr class="my-1">

                    {{-- Bid history chart --}}
                    <div class="row col-lg-7 order-lg-2 d-flex flex-column justify-content-center">
                        <canvas class="mt-4" id="bid-history-chart"></canvas>
                    </div>

                    {{-- Bid history table --}}
                    <div class="row col-lg-5 order-lg-1">
                        <table id="bid-history" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">Bidder</th>
                                <th scope="col">Bid</th>
                                <th scope="col">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($auction->bids()->orderBy('date', 'desc')->limit(6)->get() as $bid)
                                @include("partials.bid_table_entry", ["name" => "Y**p", "bid" => $bid->value, "time" => $bid->date->diffForHumans()])
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif

        {{-- Edit modal --}}
        <div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="modalLable" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" id="edit-form" method="post"
                      action="{{ route("auction_edit", [ "id" => $auction->id ]) }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLable">Edit Auction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div>

                            <div class="row">
                                {{-- Auction name --}}
                                <div class="form-group col-md-12 mt-3">
                                    <label for="inputName" class="sr-only">Auction Name</label>
                                    <input type="text" id="inputName" value="{{ $auction->title }}" class="form-control"
                                           name="title">
                                </div>

                                {{-- Auction description --}}
                                <div class="form-group col-md-12 mt-3">
                                    <label for="inputDescription" class="sr-only">Auction Description</label>
                                    <textarea class="form-control" rows="4"
                                              id="inputDescription" name="description">
                                        {{ $auction->description }}
                                    </textarea>
                                </div>

                                {{-- Starting on --}}
                                <div class="form-group col-sm-12 mt-3">
                                    <label for="startDate" class="sr-only">Starting on</label>
                                    <div class="input-group">
                                        <input type="date" id="startDate" class="form-control"
                                               name="start_date" value={{ $auction->end_date }}>
                                        <input type="time" id="startTime" class="form-control"
                                               name="start_time" value="23:59">
                                    </div>
                                </div>

                                {{-- Ending on --}}
                                <div class="form-group col-sm-12 mt-3">
                                    <label for="endDate" class="sr-only">Ending on</label>
                                    <div class="input-group">
                                        <input type="date" id="endDate" class="form-control" name="end_date"
                                               value={{ $auction->end_date }}>
                                        <input type="time" id="endTime" class="form-control" name="end_time"
                                               value="23:59">
                                    </div>
                                </div>

                                {{-- Starting bid --}}
                                <div class="form-group col-sm-6 mt-3">
                                    <label for="inputValue" class="sr-only">Starting Bid</label>
                                    <div class="input-group">
                                        <input type="text" id="inputValue" class="form-control" placeholder="0.00"
                                               aria-label="Euro amount (with dot and two decimal places)"
                                               name="starting_bid" value={{ $auction->starting_bid }}>
                                        <span class="input-group-text">&phi;</span>
                                    </div>
                                </div>

                                {{-- Increment --}}
                                <div class="form-group col-sm-6 mt-3">
                                    <label for="inputIncr" class="sr-only">Increment</label>
                                    <div class="input-group">
                                        <input type="text" id="inputIncr" class="form-control" placeholder="0.00"
                                               aria-label="Phi amount (with dot and two decimal places)"
                                               name="increment" value={{ $auction->increment_fixed }}>
                                        <span class="input-group-text">&phi;</span>
                                    </div>
                                </div>

                                <div class="d-flex flex-row mt-3">
                                    {{-- Category --}}
                                    <div class="col-sm-6">
                                        <label for="inputCategory">Category</label>
                                        <div class="input-group mb-3 col-sm-6">
                                            <select class="form-select" id="inputCategory" form="edit-form"
                                                    name="category">f
                                                <option selected>Choose...</option>
                                                <option
                                                    value="Games" {{ $auction->category == 'Games' ? "selected" : "" }}>
                                                    Games
                                                </option>
                                                <option
                                                    value="Software" {{ $auction->category == 'Software' ? "selected" : "" }}>
                                                    Software
                                                </option>
                                                <option
                                                    value="eBook" {{ $auction->category == 'eBook' ? "selected" : "" }}>
                                                    eBook
                                                </option>
                                                <option
                                                    value="Music" {{ $auction->category == 'Music' ? "selected" : "" }}>
                                                    Music
                                                </option>
                                                <option
                                                    value="Skins" {{ $auction->category == 'Skins' ? "selected" : "" }}>
                                                    Skins
                                                </option>
                                                <option
                                                    value="Other" {{ $auction->category == 'Other' ? "selected" : "" }}>
                                                    Other
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- NSFW --}}
                                    <div class="ms-3 mt-4 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="switch-nsfw"
                                               name="nsfw" value={{ $auction->nsfw }}>
                                        <label class="form-check-label" for="switch-nsfw">NSFW</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Report modal --}}
        <section class="modal fade" tabindex="-1" role="dialog" id="report-modal">
            <form id="report-form" class="modal-dialog modal-dialog-centered" method="post"
                  action="{{ route("auction_report", [ "id" => $auction->id ]) }}">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Report auction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <select name="report-reason" class="form-select" form="report-form">
                                <option selected>Choose...</option>
                                <option value="1">Fraudaulent auction</option>
                                <option value="2">Improper product pictures</option>
                                <option value="3">Improper auction title</option>
                                <option value="4">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                        <textarea class="form-control" id="report-description" rows="6" name="report-description"
                                  form="report-form"></textarea>
                            <span class="input-group-text text-wrap">Elaborate the reason to report this auction, so we can analyze the case better.</span>
                        </div>
                    </div>

                    <input hidden name="reporter" value={{ Auth::id() }}>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-danger" type="submit">Report</button>
                    </div>
                </div>
            </form>
        </section>
@endsection


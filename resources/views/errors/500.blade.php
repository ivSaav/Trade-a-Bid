@extends("layouts.app")

@section('content')

<section class='big-boy text-center d-flex flex-column justify-content-center'>
    <h1>403</h1>
    <h3>A surprise to be sure, but a pleasant one.<h3>

    <img class="pt-4" src='{{ asset('images/errors/this_is_fine.png') }}'
        style="width: 5em; height: 5em; clip-path: circle(50%);">

</section>

@endsection

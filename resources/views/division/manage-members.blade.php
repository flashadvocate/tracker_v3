@extends('application.base')

@section('content')

    @component ('application.components.division-heading')
        @slot ('icon')
            <a href="{{ route('division', $division->abbreviation) }}">
                <img src="{{ getDivisionIconPath($division->abbreviation) }}" />
            </a>
        @endslot
        @slot ('heading')
            {{ $platoon->name }}
        @endslot
        @slot ('subheading')
            {{ $division->name }} Division
        @endslot
    @endcomponent

    <div class="container-fluid">
        {!! Breadcrumbs::render('platoon', $division, $platoon) !!}

        <h4>Manage Squad Assignments</h4>
        <p>Drag members between squads to assign them. Only squad members will be shown; squad leaders cannot be reassigned from this view.</p>
        <p>If you wish to reassign an entire squad to a new platoon, you can perform that function from the
            <code>Edit Squad</code> view. </p>
        <p>To more easily access manipulate members, you can drag squads to reorder them</p>

        <div class="m-t-xl">
            <a href="{{ route('platoon', [$division->abbreviation, $platoon]) }}" class="btn btn-default">Cancel</a>
            <a class="btn btn-success"
               href="{{ route('createSquad', [$division->abbreviation, $platoon]) }}">
                <i class="fa fa-plus"></i> Create Squad
            </a>
        </div>

        <hr />

        <div class="m-t-xl">

            <div class="row mod-plt sortable-squad">
                @foreach ($platoon->squads as $squad)
                    @include('platoon.partials.droppable-member')
                @endforeach
            </div>
        </div>
    </div>

@stop

@section('footer_scripts')
    <script src="{!! asset('/js/platoon.js') !!}"></script>
@stop

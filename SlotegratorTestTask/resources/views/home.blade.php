@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{ url('prize') }}" type="button" class="btn btn-primary" id="make_operation">Get Prize</a>

    @if(isset($result['error']))
        <p id="errors_or_info">{{$result['error']}}</p>
    @endif

    @if(isset($result['amountForPrize'], $result['amountForPoints'], $result['operationId']))
        <p>Congrats! You won {{$result['amountForPrize']}} dollars!</p>
        <a type="button" href="{{ url('convert_money_to_points/' . $result['operationId']) }}" class="btn btn-primary" id="convert_money_to_points">Convert Money To Points</a>
        <a type="button" href="{{ url('make_transaction_to_bank/' . $result['operationId']) }}" class="btn btn-warning" id="make_transaction_to_bank">Send to your credit card</a>
    @endif

    @if(isset($result['amountForPointsConvert']))
        <p>Congrats! You get {{$result['amountForPointsConvert']}} points!</p>
    @endif

    @if(isset($result['send']) && $result['send'])
        <p>Congrats! Your money will be send to your bank card soon!</p>
    @endif

    @if(isset($result['add'], $result['points_earn']) && $result['add'])
        <p>Congrats! You get {{$result['points_earn']}} points!</p>
    @endif

    @if(isset($result['operationId'], $result['name']))
        <p>Congrats! You get {{$result['name']}}!</p>
            <a type="button" href="{{ url('refuse_object/' . $result['operationId']) }}" class="btn btn-secondary display-none" id="refuse_object">Refuse prize</a>
            <a type="button" href="{{ url('send_object_to_user') }}" class="btn btn-success display-none" id="send_object_to_user">Send price</a>
    @endif

    @if(isset($result['refuse']) && $result['refuse'])
        <p>You refused your prize!</p>
    @endif

    @if(isset($result['send_object']))
        <p>You will get your prize soon!</p>
    @endif

</div>
@endsection

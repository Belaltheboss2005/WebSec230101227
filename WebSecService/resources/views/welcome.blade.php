<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Test</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>
@php($j = 5)
    <div class="card m-4">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
    <table>
    @foreach (range(1, 100) as $i)
        @if (isPrime($i))
            <span class ="badge bg-primary m-1">{{$i}}&nbsp;</span>
        @else
        <span class ="badge bg-secondary m-1">{{$i}}&nbsp;</span>
        @endif
    @endforeach
    </table>
    </div>
    </div>
</body>
</html>

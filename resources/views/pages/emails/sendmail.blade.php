<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email from Laravel App</title>
</head>
<body>
    {{-- <h1></h1>
    <p></p> --}}

    @if (isset($data['message']))
        <p>{!! $data['message'] !!}</p>
    @endif
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>trips.</title>
    <meta name="description" content="Login Page">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>

<div id="app">
  <home-component></home-component>
</div>

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
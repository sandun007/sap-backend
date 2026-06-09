<!doctype html>
<html lang="en">
@include('component.head')
<body>
@include('component.navbar')
<div class="container-fluid p-4 dashboard-cards">
@yield('content')
</div>
@include('component.script')
</body>
</html>

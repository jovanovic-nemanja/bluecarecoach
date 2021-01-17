<!doctype html>
<html>
<head>
    @include('layouts.head')
</head>
<body class=" login_page">
	<div class id='app'>
	    <div class="container-fluid">
	        <div class="login-wrapper row">
	            @yield('content')
	        </div>
	    </div>
	</div>

    @include('layouts.foot')

    @yield('script')
</body>
</html>
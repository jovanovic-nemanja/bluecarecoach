<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>{{ $general_setting->site_name }}</title>
<meta content="" name="description" />
<meta content="" name="author" />

<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon" />
<!-- For iPhone -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Canbas WebGL Three.js -->
<link type="text/css" rel="stylesheet" href="{{ asset('3d/src/main.css') }}">

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Global Theme Styles(used by all pages)-->
<link href="{{ asset('finaldesign/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('finaldesign/assets/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('finaldesign/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Global Theme Styles-->

<link rel="stylesheet" href="{{ asset('finaldesign/jquery-toast-plugin/jquery.toast.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
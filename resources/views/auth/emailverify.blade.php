@extends('layouts.app')

@section('content')
<div class="ps-page--my-account">
    <div class="ps-my-account">
        <div class="container">
            <div class="ps-form--account-padding"></div>
            <h2 style="text-align: center;">Join</h2>
            <form class="ps-form--account ps-tab-root" enctype="multipart/form-data" action="{{ route('emails.sendverifycode') }}" method="POST" style="border: 1px solid;">
                <ul class="ps-tab-list-0" style="text-align: center; margin-bottom: 30px;">
                    <?php if ($role == 'buyer') {
                        $role_name_title = "Buyer"; 
                        ?>
                        
                        <li class="active" style="display: inline-block; padding: 0 15px;">
                            <a style="color: #000; font-size: 30px; font-weight: 600;" href="{{ route('emailverify') }}">Buyer</a>
                        </li>
                        <li class="" style="display: inline-block; padding: 0 15px;">
                            <a style="font-size: 20px; color: #666; font-weight: 600;" href="{{ route('emailverifyseller') }}">Seller</a>
                        </li>

                    <?php }else{
                        $role_name_title = "Seller";
                        ?>

                        <li class="" style="display: inline-block; padding: 0 15px;">
                            <a style="font-size: 20px; color: #666; font-weight: 600;" href="{{ route('emailverify') }}">Buyer</a>
                        </li>
                        <li class="active" style="display: inline-block; padding: 0 15px;">
                            <a style="font-size: 30px; color: #000; font-weight: 600;" href="{{ route('emailverifyseller') }}">Seller</a>
                        </li>

                    <?php } ?>
                </ul>
                <div class="ps-tabs">
                    <div class="ps-tab active" id="sign-in">
                        <div class="ps-form__content">
                            <h5>Join as <?= $role_name_title; ?></h5>
                            {!! csrf_field() !!}

                            <input type="hidden" name="role" value="{{ $role }}" />

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <input required type="email" name="email" class="form-control" value="{{ $email }}" placeholder="Email">
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password*" value="{{ old('password') }}" required> 
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password Confirm*" value="{{ old('password') }}" required> 
                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group submtit">
                                <?php 
                                    if($role == "buyer") {
                                        $style = "";
                                    }else{
                                        $style = "background-color: rgb(228, 133, 130);";
                                } ?>

                                <button class="ps-btn ps-btn--fullwidth" style="<?= $style ?>">Join now</button>
                            </div>
                        </div>
                        <div class="ps-form__footer">
                            <p>Connect with:</p>
                            <ul class="ps-list--social">
                                <!-- <li><a class="facebook" href=""><i class="fa fa-facebook"></i></a></li> -->
                                <li><a class="google" href="{{ url('/redirect') }}"><i class="fa fa-google-plus"></i></a></li>
                                <!-- <li><a class="twitter" href=""><i class="fa fa-twitter"></i></a></li> -->
                                <!-- <li><a class="instagram" href=""><i class="fa fa-instagram"></i></a></li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
            <div style="padding-bottom: 130px;"></div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
    @yield('js')
@stop

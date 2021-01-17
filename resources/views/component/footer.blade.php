<footer class="ps-footer ps-footer--2 ps-footer--furniture">
    <div class="container">
        <div class="ps-footer__content">
            <div class="row">
                <div class="col-xl-6 col-lg-3 col-md-4 col-sm-6 col-6 ">
                    <aside class="widget widget_footer">
                        <h4 class="widget-title">Quick links</h4>
                        <ul class="ps-list--link">
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ route('product.index') }}">Products</a></li>
                            <li><a href="{{ route('howtobuys.index') }}">How To buy</a></li>
                            <li><a href="{{ route('howtosells.index') }}">How To sell</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-xl-6 col-lg-3 col-md-6 col-sm-12 col-12 ">
                    <aside class="widget widget_newletters widget_footer">
                        <h4 class="widget-title">Newsletter</h4>
                        <p>Register now to get updates on promotions & coupons</p>
                        <form class="ps-form--newletter" action="#" method="get">
                            <div class="form-group--nest">
                                <input class="form-control" type="text" placeholder="Email Address">
                                <button class="ps-btn">Subscribe</button>
                            </div>
                            <ul class="ps-list--social">
                                <!-- <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li> -->
                                <li><a class="google-plus" href="{{ url('/redirect') }}"><i class="fa fa-google-plus"></i></a></li>
                                <!-- <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li> -->
                            </ul>
                        </form>
                    </aside>
                </div>
            </div>
        </div>
        <div class="ps-footer__copyright">
            <p style="font-size: inherit;">© 2020 Mambo Dubai. All Rights Reserved</p>
            <p style="font-size: inherit;">
                <span>We Using Safe Payment For:</span>
                <a href="#">
                    <img src="{{ asset('design/img/payment-method/1.jpg') }}" alt="">
                </a>
                <a href="#">
                    <img src="{{ asset('design/img/payment-method/2.jpg') }}" alt="">
                </a>
                <a href="#">
                    <img src="{{ asset('design/img/payment-method/3.jpg') }}" alt="">
                </a>
                <a href="#">
                    <img src="{{ asset('design/img/payment-method/4.jpg') }}" alt="">
                </a>
                <a href="#">
                    <img src="{{ asset('design/img/payment-method/5.jpg') }}" alt="">
                </a>
            </p>
        </div>
    </div>
</footer>
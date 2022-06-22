<footer class="footer">
    <div class="container">
        <div class="site-logo">
            <img alt="site logo" src="">
        </div>
        <div class="social">
            <a href="{{ $settings->facebook }}"><i class="bi bi-facebook"></i></a>
            <a href="{{ $settings->linkedin }}"><i class="bi bi-linkedin"></i></a>
        </div>
        <div class="footer-right">
            <div class="geo">
                <a href="{{ $settings->linkedin }}"><i class="bi bi-geo-alt-fill"></i><span>{{ $settings->geo }}</span></a>
            </div>
            <div class="order-button">
                <button type="button" data-bs-toggle="modal" data-bs-target="#feedbackOrderModal">Заказать услугу</button>
            </div>
        </div>
    </div>

{{--      Copyright all rights reserved &copy {{ date('Y') }}--}}
</footer>

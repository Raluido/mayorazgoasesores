<div class="cookieConsent js-cookie-consent">
    <div class="innerCookieConsent">
        <div class="">
            <div class="">
                <div class="info">
                    <p class="">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="acceptBtn">
                    <button class="js-cookie-consent-agree cookie-consent__agree blue buttonTextWt">
                        {{ trans('cookie-consent::texts.agree') }}
                    </button>
                    <button class="green">
                        <a href="{{ route('home.cookies') }}" class="buttonTextWt">Más información</a>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
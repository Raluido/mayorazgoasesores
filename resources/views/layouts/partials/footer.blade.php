<footer class="">
    <div class="innerFooter">
        <div class="publicAdtion">
            <div class="innerPublicAdtion">
                <div class="">
                    <h4 class="">Enlaces de interés</h4>
                </div>
                <div class="">
                    <a href="https://portal.seg-social.gob.es/" class="">TGSS</a>
                </div>
                <div class="">
                    <a href="https://sede.agenciatributaria.gob.es/" class="">AEAT</a>
                </div>
            </div>
        </div>
        <div class="legalLinks">
            <div class="innerLegalLinks">
                <div class="">
                    <h4 class="">Documentos legales</h4>
                </div>
                <div class="">
                    <a href="{{ route('home.cookies') }}" class="">Política de cookies</a>
                </div>
                <div class="">
                    <a href="{{ route('home.legal') }}" class="">Aviso legal</a>
                </div>
                <div class="">
                    <a href="{{ route('home.privacy') }}" class="">Política de privacidad</a>
                </div>
            </div>
        </div>
    </div>
    <div class="websiwebs">
        <h5 class="">Esta web ha sido desarrollada por websiwebs&#174; en HTML, PHP y CSS.</h5>
    </div>
    @include('cookie-consent::index')
</footer>
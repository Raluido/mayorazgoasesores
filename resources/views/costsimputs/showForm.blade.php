@extends('layouts.app-master')

@section('content')
<section class="costsImputsForm">
    <div class="innerCostsImputsForm">
        <div class="top">
            <h1 class="">Imputación de Costes del Mes</h1>
            <h3 class="">Subir los modelos de Imputación de Costes del mes seleccionado.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="get" name="showCostsImputsForm" action="/costsimputs/show" enctype="multipart/form-data">
                    <div class="inputForm">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <label for="month">Selecciona un mes</label>
                        <select name="month" id="month">
                            <?php
                            $data = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            ?>
                            @for ($i = 0; $i < count($data); $i++) @for ($j=0; $j < count($months); $j++) @if ($data[$i]==$months[$j]->month)
                                <option value="" style="background-color:green;">{{ $data[$i] }}</option>
                                @break;
                                @else
                                @if($j == (count($months)-1))
                                <option value="">{{ $data[$i] }}</option>
                                @endif
                                @endif
                                @endfor
                                @endfor

                        </select>
                    </div>
                    <div class="inputForm">
                        <label for="year">Selecciona un año</label>
                        <select name="year" id="year" onload="showMonths()" onchange=showMonths()>
                            @for($i = $presentYear; $i > ($presentYear-5); $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" id="showCostsImputs" class="stylingButtons green buttonTextWt">
                            Acceder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="{{ asset('js/showMonths.js') }}" defer></script>
@endsection
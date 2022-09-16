@extends('layouts.app')

@section('content')
<style>
    .owl-prev span,
    .owl-next span {
    color: #fff;
    font-size: 50px;
    position: absolute;
    top: 35%;
    cursor: pointer;
    }

    .owl-prev span {
    content: '<';
    left: 0;
    }

    .owl-next span {
    content: '>';
    right: 0;
    }

    .owl-dots {
    text-align: center;
    }

    .owl-dots .owl-dot span {
    width: 10px;
    height: 10px;
    margin: 5px 7px;
    background: #D6D6D6;
    display: block;
    -webkit-backface-visibility: visible;
    transition: opacity .2s ease;
    border-radius: 30px;
    }

    .owl-dots .owl-dot.active span,
    .owl-dots .owl-dot:hover span {
    background: #869791;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!--div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div-->
            <div id="banner_principal">
                <img src="{{ env('APP_URL') }}/{{ $banner_principal->imagen_desktop }}">
            </div>
            <div id="categorias_populares">
                <h3><b>Categor&iacute;as Populares</b></h3>
                <div class="owl-carousel">
                    <div>Slide 1</div>
                    <div>Slide 2</div>
                    <div>Slide 3</div>
                    ...
                </div>
            </div>
            <div id="ofertas_temporada">
            </div>
            <div id="productos_nuevos">
            </div>
            <div id="compartir" style="background-color: #E87B14; color: white; position: fixed; left: 75%; top: 88%; padding-top: 20px; padding-bottom: 20px; padding-left: 2px; padding-right: 2px; border-radius: 15px; width: 220px; height: 60px; text-align: center;">
                <span style="vertical-align: middle;">Compartir Cat&aacute;logo <i class="fa-solid fa-paper-plane" style="margin-left: 10px;"></i></span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function(){
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: true,
        responsive: { 
            0: {
                items: 1
            },
            1000: {
                items: 3
            }
        }
    })
});
</script>
@endsection
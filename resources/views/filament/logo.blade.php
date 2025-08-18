@guest
    <img id="logo-img" src="{{ asset('/img/logo1.png') }}" alt="logo"
        style="height: 150px;position:relative;margin-top: -55px;">
@endguest
@auth
    <img id="logo-img" src="{{ asset('/img/logo1.png') }}" alt="logo"
        style="height: 150px;position:relative;margin-top: -55px;">
@endauth

{{-- <style>
    .fi-simple-layout {
        width: 100%;
        height: 100%;
        background: url('{{ asset('img/homepaeabouthanapbok.jpg') }}') center no-repeat;
        background-size: cover;
        background-attachment: fixed;
        top: 0;
        left: 0;
    }

    .fi-main {
        position: relative;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 1;
        overflow: hidden;
    }

    .fi-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('{{ asset('img/booking.jpg') }}') center no-repeat;
        background-size: cover;
        background-attachment: fixed;
        filter: blur(2px);
        z-index: -1;
    }
</style> --}}

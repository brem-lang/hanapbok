@guest
    {{-- <img id="logo-img" src="{{ asset('/images/logoupdate.png') }}" alt="logo"
        style="height: 70px;position:relative;margin-top: -20px;">

    <span style="font-weight:bold;font-size:14px;margin-left:3px;margin-top:5px">WATER BILL MANAGEMENT SYSTEM</span> --}}
    <h1 style="font-weight: bolder;font-size:25px;">HANAPBOK</h1>
@endguest
@auth
    {{-- <img id="logo-img" src="{{ asset('/images/logoupdate.png') }}" alt="logo"
        style="height: 60px;position:relative;margin-top: -18px;">

    <span style="margin-left:1px;font-weight:bold;font-size:10px;margin-top:5px">WATER BILL MANAGEMENT SYSTEM</span> --}}
    <h1 style="font-weight: bolder;font-size:20px;">HANAPBOK</h1>
@endauth


<style>
    .fi-simple-layout {
        width: 100%;
        height: 100%;
        background: url('{{ asset('img/homepaeabouthanapbok.jpg') }}') center no-repeat;
        background-size: cover;
        background-attachment: fixed;
        top: 0;
        left: 0;
    }
</style>

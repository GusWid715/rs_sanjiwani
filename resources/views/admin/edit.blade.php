@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Dashboard</h1>
    <p>Selamat datang, {{ auth()->user()->nama_lengkap }}! Anda login sebagai <strong>Admin</strong>.</p>
</div>
@endsection

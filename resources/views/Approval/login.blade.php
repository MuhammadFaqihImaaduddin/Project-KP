{{-- <!-- resources/views/login.blade.php -->

@extends('layout-lw')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 login-card">
                <h1 class="card-title text-center mb-4">Login</h1>

                <form action="{{ route('login') }}" method="post" id="loginForm">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" style="border-color: #007bff;" aria-labelledby="username-label" required oninput="toggleButton()">
                        <span id="username-label" class="visually-hidden">Username:</span>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" style="border-color: #007bff;" aria-labelledby="password-label" required oninput="toggleButton()">
                            <button type="button" class="btn btn-outline-primary" onclick="togglePasswordVisibility()">Show/Hide</button>
                        </div>
                        <span id="password-label" class="visually-hidden">Password:</span>
                    </div>

                        @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-block" id="submitButton" disabled style="background-color: #007bff; border-color: #007bff;">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleButton() {
        var username = document.getElementById('username');
        var password = document.getElementById('password');
        var submitButton = document.getElementById('submitButton');

        // Enable button if all fields are filled
        submitButton.disabled = username.value === '' || password.value === '';
    }

    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var button = document.querySelector('#loginForm button[type="button"]');

        // Toggle password visibility
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            button.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            button.textContent = 'Show';
        }
    }
</script>
@endsection --}}

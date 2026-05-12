@extends('layout', ['includeNavbar' => false])
@section('title', 'Verify Code')

@section('content')
<style>
:root {
  --bg: #f4f7fb;
  --card: #ffffff;
  --text: #1e293b;
  --muted: #64748b;
  --primary: rgb(147, 192, 221);
  --primary-gradient: linear-gradient(135deg, #2aa9fb, #37e1c3);
}

html, body {
  height: 100%;
  margin: 0;
  font-family: "Inter", system-ui, sans-serif;
  background: radial-gradient(circle at 20% 0%, rgba(42,169,251,.12), transparent 40%), var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-box {
  width: 400px;
  max-width: 92%;
}

.card {
  background: var(--card);
  border-radius: 18px;
  box-shadow: 0 15px 40px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: 0.3s ease;
}

.card:hover {
  transform: translateY(-4px);
}

.card-header {
  background: var(--primary-gradient);
  text-align: center;
  padding: 30px 0;
}

.card-header img {
  width: 75px;
  margin-bottom: 10px;
}

.card-header .h1 {
  color: #fff;
  font-weight: 700;
  font-size: 1.4rem;
  margin: 0;
}

.card-body {
  padding: 30px;
}

.login-box-msg {
  text-align: center;
  color: var(--muted);
  margin-bottom: 20px;
  font-size: 0.95rem;
}

.form-control {
  padding: 12px 15px;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  font-size: 1.1rem;
  text-align: center;
  letter-spacing: 4px; /* 🔥 يعطي شكل OTP */
}

.form-control:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(42,169,251,0.15);
  background: #fff;
  outline: none;
}

.error {
  color: #ef4444;
  font-size: 0.85rem;
  margin-bottom: 10px;
  text-align: center;
}

.btn-primary {
  background: var(--primary-gradient);
  border: none;
  border-radius: 12px;
  padding: 12px;
  font-weight: 600;
  color: #ffffff;
  width: 100%;
  transition: 0.3s ease;
  box-shadow: 0 8px 18px rgba(42,169,251,0.25);
}

.btn-primary:hover {
  transform: translateY(-2px);
}

@media (max-width: 600px) {
  .card-header img {
    width: 60px;
  }
}
</style>

<div class="login-box">
    <div class="card">
        <div class="card-header">
            <img src="{{ asset('img/logo.png') }}">
            <h1 class="h1">SmartHospital.tn</h1>
        </div>

        <div class="card-body">

            <p class="login-box-msg">
                Enter the verification code sent to your email
            </p>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('verify.code.post') }}">
                @csrf

                <input type="hidden" name="email" value="{{ session('email') }}">

                <input type="text" name="code"
                       class="form-control mb-3"
                       placeholder="••••••"
                       maxlength="6"
                       required>

                <button type="submit" class="btn btn-primary">
                    Verify Code
                </button>
            </form>

        </div>
    </div>
</div>

@endsection

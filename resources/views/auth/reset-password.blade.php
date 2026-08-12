<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>

    <h2>Reset Password</h2>

    <form method="POST" action="/reset-password">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label>Email</label>
            <input
                type="email"
                name="email"
                value="{{ $email }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Password Baru</label>
            <input
                type="password"
                name="password"
                required
            >
        </div>

        <br>

        <div>
            <label>Konfirmasi Password</label>
            <input
                type="password"
                name="password_confirmation"
                required
            >
        </div>

        <br>

        <button type="submit">Reset Password</button>
    </form>

</body>
</html>
@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-gray-50 flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-2xl bg-green-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-hospital-alt text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <div class="font-extrabold text-gray-900 text-lg">RS Sari Sehat</div>
                    <div class="text-green-600 text-xs font-semibold">Daftar sebagai Pasien</div>
                </div>
            </a>
            <h1 class="text-2xl font-extrabold text-gray-900">Buat Akun Pasien</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar untuk membuat janji temu secara online</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="Sesuai KTP">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="email@contoh.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="pw1" required minlength="6"
                                class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                                placeholder="Min. 6 karakter">
                            <button type="button" onclick="togglePw('pw1','eye1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-eye text-sm" id="eye1"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="pw2" required
                                class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                                placeholder="Ulangi password">
                            <button type="button" onclick="togglePw('pw2','eye2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-eye text-sm" id="eye2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">NIK (No. KTP) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nik')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="16 digit NIK KTP">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-3 rounded-xl border {{ $errors->has('jenis_kelamin')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                            <option value="">— Pilih —</option>
                            <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('tanggal_lahir')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required maxlength="100"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('tempat_lahir')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                        placeholder="Kota tempat lahir">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2" required
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('alamat')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all resize-none"
                        placeholder="Jalan, Nomor, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3.5 rounded-xl font-extrabold text-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-green-600 font-bold hover:text-green-800">Masuk</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5">
            Dengan mendaftar, Anda menyetujui <a href="#" class="text-green-600">Syarat & Ketentuan</a> kami.
        </p>
    </div>
</div>
@endsection
@push('scripts')
<script>
function togglePw(id, eyeId) {
    const f = document.getElementById(id);
    const e = document.getElementById(eyeId);
    if (f.type === 'password') { f.type = 'text'; e.className = 'fas fa-eye-slash text-sm'; }
    else { f.type = 'password'; e.className = 'fas fa-eye text-sm'; }
}
</script>
@endpush

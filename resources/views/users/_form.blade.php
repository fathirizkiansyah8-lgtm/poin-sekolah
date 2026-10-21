<div class="row g-3">
    <!-- Role / Peran -->
    <div class="col-md-6">
        <label class="form-label">Role / Peran</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="teacher" @selected(old('role', $user->role ?? '') === 'teacher')>Guru (Teacher)</option>
            <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Admin</option>
        </select>
        @error('role')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- NIP -->
    <div class="col-md-6">
        <label class="form-label">NIP (Opsional)</label>
        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
            value="{{ old('nip', $user->nip ?? '') }}" placeholder="Masukkan NIP jika ada">
        @error('nip')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Nama Lengkap -->
    <div class="col-md-6">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name ?? '') }}" required placeholder="Nama Pengguna">
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Username -->
    <div class="col-md-6">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
            value="{{ old('username', $user->username ?? '') }}" required placeholder="Username akun">
        @error('username')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Email -->
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email ?? '') }}" required placeholder="alamat@email.com">
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Password -->
    <div class="col-md-6">
        <label class="form-label">Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }} placeholder="******">
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Status (Hanya tampil saat Edit / isset($user)) -->
    @isset($user)
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="active" @selected(old('status', $user->status ?? 'active') === 'active')>Aktif</option>
                <option value="inactive" @selected(old('status', $user->status ?? '') === 'inactive')>Tidak Aktif</option>
            </select>
            @error('status')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endisset
</div>
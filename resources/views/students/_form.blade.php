<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama siswa</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $student->user->name ?? '') }}" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class='col-md-6'>
        <label class="form-label">Email Login</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $student->user->email ?? '') }}" required>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Password {{ isset($student) ? '(kosongkan jika tidak diubah)' : ''
}}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ isset($student) ? '' : 'required' }}>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">NIS</label>
        <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror"
            value="{{ old('nis', $student->nis ?? '') }}" required>
        @error('nis')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Kelas</label>
        <select name="school_class_id" class="form-select @error('school_class_id') is-invalid @enderror" required>
            <option value="">Pilih kelas</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}" @selected(old('school_class_id', $student->school_class_id ?? '') == $class->id)>
                    {{ $class->name }} - {{ $class->academic_year }}
                </option>
            @endforeach
        </select>
        @error('school_class_id')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
            <option value="L" @selected(old('gender', $student->gender ?? '') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('gender', $student->gender ?? '') === 'P')>Perempuan</option>
        </select>
        @error('gender')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @isset($student)
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="active" @selected(
                    old('status', $student->status) ===
                    'active'
                )>Aktif</option>
                <option value="inactive" @selected(old('status', $student->status) === 'inactive')>Tidak
                    Aktif</option>
            </select>
            @error('status')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endisset
</div>
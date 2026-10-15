<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Kelas</label>
        <input type="text" name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $class->name ?? '') }}" >
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-tabel">Tingkat</label>
        <input type="text" name="grade"
                class="form-control @error('grade') is-invalid @enderror"
                value="{{ old('grade', $class->grade ?? '') }}"
                placeholder="contoh: XII" >

        @error('grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Jurusan</label>
        <input type="text" name="major"
                class="form-control @error('major') is-invalid @enderror"
                value="{{ old('major', $class->major ?? '') }}"
                placeholder="Contoh: Rekayasa Perangkat Lunak" >
            @error('major') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Tahun Ajaran</label>
        <input type="text" name="academic_year"
                class="form-control @error('academic_year') is-invalid @enderror"
                value="{{ old('academic_year', $class->academic_year ?? '') }}"
                placeholder="Contoh: 2026/2027" >
             @error('academic_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
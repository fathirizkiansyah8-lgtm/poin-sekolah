@extends('layouts.app')
@section('title', $type === 'violation' ? 'Input Pelanggaran' : 'Input Reward')
@section('content')
    <div class="mb-4">
        <h1 class="page-title h3">{{ $type === 'violation' ? 'Input Pelanggaran' : 'Input Reward' }}</h1>
        <p class="page-subtitle">Pilih siswa dan aturan poin yang sesuai.</p>
    </div>

    <div class="app-card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('point-transactions.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Siswa</label>
                        <select name="student_id" class="form-select @error('student_id') is-invalid
                        @enderror">
                            <option value="">Pilih siswa</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                    {{ $student->nis }} - {{ $student->user->name }} - {{ $student->schoolClass->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Aturan Poin</label>
                        <select name="point_rule_id" class="form-select @error('point_rule_id') is-invalid
                        @enderror">
                            <option value="">Pilih aturan</option>
                            @foreach ($rules as $rule)
                                <option value="{{ $rule->id }}" @selected(old('point_rule_id') == $rule->id)>
                                    {{ $rule->code }} - {{ $rule->name }} ({{ $rule->points }} poin)
                                </option>
                            @endforeach
                        </select>
                        @error('point_rule_id')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal & Waktu</label>
                        <input type="datetime-local" name="occurred_at"
                            class="form-control @error('occurred_at') is-invalid @enderror"
                            value="{{ old('occurred_at', now()->format('Y-m-d\\TH:i')) }}">
                        @error('occurred_at')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Bukti (opsional)</label>
                        <input type="file" name="evidence" class="form-control @error('evidence') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.pdf">
                        <div class="form-text">JPG, PNG, atau PDF. Maksimal 2 MB.</div>
                        @error('evidence')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes')}}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button class="btn {{ $type === 'reward' ? 'btn-success' : 'btn-danger'}}">Simpan</button>
                    <a href="{{ route('point-transactions.index') }}" class="btn btn-outlinesecondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
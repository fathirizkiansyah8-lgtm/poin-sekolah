<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Kode</label>
        <input name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $pointRule->code ?? '') }}">
        @error('code')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Jenis</label>
        <select name="type" class="form-select @error('type') is-invalid @enderror">
            <option value="violation" @selected(
                old('type', $pointRule->type ?? '') ===
                'violation'
            )>Pelanggaran</option>
            <option value="reward" @selected(
                old('type', $pointRule->type ?? '') ===
                'reward'
            )>Reward</option>
        </select>
        @error('type')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Kategori</label>
        <input name="category" class="form-control @error('category') is-invalid @enderror"
            value="{{ old('category', $pointRule->category ?? '') }}">
        @error('category')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Aturan</label>
        <input name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $pointRule->name ?? '') }}">
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Poin</label>
        <input type="number" min="1" name="points" class="form-control @error('points') is-invalid @enderror"
            value="{{ old('points', $pointRule->points ?? 1) }}">
        @error('points')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old(
    'description',
    $pointRule->description ?? ''
) }}</textarea>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                @checked(old('is_active', $pointRule->is_active ?? true))>
            <label class="form-check-label" for="is_active">Aturan aktif</label>
        </div>
    </div>
</div>
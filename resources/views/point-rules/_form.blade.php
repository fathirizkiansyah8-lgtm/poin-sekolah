<div class="row g-3">
    <div class="col-md-4">
        <label for="code" class="form-label">Kode Aturan</label>
        <input id="code" type="text" name="code" value="{{ old('code', $pointRule->code ?? '') }}"
            class="form-control @error('code') is-invalid @enderror" maxlength="20" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="type" class="form-label">Jenis</label>
        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="">Pilih jenis</option>
            <option value="violation" @selected(old('type', $pointRule->type ?? '') === 'violation')>Pelanggaran</option>
            <option value="reward" @selected(old('type', $pointRule->type ?? '') === 'reward')>Reward</option>
        </select>
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="points" class="form-label">Poin</label>
        <input id="points" type="number" name="points" value="{{ old('points', $pointRule->points ?? '') }}"
            class="form-control @error('points') is-invalid @enderror" min="1" max="1000" required>
        @error('points')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="category" class="form-label">Kategori</label>
        <input id="category" type="text" name="category" value="{{ old('category', $pointRule->category ?? '') }}"
            class="form-control @error('category') is-invalid @enderror" maxlength="100" required>
        @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="name" class="form-label">Nama Aturan</label>
        <input id="name" type="text" name="name" value="{{ old('name', $pointRule->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror" maxlength="255" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Deskripsi <span class="text-muted">(opsional)</span></label>
        <textarea id="description" name="description" rows="3"
            class="form-control @error('description') is-invalid @enderror">{{ old('description', $pointRule->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <input type="hidden" name="is_active" value="0">
        <div class="form-check">
            <input id="is_active" type="checkbox" name="is_active" value="1"
                class="form-check-input @error('is_active') is-invalid @enderror"
                @checked((bool) old('is_active', $pointRule->is_active ?? true))>
            <label for="is_active" class="form-check-label">Aturan aktif</label>
            @error('is_active')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

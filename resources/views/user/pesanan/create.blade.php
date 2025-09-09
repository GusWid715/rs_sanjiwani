{{-- resources/views/User/pesanan/create.blade.php --}}
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Buat Pesanan - RS Sanjiwani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Buat Pesanan</h2>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('user.pesanan.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Catatan (opsional)</label>
        <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
      </div>

      <h5>Item Pesanan</h5>
      <p class="text-muted">Pilih menu dan jumlah, lalu klik "Tambah Item".</p>

      <div class="row g-2 align-items-end mb-2">
        <div class="col-md-7">
          <label class="form-label">Menu</label>
          <select id="menu_select" class="form-select">
            <option value="">-- Pilih Menu --</option>
            @foreach($menus as $m)
              <option value="{{ $m->id }}" data-nama="{{ $m->nama_menu }}">{{ $m->nama_menu }} (stok: {{ $m->stok }})</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Jumlah</label>
          <input id="jumlah_input" type="number" min="1" value="1" class="form-control">
        </div>
        <div class="col-md-2">
          <button id="add_item" class="btn btn-primary w-100">Tambah Item</button>
        </div>
      </div>

      <table class="table table-bordered" id="items_table">
        <thead>
          <tr><th>Menu</th><th>Jumlah</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          {{-- old input handling --}}
          @if(old('items'))
            @foreach(old('items') as $i)
              <tr>
                <td>{{ \App\Models\menus::find($i['menu_id'])->nama_menu ?? 'Unknown' }}
                  <input type="hidden" name="items[][menu_id]" value="{{ $i['menu_id'] }}">
                </td>
                <td>
                  <input type="hidden" name="items[][jumlah]" value="{{ $i['jumlah'] }}">
                  {{ $i['jumlah'] }}
                </td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>

      <div class="mt-3">
        <button type="submit" class="btn btn-success">Kirim Pesanan</button>
        <a href="{{ route('user.pesanan.index') }}" class="btn btn-secondary">Batal</a>
      </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const addBtn = document.getElementById('add_item');
  const menuSelect = document.getElementById('menu_select');
  const jumlahInput = document.getElementById('jumlah_input');
  const tableBody = document.querySelector('#items_table tbody');

  addBtn.addEventListener('click', function(e){
    e.preventDefault();
    const menuId = menuSelect.value;
    const nama = menuSelect.selectedOptions[0] ? menuSelect.selectedOptions[0].dataset.nama : '';
    const jumlah = parseInt(jumlahInput.value) || 1;
    if(!menuId) return alert('Pilih menu terlebih dahulu');

    // tambahkan row
    const tr = document.createElement('tr');
    tr.innerHTML = '<td>'+ nama +'<input type="hidden" name="items[][menu_id]" value="'+menuId+'"></td>'
                 + '<td><input type="hidden" name="items[][jumlah]" value="'+jumlah+'">'+jumlah+'</td>'
                 + '<td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>';
    tableBody.appendChild(tr);
  });

  tableBody.addEventListener('click', function(e){
    if(e.target.classList.contains('remove-row')){
      e.target.closest('tr').remove();
    }
  });

  // prefill jika ada query menu_id
  @if(!empty($prefillMenuId))
    (function(){
      const select = document.getElementById('menu_select');
      select.value = "{{ $prefillMenuId }}";
    })();
  @endif
});
</script>
</body>
</html>

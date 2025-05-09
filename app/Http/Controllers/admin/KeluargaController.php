<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Keluarga;
use App\Models\RT;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class KeluargaController extends Controller
{
    public function index(Request $request, $nama_RT)
    {
        $admin = Auth::user();

        $keluargas = Keluarga::with(['rts', 'activityLog.user'])
            ->where('rt_id', $admin->rt_id)
            ->orderByRaw('LENGTH(alamat), alamat ASC')
            ->get();

        $rts = RT::orderBy('nama_RT', 'asc')->get();

        $users = User::whereIn('no_kk_keluarga', $keluargas->pluck('no_kk'))
            ->get();

        confirmDelete('Hapus Data Keluarga', 'Yakin ingin menghapus data keluarga ini?');

        return view('admin.keluarga.index', compact('keluargas', 'nama_RT', 'rts', 'users'));
    }

    public function store(Request $request, $nama_RT)
    {
        $admin = Auth::user();

        $request->validate([
            'no_kk'         => 'required|unique:keluargas,no_kk',
            'nama_keluarga' => 'required|string|max:255',
            'alamat'        => 'required|string|max:255',
            'no_telp'       => 'required|string|max:15',
        ]);

        $keluarga = Keluarga::create([
            'no_kk'         => $request->no_kk,
            'nama_keluarga' => $request->nama_keluarga,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'rt_id'         => $admin->rt_id,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat keluarga dengan No KK {$keluarga->no_kk}",
            'target_table' => 'keluargas',
            'target_id'    => $keluarga->no_kk,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Data keluarga berhasil ditambahkan.');

        return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT]);
    }

    public function edit($nama_RT, $id)
    {
        $admin    = Auth::user();
        $keluarga = Keluarga::where('id', $id)->firstOrFail();

        // Cek apakah admin berhak mengedit data keluarga ini
        if ($admin->rt_id != $keluarga->rt_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit keluarga ini.');
        }

        return view('admin.keluarga.edit', ['warga' => $keluarga, 'nama_RT' => $nama_RT]);
    }

    public function updateKeluarga(Request $request, $nama_RT, Keluarga $warga)
    {
        $admin = Auth::user();

        // Validasi input
        $request->validate([
            'no_kk'         => 'required|string|max:16|unique:keluargas,no_kk,' . $warga->id,
            'nama_keluarga' => 'required|string|max:255',
            'alamat'        => 'required|string|max:255',
            'no_telp'       => 'required|string|max:15',
        ]);

        // Simpan data lama untuk log aktivitas
        $oldData = $warga->replicate();
        $changes = [];

        // Bandingkan data lama dan baru
        if ($oldData->no_kk !== $request->no_kk) {
            $changes[] = "No KK: {$oldData->no_kk} → {$request->no_kk}";
        }
        if ($oldData->nama_keluarga !== $request->nama_keluarga) {
            $changes[] = "Nama Keluarga: {$oldData->nama_keluarga} → {$request->nama_keluarga}";
        }
        if ($oldData->alamat !== $request->alamat) {
            $changes[] = "Alamat: {$oldData->alamat} → {$request->alamat}";
        }
        if ($oldData->no_telp !== $request->no_telp) {
            $changes[] = "No HP: {$oldData->no_telp} → {$request->no_telp}";
        }

        // Menggunakan fill untuk mengupdate data
        $warga->fill([
            'no_kk'         => $request->no_kk,
            'nama_keluarga' => $request->nama_keluarga,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'rt_id'         => $admin->rt_id,
        ]);

        // Simpan perubahan
        $updated = $warga->save();

        // Debugging update result
        Log::debug('Keluarga updated:', ['updated' => $updated]);

        // Simpan log aktivitas jika ada perubahan
        if (! empty($changes)) {
            ActivityLog::create([
                'user_id'      => auth()->id(),
                'activity'     => 'update',
                'description'  => implode('<br>', $changes),
                'target_table' => 'keluargas',
                'target_id'    => $warga->id,
                'performed_at' => now(),
            ]);
        }

        Alert::success('Berhasil', 'Data keluarga berhasil diubah.');

        return redirect()->route('admin.warga.edit', ['warga' => $warga, 'nama_RT' => $nama_RT]);
    }

    public function storeAkun(Request $request, $nama_RT)
    {
        $request->validate([
            'no_kk_keluarga' => 'required|exists:keluargas,no_kk|unique:users,no_kk_keluarga',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',
        ]);

        $users = User::create([
            'no_kk_keluarga' => $request->no_kk_keluarga,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'user',
        ]);

        $keluargas = Keluarga::where('no_kk', $request->no_kk_keluarga)->first();

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat akun untuk keluarga {$keluargas->nama_keluarga} dengan Email {$users->email}",
            'target_table' => 'users',
            'target_id'    => $users->id,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Akun keluarga berhasil ditambahkan.');

        return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT])
            ->with('success', 'Akun keluarga berhasil ditambahkan.');
    }

    public function updateAkun(Request $request, $nama_RT, $no_kk_keluarga)
    {
        $user = User::where('no_kk_keluarga', $no_kk_keluarga)->firstOrFail();

        $request->validate([
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $keluargas = Keluarga::where('no_kk', $request->no_kk_keluarga)->first();

        $oldData = $user->replicate();
        $changes = [];

        if ($oldData->email !== $request->email) {
            $changes[] = "Email dari {$oldData->email} menjadi {$request->email}";
        }
        if ($oldData->password !== $request->password) {
            $changes[] = "Mengubah Password menjadi {$request->password}";
        }

        $user->update([
            'email'    => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Simpan log aktivitas jika ada perubahan
        if (! empty($changes)) {
            ActivityLog::create([
                'user_id'      => auth()->id(),
                'activity'     => 'update',
                'description'  => implode('-', $changes),
                'target_table' => 'users',
                'target_id'    => $user->id,
                'performed_at' => now(),
            ]);
        }

        Alert::success('Success', 'Data' . $oldData . ' berhasil diperbarui.');

        return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT]);
    }

    public function destroy($nama_RT, $warga)
    {
        $keluarga = Keluarga::where('no_kk', $warga)->firstOrFail();

        if ($keluarga) {

            if ($keluarga->user) {
                $keluarga->user->delete();
            }

            $keluarga->delete();

            ActivityLog::create([
                'user_id'      => auth()->id(),
                'activity'     => 'delete',
                'description'  => "Menghapus data keluarga dari No KK {$keluarga->no_kk}",
                'target_table' => 'keluargas',
                'target_id'    => $keluarga->no_kk,
                'performed_at' => now(),
            ]);

            return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT])
                ->with('success', 'Data berhasil dihapus.');
        }

        Alert::success('Success', 'Data berhasil dihapus.');

        return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT])
            ->with('error', 'Data tidak ditemukan.');
    }
}

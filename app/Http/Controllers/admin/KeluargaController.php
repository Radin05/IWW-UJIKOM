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
use Illuminate\Validation\Rule;

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

        return view('admin.keluarga.index', compact('keluargas', 'nama_RT', 'rts', 'users'));
    }

    public function store(Request $request, $nama_RT)
    {
        $request->validate([
            'no_kk'         => 'required|unique:keluargas,no_kk',
            'nama_keluarga' => 'required|string|max:255',
            'alamat'        => 'required|string|max:255',
            'no_telp'       => 'required|string|max:15',
            'rt_id'         => 'required|exists:rts,id',
        ]);

        $keluarga = Keluarga::create([
            'no_kk'         => $request->no_kk,
            'nama_keluarga' => $request->nama_keluarga,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'rt_id'         => $request->rt_id,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat keluarga dengan No KK {$keluarga->no_kk}",
            'target_table' => 'keluargas',
            'target_id'    => $keluarga->no_kk,
            'performed_at' => now(),
        ]);

        return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT])
            ->with('success', 'Keluarga berhasil ditambahkan');
    }

    public function update(Request $request, $nama_RT, $no_kk)
    {
        $keluargas = Keluarga::where('no_kk', $no_kk)->firstOrFail();

        $request->validate([
            'no_kk'         => [
                'required',
                Rule::unique('keluargas', 'no_kk')->ignore($keluargas->no_kk, 'no_kk'),
            ],
            'nama_keluarga' => 'required|string|max:255',
            'alamat'        => 'required|string|max:255',
            'no_telp'       => 'required|string|max:15',
            'rt_id'         => 'required|exists:rts,id',
        ]);

        $keluargas->update([
            'no_kk'         => $request->no_kk,
            'nama_keluarga' => $request->nama_keluarga,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'rt_id'         => $request->rt_id,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'description'  => "Mengubah data keluarga dari No KK {$keluargas->no_kk}",
            'target_table' => 'keluargas',
            'target_id'    => $keluargas->no_kk,
            'performed_at' => now(),
        ]);

        return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT])
            ->with('success', 'Data keluarga berhasil diperbarui');
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

        $user->update([
            'email'    => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'description'  => "Mengubah akun dari keluarga {$keluargas->nama_keluarga} dengan Email {$user->email}",
            'target_table' => 'users',
            'target_id'    => $user->id,
            'performed_at' => now(),
        ]);

        return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT])
            ->with('success', 'Akun keluarga berhasil diperbarui.');
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

        return redirect()->route('admin.warga.index', ['nama_RT' => $nama_RT])
            ->with('error', 'Data tidak ditemukan.');
    }
}

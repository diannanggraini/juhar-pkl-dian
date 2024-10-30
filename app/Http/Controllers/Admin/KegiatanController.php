<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\kegiatan;
use App\Models\Admin\Pembimbing;
use App\Models\Admin\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function kegiatan($id, $id_siswa)
    {
        $loginGuru = Auth::guard('guru')->user()->id_guru;

        $siswa = Siswa::find($id_siswa);

        if (!$siswa || !$siswa->id_pembimbing) {
            return back()->withErrors(['access' => 'Siswa tidak ditemukan atau tidak memiliki pembimbing.']);
        }

        if ($siswa->id_pembimbing != $id) {
            return back()->withErrors(['access' => 'Pembimbig tidak sesuai']);
        }

        $pembimbing = Pembimbing::find($id);

        if (!$pembimbing || $pembimbing->id_guru !== $loginGuru) {
            return back()->withErrors(['access' => 'Akses Anda di tolak. Siswa ini tidak dibimbing oleh Anda']);
        }

        $kegiatans = kegiatan::where('id_siswa', $id_siswa)->get();
        $kegiatan = kegiatan::where('id_siswa', $id_siswa)->first();
        $id_pembimbing = $id;
        return view('guru.kegiatan', compact('id_pembimbing', 'id_siswa', 'kegiatans', 'kegiatan'));
    }

    public function detailKegiatan($id, $id_siswa, $id_kegiatan)
    {
        $loginGuru = Auth::guard('guru')->user()->id_guru;

        $siswa = Siswa::find($id_siswa);

        if (!$siswa || !$siswa->id_pembimbing) {
            return back()->withErrors(['access' => 'Siswa tidak ditemukan atau tidak memiliki pembimbing.']);
        }

        if ($siswa->id_pembimbing != $id) {
            return back()->withErrors(['access' => 'Pembimbig tidak sesuai']);
        }

        $pembimbing = Pembimbing::find($id);

        if (!$pembimbing || $pembimbing->id_guru !== $loginGuru) {
            return back()->withErrors(['access' => 'Akses Anda di tolak. Siswa ini tidak dibimbing oleh Anda']);
        }

        $kegiatan = kegiatan::where('id_kegiatan', $id_kegiatan)
                            ->where('id_siswa', $id_siswa)
                            ->first();
        if (!$kegiatan) {
            return back()->withErrors(['access' => 'kegiatan tidak tersedia']);
        }

        return view('guru.detail_kegiatan', compact('id','kegiatan'));
    }

    public function kegiatanSiswa()
    {
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;
        $kegiatans = kegiatan::where('id_siswa', $id_siswa)->get();
        return view('siswa.kegiatan', compact('kegiatans'));
    }

    public function create()
    {
        return view('siswa.tambah_kegiatan');
    }

    public function store(Request $request)
    {
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;

        $request->validate([
            'tanggal_kegiatan' => 'required',
            'nama_kegiatan' => 'required',
            'ringkasan_kegiatan' => 'required',
            'foto' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $uniqueField = uniqid() . '-' . $request->file('foto')->getClientOriginalName();

            $request->file('foto')->storeAs('foto_kegiatan', $uniqueField, 'public');

            $foto = 'foto_kegiatan/' . $uniqueField;
        }

        kegiatan::create([
            'id_siswa' => $id_siswa,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
            'ringkasan_kegiatan' => $request->ringkasan_kegiatan,
            'foto' => $foto,
        ]);

        return redirect()->route('siswa.kegiatan')->with('success', 'Kegiatan Siswa Berhasil di Tambah.');
    }

        public function editSiswa(string $id)
    {
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;
        $kegiatan = Kegiatan::where('id_kegiatan', $id)
                            ->where('id_siswa', $id_siswa)
                            ->first();
        if (!$kegiatan) {
            return back()->withErrors(['access' => 'Kegiatan Tidak Tersedia']);
        }
        $kegiatan = Kegiatan::find($id);
        return view('siswa.edit_kegiatan', compact('kegiatan'));
    }

    public function updateKegiatan(Request $request, string $id)
    {
        $kegiatan = kegiatan::find($id);

        $request->validate([
            'tanggal_kegiatan' => 'required',
            'nama_kegiatan' => 'required',
            'ringkasan_kegiatan' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $foto = $kegiatan->foto;

        if ($request->hasFile('foto')) {
            if ($foto) {
                Storage::disk('public')->delete($foto);
            }
            $uniqueField = uniqid() . '_' . $request->file('foto')->getClientOriginalName();

            $request->file('foto')->storeAs('foto_kegiatan', $uniqueField, 'public');

            $foto = 'foto_kegiatan/' . $uniqueField;
        }

        $kegiatan->update([
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
            'ringkasan_kegiatan' => $request->ringkasan_kegiatan,
            'foto' => $foto,
        ]);

        return redirect()->route('siswa.kegiatan')->with('success', 'Data Kegiatan Berhasil di Edit');
    }

    public function deleteKegiatan($id,)
    {
        $kegiatan = kegiatan::find($id);

        if ($kegiatan->foto) {
            $foto = $kegiatan->foto;

            if (Storage::disk('public')->exists($foto)) {
                Storage::disk('public')->delete($foto);
            }
        }

        $kegiatan->delete();

        return redirect()->back()->with('success', 'Data Kegiatan berhasil di hapus.');
    }

    public function delateKegiatan($id)
    {
        $kegiatan = Kegiatan::find($id);

        if ($kegiatan->foto) {
            $foto = $kegiatan->foto;

            if (Storage::disk('public')->delete($foto)) {
                Storage::disk('public')->delete($foto);
            }
        }

        $kegiatan->delete();

        return redirect()->back()->with('success', 'Data kegiatan Berhasil di Hapus.');
    }

    public function detailKegiatanSiswa($id_kegiatan)
    {
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;
        $kegiatan = Kegiatan::where('id_kegiatan', $id_kegiatan)
                            ->where('id_siswa', $id_siswa)
                            ->first();
        if (!$kegiatan) {
            return back()->withErrors(['access' => 'Kegiatan Tidak Tersedia']);
        }

        return view('siswa.detail_kegiatan', compact('kegiatan'));
    }

    public function cariKegiatan(Request $request, $id, $id_siswa)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $loginGuru = Auth::guard('guru')->user()->id_guru;

        $siswa = Siswa::find($id_siswa);

        if (!$siswa || !$siswa->id_pembimbing) {
            return back()->withErrors(['access' => 'Siswa tidak ditemukan atau tidak memiliki pembimbing.']);
        }

        if ($siswa->id_pembimbing != $id) {
            return back()->withErrors(['access' => 'Pembimbig tidak sesuai']);
        }

        $pembimbing = Pembimbing::find($id);

        if (!$pembimbing || $pembimbing->id_guru !== $loginGuru) {
            return back()->withErrors(['access' => 'Akses Anda di tolak. Siswa ini tidak dibimbing oleh Anda']);
        }

        $kegiatans = kegiatan::where('id_siswa', $id_siswa)
            ->whereBetween('tanggal_kegiatan', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $kegiatan = kegiatan::where('id_siswa', $id_siswa)
            ->whereBetween('tanggal_kegiatan', [$tanggalAwal, $tanggalAkhir])
            ->first();

        $id_pembimbing = $id;

        return view('guru.kegiatan', compact('kegiatans', 'kegiatan', 'id_pembimbing', 'id_siswa', 'tanggalAwal', 'tanggalAkhir'));
    }
}

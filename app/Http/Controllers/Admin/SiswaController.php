<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pembimbing;
use App\Models\Admin\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function siswa($id)
    {
        $siswas = Siswa::where('id_pembimbing', $id)->get();
        $siswa = Siswa::where('id_pembimbing', $id)->first();
        return view('admin.siswa', compact('siswas', 'siswa', 'id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        return view('admin.tambah_siswa', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn|digits:10,',
            'password' => 'required|min:6',
            'nama_siswa' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $foto=null;

        if ($request->hasfile('foto')) {
            $uniqueField = uniqid() .'_'. $request->file('foto')->getClientOriginalName();

            $request->file('foto')->storeAs('foto_siswa', $uniqueField, 'public');

            $foto = 'foto_siswa/' . $uniqueField;
        }

        Siswa::create([
            'id_pembimbing' => $id,
            'nisn' => $request->nisn,
            'password' => Hash::make($request->password),
            'nama_siswa' => $request->nama_siswa,
            'foto' => $foto,
        ]);

        return redirect()->route('admin.pembimbing.siswa', $id)->with('succes', 'Data Guru Berhasil di Tambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, $id_siswa)
    {
        $siswa = Siswa::find($id_siswa);
        return view('admin.edit_siswa', compact('siswa', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, $id_siswa)
    {
        $siswa = Siswa::find($id_siswa);

        $request->validate([
            'nisn' => 'required|digits:10|unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa',
            'password' => 'nullable|min:6',
            'nama_siswa' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $foto = $siswa->foto;

        if ($request->hasFile('foto')) {
            if ($foto) {
                Storage::disk('public')->delete($foto);
            }
            $uniqueField = uniqid() . '_' . $request->file('foto')->getClientOriginalName();

            $request->file('foto')->storeAs('foto_siswa', $uniqueField, 'public');

            $foto = 'foto_siswa/' . $uniqueField;
        }

        $siswa->update([
            'nisn' => $request->nisn,
            'password' => $request->filled('password') ? Hash::make($request->password) : $siswa->password,
            'nama_siswa' => $request->nama_siswa,
            'foto' => $foto,
        ]);

        return redirect()->route('admin.pembimbing.siswa', $id)->with('success', 'Data Siswa Berhasil di update');
    }

    public function delete($id, $id_siswa)
    {
        $siswa = siswa::find($id_siswa);

        if ($siswa->foto) {
            $foto = $siswa->foto;

            if (Storage::disk('public')->exists($foto)) {
                Storage::disk('public')->delete($foto);
            }
        }

        $siswa->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil di hapus.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    public function siswaGuru($id)
    {
        $loginGuru = Auth::guard('guru')->user()->id_guru;
        $pembimbing = Pembimbing::find($id);

        if (!$pembimbing || $pembimbing->id_guru !== $loginGuru) {
            return back()->withErrors(['access' => 'Akses Anda di Tolak!']);
        }

        $siswas = Siswa::where('id_pembimbing', $id)->get();
        $siswa = Siswa::where('id_pembimbing', $id)->first();
        return view('guru.siswa', compact('siswas', 'siswa', 'id'));
    }
}

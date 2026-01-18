<?php

namespace App\Http\Controllers;

use App\Models\SPJ;
use App\Models\spj_bukti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class bukticontrol extends Controller
{
    public function store(Request $request, SPJ $spj)
    {
        $request->validate([
            'bukti_spj.*'   => 'required|image|max:2048',
            'jenis_bukti.*' => 'required|string|max:100',
            'keterangan.*'  => 'nullable|string|max:255',
        ]);

        foreach ($request->file('bukti_spj') as $i => $file) {

            $hashedName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs(
                "spj/bukti/{$spj->id}",
                $hashedName,
                'public'
            );

            spj_bukti::create([
                'spj_id'      => $spj->id,
                'file_path'   => $path,
                'file_name'   => $file->getClientOriginalName(),
                'file_type'   => $file->getClientOriginalExtension(),
                'jenis_bukti' => $request->jenis_bukti[$i],
                'keterangan'  => $request->keterangan[$i] ?? null,
                'uploaded_by' => Auth::id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bukti berhasil diupload'
        ]);
    }

    public function list(SPJ $spj)
    {
        return response()->json(
            $spj->buktis()->latest()->get()->map(function ($b) {
                return [
                    'jenis' => $b->jenis_bukti,
                    'keterangan' => $b->keterangan,
                    'file_url' => asset('storage/' . $b->file_path),
                    'file_name' => $b->file_name,
                ];
            })
        );
    }

    public function index(SPJ $spj)
    {
        return $spj->buktis->map(function ($b) {
            return [
                'id' => (int) $b->id, // ⬅️ JANGAN DIHAPUS
                'file_url' => asset('storage/'.$b->file_path),
                'keterangan' => $b->keterangan,
                'jenis' => $b->jenis_bukti,
                'file_type' => $b->file_type,
            ];
        })->values();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:255'
        ]);

        spj_bukti::where('id', $id)->update([
            'keterangan' => $request->keterangan
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $bukti = spj_bukti::findOrFail($id);

        if (Storage::disk('public')->exists($bukti->file_path)) {
            Storage::disk('public')->delete($bukti->file_path);
        }

        $bukti->delete();

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Hmps;
use App\Models\Mahasiswa;
use App\Models\HmpsMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class HmpsMemberController extends Controller
{
    public function showMembers(Hmps $hmps)
    {
        $user = Auth::user();

        // Only allow access to admin or the HMPS's own user
        // if (!$user->hasRole('admin') && $user->id !== $hmps->user_id) {
        //     abort(403, 'Unauthorized action.');
        // }

        // Get eligible students (same prodi as HMPS)
        $eligibleStudents = Mahasiswa::where('prodi', $hmps->nama)
            ->whereNotIn('id', $hmps->members->pluck('id'))
            ->paginate(10);

        return view('hmps.members', [
            'hmps' => $hmps->load('members'),
            'eligibleStudents' => $eligibleStudents
        ]);
    }

    public function addMembers(Request $request, $hmpsId)
    {
        $hmps = Hmps::findOrFail($hmpsId);

        $request->validate([
            'members' => 'required|array',
            'members.*.id' => [
                'required',
                'exists:mahasiswa,id', // Changed to match your table name
                function ($attribute, $value, $fail) use ($hmps) {
                    $student = Mahasiswa::find($value);
                    if ($student && $student->prodi !== $hmps->prodi->nama_prodi) {
                        $fail('Student must be from the same study program.');
                    }
                }
            ],
            'members.*.position' => 'required|string|max:255',
        ]);

        try {
            foreach ($request->members as $memberData) {
                // Check if member already exists
                $exists = HmpsMember::where('hmps_id', $hmps->id)
                    ->where('mahasiswa_id', $memberData['id'])
                    ->exists();

                if (!$exists) {
                    HmpsMember::create([
                        'hmps_id' => $hmps->id,
                        'mahasiswa_id' => $memberData['id'],
                        'position' => $memberData['position']
                    ]);
                }
            }

            return redirect()->route('hmps.members', $hmpsId)
                ->with('success', 'Members added successfully');
        } catch (\Exception $e) {
            Log::error('Error adding HMPS members: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to add members. Please try again.');
        }
    }

    public function updateMember(Request $request, Hmps $hmps, $memberId)
    {
        $request->validate([
            'position' => 'required|string|max:255',
        ]);

        try {
            $member = HmpsMember::where('hmps_id', $hmps->id)
                ->where('mahasiswa_id', $memberId)
                ->firstOrFail();

            $member->update([
                'position' => $request->position
            ]);

            return redirect()->back()
                ->with('success', 'Position updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating HMPS member: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update position.');
        }
    }
    public function removeMember(Hmps $hmps, $memberId)
    {
        $user = Auth::user();

        // Only allow admin or the HMPS's own user to remove members
        // if (!$user->hasRole('admin') && $user->id !== $hmps->user_id) {
        //     abort(403, 'Unauthorized action.');
        // }

        try {
            $hmps->members()->detach($memberId);
            return redirect()->back()->with('success', 'Anggota berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus anggota.');
        }
    }

    public function searchMahasiswa(Request $request, $hmpsId)
{
    $request->validate([
        'search' => 'nullable|string|max:255',
    ]);

    $search = $request->input('search');

    // Ambil HMPS berdasarkan ID dan pastikan ada
    $hmps = Hmps::findOrFail($hmpsId);

    // Cari mahasiswa dengan prodi yang sama
    $query = Mahasiswa::where('prodi', $hmps->prodi->nama_prodi) // Sesuaikan kolom jika berbeda
        ->whereDoesntHave('hmps', function ($q) use ($hmpsId) {
            $q->where('hmps_id', $hmpsId);
        });

    // Tambahkan filter pencarian jika ada input search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'LIKE', "%$search%")
                ->orWhere('nim', 'LIKE', "%$search%");
        });
    }

    // Ambil 10 data mahasiswa
    $mahasiswas = $query->limit(10)->get(['id', 'nama', 'nim', 'prodi']);

    return response()->json($mahasiswas);
}


}

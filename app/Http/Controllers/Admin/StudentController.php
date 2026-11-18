<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('nis', 'like', "%{$s}%")
                  ->orWhere('kelas', 'like', "%{$s}%")
                  ->orWhere('jurusan', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $students = $query->latest()->paginate(10)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|min:3|max:255',
            'email'    => 'required|email|unique:users,email|max:255',
            'nis'      => 'required|string|min:3|max:20|unique:users,nis|regex:/^[0-9]+$/',
            'kelas'    => 'required|string|max:50',
            'jurusan'  => 'required|string|max:50',
            'phone'    => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'name.min'          => 'Nama minimal 3 karakter.',
            'email.unique'      => 'Email sudah terdaftar.',
            'nis.unique'        => 'NIS sudah terdaftar.',
            'nis.regex'         => 'NIS hanya boleh angka.',
            'phone.regex'       => 'No. telepon hanya boleh angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'      => 'Password minimal 8 karakter dengan huruf besar, kecil, angka, dan simbol.',
        ]);

        $validated['role'] = 'siswa';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function edit(User $student)
    {
        if ($student->role !== 'siswa') {
            abort(404);
        }
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        if ($student->role !== 'siswa') {
            abort(404);
        }

        $validated = $request->validate([
            'name'  => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id . '|max:255',
            'nis'   => 'required|string|min:3|max:20|unique:users,nis,' . $student->id . '|regex:/^[0-9]+$/',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:50',
            'phone' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui! ✅');
    }

    public function destroy(User $student)
    {
        if ($student->role !== 'siswa') {
            abort(404);
        }

        // Cek apakah ada peminjaman aktif
        $activeLoans = $student->loans()
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($activeLoans > 0) {
            return back()->with('error', "Siswa tidak bisa dihapus! Masih ada {$activeLoans} peminjaman aktif.");
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil dihapus! 🗑️');
    }
}
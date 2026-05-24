<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workshop;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\WorkshopCategory;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // --- Dashboard ---
    public function dashboard()
    {
        $stats = [
            'total_workshops'   => Workshop::count(),
            'total_tickets'     => Ticket::where('status', 'active')->count(),
            'total_revenue'     => Order::sum('total_amount'),
            'pending_teachers'  => User::where('role', 'instructor')->where('is_approved_instructor', false)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // --- Manajemen Instruktur/Panitia ---
    public function instructors()
    {
        $instructors = User::where('role', 'instructor')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.instructors', compact('instructors'));
    }

    public function approveInstructor(User $user)
    {
        if ($user->role !== 'instructor') {
            return redirect()->back()->with('error', 'User ini bukan instruktur/panitia.');
        }

        $user->update([
            'is_approved_instructor' => !$user->is_approved_instructor
        ]);

        $status = $user->is_approved_instructor ? 'disetujui' : 'dinonaktifkan persetujuannya';
        return redirect()->back()->with('success', 'Akun Instruktur ' . $user->name . ' berhasil ' . $status . '!');
    }

    // --- CRUD Kategori ---
    public function categoriesIndex()
    {
        $categories = WorkshopCategory::withCount('workshops')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function categoriesCreate()
    {
        return view('admin.categories.create');
    }

    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:workshop_categories,name',
        ]);

        WorkshopCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function categoriesEdit(WorkshopCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function categoriesUpdate(Request $request, WorkshopCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:workshop_categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function categoriesDestroy(WorkshopCategory $category)
    {
        if ($category->workshops()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena memiliki workshop yang aktif.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }

    // --- CRUD Kelas ---
    public function classesIndex()
    {
        $classes = SchoolClass::withCount('studentProfiles')->orderBy('name')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function classesCreate()
    {
        return view('admin.classes.create');
    }

    public function classesStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'grade_level' => 'nullable|string|max:50',
        ]);

        SchoolClass::create($request->only('name', 'grade_level'));

        return redirect()->route('admin.classes.index')->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    public function classesEdit(SchoolClass $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function classesUpdate(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'grade_level' => 'nullable|string|max:50',
        ]);

        $class->update($request->only('name', 'grade_level'));

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function classesDestroy(SchoolClass $class)
    {
        if ($class->studentProfiles()->count() > 0) {
            return redirect()->back()->with('error', 'Kelas tidak dapat dihapus karena terdapat profil siswa yang terdaftar di kelas ini.');
        }
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dihapus!');
    }

    // --- CRUD Workshop ---
    public function workshopsIndex()
    {
        $workshops = Workshop::with(['instructor', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.workshops.index', compact('workshops'));
    }

    public function workshopsCreate()
    {
        $instructors = User::where('role', 'instructor')->where('is_approved_instructor', true)->get();
        $categories  = WorkshopCategory::all();
        return view('admin.workshops.create', compact('instructors', 'categories'));
    }

    public function workshopsStore(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:200',
            'instructor_id' => 'required|exists:users,id',
            'category_id'   => 'nullable|exists:workshop_categories,id',
            'description'   => 'nullable|string',
            'banner'        => 'nullable|image|max:2048',
            'start_at'      => 'required|date',
            'end_at'        => 'nullable|date|after_or_equal:start_at',
            'location'      => 'nullable|string|max:200',
            'capacity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'audience'      => 'required|in:student,teacher,public',
            'status'        => 'required|in:draft,published,cancelled',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
        }

        Workshop::create([
            'title'         => $request->title,
            'instructor_id' => $request->instructor_id,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'banner_image'  => $bannerPath,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
            'location'      => $request->location,
            'capacity'      => $request->capacity,
            'price'         => $request->price,
            'audience'      => $request->audience,
            'status'        => $request->status,
        ]);

        return redirect()->route('admin.workshops.index')->with('success', 'Workshop baru berhasil dibuat!');
    }

    public function workshopsEdit(Workshop $workshop)
    {
        $instructors = User::where('role', 'instructor')->where('is_approved_instructor', true)->get();
        $categories  = WorkshopCategory::all();
        return view('admin.workshops.edit', compact('workshop', 'instructors', 'categories'));
    }

    public function workshopsUpdate(Request $request, Workshop $workshop)
    {
        $request->validate([
            'title'         => 'required|string|max:200',
            'instructor_id' => 'required|exists:users,id',
            'category_id'   => 'nullable|exists:workshop_categories,id',
            'description'   => 'nullable|string',
            'banner'        => 'nullable|image|max:2048',
            'start_at'      => 'required|date',
            'end_at'        => 'nullable|date|after_or_equal:start_at',
            'location'      => 'nullable|string|max:200',
            'capacity'      => 'required|integer|min:' . $workshop->tickets_sold,
            'price'         => 'required|numeric|min:0',
            'audience'      => 'required|in:student,teacher,public',
            'status'        => 'required|in:draft,published,cancelled',
        ]);

        $bannerPath = $workshop->banner_image;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
        }

        $workshop->update([
            'title'         => $request->title,
            'instructor_id' => $request->instructor_id,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'banner_image'  => $bannerPath,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
            'location'      => $request->location,
            'capacity'      => $request->capacity,
            'price'         => $request->price,
            'audience'      => $request->audience,
            'status'        => $request->status,
        ]);

        return redirect()->route('admin.workshops.index')->with('success', 'Workshop berhasil diperbarui!');
    }

    public function workshopsDestroy(Workshop $workshop)
    {
        if ($workshop->tickets()->count() > 0) {
            return redirect()->back()->with('error', 'Workshop tidak dapat dihapus karena telah memiliki pendaftar.');
        }
        $workshop->delete();
        return redirect()->route('admin.workshops.index')->with('success', 'Workshop berhasil dihapus!');
    }
}

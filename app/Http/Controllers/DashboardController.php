<?php

namespace App\Http\Controllers;

use App\Constants\Roles;
use App\Models\Category;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Microsite::query();

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")->get();
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $microsites = $query->get();
        $categories = Category::all();

        return Inertia::render('Welcome', [
            'microsites' => $microsites,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id']),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function dashboard(): Response
    {
        $user = auth()->user();

        if ($user->role === Roles::ADMIN) {
            $payments = Payment::with('microsite')->get();
        } else {
            $payments = Payment::with('microsite')->whereHas('microsite', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }

        return inertia('Dashboard', ['payments' => $payments]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $branches = Branch::latest()->paginate(10);

    return view('branches.index', compact('branches'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('branches.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
{
    Branch::create($request->validated());

    return redirect()
        ->route('branches.index')
        ->with('success', 'Branch created successfully.');
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
    public function edit(Branch $branch)
{
    return view('branches.edit', compact('branch'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
{
    $branch->update($request->validated());

    return redirect()
        ->route('branches.index')
        ->with('success', 'Branch updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
{
    $branch->delete();

    return redirect()
        ->route('branches.index')
        ->with('success', 'Branch deleted successfully.');
}
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Templates;
use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Templates::latest()->get();

        return view('admin.feright.template.upload', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required',
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // Check if template already exists
        $existingTemplate = Templates::where('name', $request->name)->first();

        if ($existingTemplate) {

            // Delete old file
            if (Storage::exists($existingTemplate->file)) {
                Storage::delete($existingTemplate->file);
            }

            // Delete old record
            $existingTemplate->delete();
        }

        // Store file in storage/app/templates
        $filePath = $request->file('file')->store('templates');

        // Save in database
        Templates::create([
            'name' => $request->name,
            'type'=>$request->type,
            'file' => $filePath,
        ]);

        return redirect()->back()
            ->with('success', 'Template uploaded successfully.');
    }

    public function manage()
    {
        $template=Templates::all();
        return view('admin.feright.template.manage',['templates'=>$template]);
    }
    public function delete($id)
    {
        $template = Templates::findOrFail($id);

        // Delete file from storage
        if (Storage::exists($template->file)) {
            Storage::delete($template->file);
        }

        // Delete database record
        $template->delete();

        return redirect()->back()
            ->with('success', 'Template deleted successfully.');
    }
    public function download($id)
    {
        $template = Templates::findOrFail($id);

        if (!Storage::exists($template->file)) {
            return redirect()->back()
                ->with('error', 'Template file not found.');
        }

        return Storage::download(
            $template->file,
            $template->name . '.xlsx'
        );
    }
}
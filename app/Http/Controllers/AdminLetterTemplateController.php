<?php

namespace App\Http\Controllers;

use App\Models\LetterTemplate;
use Illuminate\Http\Request;

class AdminLetterTemplateController extends Controller
{
    public function index()
    {
        $templates = LetterTemplate::orderBy('letter_type')->get();

        return view('admin/letter-templates/index', compact('templates'));
    }

    public function create()
    {
        return view('admin/letter-templates/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'letter_type' => ['required', 'in:bebas_pustaka,penyerahan_skripsi', 'unique:letter_templates,letter_type'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'footer' => ['nullable', 'string'],
        ]);

        LetterTemplate::create($request->only('letter_type', 'title', 'body', 'footer'));

        return redirect()->route('admin.letter_templates.index')->with('success', 'Template dibuat.');
    }

    public function edit(int $id)
    {
        $template = LetterTemplate::findOrFail($id);

        return view('admin/letter-templates/edit', compact('template'));
    }

    public function update(Request $request, int $id)
    {
        $template = LetterTemplate::findOrFail($id);

        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'footer' => ['nullable', 'string'],
        ]);

        $template->update($request->only('title', 'body', 'footer'));

        return redirect()->route('admin.letter_templates.index')->with('success', 'Template diperbarui.');
    }

    public function destroy(int $id)
    {
        $template = LetterTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.letter_templates.index')->with('success', 'Template dihapus.');
    }
}

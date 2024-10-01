<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class NoteController extends Controller
{   
    //Index (listado de notas)
    public function index(Request $request)
    {
        $notes = Note::where('user_id', Auth::id())
            ->orderBy($request->get('order_by', 'created_at'), $request->get('sort', 'asc'))
            ->get();
    
        return response()->json($notes);
    }

    //Store (crear nota)
    public function store(Request $request)
    {
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'tags' => 'nullable|array',
        'due_date' => 'nullable|date',
        'image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $validated['image_path'] = $path;
    }

    $validated['user_id'] = Auth::id();

    $note = Note::create($validated);

    return response()->json($note, 201);
    }

    //Show (mostrar una nota especifica)
    public function show(Note $note)
    {
        $this->authorize('view', $note);
    
        return response()->json($note);
    }
    
    //Update (actualizar una nota)
    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'nullable|array',
            'due_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($note->image_path) {
                Storage::disk('public')->delete($note->image_path);
            }
    
            $path = $request->file('image')->store('images', 'public');
            $validated['image_path'] = $path;
        }
    
        $note->update($validated);
    
        return response()->json($note);
    }

    //Destroy (eliminar una nota)
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
    
        // Eliminar imagen asociada si existe
        if ($note->image_path) {
            Storage::disk('public')->delete($note->image_path);
        }
    
        $note->delete();
    
        return response()->json(null, 204);
    }
    


}

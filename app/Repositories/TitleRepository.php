<?php

namespace App\Repositories;

use App\Models\Title;

class TitleRepository {
    public function storeTitle($validatedData, $cover)
    {
        $title = Title::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
        ]);

        $extension = $cover->getClientOriginalExtension();
        $relativePath = $cover->storeAs("covers/{$title->slug}", "title.{$extension}", 'public');

        $title->update(['cover_image' => '/storage/' . $relativePath]);

        return $title;
    }

    public function getTitles()
    {
        return Title::latest()->paginate(10);
    }

    public function deleteTitle(Title $title)
    {
        $title->delete();
    }

    public function updateTitle($validatedData)
    {
        $title = Title::findOrFail($validatedData['id']);

        $title->update([
            'title'       => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        $title->tags()->sync($validatedData['tags'] ?? []);

        return;
    }

    public function updateCover($validatedData, $cover)
    {
        $title = Title::find($validatedData['id']);
        $extension = $cover->getClientOriginalExtension();
        $relativePath = $cover->storeAs("covers/{$title->slug}", "title.{$extension}", 'public');
        $title->update(['cover_image' => '/storage/' . $relativePath]);
    }
}
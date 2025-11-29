<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $portfolioItems = $user->portfolioItems()->latest()->get();
        
        return view('profile.show', compact('user', 'portfolioItems'));
    }

    public function uploadPortfolio(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:image,link',
            'file' => 'required_if:type,image|image|mimes:jpeg,png,jpg,gif|max:5120',
            'link_url' => 'required_if:type,link|url|max:500',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
        ];

        if ($request->type === 'image' && $request->hasFile('file')) {
            $path = $request->file('file')->store('portfolio', 'public');
            $data['file_path'] = $path;
        } elseif ($request->type === 'link') {
            $data['link_url'] = $request->link_url;
        }

        PortfolioItem::create($data);

        return redirect()->route('profile')->with('success', 'Item adicionado ao portfólio!');
    }

    public function deletePortfolioItem($id)
    {
        $item = PortfolioItem::where('id', $id)
                            ->where('user_id', auth()->id())
                            ->firstOrFail();

        if ($item->file_path) {
            Storage::disk('public')->delete($item->file_path);
        }

        $item->delete();

        return redirect()->route('profile')->with('success', 'Item removido do portfólio!');
    }
}
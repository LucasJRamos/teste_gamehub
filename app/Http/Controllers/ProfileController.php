<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Mostra o perfil do usuário logado
    public function show()
    {
        $user = auth()->user();
        $portfolioItems = $user->portfolioItems()->latest()->get();
        
        return view('profile.show', compact('user', 'portfolioItems'));
    }

    // Mostra o perfil de outro usuário
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        $portfolioItems = $user->portfolioItems()->latest()->get();
        $isOwnProfile = auth()->id() === $user->id;
        
        return view('profile.show', compact('user', 'portfolioItems', 'isOwnProfile'));
    }

    // Formulário de edição
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    // Atualizar perfil
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'data_nascimento' => 'required|date',
            'professional_title' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'data_nascimento' => $request->data_nascimento,
            'professional_title' => $request->professional_title,
        ];

        if ($request->hasFile('profile_photo')) {
            // Deletar foto antiga se existir
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return redirect()->route('profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    // Upload de portfólio
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

    // Deletar item do portfólio
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

    // Página de explorar usuários
    public function explore(Request $request)
    {
        $search = $request->input('search');
        
        $users = User::when($search, function ($query, $search) {
            return $query->where('username', 'like', "%{$search}%")
                        ->orWhere('professional_title', 'like', "%{$search}%");
        })
        ->where('id', '!=', auth()->id())
        ->paginate(10);

        return view('profile.explore', compact('users', 'search'));
    }
}
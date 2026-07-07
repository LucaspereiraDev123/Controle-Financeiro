<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('login.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credenciais, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return to_route('dashboard');
        }

        return back()->withErrors([
            'email' => 'E-mail ou senha inválidos!',
        ])->onlyInput('email');
    }

    // aba de registrar
    public function create()
    {
        return view('usuarios.create');
    }

    public function registerStore(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $usuario = Usuario::create([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
            // período de teste inicial; a lógica de billing entra na Fase 3/4
            'trial_ends_at' => now()->addDays(14),
        ]);

        $this->criarCategoriasPadrao($usuario);

        Auth::login($usuario);
        $request->session()->regenerate();

        return to_route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }

    /**
     * Cria um conjunto inicial de categorias para o novo usuário,
     * evitando que ele fique sem opções ao cadastrar a primeira transação.
     */
    private function criarCategoriasPadrao(Usuario $usuario): void
    {
        $padrao = [
            ['nome' => 'Salário', 'tipo' => 'Receitas'],
            ['nome' => 'Outras Receitas', 'tipo' => 'Receitas'],
            ['nome' => 'Alimentação', 'tipo' => 'Despesas'],
            ['nome' => 'Moradia', 'tipo' => 'Despesas'],
            ['nome' => 'Transporte', 'tipo' => 'Despesas'],
            ['nome' => 'Lazer', 'tipo' => 'Despesas'],
        ];

        $usuario->categorias()->createMany($padrao);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class TransacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transacoes = Auth::user()->transacoes()
            ->with('categoria')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('transacoes.index')->with('transacoes', $transacoes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Auth::user()->categorias()->get();

        return view('transacoes.create')->with('categorias', $categorias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $this->validarTransacao($request);
        $dados['usuario_id'] = Auth::id();

        Transacao::create($dados);

        return redirect()->route('dashboard')
            ->with('msg', 'Transação cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transacao $transacao)
    {
        $this->authorize('view', $transacao);

        return view('transacoes.show')->with('transacao', $transacao);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transacao $transacao)
    {
        $this->authorize('update', $transacao);

        $categorias = Auth::user()->categorias()->get();

        return view('transacoes.edit')->with('transacao', $transacao)
            ->with('categorias', $categorias);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transacao $transacao)
    {
        $this->authorize('update', $transacao);

        $transacao->update($this->validarTransacao($request));

        return redirect()->route('dashboard')
            ->with('msg', 'Transação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transacao $transacao)
    {
        $this->authorize('delete', $transacao);

        $transacao->delete();

        return redirect()->route('dashboard')
            ->with('msg', 'Transação excluída com sucesso!');
    }

    /**
     * Valida os campos da transação, garantindo que a categoria
     * escolhida pertença ao usuário autenticado.
     */
    private function validarTransacao(Request $request): array
    {
        return $request->validate([
            'tipo' => ['required', 'string', 'in:Receitas,Despesas'],
            'descricao' => ['required', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0'],
            'categoria_id' => [
                'required',
                'exists:categorias,id,usuario_id,' . Auth::id(),
            ],
        ], [
            'categoria_id.exists' => 'Selecione uma categoria válida.',
        ]);
    }
}

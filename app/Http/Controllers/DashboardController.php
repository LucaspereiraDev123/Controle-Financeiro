<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Quantidade de transações exibidas por página.
     */
    private const POR_PAGINA = 20;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('dashboard.index', $this->montarResumo($request));
    }

    public function filtroDashboard(Request $request)
    {
        return view('dashboard.index', $this->montarResumo($request));
    }

    /**
     * Monta os totais (sobre TODO o conjunto filtrado) e a lista de
     * transações paginada (apenas a página atual) para a view.
     */
    private function montarResumo(Request $request): array
    {
        $query = $this->construirQuery($request);

        // Totais calculados no banco sobre o conjunto filtrado completo,
        // independentemente da paginação da tabela.
        $receitas = (clone $query)->where('tipo', 'Receitas')->sum('valor');
        $despesas = (clone $query)->where('tipo', 'Despesas')->sum('valor');
        $saldo = $receitas - $despesas;

        // Apenas a página atual é carregada para exibição.
        $transacoes = $query->orderByDesc('created_at')
            ->paginate(self::POR_PAGINA)
            ->withQueryString();

        $categorias = Auth::user()->categorias()->get();
        $grafico = $this->serieMensal();

        return compact('transacoes', 'categorias', 'saldo', 'receitas', 'despesas', 'grafico');
    }

    /**
     * Série de receitas x despesas dos últimos 6 meses (sempre do usuário
     * logado, independentemente dos filtros da tabela), para o gráfico.
     *
     * @return array{labels: string[], receitas: float[], despesas: float[]}
     */
    private function serieMensal(): array
    {
        $meses = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $inicio = now()->startOfMonth()->subMonths(5);

        $labels = [];
        $receitas = [];
        $despesas = [];

        for ($i = 0; $i < 6; $i++) {
            $mes = (clone $inicio)->addMonths($i);
            $labels[] = $meses[$mes->month] . '/' . $mes->format('y');

            $base = Auth::user()->transacoes()
                ->whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month);

            $receitas[] = (float) (clone $base)->where('tipo', 'Receitas')->sum('valor');
            $despesas[] = (float) (clone $base)->where('tipo', 'Despesas')->sum('valor');
        }

        return compact('labels', 'receitas', 'despesas');
    }

    /**
     * Monta a query base das transações do usuário, aplicando os filtros
     * enviados pelo formulário (quando presentes).
     */
    private function construirQuery(Request $request)
    {
        $query = Auth::user()->transacoes()->with('categoria');

        // filtro por tipo (o formulário envia o campo "entrada")
        if ($request->entrada) {
            $query->where('tipo', $request->entrada);
        }

        // filtro por categoria
        if ($request->categoria) {
            $query->where('categoria_id', $request->categoria);
        }

        // busca por descrição
        if ($request->busca) {
            $query->where('descricao', 'like', '%' . $request->busca . '%');
        }

        // filtro por período (mês/ano)
        if ($request->periodo) {
            $query->whereMonth('created_at', substr($request->periodo, 5, 2))
                ->whereYear('created_at', substr($request->periodo, 0, 4));
        }

        return $query;
    }
}

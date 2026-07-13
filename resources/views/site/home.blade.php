@extends('layouts.publico')

@section('titulo', 'Controle financeiro pessoal simples')
@section('main_classe', 'site-landing')

@section('conteudo')
    {{-- 1. Barra de oferta (honesta) --}}
    <div class="lp-topbar">
        ✨ Teste grátis por 14 dias — sem cartão de crédito.
        <a href="{{ route('register') }}">Começar agora</a>
    </div>

    {{-- 2. Hero --}}
    <section class="lp-hero">
        <span class="lp-hero-badge">● Controle financeiro pessoal, sem planilha</span>
        <h1>Seu dinheiro, na <span class="destaque">conta certa</span>.</h1>
        <p class="lp-hero-sub">
            O <strong>Economiza Certo</strong> ajuda você a registrar receitas e despesas,
            organizar por categorias e enxergar seu saldo em tempo real. Simples, direto e no seu controle.
        </p>
        <div class="lp-hero-acoes">
            <a href="{{ route('register') }}" class="site-btn site-btn-grande">Começar grátis</a>
            <a href="#recursos" class="lp-btn-sec">Ver recursos</a>
        </div>
        <div class="lp-hero-chips">
            <span>🔒 Seus dados isolados</span>
            <span>✓ 14 dias grátis</span>
            <span>✓ Setup em 2 minutos</span>
            <span>✓ Cancele quando quiser</span>
        </div>
    </section>

    {{-- 3. Números (fatos honestos, com contagem animada) --}}
    <section class="lp-secao lp-secao--alt">
        <div class="lp-inner lp-stats">
            <div>
                <div class="lp-stat-num" data-alvo="14">0</div>
                <div class="lp-stat-rotulo">dias grátis para testar</div>
            </div>
            <div>
                <div class="lp-stat-num" data-alvo="19.90" data-prefixo="R$ " data-decimais="2">0</div>
                <div class="lp-stat-rotulo">por mês, um plano só</div>
            </div>
            <div>
                <div class="lp-stat-num" data-alvo="6">0</div>
                <div class="lp-stat-rotulo">meses de histórico no gráfico</div>
            </div>
            <div>
                <div class="lp-stat-num" data-alvo="100" data-sufixo="%">0</div>
                <div class="lp-stat-rotulo">dos seus dados só seus</div>
            </div>
        </div>
    </section>

    {{-- 5. Como funciona --}}
    <section class="lp-secao">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">Simples de começar</span>
            <h2 class="lp-secao-titulo">4 passos para organizar seu dinheiro</h2>
            <p class="lp-secao-sub">Do cadastro ao controle do saldo em poucos minutos.</p>
        </div>
        <div class="lp-inner lp-passos">
            <div class="lp-passo">
                <div class="lp-passo-num">1</div>
                <h3>Crie sua conta</h3>
                <p>Cadastro grátis em segundos, com 14 dias para testar sem cartão.</p>
            </div>
            <div class="lp-passo">
                <div class="lp-passo-num">2</div>
                <h3>Registre</h3>
                <p>Lance suas receitas e despesas do dia a dia, sem complicação.</p>
            </div>
            <div class="lp-passo">
                <div class="lp-passo-num">3</div>
                <h3>Organize</h3>
                <p>Agrupe tudo por categorias que fazem sentido para a sua vida.</p>
            </div>
            <div class="lp-passo">
                <div class="lp-passo-num">4</div>
                <h3>Acompanhe</h3>
                <p>Veja saldo, gráficos e para onde o seu dinheiro está indo.</p>
            </div>
        </div>
    </section>

    {{-- 6. Calculadora de economia --}}
    <section class="lp-secao lp-secao--alt">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">Faça as contas</span>
            <h2 class="lp-secao-titulo">Quanto dá para sobrar no fim do mês?</h2>
            <p class="lp-secao-sub">
                Simule quanto você pode economizar cortando gastos ao enxergar para onde vai o dinheiro.
            </p>
        </div>
        <div class="lp-inner">
            <div class="lp-calc">
                <div>
                    <div class="lp-calc-campo">
                        <label>Gasto mensal <strong id="calc-gasto-lbl">R$ 3.000</strong></label>
                        <input type="range" id="calc-gasto" min="500" max="20000" step="100" value="3000">
                    </div>
                    <div class="lp-calc-campo">
                        <label>Quanto dá para cortar organizando <strong id="calc-pct-lbl">15%</strong></label>
                        <input type="range" id="calc-pct" min="5" max="30" step="1" value="15">
                    </div>
                    <p class="lp-calc-nota">
                        * Estimativa ilustrativa. O resultado real depende dos seus hábitos e escolhas.
                    </p>
                </div>
                <div class="lp-calc-resultado">
                    <div class="lp-calc-eco-rot">Você pode economizar por mês</div>
                    <div class="lp-calc-eco" id="calc-mes">R$ 450</div>
                    <div class="lp-calc-ano">ou <span id="calc-ano">R$ 5.400</span> por ano</div>
                    <a href="{{ route('register') }}" class="site-btn" style="margin-top:1.5rem;">Começar a economizar</a>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. Recursos reais --}}
    <section class="lp-secao" id="recursos">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">Plataforma completa</span>
            <h2 class="lp-secao-titulo">Tudo que você precisa para se organizar</h2>
            <p class="lp-secao-sub">Recursos simples e diretos para colocar as contas em ordem.</p>
        </div>
        <div class="lp-inner lp-cards">
            <div class="lp-card">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                <h3>Receitas e despesas</h3>
                <p>Cadastre suas transações e mantenha o histórico sempre organizado.</p>
            </div>
            <div class="lp-card">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h10"/></svg></div>
                <h3>Categorias</h3>
                <p>Agrupe seus lançamentos por categoria e entenda para onde vai o dinheiro.</p>
            </div>
            <div class="lp-card">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
                <h3>Saldo em tempo real</h3>
                <p>Total, receitas, despesas e saldo calculados automaticamente.</p>
            </div>
            <div class="lp-card">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19V5M4 19h16M8 15l3-4 3 2 4-6"/></svg></div>
                <h3>Gráficos</h3>
                <p>Compare receitas e despesas dos últimos 6 meses num relance.</p>
            </div>
            <div class="lp-card">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z"/><path d="M9.5 12l1.8 1.8L15 10"/></svg></div>
                <h3>Seus dados, só seus</h3>
                <p>Cada conta é isolada. Ninguém mais enxerga o que é seu.</p>
            </div>
            <div class="lp-card">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 8l10 6 10-6"/></svg></div>
                <h3>Acesso de qualquer lugar</h3>
                <p>Use no navegador, sem instalar nada. E-mails para verificar e recuperar a conta.</p>
            </div>
        </div>
    </section>

    {{-- 8. Em breve / roadmap --}}
    <section class="lp-secao lp-secao--verde">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">No forno</span>
            <h2 class="lp-secao-titulo">O que vem por aí</h2>
            <p class="lp-secao-sub">
                Recursos que estamos construindo. Ainda não estão disponíveis — mas já dá para sonhar.
            </p>
        </div>
        <div class="lp-inner lp-cards">
            <div class="lp-card">
                <span class="lp-badge-breve">Em breve</span>
                <h3>Alertas inteligentes</h3>
                <p>Avisos quando um gasto sair do seu padrão habitual.</p>
            </div>
            <div class="lp-card">
                <span class="lp-badge-breve">Em breve</span>
                <h3>Metas de economia</h3>
                <p>Defina objetivos e acompanhe o quanto falta para bater a meta.</p>
            </div>
            <div class="lp-card">
                <span class="lp-badge-breve">Em breve</span>
                <h3>Previsão de gastos</h3>
                <p>Projeção do fechamento do mês com base no seu histórico.</p>
            </div>
            <div class="lp-card">
                <span class="lp-badge-breve">Em breve</span>
                <h3>App no celular</h3>
                <p>Registre um gasto na hora, de onde você estiver.</p>
            </div>
        </div>
    </section>

    {{-- 10. Integrações --}}
    <section class="lp-secao">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">Integrações</span>
            <h2 class="lp-secao-titulo">Conectado ao que importa</h2>
            <p class="lp-secao-sub">Pagamento seguro e comunicação por e-mail, sem você se preocupar com o resto.</p>
        </div>
        <div class="lp-inner lp-integ">
            <div class="lp-integ-item">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg></div>
                <div>
                    <h3>Mercado Pago</h3>
                    <p>Assinatura paga com segurança. O número do seu cartão nunca passa pelo nosso servidor.</p>
                </div>
            </div>
            <div class="lp-integ-item">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 8l10 6 10-6"/></svg></div>
                <div>
                    <h3>E-mail</h3>
                    <p>Verificação de conta e recuperação de senha chegam direto na sua caixa.</p>
                </div>
            </div>
            <div class="lp-integ-item">
                <div class="lp-card-icone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.5 8.5 0 0 1-12.5 7.5L3 21l2-5.5A8.5 8.5 0 1 1 21 11.5z"/></svg></div>
                <div>
                    <h3>WhatsApp <span class="lp-badge-breve">Em breve</span></h3>
                    <p>Registrar gastos e consultar o saldo por mensagem — em construção.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 11. Depoimentos (modelo) --}}
    <section class="lp-secao lp-secao--alt">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">Depoimentos</span>
            <h2 class="lp-secao-titulo">Quem usa, recomenda</h2>
        </div>
        <div class="lp-inner">
            <p class="lp-aviso-modelo">
                ⚠️ Seção modelo — substitua pelos depoimentos reais dos seus usuários antes de publicar.
            </p>
            <div class="lp-depos">
                <div class="lp-depo">
                    <div class="lp-depo-metrica">[resultado]</div>
                    <p class="lp-depo-texto">"[Depoimento de exemplo — conte aqui como o Economiza Certo ajudou o cliente a se organizar.]"</p>
                    <div class="lp-depo-autor">
                        <span class="lp-depo-avatar">NC</span>
                        <div><strong>[Nome do cliente]</strong><small>[Cidade / ocupação]</small></div>
                    </div>
                </div>
                <div class="lp-depo">
                    <div class="lp-depo-metrica">[resultado]</div>
                    <p class="lp-depo-texto">"[Depoimento de exemplo — destaque um benefício concreto que o cliente percebeu.]"</p>
                    <div class="lp-depo-autor">
                        <span class="lp-depo-avatar">NC</span>
                        <div><strong>[Nome do cliente]</strong><small>[Cidade / ocupação]</small></div>
                    </div>
                </div>
                <div class="lp-depo">
                    <div class="lp-depo-metrica">[resultado]</div>
                    <p class="lp-depo-texto">"[Depoimento de exemplo — uma frase curta e verdadeira funciona melhor.]"</p>
                    <div class="lp-depo-autor">
                        <span class="lp-depo-avatar">NC</span>
                        <div><strong>[Nome do cliente]</strong><small>[Cidade / ocupação]</small></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 12. Plano (único) --}}
    <section class="lp-secao">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">Planos</span>
            <h2 class="lp-secao-titulo">Um plano, sem pegadinha</h2>
            <p class="lp-secao-sub">Tudo incluído. Comece grátis e assine quando quiser continuar.</p>
        </div>
        <div class="lp-inner">
            <div class="lp-plano">
                <h3>Plano Economiza Certo</h3>
                <div class="lp-plano-preco">
                    <span class="lp-plano-valor">R$ 19,90</span>
                    <span class="lp-plano-periodo">/mês</span>
                </div>
                <ul class="lp-plano-itens">
                    <li>Receitas e despesas ilimitadas</li>
                    <li>Categorias personalizadas</li>
                    <li>Saldo e gráficos em tempo real</li>
                    <li>Seus dados isolados e seguros</li>
                    <li>Acesso de qualquer lugar</li>
                </ul>
                <a href="{{ route('register') }}" class="site-btn site-btn-grande" style="width:100%;text-align:center;">Começar grátis</a>
                <p class="lp-plano-nota">14 dias grátis. Sem cartão de crédito. Cancele quando quiser.</p>
            </div>
        </div>
    </section>

    {{-- 13. FAQ --}}
    <section class="lp-secao lp-secao--alt">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">Dúvidas</span>
            <h2 class="lp-secao-titulo">Perguntas frequentes</h2>
        </div>
        <div class="lp-faq">
            <details class="lp-faq-item">
                <summary>Como funciona o teste grátis?</summary>
                <div class="lp-faq-resp">Você cria a conta e usa o Economiza Certo por 14 dias, sem pagar nada e sem informar cartão. Depois é só assinar para continuar.</div>
            </details>
            <details class="lp-faq-item">
                <summary>Preciso de cartão de crédito para começar?</summary>
                <div class="lp-faq-resp">Não. O cadastro e o período de teste não exigem cartão. Ele só é pedido quando você decide assinar.</div>
            </details>
            <details class="lp-faq-item">
                <summary>Meus dados estão seguros?</summary>
                <div class="lp-faq-resp">Sim. Cada conta é isolada — ninguém além de você enxerga seus lançamentos. O pagamento é processado pelo Mercado Pago, então o número do seu cartão nunca passa pelo nosso servidor.</div>
            </details>
            <details class="lp-faq-item">
                <summary>É controle pessoal ou para empresa?</summary>
                <div class="lp-faq-resp">O Economiza Certo é feito para finanças pessoais (pessoa física) — organizar as suas contas do dia a dia.</div>
            </details>
            <details class="lp-faq-item">
                <summary>Quais são as formas de pagamento?</summary>
                <div class="lp-faq-resp">A assinatura é cobrada via Mercado Pago. As opções disponíveis aparecem no checkout na hora de assinar.</div>
            </details>
            <details class="lp-faq-item">
                <summary>Posso cancelar quando quiser?</summary>
                <div class="lp-faq-resp">Pode. Não há fidelidade nem multa — você cancela a qualquer momento e mantém o acesso até o fim do período já pago.</div>
            </details>
        </div>
    </section>

    {{-- 14. CTA final --}}
    <section class="lp-secao lp-cta-final">
        <div class="lp-inner lp-centro">
            <span class="lp-secao-rotulo">Comece hoje</span>
            <h2 class="lp-secao-titulo">Pronto para deixar seu dinheiro na conta certa?</h2>
            <p class="lp-secao-sub">Crie sua conta grátis e comece a se organizar em poucos minutos.</p>
            <div class="lp-hero-acoes">
                <a href="{{ route('register') }}" class="site-btn site-btn-grande">Começar grátis</a>
                <a href="{{ route('planos') }}" class="lp-btn-sec">Ver o plano</a>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const brl = (v, dec = 0) => 'R$ ' + v.toLocaleString('pt-BR', {
                minimumFractionDigits: dec, maximumFractionDigits: dec
            });

            // --- Contagem animada dos números ---
            const anima = (el) => {
                const alvo = parseFloat(el.dataset.alvo);
                const dec = parseInt(el.dataset.decimais || '0', 10);
                const pre = el.dataset.prefixo || '';
                const suf = el.dataset.sufixo || '';
                const dur = 1100;
                const inicio = performance.now();
                const passo = (agora) => {
                    const t = Math.min((agora - inicio) / dur, 1);
                    const val = alvo * (1 - Math.pow(1 - t, 3)); // easeOutCubic
                    el.textContent = pre + val.toLocaleString('pt-BR', {
                        minimumFractionDigits: dec, maximumFractionDigits: dec
                    }) + suf;
                    if (t < 1) requestAnimationFrame(passo);
                };
                requestAnimationFrame(passo);
            };

            const nums = document.querySelectorAll('.lp-stat-num');
            if ('IntersectionObserver' in window) {
                const obs = new IntersectionObserver((entradas) => {
                    entradas.forEach((e) => {
                        if (e.isIntersecting) { anima(e.target); obs.unobserve(e.target); }
                    });
                }, { threshold: 0.4 });
                nums.forEach((n) => obs.observe(n));
            } else {
                nums.forEach(anima);
            }

            // --- Calculadora de economia ---
            const gasto = document.getElementById('calc-gasto');
            const pct = document.getElementById('calc-pct');
            const gastoLbl = document.getElementById('calc-gasto-lbl');
            const pctLbl = document.getElementById('calc-pct-lbl');
            const mesEl = document.getElementById('calc-mes');
            const anoEl = document.getElementById('calc-ano');

            const recalc = () => {
                const g = parseInt(gasto.value, 10);
                const p = parseInt(pct.value, 10);
                const eco = g * p / 100;
                gastoLbl.textContent = brl(g);
                pctLbl.textContent = p + '%';
                mesEl.textContent = brl(eco);
                anoEl.textContent = brl(eco * 12);
            };

            if (gasto && pct) {
                gasto.addEventListener('input', recalc);
                pct.addEventListener('input', recalc);
                recalc();
            }
        })();
    </script>
@endsection

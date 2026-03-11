<x-layouts::marketing>
    <div class="flex flex-col w-full space-y-10">
        <nav class="w-full flex justify-between items-center px-8 h-20 border-b border-zinc-100 z-20">
            <div class="text-2xl black uppercase text-white tracking-tighter">
                RPG<span class="text-orange-500">ATE</span>
            </div>

            <div class="flex items-center gap-4">
                <flux:button
                    href="{{ route('login') }}"
                    icon="login"
                    variant="ghost"
                    class="!text-zinc-400 hover:!text-white"
                    wire:navigate
                >
                    Entrar
                </flux:button>
                <flux:button
                    href="{{ route('register') }}"
                    icon="user-plus"
                    variant="primary"
                    class="!bg-white !text-black hover:!bg-zinc-200 font-bold"
                    wire:navigate
                >
                    Cadastrar
                </flux:button>
            </div>
        </nav>
        
        <section class="relative flex-1 flex flex-col items-center justify-center text-center px-6 overflow-hidden bg-orange-200">
        
            {{-- Fundo (Preto agora, imagem depois) --}}
            <div class="absolute inset-0 z-0 bg-black">
                {{-- <img src="..." class="w-full h-full object-cover opacity-30"> --}}
                <div class="w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-zinc-900/40 via-black to-black"></div>
            </div>

            {{-- Slogan e Botão Centralizados --}}
            <div class="relative z-10 max-w-5xl space-y-12">
                <h1 class="text-4xl md:text-7xl font-black text-white uppercase italic">
                    Mestre, <span class="text-orange-500">Automatize.</span><br>
                    Aventureiro, <span class="text-zinc-500">Evolua.</span>
                </h1>

                <p class="md:text-2xl text-zinc-500 max-w-2xl mx-auto font-medium italic">
                    A plataforma definitiva para transformar suas campanhas em histórias épicas.
                </p>

                <div class="pt-6">
                    <flux:button href="{{ route('register') }}" variant="primary" class="!h-16 px-12 !text-2xl font-black uppercase italic tracking-widest !bg-orange-500 hover:!bg-orange-500 transition-transform hover:scale-105 shadow-2xl shadow-orange-900/40">
                        Iniciar Jornada
                    </flux:button>
                </div>
            </div>
        </section>
    </div>
</x-layouts::marketing>
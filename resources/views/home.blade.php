@extends('layouts.app')

@section('content')
<div class="container">
    <div class="h-screen flex">
        <!-- Sidebar -->
        <nav class="w-1/5 bg-gray-800 text-white p-5">
          <h2 class="text-xl font-bold">Meu Financeiro</h2>
          <ul class="mt-5">
            <li class="my-2"><a href="#" class="hover:text-gray-300">Dashboard</a></li>
            <li class="my-2"><a href="#" class="hover:text-gray-300">Gastos Diários</a></li>
            <li class="my-2"><a href="#" class="hover:text-gray-300">Gastos Recorrentes</a></li>
            <li class="my-2"><a href="#" class="hover:text-gray-300">Metas de Investimento</a></li>
            <li class="my-2"><a href="#" class="hover:text-gray-300">Cartões de Crédito</a></li>
          </ul>
        </nav>
      
        <!-- Main Content -->
        <div class="w-4/5 p-5">
          <header class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <button class="bg-red-500 text-white px-4 py-2 rounded">Sair</button>
          </header>
      
          <section class="mt-5">
            <!-- Conteúdo -->
            <p>Bem-vindo ao sistema financeiro!</p>
          </section>
        </div>
      </div>
      
</div>
@endsection

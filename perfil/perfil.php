<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Git Club - Perfil</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <!-- ==================================================
         CABEÇALHO
    =================================================== -->

    <header class="cabecalho-heroi">

        <!-- LOGO -->

        <div class="logo-heroi">
            <span>Git</span> Club
        </div>


        <!-- MENU -->

        <nav class="menu-navegacao">

            <a href="../projetos/projetos.php">
                Projetos
            </a>

            <a href="../comentarios/comentarios.php">
                Comentários
            </a>

            <a href="../curtidas/curtidas.php">
                Curtidas
            </a>

            <a href="../seguidores/seguidores.php">
                Seguidores
            </a>

            <a href="../seguindo/seguindo.php">
                Seguindo
            </a>

            <a href="../colab/Colaboradores.php">
                Colaboradores
            </a>

            <a href="../posts/postagens.php">
                Postagens
            </a>

            <!-- PÁGINA ATUAL -->

            <a href="../perfil/perfil.php" class="ativo">
                Perfil
            </a>

        </nav>


        <!-- VOLTAR -->

        <div class="acoes-header">

            <a href="../feed.php" class="botao-voltar">
                ← Voltar
            </a>

        </div>

    </header>



    <!-- ==================================================
         CONTEÚDO
    =================================================== -->

    <main class="conteudo">


        <!-- TÍTULO -->

        <div class="titulo-pagina">

            <span class="icone">
                👤
            </span>

            <div>

                <h1>Meu Perfil</h1>

                <p>
                    Visualize suas informações e seus projetos.
                </p>

            </div>

        </div>



        <!-- ==================================================
             PERFIL
        =================================================== -->

        <section class="perfil-container">


            <!-- CARTÃO PRINCIPAL -->

            <div class="perfil-card">


                <!-- FOTO / AVATAR -->

                <div class="perfil-avatar">
                    JS
                </div>


                <!-- INFORMAÇÕES -->

                <div class="perfil-informacoes">

                    <h2>
                        Josney Silva
                    </h2>

                    <span class="perfil-usuario">
                        @Josney556
                    </span>

                    <p class="perfil-bio">
                        Estou fazendo uns cursos aí e
                        desenvolvendo alguns projetos.
                    </p>

                    <div class="perfil-detalhes">

                        <span>
                            📍 SP
                        </span>

                        <span>
                            ✉️ josneysilva@etc.com
                        </span>

                    </div>

                </div>

            </div>



            <!-- ==================================================
                 PROJETOS
            =================================================== -->

            <div class="perfil-projetos">


                <div class="perfil-projetos-titulo">

                    <h2>
                        Meus Projetos
                    </h2>

                    <span>
                        2 projetos
                    </span>

                </div>


                <div class="perfil-projetos-grid">


                    <!-- PROJETO GTA -->

                    <div class="perfil-projeto-card">

                        <div class="perfil-projeto-icone">
                            🚗
                        </div>

                        <div>

                            <h3>
                                GTA 6
                            </h3>

                            <p>
                                Projeto privado.
                            </p>

                            <span class="status privado">
                                🔒 Privado
                            </span>

                        </div>

                    </div>


                    <!-- PROJETO MINICRAFT -->

                    <div class="perfil-projeto-card">

                        <div class="perfil-projeto-icone">
                            🎮
                        </div>

                        <div>

                            <h3>
                                Minecraft 2
                            </h3>

                            <p>
                                Projeto público.
                            </p>

                            <span class="status publico">
                                🔓 Público
                            </span>

                        </div>

                    </div>


                </div>


                <!-- VER MAIS -->

                <a
                    href="../projetos/projetos.php"
                    class="ver-projetos"
                >
                    Ver todos os projetos →
                </a>


            </div>

        </section>

    </main>

</body>

</html>

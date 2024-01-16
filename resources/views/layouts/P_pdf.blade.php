<html>
    <head>
        <title>@yield('title', 'Relatório')</title>
        <style>
            /** Define the margins of your page **/
            @page {
                margin: 140px 25px 120px 25px;
            }

            header {
                position: fixed;
                top: -110px;
                left: 0px;
                right: 0px;
                height: 83px;

                /** Extra personal styles **/
                background-color: white;
                color: black;
                font-family: "Arial, Helvetica, sans-serif";
                text-align: center;
                line-height: 25px;
            }

            footer {
                position: fixed; 
                bottom: -80px; 
                left: 0px; 
                right: 0px;
                height: 60px; 

                /** Extra personal styles **/
                background-color: white;
                color: black;
                text-align: center;
                line-height: 25px;
                font-size: 10px;
                font-family: "Arial, Helvetica, sans-serif";
            }

            main {
                font-size: 12;
                font-family: "Arial, Helvetica, sans-serif";
            }
        </style>
        @stack('css')
    </head>
    <body>

        <!-- Define header and footer blocks before your content -->
        <header>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" height="90px">
                <tr>
                <td width="12%" rowspan="3" align="center" valign="middle">
                    @if(Auth::user()-> configuracao && Storage::disk('public')->exists(Auth::user()-> configuracao -> logo1))
                        <img src="{{ public_path('storage/'.Auth::user()-> configuracao -> logo1) }}" alt="logo1" width="" height="82">
                    @else
                        <img src="{{ public_path('storage/logos/RJ/logo1.png') }}" alt="logo1" width="" height="82"></td>
                    @endif
                <td width="70%" align="center">
                    <strong>
                    @if(Auth::user()-> configuracao)
                    {{  Auth::user() -> configuracao -> titulo1 }}
                    @else
                        {{'PREFEITURA DA CIDADE DO RIO DE JANEIRO'}}
                    @endif
                    </strong>
                </td>
                <td width="12%" rowspan="3" align="center" valign="middle" >
                    @if(Auth::user()-> configuracao && Storage::disk('public')->exists(Auth::user()-> configuracao -> logo2))
                        <img src="{{ public_path('storage/'.Auth::user()-> configuracao -> logo2) }}" alt="logo2" width="" height="82">
                    @else
                        <img src="{{ public_path('storage/logos/RJ/logo2.png') }}" alt="logo2" width="" height="82"></td>
                    @endif
                </tr>
                <tr>
                <td align="center">
                    <strong>
                        @if(Auth::user()-> configuracao)
                        {{  Auth::user() -> configuracao -> titulo2 }}
                        @else
                        {{'SUBSECRETARIA DE PROTEÇÃO E DEFESA CIVIL'}}
                        @endif
                    </strong>
                </td>
                </tr>
                <tr>
                <td align="center">
                    <strong>
                        @if(Auth::user()-> Setor)
                        {{  Auth::user() -> Setor -> setor }}
                        @else
                        {{''}}
                        @endif
                    </strong>
                </td>
                </tr>
            </table>
        </header>

        <footer>
            <table style="font-size: 8px" border="0" cellspacing="0" cellpadding="0" width="100%" height="70px">
                <tr>
                    <td align="center">
                        @if(Auth::user() -> configuracao)
                        {{ Auth::user() -> configuracao -> endereco }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td align="center">
                       @if(Auth::user() -> configuracao)
                       {{ Auth::user() -> configuracao -> telefone1 }}
                       @endif
                       
                       @if(Auth::user() -> configuracao)
                       {{ " / ".Auth::user() -> configuracao -> telefone1 }}
                       @endif
                       
                       @if(Auth::user() -> configuracao)
                       {{ " - Emergência: ".Auth::user() -> configuracao -> emergencia }}
                       @endif
                       
                       @if(Auth::user() -> configuracao)
                       {{ " - E-mail: ".Auth::user() -> configuracao -> email }}
                       @endif
                    </td>
                </tr>
            </table> 
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            @yield('conteudo')
        </main>

        <script type='text/php'>
            if ( isset($pdf) ) { 
                $pdf->page_script('
                        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                        $size = 7;
                        $pageText = "Página " . $PAGE_NUM."/".$PAGE_COUNT ;
                        $y = $pdf->get_height() - 30;
                        $x = ($pdf->get_width()/2 - $font - $size);
                        $pdf->text(280, $y, $pageText, $font, $size, array(0,0,0) );
                ');
            }
        </script>
    </body>
</html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Tamanhos e Cores</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .text-uppercase {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Formulário de Tamanhos e Cores</h2>
        <form id="formulario">
            <div class="form-group">
                <label for="codigoPai">Código PAI:</label>
                <input type="text" class="form-control" id="codigoPai" name="codigoPai" placeholder="Insira o código PAI">
            </div>
            <div class="form-group">
                <label for="sizes">Digite os tamanhos separados por vírgula:</label>
                <input type="text" class="form-control text-uppercase" id="sizes" name="sizes" placeholder="Ex: PP, P, M, G, GG, EXG">
            </div>
            <div class="form-group">
                <label for="colors">Insira os nomes das cores (separados por vírgula):</label>
                <input type="text" class="form-control text-uppercase" id="colors" name="colors" placeholder="Ex: VERMELHO, AZUL, VERDE">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <div class="mt-4">
            <h5>Resultado:</h5>
            <div id="resultado" class="border p-3" style="min-height: 50px;">
                <!-- O conteúdo será exibido aqui -->
            </div>
            <button id="copiar" class="btn btn-secondary mt-2">Copiar Conteúdo</button>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('formulario').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const codigoPai = document.getElementById('codigoPai').value.trim();
            const tamanhos = document.getElementById('sizes').value.trim().toUpperCase().split(',').map(tamanho => tamanho.trim());
            const cores = document.getElementById('colors').value.trim().toUpperCase().split(',').map(cor => cor.trim());
            let resultado = '';

            cores.forEach(cor => {
                tamanhos.forEach(tamanho => {
                    resultado += `${codigoPai}-${tamanho}-${cor}\n`;
                });
            });

            document.getElementById('resultado').innerText = resultado;
        });

        document.getElementById('copiar').addEventListener('click', function() {
            const resultadoDiv = document.getElementById('resultado');
            const range = document.createRange();
            range.selectNode(resultadoDiv);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            try {
                document.execCommand('copy');
                alert('Conteúdo copiado!');
            } catch (err) {
                alert('Erro ao copiar o conteúdo');
            }
            window.getSelection().removeAllRanges();
        });
    </script>
</body>
</html>

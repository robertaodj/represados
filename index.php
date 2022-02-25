<?php require 'tratandoStatus.php'; ?> <!-- Puxando o tratamento do status do GET -->

<!DOCTYPE html>
<html lang="pt-br">
    <head>
    <title>Enviar Arquivos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    </head>
    <body>

        <div class="container mt-5" style="max-width: 700px;">
            <h2 class="text-center display-6 mb-3">Upload arquivo CSV</h2>

            <div class="border border-3 rounded-3 p-5 pt-4">
                <h2 class="display-6 mb-4" style="font-size: 30px;">Enviando para uploadArquivo.php</h2>

                <div class="form-group">
                    <form action="uploadArquivo.php" method="POST" enctype="multipart/form-data"> <!-- Começo formulário -->

                        <div class="row">

                            <div class="col">
                                <label  class="form-label" style="width: 100%;">
                                    <p class="form-text" style="margin-bottom: -1px;">Nome do arquivo (Opcional)</p>
                                    <input type="text" class="form-control" name="nomeArquivo" placeholder="Nome do arquivo" aria-label="Nome do arquivo">
                                </label>
                            </div>
                            <div class="col">
                                <label class="form-label" style="width: 100%;"> 
                                    <p class="form-text" style="margin-bottom: -1px;">Data da extração</p>
                                    <input type="datetime-local" class="form-control" name="dataExtracao" placeholder="Data de extração" aria-label="Data de extração" required>
                                </label>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label">
                                <p class="form-text" style="margin-bottom: -1px;">Selecione o arquivo</p>
                            </label>
                            <input class="form-control form-control" name="arquivo" type="file" id="formFile" required>
                        </div>
                
                        <input type="submit" class="btn btn-primary" style="width: 150px;" value="Enviar">


                        <?php if(isset($_GET['status'])) : ?>
                            <div class="alert <?=$status['alert']?> alert-dismissible d-flex align-items-center mt-3">

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                                <ion-icon name="<?=$status['icon']?>" size="large"></ion-icon>

                                <p class="pt-3" style="margin-left: 10px;"><?=$status['info']?>  </p>

                            </div>
                        <?php endif; ?>

                    </form> <!-- Fim formulário -->
                </div>
            </div>
        </div>


         <!-- Bootstrap 5 -->
         <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
        <!-- Ionicons -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </body>
</html>

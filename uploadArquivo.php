<?php
    //Apaga o arquivo text.tx
    // @'arquivos/text.txt' ? unlink('arquivos/text.txt') : '';

    
error_reporting(E_ALL);
ini_set( 'display_errors','1');
       
    // Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = 'arquivos/';
    
    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
    
    // Array com as extensões permitidas
    $_UP['extensoes'] = array('csv');
    
    // Renomeia o arquivo? (Se true, o arquivo será salvo como .csv e um nome único)
    $_UP['renomeia'] = false;
    
    // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
    
    // Faz a verificação da extensão do arquivo
    $tmp = explode('.', $_FILES['arquivo']['name']);

    $extensao = strtolower(end($tmp));


    if ($extensao != 'csv') {

        header('Location: /?status=erro3'); // Erro3 = Arquivo com outra extensão | Podemos mostrar a extensão através do $extensao

    } else if ($_FILES['arquivo']['name'] === '') {

        header('Location: /?status=erro6'); //erro=6 nenhum arquivo foi enviado

    } else {

        // Faz a verificação do tamanho do arquivo
        if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {

            header('Location: /?status=erro4'); // Erro4 = Arquivo muito grande, maior que 2MB

        } else {

            // Primeiro verifica se deve trocar o nome do arquivo
            if ($_UP['renomeia'] == true) {
                // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
                $nome_final = time().'.csv';

            } else {
                // Mantém o nome original do arquivo
                $nome_final = $_FILES['arquivo']['name'];
            }
 
            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {

             
                $linhasArquivo = 0;
                $linhasArquivo += count(file('arquivos/' . $nome_final)); /* Total de linhas no arquivo */


                $arquivo_final = __DIR__. "\arquivos/" . $nome_final;
                $arquivo_final = str_replace("\\", "/", $arquivo_final); /* Path do arquivo final */

                if ($linhasArquivo <= 4) {

                    $resultado = unlink($arquivo_final); /* Deleta o arquivo após o envio */
                    header('Location: /?status=erro5'); /* Arquivo vázio */

                } else {

                    // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                    /* echo "Upload efetuado com sucesso!"; */

                    /* Caso o arquivo for muito grande ative esta função e comente a da linha 7*/
                    /* ini_set('max_execution_time', 0); */

                    require_once 'conectionDB.php'; /* Conexão com o banco de dados */
 

                    $arquivo_final = __DIR__. "\arquivos/" . $nome_final;

                    $arquivo_final = str_replace("\\", "/", $arquivo_final);

                    /* upload no tabela upload e pegar o ultimo id incrementado e passa-lo para o novo upload na tabela represados */


                    $sqlUpload = $pdo->prepare("INSERT INTO uploads (name_upload, date_extracao, date_upload) VALUES (:name_upload, :data_extracao, CURRENT_TIMESTAMP)");

                    if ($_POST['nomeArquivo']) { // Caso o nome do arquivo for digitado vai aparecer aqui

                        $sqlUpload->bindValue(':name_upload', $_POST['nomeArquivo']);

                    } else {

                        $sqlUpload->bindValue(':name_upload', $_FILES['arquivo']['name']);
                    }

                    $sqlUpload->bindValue(':data_extracao', $_POST['dataExtracao']);
                    $sqlUpload->execute();
                    $uploadLastIncremment = $pdo->lastInsertId();



                    // Upload para a tabela represados ultimo
                    // Precisamos comparar os dados agora

                    
                    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,FALSE);  
                    $sql = $pdo->prepare("LOAD DATA LOCAL INFILE :arquivo INTO TABLE represadosultimo
                    FIELDS TERMINATED BY ','
                    LINES TERMINATED BY '\n'
                    IGNORE 1 ROWS 
                    SET upload = :upload, id_represados = NULL");
                    $sql->bindValue(':upload', $uploadLastIncremment);
                    $sql->bindValue(':arquivo', $arquivo_final);
                    $sqlres = $sql->execute();
  
                    if($sqlres){
                        echo 'carregou com sucesso!';  
 
                    }
                    else{
                        echo 'erro no carregamento! do arquivo: '. $arquivo_final; die;
                    }

                    $contanto = 0;

                    // Fazer a comparação e enviar a diferença para a tabela diferenca
                    // Compara a última com a penultima
                   

                    // Comparamos as duas tabelas, caso tenha algum registro diferente será savlo
                    $sql = $pdo->prepare("SELECT *
                        FROM ( 
                            SELECT der, protocolo, servicos,	siape_numerico,	dias_represados,	dt_extracao,	upload,	observacao, status, motivo_status,	dt_previsao_conclusao,	create_at,	update_at,	delete_at
                            FROM represados
                            WHERE upload = :upload
                            UNION ALL
                            SELECT der, protocolo, servicos,	siape_numerico,	dias_represados,	dt_extracao,	upload,	observacao, status, motivo_status,	dt_previsao_conclusao,	create_at,	update_at,	delete_at
                            FROM represadosultimo
                        )  represados
                        GROUP BY observacao, status,  motivo_status
                        HAVING COUNT(*) = 1");
                        $sql->bindValue(':upload', ($uploadLastIncremment -1));
                        $sql->execute();


                    // Vamos buscar os dados na tabela represados para ter o ID e salvar na tabela DIFERENCA
                    if ($sql->rowCount() > 0) {
                        $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($dados as $itens) {    

                            // Podemos colocar quantas condiçoes quisermos para buscar na tabela REPRESADOS
                            if ($itens['observacao'] != NULL) {

                                $sqlFind = $pdo->prepare("INSERT INTO diferenca SELECT * FROM represados WHERE observacao = :observacao");
                                $sqlFind->bindValue(':observacao', $itens['observacao']);
                                $sqlFind->execute();
                                continue;

                            } else if ($itens['status'] != NULL) {

                                $sqlFind = $pdo->prepare("INSERT INTO diferenca SELECT * FROM represados WHERE status = :status");
                                $sqlFind->bindValue(':status', $itens['status']);
                                $sqlFind->execute();  
                                continue;     

                            } else if ($itens['motivo_status'] != NULL) {

                                $sqlFind = $pdo->prepare("INSERT INTO diferenca diferenca SELECT * FROM represados WHERE motivo_status = :motivo_status");
                                $sqlFind->bindValue(':motivo_status', $itens['motivo_status']);
                                $sqlFind->execute();
                                continue;

                            } // Saída da condição
                            $contanto++;
                        } // Saída do foreach  
                    }

                    // Ultimo passo, vai enviar os dados para a tabela represados e apagar os seus dados
                    $sql = $pdo->query("INSERT INTO represados SELECT * FROM represadosultimo; DELETE FROM represadosultimo");
                    $sql->execute();
                    

                    //unlink($arquivo_final); /* Apaga o arquivo inserido da pasta arquivos */   

                    if ($sql) {

                        header('Location: /?status=enviado&'.$contanto);

                    } else {

                        header('Location: /?status=erro2'); /* erro2 : não foi possível enviar os dados */
                    }
                }

            } else {
                header('Location: /?status=erro1'); /* erro1: não foi possível enviar o arquivo */
            }         
        }
    }   
?>
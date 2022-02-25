<?php

    try {
        /* Conexão com o banco de dados */
        $dbType = 'mysql'; /* Tipo */
        $dbName = 'povph_31151588_represados'; /* Nome */
        $dbPath = 'sql308.vphs.top'; /* Local */
        $dbCharset = 'utf8'; /* Agrupamento */
        $dbUser = 'povph_31151588'; /* Nome do Usuário */
        $dbPass = 'crocodilo0210'; /* Senha do Usuário */

        $pdo = new PDO( /* Conexão com o banco de dados */
            "{$dbType}:dbname={$dbName};host={$dbPath};charset={$dbCharset}",
            $dbUser,
            $dbPass,
            [\PDO::MYSQL_ATTR_LOCAL_INFILE => true] 
        );
    } catch (PDOException $e) {
        echo 'Não foi possível conectar ao banco de dados.<br>';
        echo 'Erro: ' . $e;
    }
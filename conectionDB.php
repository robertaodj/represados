<?php

    try {
        /* Conexão com o banco de dados */
        $dbType = 'mysql'; /* Tipo */
        $dbName = 'represados'; /* Nome */
        $dbPath = 'localhost'; /* Local */
        $dbCharset = 'utf8'; /* Agrupamento */
        $dbUser = 'root'; /* Nome do Usuário */
        $dbPass = ''; /* Senha do Usuário */

        $pdo = new PDO( /* Conexão com o banco de dados */
            "{$dbType}:dbname={$dbName};host={$dbPath}:3307;charset={$dbCharset}",
            $dbUser,
            $dbPass,
            [\PDO::MYSQL_ATTR_LOCAL_INFILE => true] 
        );
    } catch (PDOException $e) {
        echo 'Não foi possível conectar ao banco de dados.<br>';
        echo 'Erro: ' . $e;
    }
<?php


    try {
        
        /* Conexão com o banco de dados */
        $dbType = 'mysql'; /* Tipo */
        $dbName = 'd9tfkt0bt6tyhm2p'; /* Nome */
        $dbPath = 'uyu7j8yohcwo35j3.cbetxkdyhwsb.us-east-1.rds.amazonaws.com'; /* Local */
        $dbCharset = 'utf8'; /* Agrupamento */
        $dbUser = 'fojzkoydhafbgmja'; /* Nome do Usuário */
        $dbPass = 'xfokw1yvp9iw4o83'; /* Senha do Usuário */

        $pdo = new PDO( /* Conexão com o banco de dados */
            "{$dbType}:dbname={$dbName};host={$dbPath};charset={$dbCharset}",
            $dbUser,
            $dbPass,
            [\PDO::MYSQL_ATTR_LOCAL_INFILE => true] 
        );

       // mysql://fojzkoydhafbgmja:xfokw1yvp9iw4o83@uyu7j8yohcwo35j3.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/d9tfkt0bt6tyhm2p

    } catch (PDOException $e) {
        echo 'Não foi possível conectar ao banco de dados.<br>';
        echo 'Erro: ' . $e;
    }
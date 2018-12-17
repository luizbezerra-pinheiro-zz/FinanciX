<?php



class Database

{



    /**

     * Function connect

     *

     * Connexion a la base locale

     *

     * @return boolean|array false si erreur, connexion a la base sinon

     */

    public static function connect()

    {

        $dbName = 'financix';

        $dbServer = 'localhost';

        $dbUser = 'root';

        $dbPasswd = '';

        $dsn = 'mysql:dbname='.$dbName.';host='.$dbServer;

        $dbh = null;

        try {

            $dbh = new PDO($dsn, $dbUser, $dbPasswd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            //$message = 'Echec connexion à la base : '.$e->getMessage();

            return false;

        }



        return $dbh;

    }

}
?>
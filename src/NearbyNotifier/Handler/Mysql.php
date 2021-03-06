<?php
namespace NearbyNotifier\Handler;

use NearbyNotifier\Entity\Pokemon;
use PDO;

/**
 * Class Mysql
 *
 * Super simple example to add Pokemon to Mysql
 *
 * @package NearbyNotifier\Handler
 * @author Freek Post <freek@kobalt.blue>
 */
class Mysql extends Handler
{

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $table;

    /**
     * Mysql constructor.
     *
     * @param string $host
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string $table
     *
     * @param array $filters
     */
    public function __construct(string $host, string $database, string $username, string $password, string $table = 'pokemon', array $filters = [])
    {
        parent::__construct($filters);
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->table = $table;
    }

    /**
     * Handle
     *
     * @param Pokemon $pokemon
     * @param bool $newEncounter
     */
    public function handle(Pokemon $pokemon, bool $newEncounter)
    {
        if ($newEncounter) {
            $query = $this->getPdo()->prepare("INSERT IGNORE INTO {$this->table} VALUES(:encounter, :pokemon, :spawnPoint, :ts, :latitude, :longitude, :tth, :pokemonName)");
            $query->bindValue('encounter', $pokemon->getEncounterId(), PDO::PARAM_INT);
            $query->bindValue('pokemon', $pokemon->getPokemonId(), PDO::PARAM_INT);
            $query->bindValue('spawnPoint', $pokemon->getSpawnPoint());
            $query->bindValue('ts', $pokemon->getTimestamp(), PDO::PARAM_INT);
            $query->bindValue('latitude', $pokemon->getLatitude());
            $query->bindValue('longitude', $pokemon->getLongitude());
            $query->bindValue('tth', $pokemon->getExpiry()->getTimestamp(), PDO::PARAM_INT);
            $query->bindValue('pokemonName', $pokemon->getName());
            $query->execute();
        }
    }

    /**
     * Get the connection
     *
     * @return PDO
     */
    protected function getPdo()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database, $this->username, $this->password);
        }

        return $this->pdo;
    }
}

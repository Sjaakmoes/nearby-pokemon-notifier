<?php
namespace NearbyNotifier\Entity;

use DateTime;
use DateInterval;
use JsonSerializable;
use POGOProtos\Enums\PokemonId;

/**
 * Class Pokemon
 *
 * @package NearbyNotifier\Entity
 * @author Freek Post <freek.post@onecube.nl>
 */
class Pokemon implements JsonSerializable
{
    /**
     * @var int
     */
    protected $encounterId;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var DateTime
     */
    protected $expiry;

    /**
     * Pokemon constructor.
     *
     * @param int $id
     * @param float $latitude
     * @param float $longitude
     * @param int $pokemonId
     * @param int $expiry
     */
    public function __construct(int $id, float $latitude, float $longitude, int $pokemonId, int $expiry)
    {
        $this->encounterId = $id;
        $this->id = $pokemonId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        $expires = new DateTime();
        $expires->add(new DateInterval('PT' . floor($expiry / 1000) . 'S'));
        $this->expiry = $expires;
    }

    /**
     * Get the encounter ID
     *
     * @return int
     */
    public function getEncounterId() : int
    {
        return $this->encounterId;
    }

    /**
     * Get the latitude
     *
     * @return float
     */
    public function getLatitude() : float
    {
        return $this->latitude;
    }

    /**
     * Get the longitude
     *
     * @return float
     */
    public function getLongitude() : float
    {
        return $this->longitude;
    }

    /**
     * Get the pokemon's ID
     *
     * @return int
     */
    public function getPokemonId() : int
    {
        return $this->id;
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName() : string
    {
        return ucfirst(strtolower(PokemonId::valueOf($this->id)));
    }

    /**
     * Get expiry
     *
     * @return DateTime
     */
    public function getExpiry() : DateTime
    {
        return $this->expiry;
    }

    /**
     * Check if this pokemon has expired.
     *
     * @return bool
     */
    public function hasExpired() : bool
    {
        $now = new DateTime();
        return $this->expiry <= $now;
    }

    /**
     * Get expiry in total minutes
     *
     * @return int
     */
    public function getExpiryInMinutes() : int
    {
        $now = new DateTime();
        return floor(($this->expiry->getTimestamp() - $now->getTimestamp()) / 60);
    }

    /**
     * To array
     *
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'encounterId' => $this->getEncounterId(),
            'pokemonId' => $this->getPokemonId(),
            'name' => $this->getName(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'expiresAt' => $this->getExpiry()->getTimestamp()
        ];
    }
}

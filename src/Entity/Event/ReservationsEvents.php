<?php

namespace App\Entity\Event;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReservationsEvents
 *
 * @ORM\Table(name="reservations_events", indexes={@ORM\Index(name="ideventclient2", columns={"id_event"}), @ORM\Index(name="ideventclient", columns={"id_client"})})
 * @ORM\Entity
 */
class ReservationsEvents
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_client", type="integer", nullable=false)
     */
    private $idClient;

    /**
     * @var int
     *
     * @ORM\Column(name="mail_client", type="integer", nullable=false)
     */
    private $mailClient;

    /**
     * @var int
     *
     * @ORM\Column(name="id_event", type="integer", nullable=false)
     */
    private $idEvent;

    /**
     * @var int
     *
     * @ORM\Column(name="nbre_personnes", type="integer", nullable=false)
     */
    private $nbrePersonnes;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="integer", nullable=false)
     */
    private $etat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prix_total", type="integer", nullable=true)
     */
    private $prixTotal;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIdClient(): int
    {
        return $this->idClient;
    }

    /**
     * @param int $idClient
     */
    public function setIdClient(int $idClient): void
    {
        $this->idClient = $idClient;
    }

    /**
     * @return int
     */
    public function getMailClient(): int
    {
        return $this->mailClient;
    }

    /**
     * @param int $mailClient
     */
    public function setMailClient(int $mailClient): void
    {
        $this->mailClient = $mailClient;
    }

    /**
     * @return int
     */
    public function getIdEvent(): int
    {
        return $this->idEvent;
    }

    /**
     * @param int $idEvent
     */
    public function setIdEvent(int $idEvent): void
    {
        $this->idEvent = $idEvent;
    }

    /**
     * @return int
     */
    public function getNbrePersonnes(): int
    {
        return $this->nbrePersonnes;
    }

    /**
     * @param int $nbrePersonnes
     */
    public function setNbrePersonnes(int $nbrePersonnes): void
    {
        $this->nbrePersonnes = $nbrePersonnes;
    }

    /**
     * @return int
     */
    public function getEtat(): int
    {
        return $this->etat;
    }

    /**
     * @param int $etat
     */
    public function setEtat(int $etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return int|null
     */
    public function getPrixTotal(): ?int
    {
        return $this->prixTotal;
    }

    /**
     * @param int|null $prixTotal
     */
    public function setPrixTotal(?int $prixTotal): void
    {
        $this->prixTotal = $prixTotal;
    }

}

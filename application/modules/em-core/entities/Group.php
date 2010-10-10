<?php
/**
 * @Entity
 * @Table (name="emerald_ugroup") 
 */
class EmCore_Entity_Group
{
    /**
     *  @Id
     *  @Column (type="integer")
     *  @GeneratedValue
     */
    private $id;
    
    /** @Column (type="string") */
    private $name;
    
    /**
     * @ManyToMany(targetEntity="EmCore_Entity_User", mappedBy="groups")
     */
    private $users;
    
    
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
}
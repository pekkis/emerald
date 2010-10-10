<?php
/**
 * @Entity
 * @Table(name="emerald_user")
 */
class EmCore_Entity_User
{
    /** 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue 
     */
    private $id;
    
    /** @Column(type="string", unique=true) */
    private $email;
    
    /** @Column(type="string") */
    private $passwd;
    
    /** @Column(type="string", nullable=true) */
    private $firstname;
    
    /** @Column(type="string", nullable=true) */
    private $lastname;
    
    /** @Column(type="smallint") */
    private $status;
        
    /**
     * @ManyToMany(targetEntity="EmCore_Entity_Group", inversedBy="users")
     * @JoinTable(name="emerald_user_uroup",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="ugroup_id", referencedColumnName="id")}
     *      )
     */
    private $groups;
    
    public function __construct() {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    
    
}
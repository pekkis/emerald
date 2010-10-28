<?php

namespace Emerald\Filelib\Backend;

/**
 * Doctrine 2 backend for filelib
 *
 * @category Emerald
 * @package  Emerald_Filelib
 * @author   Mikko Hirvonen
 * @author   pekkis
 */
class Doctrine2Backend extends AbstractBackend
{
    /**
     * File entity name
     *
     * @var string
     */
    private $_fileEntityName = '\Emerald\Filelib\Backend\Doctrine2\Entity\File';

    /**
     * Folder entity name
     *
     * @var string
     */
    private $_folderEntityName = '\Emerald\Filelib\Backend\Doctrine2\Entity\File';

    /**
     * Entity manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;

    
    public function setFileEntityName($fileEntityName)
    {
        $this->_fileEntityName = $fileEntityName;
    }
    
    
    public function getFileEntityName()
    {
        return $this->_fileEntityName;
    }
    
    
    
    public function setEntityManager(\Doctrine\Orm\EntityManager $em)
    {
        $this->_em = $em;
    }
    
    
    public function getEntityManager()
    {
        return $this->_em;
    }
    
    
    
    public function setFolderEntityName($folderEntityName)
    {
        $this->_folderEntityName = $folderEntityName;
    }
    
    
    public function getFolderEntityName()
    {
        return $this->_folderEntityName;
    }
    
    
    public function init()
    { }
    
    
    /**
     * Sets filelib
     *
     * @param  \Emerald_Filelib                   $filelib
     * @return \Emerald\Filelib\Backend\Doctrine2Backend
     */
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib)
    {
        $this->_filelib = $filelib;

        return $this;
    }

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }

    /**
     * Finds a file
     *
     * @param  integer                        $id
     * @return \Emerald\Filelib\File\File|false
     */
    public function findFile($id)
    {
        $file = $this->_em->find($this->_fileEntityName, $id);

        if (!$file) {
            return false;
        }

        return $this->_fileToArray($file);
    }

    /**
     * Finds all files
     *
     * @return \Emerald\Filelib\File\FileIterator
     */
    public function findAllFiles()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_fileEntityName, 'f')
        ->orderBy('f.id', 'ASC');

        $files = array();

        foreach ($qb->getQuery()->getResult() as $file) {
            $files[] = $this->_fileToArray($file);
        }

        return $files;
    }

    /**
     * Finds a file
     *
     * @param  \Emerald\Filelib\Folder\Folder       $folder
     * @return \Emerald\Filelib\File\FileIterator
     */
    public function findFilesIn(\Emerald\Filelib\Folder\Folder $folder)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_fileEntityName, 'f')
        ->where('f.folder = :folder');

        $qb->setParameter('folder', $folder->getId());

        $files = array();
        
        foreach ($qb->getQuery()->getResult() as $file) {
            $files[] = $this->_fileToArray($file);
        }

        return $files;
    }

    /**
     * Updates a file
     *
     * @param  \Emerald\Filelib\File\File  $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFile(\Emerald\Filelib\File\File $file)
    {
        try {
            $file->setLink($file->getProfileObject()->getLinker()->getLink($file, true));

            $fileRow = $this->_em->getReference($this->_fileEntityName,
            $file->getId());

            $fileRow->setFolder($this->_em->getReference($this->_folderEntityName,
            $file->getFolderId()));

            $fileRow->setMimetype($file->getMimetype());
            $fileRow->setProfile($file->getProfile());
            $fileRow->setSize($file->getSize());
            $fileRow->setName($file->getName());
            $fileRow->setLink($file->getLink());
            $fileRow->setDateUploaded($file->getDateUploaded());

            $this->_em->flush();
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Deletes a file
     *
     * @param  \Emerald\Filelib\File\File  $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFile(\Emerald\Filelib\File\File $file)
    {
        try {
            $fileRow = $this->_em->getReference($this->_fileEntityName, $file->getId());
            $this->_em->remove($fileRow);
            $this->_em->flush();
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Finds folder
     *
     * @param  integer                          $id
     * @return \Emerald\Filelib\Folder\Folder|false
     */
    public function findFolder($id)
    {
        $folder = $this->_em->find($this->_folderEntityName, $id);

        if(!$folder) {
            return false;
        }
                
        return $this->_folderToArray($folder);
    }

    /**
     * Finds the root folder
     *
     * @return \Emerald\Filelib\Folder\Folder
     */
    public function findRootFolder()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_folderEntityName, 'f')
        ->where('f.parent IS NULL');

        try {
            $folder = $qb->getQuery()->getSingleResult();    
        } catch(\Doctrine\ORM\NoResultException $e) {
            $folder = new \Emerald\Filelib\Backend\Doctrine2\Entity\Folder();
            $folder->setName('root');
            $folder->removeParent();
            $folder->setVisible(1);
            $this->_em->persist($folder);
            $this->_em->flush();        
        }
        
        return $this->_folderToArray($folder);       
        
    }

    /**
     * Finds subfolders of a folder
     *
     * @param  \Emerald\Filelib\Folder\Folder         $id
     * @return \Emerald\Filelib\Folder\FolderIterator
     */
    public function findSubFolders(\Emerald\Filelib\Folder\Folder $folder)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_folderEntityName, 'f')
        ->where('f.parent = :folder');

        $qb->setParameter('folder', $folder->getId());

        $folders = array();

        foreach ($qb->getQuery()->getResult() as $folderRow) {
            $folders[] = $this->_folderToArray($folderRow);
        }

        return $folders;
    }

    /**
     * Creates a folder
     *
     * @param  \Emerald\Filelib\Folder\Folder $folder
     * @return \Emerald\Filelib\Folder\Folder Created folder
     * @throws \Emerald\Filelib\FilelibException  When fails
     */
    public function createFolder(\Emerald\Filelib\Folder\Folder $folder)
    {
        try {
            $folderRow = new $this->_folderEntityName();

            if ($folder->getParentId()) {
                $folderRow->setParent($this->_em->getReference($this->_folderEntityName,
                $folder->getParentId()));
            }

            $folderRow->setName($folder->getName());
            
            $folderRow->setVisible(1);

            $this->_em->persist($folderRow);
            $this->_em->flush();

            $folder->setId($folderRow->getId());

            return $folder;
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Updates a folder
     *
     * @param  \Emerald\Filelib\Folder\Folder $folder
     * @throws \Emerald\Filelib\FilelibException  When fails
     */
    public function updateFolder(\Emerald\Filelib\Folder\Folder $folder)
    {
        try {
            $folderRow = $this->_em->getReference($this->_folderEntityName,
            $folder->getId());

            if ($folder->getParentId()) {
                $folderRow->setParent($this->_em->getReference($this->_folderEntityName,
                $folder->getParentId()));
            } else {
                $folderRow->removeParent();
            }

            $folderRow->setName($folder->getName());
            $folderRow->setVisible(1);

            $this->_em->flush();
            
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Deletes a folder
     *
     * @param  \Emerald\Filelib\Folder\Folder $folder
     * @throws \Emerald\Filelib\FilelibException  When fails
     */
    public function deleteFolder(\Emerald\Filelib\Folder\Folder $folder)
    {
        try {
            $folder = $this->_em->getReference($this->_folderEntityName, $folder->getId());

            $this->_em->remove($folder);
            $this->_em->flush();
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Uploads a file
     *
     * @param  \Emerald\Filelib\File\FileUpload $upload Fileobject to upload
     * @param  \Emerald\Filelib\Folder\Folder $folder Folder
     * @return \Emerald\Filelib\File\File   File item
     * @throws \Emerald\Filelib\FilelibException  When fails
     */
    public function upload(\Emerald\Filelib\File\FileUpload $upload,
    \Emerald\Filelib\Folder\Folder $folder,
    \Emerald\Filelib\File\FileProfile $profile
    ){
        try {
            
            $conn = $this->_em->getConnection();
            $conn->beginTransaction();

            $file = new $this->_fileEntityName();

            $file->setFolder($this->_em->getReference($this->_folderEntityName,
            $folder->getId()));
            $file->setMimetype($upload->getMimeType());
            $file->setSize($upload->getSize());
            $file->setName($upload->getOverrideFilename());
            $file->setProfile($profile->getIdentifier());
            $file->setDateUploaded($upload->getDateUploaded());
            
            $this->_em->persist($file);
            $this->_em->flush();

            $conn->commit();
                                    
            return $this->_fileToArray($file);
            
        } catch (Exception $e) {
            $conn->rollback();

            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * File to array
     *
     * @param  object $file
     * @return array
     */
    private function _fileToArray($file)
    {
        return array(
            'id'        => $file->getId(),
            'folder_id' => $file->getFolder() ? $file->getFolder()->getId() : null,
            'mimetype'  => $file->getMimetype(),
            'profile'   => $file->getProfile(),
            'size'      => $file->getSize(),
            'name'      => $file->getName(),
            'link'      => $file->getLink(),
            'date_uploaded' => $file->getDateUploaded(),
        );
    }

    /**
     * Folder to array
     *
     * @param  object $folder
     * @return array
     */
    private function _folderToArray($folder)
    {
        return array(
            'id'        => $folder->getId(),
            'parent_id' => $folder->getParent() ? $folder->getParent()->getId() : null,
            'name'      => $folder->getName(),
        );
    }
}

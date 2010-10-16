<?php

namespace Emerald\Filelib\Backend;

/**
 * Doctrine 2 backend for filelib
 *
 * @category Emerald
 * @package  Emerald_Filelib
 * @author   Mikko Hirvonen
 */
class Doctrine2Backend implements Backend
{
    /**
     * File entity name
     *
     * @var string
     */
    private $_fileEntityName;

    /**
     * Folder entity name
     *
     * @var string
     */
    private $_folderEntityName;

    /**
     * Entity manager
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $_em;

    public function __construct(Doctrine\ORM\EntityManager $em, $fileEntityName,
    $folderEntityName
    ) {
        $this->_em               = $em;
        $this->_fileEntityName   = $fileEntityName;
        $this->_folderEntityName = $folderEntityName;
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
     * @return \Emerald\Filelib\File|false
     */
    public function findFile($id)
    {
        $file = $this->_em->find($this->_fileEntityName, $id);

        if (!$file) {
            return false;
        }

        $fileItemClass = $this->getFilelib()->getFileItemClass();

        return new $fileItemClass($this->_fileToArray($file));
    }

    /**
     * Finds all files
     *
     * @return \Emerald\Filelib\FileIterator
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

        return new \Emerald\Filelib\FileIterator($files);
    }

    /**
     * Finds a file
     *
     * @param  \Emerald\Filelib\Folder       $folder
     * @return \Emerald\Filelib\FileIterator
     */
    public function findFilesIn(\Emerald\Filelib\Folder $folder)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_fileEntityName, 'f')
        ->where('f.folder = :folder');

        $qb->setParameter('folder', $folder->id);

        $files = array();

        foreach ($qb->getQuery()->getResult() as $file) {
            $files[] = $this->_fileToArray($file);
        }

        return new \Emerald\Filelib\FileIterator($files);
    }

    /**
     * Updates a file
     *
     * @param  \Emerald\Filelib\File  $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFile(\Emerald\Filelib\File $file)
    {
        try {
            $file->link = $file->getProfileObject()
            ->getLinker()->getLink($file, true);

            $fileRow = $this->_em->getReference($this->_fileEntityName,
            $file->id);

            $fileRow->setFolder($this->_em->getReference($this->_folderEntityName,
            $file->folder_id));

            $fileRow->setMimetype($file->mimetype);
            $fileRow->setProfile($file->profile);
            $fileRow->setSize($file->size);
            $fileRow->setName($file->name);
            $fileRow->setLink($file->link);

            $this->_em->flush();
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Deletes a file
     *
     * @param  \Emerald\Filelib\File  $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFile(\Emerald\Filelib\File $file)
    {
        try {
            $fileRow = $this->_em->getReference($this->_fileEntityName, $file->id);

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
     * @return \Emerald\Filelib\Folder|false
     */
    public function findFolder($id)
    {
        $folder = $this->_em->find($this->_folderEntityName, $id);

        $folderItemClass = $this->getFilelib()->getFolderItemClass();

        return new $folderItemClass($this->_folderToArray($folder));
    }

    /**
     * Finds the root folder
     *
     * @return \Emerald\Filelib\Folder
     */
    public function findRootFolder()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_folderEntityName, 'f')
        ->where('f.parent IS NULL');

        $folder = $qb->getQuery()->getSingleResult();

        $folderItemClass = $this->getFilelib()->getFolderItemClass();

        return new $folderItemClass($this->_folderToArray($folder));
    }

    /**
     * Finds subfolders of a folder
     *
     * @param  \Emerald\Filelib\Folder         $id
     * @return \Emerald\Filelib\FolderIterator
     */
    public function findSubFolders(\Emerald\Filelib\Folder $folder)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_folderEntityName, 'f')
        ->where('f.parent = :folder');

        $qb->setParameter('folder', $folder->id);

        $folderItemClass = $this->getFilelib()->getFolderItemClass();

        $folders = array();

        foreach ($qb->getQuery()->getResult() as $folderRow) {
            $folders[] = new $folderItemClass($this->_folderToArray($folderRow));
        }

        return new \Emerald\Filelib\FolderIterator($folders);
    }

    /**
     * Creates a folder
     *
     * @param  \Emerald\Filelib\Folder $folder
     * @return \Emerald\Filelib\Folder Created folder
     * @throws \Emerald\Filelib\FilelibException  When fails
     */
    public function createFolder(\Emerald\Filelib\Folder $folder)
    {
        try {
            $folderRow = new $this->_folderEntityName();

            if ($folder->parent_id) {
                $folderRow->setParent($this->_em->getReference($this->_folderEntityName,
                $folder->parent_id));
            }

            $folderRow->setName($folder->name);
            $folderRow->setVisible($folder->visible);

            $this->_em->persist($folderRow);
            $this->_em->flush();

            $folder->id = $folderRow->getId();

            return $folder;
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Updates a folder
     *
     * @param  \Emerald\Filelib\Folder $folder
     * @throws \Emerald\Filelib\FilelibException  When fails
     */
    public function updateFolder(\Emerald\Filelib\Folder $folder)
    {
        try {
            $folderRow = $this->_em->getReference($this->_folderEntityName,
            $folder->id);

            if ($folder->parent_id) {
                $folderRow->setParent($this->_em->getReference($this->_folderEntityName,
                $folder->parent_id));
            } else {
                $folderRow->removeParent();
            }

            $folderRow->setName($folder->name);
            $folderRow->setVisible($folder->visible);

            $this->_em->flush();
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Deletes a folder
     *
     * @param  \Emerald\Filelib\Folder $folder
     * @throws \Emerald\Filelib\FilelibException  When fails
     */
    public function deleteFolder(\Emerald\Filelib\Folder $folder)
    {
        try {
            $folder = $this->_em->getReference($this->_folderEntityName, $folder->id);

            $this->_em->remove($folder);
            $this->_em->flush();
        } catch (Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    }

    /**
     * Uploads a file
     *
     * @param  \Emerald\Filelib\FileUpload $upload Fileobject to upload
     * @param  \Emerald\Filelib\Folder $folder Folder
     * @return \Emerald\Filelib\File   File item
     * @throws \Emerald\Filelib\FilelibException  When fails
     */
    public function upload(\Emerald\Filelib\FileUpload $upload,
    \Emerald\Filelib\Folder $folder,
    \Emerald\Filelib\FileProfile $profile
    ){
        try {
            $conn = $this->_em->getConnection();
            $conn->beginTransaction();

            $file = new $this->_fileEntityName();

            $file->setFolder($this->_em->getReference($this->_folderEntityName,
            $folder->id));
            $file->setMimetype($upload->getMimeType());
            $file->setSize($upload->getSize());
            $file->setName($upload->getOverrideFilename());
            $file->setProfile($profile->getIdentifier());

            $this->_em->persist($file);
            $this->_em->flush();


            $fileItemClass = $this->getFilelib()->getFileItemClass();

            $fileItem = new $fileItemClass($this->_fileToArray($file));
            $fileItem->setFilelib($this->getFilelib());

            $fileItem->link = $profile->getLinker()->getLink($fileItem, true);

            $file->setLink($fileItem->link);


            $this->_em->flush();

            $conn->commit();

            return $fileItem;
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
            'visible'   => $folder->getVisible(),
        );
    }
}

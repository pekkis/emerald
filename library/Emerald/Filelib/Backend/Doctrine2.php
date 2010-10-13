<?php

/**
 * Doctrine 2 backend for filelib
 *
 * @category Emerald
 * @package  Emerald_Filelib
 * @author   Mikko Hirvonen
 */
class Emerald_Filelib_Backend_Doctrine2 implements Emerald_Filelib_Backend_Interface
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

    /**
     * Sets filelib
     *
     * @param  Emerald_Filelib                   $filelib
     * @return Emerald_Filelib_Backend_Doctrine2
     */
    public function setFilelib(Emerald_Filelib $filelib)
    {
        $this->_filelib = $filelib;

        return $this;
    }

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }

    /**
     * Finds a file
     *
     * @param  integer                        $id
     * @return Emerald_Filelib_FileItem|false
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
     * @return Emerald_Filelib_FileItemIterator
     */
    public function findAllFiles()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_fileEntityName, 'f')
        ->orderBy('f.id', 'ASC');

        $files = array();

        $fileItemClass = $this->getFilelib()->getFileItemClass();

        foreach ($qb->getQuery()->getResult() as $file) {
            $files[] = new $fileItemClass($this->_fileToArray($file));
        }

        return new Emerald_Filelib_FileItemIterator($files);
    }

    /**
     * Finds a file
     *
     * @param  Emerald_Filelib_FolderItem       $folder
     * @return Emerald_Filelib_FileItemIterator
     */
    public function findFilesIn(Emerald_Filelib_FolderItem $folder)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
        ->from($this->_fileEntityName, 'f')
        ->where('f.folder = :folder');

        $qb->setParameter('folder', $folder->id);

        $files = array();

        $fileItemClass = $this->getFilelib()->getFileItemClass();

        foreach ($qb->getQuery()->getResult() as $file) {
            $files[] = new $fileItemClass($this->_fileToArray($file));
        }

        return new Emerald_Filelib_FileItemIterator($files);
    }

    /**
     * Updates a file
     *
     * @param  Emerald_Filelib_FileItem  $file
     * @throws Emerald_Filelib_Exception When fails
     */
    public function updateFile(Emerald_Filelib_FileItem $file)
    {
        try {
            $file->link = $file->getProfileObject()
            ->getSymlinker()->getLink($file, false, true);

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
            throw new Emerald_Filelib_Exception($e->getMessage());
        }
    }

    /**
     * Deletes a file
     *
     * @param  Emerald_Filelib_FileItem  $file
     * @throws Emerald_Filelib_Exception When fails
     */
    public function deleteFile(Emerald_Filelib_FileItem $file)
    {
        try {
            $fileRow = $this->_em->getReference($this->_fileEntityName, $file->id);

            $this->_em->remove($fileRow);
            $this->_em->flush();
        } catch (Exception $e) {
            throw new Emerald_Filelib_Exception($e->getMessage());
        }
    }

    /**
     * Finds folder
     *
     * @param  integer                          $id
     * @return Emerald_Filelib_FolderItem|false
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
     * @return Emerald_Filelib_FolderItem
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
     * @param  Emerald_Filelib_FolderItem         $id
     * @return Emerald_Filelib_FolderItemIterator
     */
    public function findSubFolders(Emerald_Filelib_FolderItem $folder)
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

        return new Emerald_Filelib_FolderItemIterator($folders);
    }

    /**
     * Creates a folder
     *
     * @param  Emerald_Filelib_FolderItem $folder
     * @return Emerald_Filelib_FolderItem Created folder
     * @throws Emerald_Filelib_Exception  When fails
     */
    public function createFolder(Emerald_Filelib_FolderItem $folder)
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
            throw new Emerald_Filelib_Exception($e->getMessage());
        }
    }

    /**
     * Updates a folder
     *
     * @param  Emerald_Filelib_FolderItem $folder
     * @throws Emerald_Filelib_Exception  When fails
     */
    public function updateFolder(Emerald_Filelib_FolderItem $folder)
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
            throw new Emerald_Filelib_Exception($e->getMessage());
        }
    }

    /**
     * Deletes a folder
     *
     * @param  Emerald_Filelib_FolderItem $folder
     * @throws Emerald_Filelib_Exception  When fails
     */
    public function deleteFolder(Emerald_Filelib_FolderItem $folder)
    {
        try {
            $folder = $this->_em->getReference($this->_folderEntityName, $folder->id);

            $this->_em->remove($folder);
            $this->_em->flush();
        } catch (Exception $e) {
            throw new Emerald_Filelib_Exception($e->getMessage());
        }
    }

    /**
     * Uploads a file
     *
     * @param  Emerald_Filelib_FileUpload $upload Fileobject to upload
     * @param  Emerald_Filelib_FolderItem $folder Folder
     * @return Emerald_Filelib_FileItem   File item
     * @throws Emerald_Filelib_Exception  When fails
     */
    public function upload(Emerald_Filelib_FileUpload $upload,
    Emerald_Filelib_FolderItem $folder,
    Emerald_Filelib_FileProfile $profile
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

            $fileItem->link = $profile->getSymlinker()->getLink($fileItem, false, true);

            $file->setLink($fileItem->link);


            $this->_em->flush();

            $conn->commit();

            return $fileItem;
        } catch (Exception $e) {
            $conn->rollback();

            throw new Emerald_Filelib_Exception($e->getMessage());
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

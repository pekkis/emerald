<?php

namespace Emerald\Base\Spl;

use \SplFileObject, \finfo;

/**
 * Extends SplFileObject to offer mime type detection via Fileinfo extension.
 *
 * @author pekkis
 *
 */
class FileObject extends SplFileObject
{
    /**
     * @var string Mimetype is cached here
     */
    private $_mimeType;

    /**
     * Returns file's mime type.
     *
     * @return string
     */
    public function getMimeType()
    {
        if (!$this->_mimeType) {
            $fileinfo = new finfo(FILEINFO_MIME_TYPE);
            $this->_mimeType = $fileinfo->file($this->getRealPath());
        }
        return $this->_mimeType;
    }
}

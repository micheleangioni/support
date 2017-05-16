<?php

namespace MicheleAngioni\Support\Repos;

use UnexpectedValueException;

class AbstractSimpleXMLRepository implements XMLRepositoryInterface
{
    /**
     * Indicates if the xml file is auto loaded on construct or must be loaded through load() method.
     *
     * @var bool
     */
    protected $autoload = true;

    protected $xmlFile;

    /**
     * Relative file path from the document root folder.
     *
     * @var string
     */
    protected $xmlPath;


    public function __construct()
    {
        $this->xmlPath = app_path() . $this->xmlPath;

        if ($this->autoload) {
            $this->loadFile();
        }
    }

    /*
     * Return file path.
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->xmlPath;
    }

    /**
     * Return the xml file as an instance of SimpleXML
     *
     * @throws UnexpectedValueException
     * @return \SimpleXMLElement
     */
    public function getFile()
    {
        if (!$this->xmlFile) {
            $this->loadFile();
        }

        return $this->xmlFile;
    }

    /**
     * Load the file and save it into the class var
     */
    public function loadFile()
    {
        if (!$this->xmlFile) {
            $this->xmlFile = simplexml_load_file($this->xmlPath);
        }
    }
}

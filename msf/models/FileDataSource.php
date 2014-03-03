<?php
namespace msf\models;

class FileDataSource {

    /**
     * Base path for files
     * @var string
     */
    private $_basePath;

    /**
     * Default file extension
     * @var string
     */
    private $_extension;

    public function __construct($basePath, $extension='json') {
        $this->_basePath = $basePath;
        $this->_extension = $extension;
    } // end __construct()

    /**
     * Builds and returns a fully qualified file name given
     * a model name and identifier.
     * @param string $modelName
     * @param string|int $id
     * @return string
     */
    public function getFileName($modelName, $id) {
        $fileName = $this->_basePath . DIRECTORY_SEPARATOR;
        $fileName .= "{$modelName}_{$id}.{$this->_extension}";
        return $fileName;
    } // end getFileName()

    /**
     * Writes the supplied data to a text file using JSON
     * as the serialization format.
     * @param array $data
     * @param string $baseName
     * @throws RuntimeException
     */
    public function write($data, $modelName) {
        if(!isset($data['id'])) {
            throw new \RuntimeException('Data must have id field set');
        }
        $fileName = $this->getFileName($modelName, $data['id']);
        $file = @fopen($fileName, 'w');
        if(!$file) {
            throw new \RuntimeException("{$fileName} cannot be opened for writing");
        }
        $jsonData = json_encode($data);
        fwrite($file, $jsonData);
        fclose($file);

        return $fileName;
    } // end write()

    /**
     * Reads the data file specified by the given model and identifier
     * and returns an array of data
     * @param string $modelName
     * @param string|int $id
     * @return array
     */
    public function read($modelName, $id) {
        $fileName = $this->getFileName($modelName, $id);
        $jsonData = @file_get_contents($fileName);
        if(!$jsonData) {
            throw new \RuntimeException("{$fileName} cannot be opened for reading");
        }

        return json_decode($jsonData, true);
    } // end read()

    /**
     * Reads all files with the given model name pattern and
     * returns an array of arrays containing model data
     * @param string $modelName
     * @param int $limit
     * @param string $orderBy
     * @param string $direction
     * @return array
     */
    public function readAll($modelName, $limit=0, $orderBy='', $direction='ASC') {
        $filePattern = $this->_basePath . DIRECTORY_SEPARATOR . $modelName . '*' . $this->_extension;
        $data = array();
        foreach(glob($filePattern) as $f) {
            $jsonData = @file_get_contents($f);
            if(!$jsonData) {
                throw new \RuntimeException("{$fileName} cannot be opened for reading");
            }
            $data[] = json_decode($jsonData, true);
        }

        /* Sort if applicable */
        if(!empty($orderBy)) {
            $data = $this->sortData($data, $orderBy, $direction);
        }

        /* Slice if applicable */
        if($limit > 0) {
            return array_slice($data, 0, $limit);
        }
        else {
            return $data;
        }
    } // end readAll()

    /**
     * Reads the data file specified by the given model and identifier
     * and returns an array of data
     * @param string $modelName
     * @param string|int $id
     * @return boolean
     */
    public function delete($modelName, $id) {
        $fileName = $this->getFileName($modelName, $id);
        return unlink($fileName);
    } // end delete()

    /**
     * Sorts the given multi-dimension array by the orderBy field
     *
     * @param array $data
     * @param string $orderBy
     * @param string $direction
     */
    public function sortData($data, $orderBy, $direction) {
        /* Build index based on field */
        $index = array();
        foreach($data as $key => $row) {
            if(!isset($row[$orderBy])) {
                throw new \RuntimeException("{$orderBy} is not found in data at index {$key}");
            }
            $index[$key] = $row[$orderBy];
        }

        if($direction === 'ASC') {
            array_multisort($index, SORT_ASC, SORT_REGULAR, $data);
        }
        else {
            array_multisort($index, SORT_DESC, SORT_REGULAR, $data);
        }

        return $data;
    } // end sortData()
} // end class FileDataSource